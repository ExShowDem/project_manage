<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Services\Api\CommentService;

class CommentController extends BaseController
{
    protected $service;

    public function __construct(CommentService $service)
    {
        $this->service = $service;
    }

    public function store(CommentRequest $request)
    {
        $comment = $this->service->create($request);

        return new CommentResource($comment);
    }
}
