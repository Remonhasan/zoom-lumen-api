<?php

namespace App\Http\Controllers;

use App\Validators\LiveMeetingAccountValidator;
use App\Repositories\LiveMeetingAccountRepository;
use App\Http\Resources\LiveMeetingAccountResource;
use App\Traits\Controller\RestControllerTrait;

class LiveMeetingAccountController extends Controller
{
    private $repository;

    private $validator;

    private $resource;

    private $updateFields = ['status'];

    use RestControllerTrait;

    public function __construct(LiveMeetingAccountRepository $repository, LiveMeetingAccountValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->resource = LiveMeetingAccountResource::class;
    }

}
