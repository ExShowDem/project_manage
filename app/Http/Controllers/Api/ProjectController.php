<?php


namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\ProjectRequest;
use App\Services\Api\ProjectService;
use Illuminate\Http\Request;
use App\Http\Resources\ProjectResource;

class ProjectController extends BaseController
{
    protected $service;
    protected $module = 'projects';

    public function __construct(ProjectService $service)
    {
        $this->service = $service;
    }

    public function store(ProjectRequest $request)
    {
        $inputs = $request->all();

        $inputs['created_by'] = auth()->id();

        return parent::genericSave($request, $inputs, 'create');
    }

    public function index(Request $request)
    {
        $params = $request->only('per_page', 'current_page', 'search_option');

        $items = ProjectResource::apiPaginate($this->service->getList($params), $request);

        return $this->responseSuccess($items);
    }

    public function destroy(Request $request, $id)
    {
        $result = $this->service->delete($id);

        if ($result) 
        {
            return $this->responseSuccess();
        }

        return $this->responseError('api.code.common.delete_failed');
    }

    public function show(Request $request, $id)
    {
        $item = $this->service->find($id);

        if ($item)
        {
            return $this->responseSuccess(compact('item'));
        }
        else
        {
            return $this->responseError('api.code.common.show_failed');
        }
    }

    public function update(ProjectRequest $request, $id)
    {
        $inputs = $request->all();

        return parent::genericSave($request, $inputs, 'update', $id);
    }
}
