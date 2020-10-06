<?php

namespace App\Services\Api;

use App\Models\User;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class UserService extends BaseService
{
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function create($params)
    {
        try 
        {
            $this->checkPassword($params['password'], $params['password_confirm']);

            DB::beginTransaction();

            $user = parent::create([
                'name'     => $params['name'],
                'email'    => $params['email'],
                'password' => bcrypt($params['password']),
                'image'    => $params['image'],
            ]);

            $this->logPivotAction('attach', $user, 'roles', $params['roles']);
            $this->logPivotAction('attach', $user, 'projects', $params['projects']);

            DB::commit();

            $result = [
                'error' => false,
                'response' => $user,
            ];
        }
        catch (\Exception $e)
        {
            $result = [
                'error' => true,
                'key' => 'api.code.common.validate_failed',
                'data' => ['user' => [errorMessage($e)]],
            ];

            Log::error($result);

            DB::rollBack();
        }
        finally
        {
            return $result;
        }
    }

    private function checkPassword($password, $passwordConfirm)
    {
        if ($password !== $passwordConfirm)
        {
            throw new \Exception('Mật khẩu xác nhận không đúng.');
        }
    }

    public function update($id, $params)
    {
        try 
        {
            $entry = [
                'name'  => $params['name'],
                'image' => $params['image'],
            ];

            if (isset($params['password']) && isset($params['password_confirm']))
            {
                $this->checkPassword($params['password'], $params['password_confirm']);

                $entry['password'] = bcrypt($params['password']);
            }

            DB::beginTransaction();

            $user = parent::update($id, $entry);

            $this->logPivotAction('sync', $user, 'roles', $params['roles']);
            $this->logPivotAction('sync', $user, 'projects', $params['projects']);

            DB::commit();

            $this->updateNotify($user, $id);

            $result = [
                'error' => false,
                'response' => $user->find($id)
            ];
        }
        catch (\Exception $e)
        {
            $result = [
                'error' => true,
                'key' => 'api.code.common.validate_failed',
                'data' => ['user' => [errorMessage($e)]],
            ];

            Log::error($result);

            DB::rollBack();
        }
        finally
        {
            return $result;
        }
    }

    public function getNoticeBody($attributes)
    {
        return 'Tài khoản bị đổi: ' . $attributes['name'];
    }

    public function getSpecNoticeUrl($attributes, $urlKey, $id)
    {
        return route($urlKey . '.edit', ['projectId' => $attributes['project_id'], 'id' => $id]);
    }

    public function getList($params)
    {
        $query = $this->model->with(['projects', 'roles']);

        if (isset($params['search_option']))
        {
            $searchOption = $params['search_option'];
        }

        if (isset($searchOption['keyword'])) 
        {
            $query = $query->whereNested(function($q) use ($searchOption) {
                $q
                    ->where('name', 'like', '%' . $searchOption['keyword'] . '%')
                    ->orWhere('email', 'like', '%' . $searchOption['keyword'] . '%');
            });
        }

        if (isset($searchOption['exclude_admin'])) 
        {
            $query = $query->where('id', '!=', 1);
        }

        if (isset($searchOption['date_from']) && isset($searchOption['date_till'])) 
        {
            $query = $query
                ->where('created_at', '>=', carbon_date($searchOption['date_from'])->startOfDay())
                ->where('created_at', '<=', carbon_date($searchOption['date_till'])->endOfDay());
        }

        if (isset($searchOption['project_id'])) 
        {
            $query = $query->whereIn('users.id', function ($subQuery) use ($searchOption) {
                $subQuery
                    ->select('user_project.user_id')
                    ->from('user_project')
                    ->where('user_project.project_id', $searchOption['project_id']);
            });
        }

        if (isset($searchOption['role'])) 
        {
            $roleId = $searchOption['role'];
            $roleIds = array_unique(array_merge([$roleId], get_child_roles([$roleId])));

            $query = $query->whereIn('id', function ($subQuery) use ($roleIds) {
                $subQuery
                    ->select('model_has_roles.model_id')
                    ->from('model_has_roles')
                    ->whereIn('role_id', $roleIds)
                    ->where('model_type', User::class);
            });
        }

        return $query->orderBy('created_at', 'desc');
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
