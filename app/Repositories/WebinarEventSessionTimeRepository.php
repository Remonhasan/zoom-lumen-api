<?php

namespace App\Repositories;

use App\Models\WebinarEventSessionTime;
use App\Services\ODataService;

class WebinarEventSessionTimeRepository extends BaseRepository
{
    /**
     * @var WebinarEventSessionTime
     */
    protected $model;

    protected $request;

    protected $oDataService;

    protected $fieldSearchable = ['title', 'description'];

    public function __construct()
    {
        $this->model = new WebinarEventSessionTime();
        $this->request = request();
        $this->oDataService = new ODataService();
    }

    /**
     * Get Webinar Event Session Time By ApplicationId
     *
     * @param  mixed $applicationId
     * @return void
     */
    public function getSessionTimeByApplicationId($applicationId)
    {
        return $this->newQuery()
            ->where('webinar_event_id', $applicationId)
            ->get();

    }

    /**
     * Delete Webinar Event Session Time By Ids
     *
     * @param  mixed $webinarEventId
     * @param  mixed $webinarEventSessionTimeIds
     * @return void
     */
    public function deleteSessionTimeByIds($webinarEventId, $webinarEventSessionTimeIds)
    {
        return $this->newQuery()
            ->where(['webinar_event_id' => $webinarEventId])
            ->whereNotIn('id', $webinarEventSessionTimeIds)
            ->delete();
    }

    /**
     * Get Session Time Info by Event Id
     *
     * @param  mixed $eventId
     * @return void
     */
    public function getSessionTimeInfo($eventId)
    {
        return $this->newQuery()
            ->where(['webinar_event_id' => $eventId])
            ->select('session_start_date_time', 'session_end_date_time')
            ->first();
    }

    /**
     * Get Webinar SessionTime Detail Ids By EventId
     *
     * @param  mixed $webinarEventId
     * @return void
     */
    public function getWebinarSessionTimeDetailIdsByEventId($webinarEventId)
    {
        return $this->newQuery()
            ->where('webinar_event_id', $webinarEventId)
            ->pluck('id');
    }

}
