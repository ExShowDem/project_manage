<?php

namespace App\Services\Api;

use App\Models\Comment;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\MppTask;
use App\Models\OfferBuy;
use App\Models\Plan;
use App\Models\ReceiptInput;
use App\Models\ReceiptOutput;
use App\Models\ReceiptTransfer;
use App\Models\DeviceRoundRobin;
use App\Models\RequestSupply;
use App\Models\Stocktaking;
use App\Models\TicketImport;
use App\Models\PaymentOrder;
use App\Models\DeviceToProject;
use App\Models\DeviceReturnToCompany;
use App\Models\DeviceTransfer;
use App\Models\ContractSubContractor;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CommentService extends BaseService
{
    public function __construct(Comment $model)
    {
        $this->model = $model;
    }

    private function dataPolymorphic(Model $model)
    {
        return [
            'commentable_id' => $model->id,
            'commentable_type' => get_class($model),
        ];
    }

    public function create($request)
    {
        $commentContent = $request->get('content');
        $taskableId     = $request->get('id');
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
                $taskableName = $taskableAttr['name'];
                $taskableCode = $taskableAttr['code'];
                $projectId    = $taskableAttr['project_id'];
            }
        }

        $dataComment = [
            'from_user' => auth()->id(),
            'content'   => $commentContent,
        ];

        if ($taskable)
        {
            $dataComment = array_merge($dataComment, $this->dataPolymorphic($taskable));
        }

        $saveResult = parent::create($dataComment);

        if ($taskable)
        {
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
                'body'            => 'Bình luận mới tại: ' . $taskableName . ' [' . $taskableCode . ']',
                'project_id'      => $projectId,
                'targetable_id'   => $taskableId,
                'targetable_type' => $taskableType,
            ];

            $notice['content'] = $notice['body'];

            sendNotifications($toUserIds, $notice);
        }

        return $saveResult;
    }

    public function updateAbleType($ids, Model $model)
    {
        return $this->model::whereIn('id', $ids)
            ->update($this->dataPolymorphic($model));
    }

    public function createCommentWhenHandleTask($task, $comment)
    {
        if ($comment) {
            $commentInput = [
                'from_user' => auth()->id(),
                'content' => $comment,
                'commentable_id' => $task['taskable_id'],
                'commentable_type' => $task['taskable_type'],
            ];

            $this->model->create($commentInput);
        }
    }
}
