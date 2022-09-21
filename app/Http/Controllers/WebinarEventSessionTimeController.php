<?php

namespace App\Http\Controllers;

use App\Validators\WebinarEventSessionTimeValidator;
use App\Repositories\WebinarEventSessionTimeRepository;
use App\Http\Resources\WebinarEventSessionTimeResource;
use App\Traits\Controller\RestControllerTrait;

class WebinarEventSessionTimeController extends Controller
{
    private $repository;

    private $validator;

    private $resource;

    private $updateFields = ['status'];

    use RestControllerTrait;

    public function __construct(WebinarEventSessionTimeRepository $repository, WebinarEventSessionTimeValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->resource = WebinarEventSessionTimeResource::class;
    }

}
