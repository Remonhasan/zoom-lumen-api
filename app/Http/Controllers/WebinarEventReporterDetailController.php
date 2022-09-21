<?php

namespace App\Http\Controllers;

use App\Validators\WebinarEventReporterDetailValidator;
use App\Repositories\WebinarEventReporterDetailRepository;
use App\Http\Resources\WebinarEventReporterDetailResource;
use App\Traits\Controller\RestControllerTrait;

class WebinarEventReporterDetailController extends Controller
{
    private $repository;

    private $validator;

    private $resource;

    private $updateFields = ['status'];

    use RestControllerTrait;

    public function __construct(WebinarEventReporterDetailRepository $repository, WebinarEventReporterDetailValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->resource = WebinarEventReporterDetailResource::class;
    }

}
