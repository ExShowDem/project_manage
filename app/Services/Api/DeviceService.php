<?php

namespace App\Services\Api;

use App\Models\Devices;
use App\Models\DeviceDetail;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class DeviceService extends BaseService
{
    public function __construct(Devices $model)
    {
        $this->model = $model;
    }

    public function getListSelect2($params, $select = [])
    {
        $query = $this->model;

        if ($select)
        {
            unset($select['name']);

            $select[] = DB::raw("(SELECT CONCAT(devices.name, ' - ', device_unit.name) 
                                    FROM units as device_unit 
                                    WHERE device_unit.id = devices.unit_id) as name"
            );

            $query = $query->select($select);
        }

        if (isset($params['search_option']['keyword']))
        {
            $query = $query->whereNested(function($q) use ($params) {
                $q
                    ->where('name', 'like', '%' . $params['search_option']['keyword'] . '%')
                    ->orWhere('code', 'like', '%' . $params['search_option']['keyword'] . '%');
            });
        }

        if (isset($params['search_option']['unit']))
        {
            $query = $query->where('unit_id', '=', $params['search_option']['unit']);
        }

        if (isset($params['search_option']['exclude_ids']))
        {
            $query = $query->whereNotIn('devices.id', $params['search_option']['exclude_ids']);
        }

        if (isset($params['search_option']['for_device_issuance']))
        {
            $query = $query
                ->join('units', 'devices.unit_id', '=', 'units.id')
                ->leftJoin(
                    DB::raw('(SELECT devices_id, project_id, SUM(mass) AS total_quantity FROM device_estimate_devices LEFT JOIN device_estimates ON device_estimate_devices.device_estimate_id = device_estimates.id AND device_estimates.deleted_at IS NULL GROUP BY devices_id, project_id) AS estimate_devices'),
                    'devices.id',
                    '=',
                    'estimate_devices.devices_id'
                )
                ->leftJoin(
                    DB::raw('(SELECT devices_id, SUM(quantity) AS monthly_estimated_quantity FROM device_monthly_estimate_devices LEFT JOIN device_monthly_estimates ON device_monthly_estimate_devices.device_monthly_estimate_id = device_monthly_estimates.id AND device_monthly_estimates.deleted_at IS NULL GROUP BY devices_id) AS monthly_estimate_devices'),
                    'devices.id',
                    '=',
                    'monthly_estimate_devices.devices_id'
                )
                ->leftJoin(
                    DB::raw('(SELECT devices_id, SUM(quantity) AS accumulated_quantity FROM device_issuance_devices LEFT JOIN device_issuances ON device_issuance_devices.device_issuance_id = device_issuances.id AND device_issuances.deleted_at IS NULL WHERE device_issuances.project_id = '.$params['search_option']['project_id'].' GROUP BY devices_id) AS issuance_devices'),
                    'devices.id',
                    '=',
                    'issuance_devices.devices_id'
                )
                ->select(
                    'devices.code AS code',
                    'devices.id AS id',
                    DB::raw("CONCAT(devices.name, ' - ', units.name) AS name"),
                    'units.id AS unit_id',
                    'units.name AS unit_name',
                    DB::raw('COALESCE(estimate_devices.total_quantity, 0) AS total_quantity'),
                    DB::raw('COALESCE(monthly_estimate_devices.monthly_estimated_quantity, 0) AS monthly_estimated_quantity'),
                    DB::raw('COALESCE(issuance_devices.accumulated_quantity, 0) AS accumulated_quantity'),
                    DB::raw('0 AS has_surpassed_estimates'),
                    DB::raw('"Có" AS has_surpassed_estimates_label')
                );

            $query = $query
                ->where('estimate_devices.project_id', $params['search_option']['project_id']);
        }

        return $query->apiPaginate($params['per_page'] ?? null);
    }

    public function getEstimateListSelect2($params)
    {
        $query =
            DB::table(
                DB::raw('(SELECT devices_id, project_id, SUM(mass) AS total_quantity FROM device_estimate_devices JOIN device_estimates ON device_estimate_devices.device_estimate_id = device_estimates.id GROUP BY devices_id, project_id) AS estimate_devices')
            )

            ->join('devices', 'estimate_devices.devices_id', '=', 'devices.id')
            ->join('units', 'devices.unit_id', '=', 'units.id');

        $select = [
            'devices.id AS id',
            DB::raw("CONCAT(devices.name, ' - ', units.name) AS name"),
            'devices.code AS code',
            'units.id AS unit_id',
            'units.name AS unit_name',
            'estimate_devices.total_quantity AS total_quantity',
            DB::raw("0 AS monthly_estimated_quantity"),
        ];

        if (isset($params['search_option']['for_monthly_estimates']))
        {
            $query = $query->leftJoin( 
                DB::raw('(SELECT devices_id, SUM(quantity) AS accumulated_quantity FROM device_monthly_estimate_devices JOIN device_monthly_estimates ON device_monthly_estimates.id=device_monthly_estimate_devices.device_monthly_estimate_id WHERE device_monthly_estimates.project_id='.$params['search_option']['project_id'].' GROUP BY devices_id) AS monthly_estimate_devices'),
                'monthly_estimate_devices.devices_id',
                '=',
                'estimate_devices.devices_id'
            );

            $select[] = DB::raw('COALESCE(monthly_estimate_devices.accumulated_quantity, 0) AS accumulated_quantity');
        }

        if (isset($params['search_option']['for_purchase_request']))
        {
            $query = $query->leftJoin( 
                DB::raw('(SELECT devices_id, SUM(quantity) AS input_cumulative FROM device_input_devices JOIN devices_input ON devices_input.id=device_input_devices.device_input_id WHERE devices_input.project_id='.$params['search_option']['project_id'].' AND devices_input.purchase_request_id='.$params['search_option']['this_request_id'].' GROUP BY devices_id) AS input_devices'),
                'input_devices.devices_id',
                '=',
                'estimate_devices.devices_id'
            );

            $select[] = DB::raw('COALESCE(input_devices.input_cumulative, 0) AS input_cumulative');
        }

        $query = $query->select($select);

        if (isset($params['search_option']['exclude_ids']))
        {
            $query = $query->whereNotIn('devices.id', $params['search_option']['exclude_ids']);
        }

        if (isset($params['search_option']['project_id']) && $params['search_option']['project_id'])
        {
            $query = $query->where('estimate_devices.project_id', $params['search_option']['project_id']);
        }

        return response(json_encode(['data' => $query->get()->toArray()]))
            ->header('Content-Type', 'application/json');
    }

    public function getMonthlyEstimateListSelect2($params)
    {
        $query = DB::table('device_monthly_estimate_devices')
            ->join('device_monthly_estimates', 'device_monthly_estimate_devices.device_monthly_estimate_id', '=', 'device_monthly_estimates.id')
            ->join('devices', 'device_monthly_estimate_devices.devices_id', '=', 'devices.id')
            ->join('units', 'devices.unit_id', '=', 'units.id')
            ->select(
                'devices.id AS id',
                DB::raw("CONCAT(devices.name, ' - ', units.name) AS name"),
                'devices.code AS code',
                'units.id AS unit_id',
                'units.name AS unit_name',
                'device_monthly_estimate_devices.quantity AS monthly_estimated_quantity'
            );

        if (isset($params['search_option']['exclude_ids']))
        {
            $query = $query->whereNotIn('devices.id', $params['search_option']['exclude_ids']);
        }

        if (isset($params['search_option']['project_id']) && $params['search_option']['project_id'])
        {
            $query = $query->where('device_monthly_estimates.project_id', $params['search_option']['project_id']);
        }

        return response(json_encode(['data' => $query->get()->toArray()]))
            ->header('Content-Type', 'application/json');
    }

    public function getIssuanceListSelect2($params)
    {
        $query = DB::table('device_issuance_devices')
            ->join('device_issuances', 'device_issuance_devices.device_issuance_id', '=', 'device_issuances.id')
            ->join('devices', 'device_issuance_devices.devices_id', '=', 'devices.id')
            ->join('units', 'devices.unit_id', '=', 'units.id')
            ->select(
                'devices.id AS id',
                DB::raw("CONCAT(devices.name, ' - ', units.name) AS name"),
                'devices.code AS code',
                'units.id AS unit_id',
                'units.name AS unit_name',
                'device_issuance_devices.quantity AS issued_quantity'
            );

        if (isset($params['search_option']['exclude_ids']))
        {
            $query = $query->whereNotIn('devices.id', $params['search_option']['exclude_ids']);
        }

        if (isset($params['search_option']['project_id']) && $params['search_option']['project_id'])
        {
            $query = $query->where('device_issuances.project_id', $params['search_option']['project_id']);
        }

        return response(json_encode(['data' => $query->get()->toArray()]))
            ->header('Content-Type', 'application/json');
    }

    public function getPurchaseRequestListSelect2($params)
    {
        $query = DB::table('device_purchase_request_devices')
            ->join('devices', 'device_purchase_request_devices.devices_id', '=', 'devices.id')
            ->join('units', 'devices.unit_id', '=', 'units.id')
            ->select(
                'devices.id AS id',
                DB::raw("CONCAT(devices.name, ' - ', units.name) AS name"),
                'devices.code AS code',
                'units.id AS unit_id',
                'units.name AS unit_name',
                'device_purchase_request_devices.quantity AS requested_quantity'
            );

        if (isset($params['search_option']['exclude_ids']))
        {
            $query = $query->whereNotIn('devices.id', $params['search_option']['exclude_ids']);
        }

        return response(json_encode(['data' => $query->get()->toArray()]))
            ->header('Content-Type', 'application/json');
    }

    public function getPurchaseListSelect2($params)
    {
        $query = DB::table('device_purchase_devices')
            ->join('devices', 'device_purchase_devices.devices_id', '=', 'devices.id')
            ->join('units', 'devices.unit_id', '=', 'units.id')
            ->select(
                'devices.id AS id',
                DB::raw("CONCAT(devices.name, ' - ', units.name) AS name"),
                'devices.code AS code',
                'units.id AS unit_id',
                'units.name AS unit_name',
                'device_purchase_devices.quantity AS needed_quantity'
            );

        if (isset($params['search_option']['exclude_ids']))
        {
            $query = $query->whereNotIn('devices.id', $params['search_option']['exclude_ids']);
        }

        return response(json_encode(['data' => $query->get()->toArray()]))
            ->header('Content-Type', 'application/json');
    }

    public function getList($params, $relations = [])
    {
        $query = $this->model;

        if (isset($params['project_id']) && $params['project_id'])
        {
            $query = $query->where('project_id', $params['project_id']);
        }

        if (isset($params['search_option']['keyword']))
        {
            $query = $query->whereNested(function($q) use ($params) {
                $q
                    ->where('name', 'like', '%' . $params['search_option']['keyword'] . '%')
                    ->orWhere('code', 'like', '%' . $params['search_option']['keyword'] . '%');
            });
        }

        $query->orderBy('created_at', 'desc');

        $query = $query->with($relations);

        return $query;
    }

    public function createItem($inputs)
    {
        try
        {
            DB::beginTransaction();

            $device = $this->model->create($inputs['basicInputs']);

            $device->deviceDetail()->create($inputs['detailInputs']);

            DB::commit();

            $result = [
                'error' => false,
                'response' => $device,
            ];
        } 
        catch (\Exception $e) 
        {
            $result = [
                'error' => true,
                'key' => 'api.code.common.validate_failed',
                'data' => ['device' => [errorMessage($e)]],
            ];

            Log::error($result);

            DB::rollBack();
        }
        finally
        {
            return $result;
        }
    }

    public function updateItem($inputs, $itemId)
    {
        $device = $this->model->findOrFail($itemId);

        try
        {
            DB::beginTransaction();

            $device->update($inputs['basicInputs']);

            DeviceDetail::where('devices_id', $device->id)->update($inputs['detailInputs']);

            DB::commit();

            $result = [
                'error' => false,
                'response' => $device,
            ];
        }
        catch (\Exception $e) 
        {
            $result = [
                'error' => true,
                'key' => 'api.code.common.validate_failed',
                'data' => ['device' => [errorMessage($e)]],
            ];

            Log::error($result);

            DB::rollBack();
        }
        finally
        {
            return $result;
        }
    }
}
