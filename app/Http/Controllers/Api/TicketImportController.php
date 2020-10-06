<?php

namespace App\Http\Controllers\Api;

use App\Enums\InvoiceStatus;
use App\Http\Requests\Api\TicketImportRequest;
use App\Services\Api\AttachFileService;
use App\Services\Api\CommentService;
use App\Services\Api\ProjectService;
use App\Services\Api\InvoiceService;
use App\Services\Api\TicketImportService;
use Illuminate\Support\Arr;

class TicketImportController extends BaseController
{
    protected $service;
    protected $fileService;
    protected $commentService;
    protected $projectService;
    protected $invoiceService;

    public function __construct(
        TicketImportService $service,
        AttachFileService $fileService,
        CommentService $commentService,
        ProjectService $projectService,
        InvoiceService $invoiceService
    ) {
        $this->service = $service;
        $this->fileService = $fileService;
        $this->commentService = $commentService;
        $this->projectService = $projectService;
        $this->invoiceService = $invoiceService;
    }

    public function store(TicketImportRequest $request, $id)
    {
        $projectId = $request->project_id;
        list ($importSup, $ticketImport) = $this->service->store($id, $request->all());
        $this->projectService->addSupplies($projectId, $importSup);
        $this->invoiceService->updateStatus($id, InvoiceStatus::IMPORTED);

        if (!empty($files = $request->get('files')) && is_array($files)) {
            $fileIds = Arr::pluck($files, 'id');
            $this->fileService->updateAbleType($fileIds, $ticketImport);
        }

        if (!empty($comments = $request->get('comments')) && is_array($comments)) {
            $commentIds = Arr::pluck($comments, 'id');
            $this->commentService->updateAbleType($commentIds, $ticketImport);
        }

        return $this->responseSuccess(compact('ticketImport'));
    }
}
