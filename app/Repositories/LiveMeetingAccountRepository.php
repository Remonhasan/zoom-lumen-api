<?php

namespace App\Repositories;

use App\Models\LiveMeetingAccount;
use App\Services\ODataService;

class LiveMeetingAccountRepository extends BaseRepository
{
    /**
    * @var LiveMeetingAccount
    */
    protected $model;

    protected $request;

    protected $oDataService;

    protected $fieldSearchable = [];

    public function __construct()
    {
       $this->model         = new LiveMeetingAccount();
        }
 protected function init()
    {
        $this->request      = request();
        $this->oDataService = (new ODataService())->init();
    }
}
