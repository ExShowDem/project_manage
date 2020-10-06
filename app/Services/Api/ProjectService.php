<?php
/**
 * Created by PhpStorm.
 * User: vohongquan
 * Date: 7/24/19
 * Time: 12:54 PM
 */

namespace App\Services\Api;

use App\Models\Project;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Artisan;

class ProjectService extends BaseService
{
    public function __construct(Project $model)
    {
        $this->model = $model;
    }

    public function smoothDataBeforeSave($inputs, $fields = [])
    {
        if (isset($inputs['image_base64']) && $inputs['image_base64'] && isset($inputs['id'])) 
        {
            $inputs['featured_image'] = upload_image_base64($inputs['image_base64'], 'projects/' . $inputs['id']);
        }

        return [$inputs, [], ''];
    }

    public function create($inputs)
    {
        return $this->createGeneric($inputs, 'project');
    }

    public function createSpec(&$entry, &$inputs, &$pivotData)
    {
        if (isset($inputs['image_base64']) && $inputs['image_base64']) 
        {
            $entry->featured_image = upload_image_base64($inputs['image_base64'], 'projects/' . $entry->id);
            $entry->save();
        }

        $exitCode = Artisan::call('db:seed', ['--class' => 'PermissionTableSeeder']);

        if ($exitCode !== 0)
        {
            throw new \Exception('Project Permissions did not sync properly. Please contact admin.');
        }
    }

    public function update($id, $inputs)
    {
        return $this->updateGeneric($id, $inputs, 'project');
    }

    public function getNoticeBody($attributes)
    {
        return 'Dự án bị đổi: ' . $attributes['name'];
    }

    public function getSpecNoticeUrl($attributes, $urlKey, $id)
    {
        return route($urlKey . '.edit', ['projectId' => $attributes['project_id']]);
    }

    public function getList($params)
    {
        $query = $this->model;

        if (isset($params['search_option']))
        {
            $searchOption = $params['search_option'];
        }

        if (isset($searchOption['keyword'])) 
        {
            $query = $query->whereNested(function($q) use ($searchOption) {
                $q->where('name', 'like', '%' . $searchOption['keyword'] . '%')
                    ->orWhere('code', 'like', '%' . $searchOption['keyword'] . '%')
                    ->orWhere('description', 'like', '%' . $searchOption['keyword'] . '%');
            });
        }

        if (isset($searchOption['date_from']) && isset($searchOption['date_till'])) 
        {
            $query = $query
                ->where('created_at', '>=', carbon_date($searchOption['date_from'])->startOfDay())
                ->where('created_at', '<=', carbon_date($searchOption['date_till'])->endOfDay());
        }

        if (isset($params['hide_first_project']))
        {
            $query = $query->where('id', '<>', 1);
        }

        $userPermissions = auth()->user()->getPermissionsViaRoles()->toArray();
        $userPermissions = array_values($userPermissions);
        $userProjectPermissions = preg_grep('/^project_/', $userPermissions);

        $allowedProjectIds = array_map(function($perm) {
            return (int) str_replace( 'project_', '', str_replace('.read', '', $perm) );
        }, $userProjectPermissions);

        $allowedProjectIds = array_values($allowedProjectIds);

        if (!empty($allowedProjectIds))
        {
            $query = $query->whereIn('id', $allowedProjectIds);
        }

        return $query->orderBy('created_at', 'desc');
    }

    /**
     * @param $id
     * @param $supplies: [supplies_id => quantity]
     * @return bool
     */
    public function addSupplies($id, $supplies)
    {
        $project         = $this->model->find($id);
        $currentSupplies = $project->supplies->pluck('pivot.quantity', 'id');

        $syncData = [];

        foreach ($currentSupplies as $supId => $quantity) 
        {
            $addQuantity = 0;

            if (isset($supplies[$supId])) 
            {
                $addQuantity = $supplies[$supId];
                unset($supplies[$supId]);
            }

            $syncData[$supId] = ['quantity' => $quantity + $addQuantity];
        }

        $attachData = [];

        foreach ($supplies as $supId1 => $quantity1) 
        {
            $attachData[$supId1] = ['quantity' => $quantity1];
        }

        try 
        {
            DB::beginTransaction();

            $this->logPivotAction('sync', $project, 'supplies', $syncData);

            DB::commit();

            return true;
        } 
        catch (\Exception $e) 
        {
            DB::rollback();

            return false;
        }
    }

    public function getListSelect2($params, $select = [])
    {
        $query = $this->model;

        if ($select) 
        {
            $query = $query->select($select);
        }

        return $query->apiPaginate($params['per_page'] ?? null);
    }
}
