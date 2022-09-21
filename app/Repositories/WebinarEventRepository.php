<?php

namespace App\Repositories;

use App\Models\WebinarEvent;
use App\Services\ODataService;

class WebinarEventRepository extends BaseRepository
{
    /**
     * @var WebinarEvent
     */
    protected $model;

    protected $request;

    protected $oDataService;

    protected $orgFilterFields = ['organization_id'];

    protected $fieldSearchable = ['event_name'];

    public function __construct()
    {
        $this->model = new WebinarEvent();
        $this->request = request();
        $this->oDataService = new ODataService();
    }

    /**
     * Get Application By Id
     *
     * @param  mixed $applicationId
     * @return void
     */
    public function getApplicationById($applicationId)
    {
        return $this->newQuery()
            ->where('id', $applicationId)
            ->first();
    }

    /**
     * Get Webinar Event Information
     *
     * @param  mixed $profileId
     * @return void
     */
    public function getWebinarEventInfo($eventId)
    {
        return $this->findBy('id', $eventId);
    }

     /**
     * Get Event Name Info by Id
     *
     * @param  mixed $eventId
     * @return void
     */
    public function getEventName($participantEventId)
    {
        return $this->newQuery()
            ->where(['id' => $participantEventId])
            ->pluck('event_name');
    }
}
