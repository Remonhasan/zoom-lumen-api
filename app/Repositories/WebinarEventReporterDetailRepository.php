<?php

namespace App\Repositories;

use App\Models\WebinarEventReporterDetail;
use App\Services\ODataService;

class WebinarEventReporterDetailRepository extends BaseRepository
{
    /**
     * @var WebinarEventReporterDetail
     */
    protected $model;

    protected $request;

    protected $oDataService;

    protected $fieldSearchable = ['title', 'description'];

    public function __construct()
    {
        $this->model = new WebinarEventReporterDetail();
        $this->request = request();
        $this->oDataService = new ODataService();
    }

    /**
     * Get Webinar Event Reporter Detail By ApplicationId
     *
     * @param  mixed $applicationId
     * @return void
     */
    public function getReporterDetailByApplicationId($applicationId)
    {
        return $this->newQuery()
            ->where('webinar_event_id', $applicationId)
            ->get();

    }

    /**
     * Delete Webinar Event Reporter Detail By Ids
     *
     * @param  mixed $webinarEventId
     * @param  mixed $webinarEventReporterDetailIds
     * @return void
     */
    public function deleteReporterDetailByIds($webinarEventId, $webinarEventReporterDetailIds)
    {
        return $this->newQuery()
            ->where(['webinar_event_id' => $webinarEventId])
            ->whereNotIn('id', $webinarEventReporterDetailIds)
            ->delete();
    }

    /**
     * Get Webinar Reporter Detail Ids By EventId
     *
     * @param  mixed $webinarEventId
     * @return void
     */
    public function getWebinarReporterDetailIdsByEventId($webinarEventId)
    {
        return $this->newQuery()
            ->where('webinar_event_id', $webinarEventId)
            ->pluck('id');
    }
}
