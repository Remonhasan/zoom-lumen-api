<?php

namespace App\Http\Controllers;

use App\Exceptions\ValidatorException;
use App\Http\Resources\WebinarEventResource;
use App\Repositories\LiveMeetingAccountRepository;
use App\Repositories\WebinarEventReporterDetailRepository;
use App\Repositories\WebinarEventRepository;
use App\Repositories\WebinarEventSessionTimeRepository;
use App\Services\ResourceService;
use App\Traits\Controller\RestControllerTrait;
use App\Validators\WebinarEventValidator;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WebinarEventController extends Controller
{
    private $repository;

    private $validator;

    private $resource;

    private $updateFields = ['status'];

    use RestControllerTrait;

    public function __construct(WebinarEventRepository $repository, WebinarEventSessionTimeRepository $webinarEventSessionTimeRepository, WebinarEventReporterDetailRepository $webinarEventReporterDetailRepository, WebinarEventValidator $validator)
    {
        $this->repository = $repository;
        $this->webinarEventSessionTimeRepository = $webinarEventSessionTimeRepository;
        $this->webinarEventReporterDetailRepository = $webinarEventReporterDetailRepository;
        $this->validator = $validator;
        $this->resource = WebinarEventResource::class;
    }

    /**
     * Get Application by Id
     *
     * @param  mixed $applicationId
     * @return void
     */
    public function getApplication($applicationId)
    {
        try {
            $result = $this->repository->getApplicationById($applicationId);
            $response = isset($this->resource) ? new $this->resource($result) : $result;
            return $this->successResourceResponse($response);
        } catch (\Exception$e) {
            $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Store New Resource of Data
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request)
    {
        try {

            $items = $request->all();
            $applicationData = !empty($items['applicationData']) ? $items['applicationData'] : null;
            $webinarEventSessionTimeList = !empty($items['webinarEventSessionTimeListData']) ? $items['webinarEventSessionTimeListData'] : null;
            $webinarEventReporterDetailList = !empty($items['webinarEventReporterDetailListData']) ? $items['webinarEventReporterDetailListData'] : null;

            // Get Live Meeting Account Id
            $liveMeetingAccountRepository = new LiveMeetingAccountRepository();
            $liveAccountInfo = $liveMeetingAccountRepository->findById($applicationData['live_meeting_account_id']);

            // Create Meeting by Particular Account
            if (!empty($liveAccountInfo)) {

                // Set Api Key and Secret from Live Meeting Setup Account
                $zoomAccount = new \MacsiDigital\Zoom\Support\Entry($liveAccountInfo->api_key, $liveAccountInfo->api_secret);

                // Get Data From Form to Create New Webinar Event
                $meetingData = [
                    "topic" => $applicationData['event_name'],
                    'duration' => $applicationData['duration'],
                    'password' => $request->password,
                    'start_time' => new Carbon($applicationData['start_at']),
                    'timezone' => config('zoom.timezone'),
                ];

                // Create Zoom Meeting
                $user = $zoomAccount->user()->first();
                $meeting = $zoomAccount->meeting()->make($meetingData);

                // Add Zoom Meeting Settings
                $meeting->settings()->make([
                    'join_before_host' => true,
                    'host_video' => false,
                    'participant_video' => false,
                    'mute_upon_entry' => true,
                    'waiting_room' => true,
                    'approval_type' => config('zoom.approval_type'),
                    'audio' => config('zoom.audio'),
                    'auto_recording' => config('zoom.auto_recording'),
                ]);

                $user->meetings()->save($meeting);

                // Store Zoom Meeting Data
                $applicationData['meeting_id'] = $meeting->id;
                $applicationData['event_name'] = $meeting->topic;
                $applicationData['start_at'] = $meeting->start_time;
                $applicationData['duration'] = $meeting->duration;
                $applicationData['password'] = $meeting->password;
                $applicationData['start_url'] = $meeting->start_url;
                $applicationData['join_url'] = $meeting->join_url;

                // Insert Data into Master Table Journal Application
                $applicationDataResult = $this->repository->create($applicationData);
            } else {
                throw new Exception("Meeting Account does not exist");
            }

            $applicationDataResultId = $applicationDataResult->id;

            if (empty($applicationDataResult)) {
                throw new Exception("Application save fail!");
            }

            // Insert Data into Child Table Webinar Event Session Time
            $responseApplication = '';
            if ($webinarEventSessionTimeList) {
                $webinarEventSessionTimeIds = array_column($webinarEventSessionTimeList, 'id');
                $this->webinarEventSessionTimeRepository->deleteSessionTimeByIds($applicationDataResultId, $webinarEventSessionTimeIds);

                foreach ($webinarEventSessionTimeList as $item) {
                    $webinarEventSessionTimeData = $item;

                    $webinarEventSessionTimeData['webinar_event_id'] = $applicationDataResult->id;
                    if (!empty($item['id'])) {
                        $webinarEventSessionTimeDataResult = $this->webinarEventSessionTimeRepository->update($webinarEventSessionTimeData, $item['id']);
                    } else {
                        $webinarEventSessionTimeDataResult = $this->webinarEventSessionTimeRepository->create($webinarEventSessionTimeData);
                    }
                }

                $responseApplication = isset($this->resource) ? new $this->resource($webinarEventSessionTimeDataResult) : $webinarEventSessionTimeDataResult;
            }

            // Insert Data into Child Table Webinar Event Reporter Details
            $responseApplicationReporter = '';
            if ($webinarEventReporterDetailList) {
                $webinarEventReporterDetailIds = array_column($webinarEventReporterDetailList, 'id');
                $this->webinarEventReporterDetailRepository->deleteReporterDetailByIds($applicationDataResultId, $webinarEventReporterDetailIds);

                foreach ($webinarEventReporterDetailList as $item) {
                    $webinarEventReporterDetailData = $item;

                    $webinarEventReporterDetailData['webinar_event_id'] = $applicationDataResult->id;
                    if (!empty($item['id'])) {
                        $webinarEventReporterDetailDataResult = $this->webinarEventReporterDetailRepository->update($webinarEventReporterDetailData, $item['id']);
                    } else {
                        $webinarEventReporterDetailDataResult = $this->webinarEventReporterDetailRepository->create($webinarEventReporterDetailData);
                    }
                }

                $responseApplicationReporter = isset($this->resource) ? new $this->resource($webinarEventReporterDetailDataResult) : $webinarEventReporterDetailDataResult;
            }

            $response = isset($this->resource) ? new $this->resource($applicationDataResult) : $applicationDataResult;
            return $this->successResourceResponse([
                'application' => $response,
                'applicationDetail' => $responseApplication,
                'applicationReporterDetail' => $responseApplicationReporter,
            ]);
        } catch (ValidationException $e) {
            throw new ValidatorException($e);
        } catch (\Exception$e) {
            $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Updated the Resource Data
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $items = $request->all();
            $applicationData = !empty($items['applicationData']) ? $items['applicationData'] : null;
            $webinarEventSessionTimeList = !empty($items['webinarEventSessionTimeListData']) ? $items['webinarEventSessionTimeListData'] : null;
            $webinarEventReporterDetailList = !empty($items['webinarEventReporterDetailListData']) ? $items['webinarEventReporterDetailListData'] : null;

            $applicationId = isset($id) ? $id : null;

            if (empty($applicationId)) {
                throw new Exception("Application update fail!");
            }

            // Fetch Particular Event Data from Webinar Event Table
            $eventInfo = $this->repository->findById($applicationId);

            // Get Live Meeting Account from Webinar Event Table
            $liveMeetingAccountRepository = new LiveMeetingAccountRepository();
            $liveAccountInfo = $liveMeetingAccountRepository->findById($eventInfo->live_meeting_account_id);

            // Apply Condition Where the Live Meeting Account is Empty or Not
            if (!empty($liveAccountInfo)) {

                // Set Api Key and Secret from Live Meeting Setup Account
                $zoomAccount = new \MacsiDigital\Zoom\Support\Entry($liveAccountInfo->api_key, $liveAccountInfo->api_secret);

                // Get the Email which Meetings will be Updated
                $user = $zoomAccount->user()->find($liveAccountInfo->email);

                // Get the Updated Data from Form to Update
                $user->meetings()->find($eventInfo->meeting_id)->update([
                    "topic" => $applicationData['event_name'],
                    'duration' => $applicationData['duration'],
                    'password' => $request->password,
                    'start_time' => new Carbon($applicationData['start_at']),
                    'timezone' => config('zoom.timezone'),
                ]);

                // Updated Master Table Webinar Event Data
                $applicationDataResult = $this->repository->update($applicationData, $applicationId);
            } else {
                throw new Exception("Meeting Account does not exist");
            }

            // Update Child Table Webinar Event Session Time Data
            if ($webinarEventSessionTimeList) {
                $webinarEventSessionTimeIds = array_column($webinarEventSessionTimeList, 'id');
                $this->webinarEventSessionTimeRepository->deleteSessionTimeByIds($id, $webinarEventSessionTimeIds);

                foreach ($webinarEventSessionTimeList as $item) {
                    $webinarEventSessionTimeData = $item;

                    $webinarEventSessionTimeData['webinar_event_id'] = $id;
                    if (!empty($item['id'])) {
                        $webinarEventSessionTimeDataResult = $this->webinarEventSessionTimeRepository->update($webinarEventSessionTimeData, $item['id']);
                    } else {
                        $webinarEventSessionTimeDataResult = $this->webinarEventSessionTimeRepository->create($webinarEventSessionTimeData);
                    }
                }
            }

            // Update Child Table Webinar Event Reporter Details Data
            if ($webinarEventReporterDetailList) {
                $webinarEventReporterDetailIds = array_column($webinarEventReporterDetailList, 'id');
                $this->webinarEventReporterDetailRepository->deleteReporterDetailByIds($id, $webinarEventReporterDetailIds);

                foreach ($webinarEventReporterDetailList as $item) {
                    $webinarEventReporterDetailData = $item;

                    $webinarEventReporterDetailData['webinar_event_id'] = $id;
                    if (!empty($item['id'])) {
                        $webinarEventReporterDetailDataResult = $this->webinarEventReporterDetailRepository->update($webinarEventReporterDetailData, $item['id']);
                    } else {
                        $webinarEventReporterDetailDataResult = $this->webinarEventReporterDetailRepository->create($webinarEventReporterDetailData);
                    }
                }
            }

            DB::commit();
            $response = $this->repository->show($id);
            $response = ResourceService::getResources($response, WebinarEventResource::class);
            return $this->successResponse($response);
        } catch (ValidationException $e) {
            DB::rollBack();
            throw new ValidatorException($e);
        } catch (\Exception$e) {
            DB::rollBack();
            $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Destroy the Resource Data
     *
     * @param  mixed $id
     * @return void
     */
    public function destroy($id)
    {
        try {
            // Master Table (Webinar Event) Data Check Exists or Not
            if (!isset($this->repository)) {
                $this->errorResponse('Repository not defined');
            }

            $entity = $this->repository->findById($id);

            if (!$entity) {
                $this->notFoundResponse();
            }

            // Webinar Session Time Table Data Deleted
            if (!isset($this->webinarEventSessionTimeRepository)) {
                $this->errorResponse('Repository not defined');
            }

            $webinarSessionTimeIds = $this->webinarEventSessionTimeRepository->getWebinarSessionTimeDetailIdsByEventId($id);

            if (!empty($webinarSessionTimeIds)) {
                foreach ($webinarSessionTimeIds as $key => $sessionTimeIds) {
                    $this->webinarEventSessionTimeRepository->delete($sessionTimeIds);
                }
            }

            // Webinar Reporter Details Table Data Deleted
            if (!isset($this->WebinarEventReporterDetailRepository)) {
                $this->errorResponse('Repository not defined');
            }

            $webinarReportersIds = $this->WebinarEventReporterDetailRepository->getWebinarReporterDetailIdsByEventId($id);

            if (!empty($webinarReportersIds)) {
                foreach ($webinarReportersIds as $key => $reporterIds) {
                    $this->WebinarEventReporterDetailRepository->delete($reporterIds);
                }
            }

            //  Master Table (Webinar Event) Data Deleted
            $response = $this->repository->delete($id);
            if (!$response) {
                $this->errorResponse();
            }
            return $this->deleteResponse();
        } catch (\Exception$e) {
            $this->errorResponse($e->getMessage());
        }
    }
}
