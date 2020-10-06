<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UpdateUserRequest;
use App\Http\Requests\Api\UserRequest;
use App\Models\User;
use App\Services\Api\UserService;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource as Resource;
use Storage;

class UserController extends BaseController
{
    protected $service;
    protected $module = 'users';

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function store(UserRequest $request)
    {
        $inputs = $request->all();

        if ($request->hasFile('image'))
        {
            $image_name = date('d-m-Y')."-".time().".".$request->image->getClientOriginalExtension();
            Storage::putFileAs('images/avatars/', $request->file('image'), $image_name);
            $inputs['image'] = $image_name;
        } 
        else
        {
            $inputs['image'] = null;
        }

        $inputs['roles']    = json_decode($inputs['roles'], true);
        $inputs['projects'] = json_decode($inputs['projects'], true);

        return parent::genericSave($request, $inputs, 'create');
    }

    public function index(Request $request)
    {
        $params = $request->only('per_page', 'search_option', 'current_page');

        $items = Resource::apiPaginate($this->service->getList($params), $request);

        return $this->responseSuccess($items);
    }

    public function destroy($id)
    {
        $result = $this->service->delete($id);

        if ($result) 
        {
            return $this->responseSuccess();
        }

        return $this->responseError('api.code.common.delete_failed');
    }

    public function show($id)
    {
        $item = $this->service->find($id, ['projects', 'roles']);

        if ($item)
        {
            return $this->responseSuccess(compact(['item']));
        }
        else
        {
            return $this->responseError('api.code.common.show_failed');
        }
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $inputs = $request->all();

        if ($request->hasFile('image'))
        {
            $image_name = date('d-m-Y')."-".time().".".$request->image->getClientOriginalExtension();
            Storage::putFileAs('images/avatars/', $request->file('image'), $image_name);

            $user = User::findOrFail($id);
            $previousAvatar = $user->image;
            Storage::delete('images/avatars/'.$previousAvatar, 'public');
            $inputs['image'] = $image_name;
        } 
        else
        {
            $inputs['image'] = null;
        }

        $inputs['roles']    = json_decode($inputs['roles'], true);
        $inputs['projects'] = json_decode($inputs['projects'], true);

        return parent::genericSave($request, $inputs, 'update', $id);
    }

    public function storePush(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $endpoint = $request->endpoint;
        $token = $request->keys['auth'];
        $key = $request->keys['p256dh'];
        $user->updatePushSubscription($endpoint, $key, $token);

        return response()->json(['success' => true], 200);
    }

    public function autosuggest(Request $request, $name)
    {
        $users = User::where('name', 'like', $name . '%')->get();
        
        return $users;
    }
}
