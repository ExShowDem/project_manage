<?php

namespace App\Services\Api;

use App\Models\AttachFile;
use App\Models\MppTask;
use App\Models\OfferBuy;
use App\Models\Invoice;
use App\Models\ReceiptInput;
use App\Models\ReceiptOutput;
use App\Models\ReceiptTransfer;
use App\Models\DeviceRoundRobin;
use App\Models\DeviceTransfer;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\DeviceToProject;
use App\Models\DeviceReturnToCompany;
use App\Models\ContractSubContractor;

class AttachFileService extends BaseService
{
    protected $projectService;

    public function __construct(AttachFile $model, ProjectService $projectService)
    {
        $this->model = $model;
        $this->projectService = $projectService;
    }

    private function dataPolymorphic(Model $model)
    {
        return [
            'fileable_type' => get_class($model),
            'fileable_id' => $model->id,
        ];
    }

    public function create($request)
    {
        $file       = $request->file;
        $extension  = $file->getClientOriginalExtension() ?: $file->extension();
        $taskableId = $request->get('id');
        list($taskableType, $path, $urlKey) = detectModel($request);
        $taskable     = $taskableType::find($taskableId);

        if ($taskable)
        {
            $taskableAttr = $taskable->getAttributes();

            if ($taskable instanceof MppTask)
            {
                $projectId = $taskable->workPlan->project_id;
                $taskableName      = $taskable['text'];
                $taskableCode      = $taskable['id'];
            }
            else if ($taskable instanceof DeviceToProject || $taskable instanceof DeviceReturnToCompany)
            {
                $taskableName = '';
                $taskableCode = $taskableAttr['code'];
                $projectId    = $taskableAttr['project_id'];
            }
            else if ($taskable instanceof OfferBuy)
            {
                $taskableName = $taskableAttr['name'];
                $taskableCode = $taskableAttr['ticket_number'];
                $projectId    = $taskableAttr['project_id'];
            }
            else if ($taskable instanceof Invoice)
            {
                $taskableName = $taskableAttr['contract_number'];
                $taskableCode = $taskableAttr['code'];
                $projectId    = $taskableAttr['project_id'];
            }
            else if ($taskable instanceof ReceiptInput || $taskable instanceof ReceiptTransfer)
            {
                $taskableName = '';
                $taskableCode = $taskableAttr['code'];
                $projectId    = $taskableAttr['input_id'];
            }
            else if ($taskable instanceof ReceiptOutput)
            {
                $taskableName = '';
                $taskableCode = $taskableAttr['code'];
                $projectId    = $taskableAttr['output_id'];
            }
            else if ($taskable instanceof DeviceRoundRobin)
            {
                $taskableName = $taskableAttr['name'];
                $taskableCode = $taskableAttr['code'];
                $projectId    = $taskableAttr['from_project_id'];
            }
            else if ($taskable instanceof DeviceTransfer)
            {
                $taskableName = $taskableAttr['name'];
                $taskableCode = $taskableAttr['device_issuance_id'];
                $projectId    = $taskableAttr['project_id'];
            }
            else if ($taskable instanceof ContractSubContractor)
            {
                $taskableName = '';
                $taskableCode = '';
                $projectId    = $taskableAttr['project_id'];
            }
            else
            {
                $taskableName = $taskableAttr['name'] ?? '';
                $taskableCode = $taskableAttr['code'] ?? '';
                $projectId    = $taskableAttr['project_id'];
            }
        }

        $fullPath  = $file->storePublicly($path);
        $dataFiles = [
            'path'      => $fullPath,
            'real_name' => $file->getClientOriginalName(),
            'extension' => $extension,
        ];

        if ($taskable)
        {
            if ($taskable instanceof PaymentOrder)
            {
                $project = $taskable->project;

                if (!now()->isSameMonth(($project->file_in_month)))
                {
                    $project->file_in_month = now();
                    $project->number_file = 1;
                }
                else
                {
                    $project->number_file += 1;
                }

                $project->save();
                $dataFiles['real_name'] = generate_attach_file_name_payment_order($project, $extension);
            }

            $dataFiles = array_merge($dataFiles, $this->dataPolymorphic($taskable));

            $toUserIds = getNotifiedUsers($taskable);

            $url = '';

            if (strtolower($request->get('type')) === 'request')
            {
                $url = route($urlKey . '.show', ['projectId' => $projectId, 'target' => $taskableAttr['target'], 'id' => $taskableId]);
            }
            else
            {
                $url = route($urlKey . '.show', ['projectId' => $projectId, 'id' => $taskableId]);
            }

            $notice = [
                'url'             => $url,
                'body'            => 'Tệp đính kèm mới tại: ' . $taskableName . ' [' . $taskableCode . ']',
                'project_id'      => $projectId,
                'targetable_id'   => $taskableId,
                'targetable_type' => $taskableType,
            ];

            $notice['content'] = $notice['body'];

            sendNotifications($toUserIds, $notice);
        }

        return parent::create($dataFiles);
    }

    public function updateAbleType($ids, Model $model)
    {
        return $this->model->whereIn('id', $ids)
            ->update($this->dataPolymorphic($model));
    }

    public function updateFileNamePaymentOrder($files, $projectId)
    {
        try {
            DB::beginTransaction();
            $project = $this->projectService->find($projectId);
            if (!now()->isSameMonth(($project->file_in_month))) {
                $project->file_in_month = now();
            }

            foreach ($files as $file) {
                $project->number_file += 1;
                $file = $this->find($file['id']);
                $file->real_name = generate_attach_file_name_payment_order($project, $file->extension);
                $file->save();
            }

            $project->save();

            DB::commit();
        } catch (\Exception $e) {
            Log::debug($e);
            DB::rollBack();
        }
    }

    public function history($input)
    {
        return $this->model
            ->with(['userCreated', 'userDeleted'])
            ->where('fileable_id', $input['fileable_id'])
            ->where('fileable_type', $input['fileable_type'])
            ->withTrashed()
            ->get();
    }
}
