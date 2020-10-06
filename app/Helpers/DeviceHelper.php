<?php

use Illuminate\Support\Facades\DB;
use App\Enums\CommonStatus;

function get_existing_device_quantities()
{
    $inputDevices = DB::table('device_input_devices')
        ->join('devices_input', 'devices_input.id', '=', 'device_input_devices.device_input_id')
        ->whereNull('devices_input.deleted_at')
        ->where('devices_input.project_id', 1)
        ->where('devices_input.status', CommonStatus::APPROVED)
        ->select('devices_id', DB::raw("SUM(quantity) AS sum_quantity"))
        ->groupBy('devices_id')
        ->get()
        ->keyBy('devices_id')
        ->toArray();

    // Lent
    $projectDevices = DB::table('device_project_devices')
        ->join('devices_to_project', 'devices_to_project.id', '=', 'device_project_devices.device_project_id')
        ->whereNull('devices_to_project.deleted_at')
        ->where('devices_to_project.status', CommonStatus::APPROVED)
        ->select('devices_id', DB::raw("SUM(quantity) AS sum_quantity"))
        ->groupBy('devices_id')
        ->get()
        ->keyBy('devices_id')
        ->toArray();

    // Returned
    $companyDevices = DB::table('device_company_devices')
        ->join('devices_return_to_company', 'devices_return_to_company.id', '=', 'device_company_devices.device_company_id')
        ->whereNull('devices_return_to_company.deleted_at')
        ->where('devices_return_to_company.status', CommonStatus::APPROVED)
        ->select('devices_id', DB::raw("SUM(quantity_returned) AS sum_quantity_returned"))
        ->groupBy('devices_id')
        ->get()
        ->keyBy('devices_id')
        ->toArray();

    // Cleared
    $clearanceDevices = DB::table('device_clearance_devices')
        ->join('device_clearances', 'device_clearances.id', '=', 'device_clearance_devices.device_clearance_id')
        ->whereNull('device_clearances.deleted_at')
        ->where('device_clearances.status', CommonStatus::APPROVED)
        ->select('devices_id', DB::raw("SUM(quantity) AS sum_quantity"))
        ->groupBy('devices_id')
        ->get()
        ->keyBy('devices_id')
        ->toArray();

    return [$inputDevices, $projectDevices, $companyDevices, $clearanceDevices];
}

function get_passed_around_devices($refProjectId)
{
    // Passed from
    $losses = DB::table('device_round_robin_devices')
        ->join('device_round_robins', 'device_round_robins.id', '=', 'device_round_robin_devices.device_round_robin_id')
        ->whereNull('device_round_robins.deleted_at')
        ->where('device_round_robins.status', CommonStatus::APPROVED)
        ->where('device_round_robins.from_project_id', $refProjectId)
        ->select('devices_id', DB::raw("SUM(quantity) AS sum_quantity"))
        ->groupBy('devices_id')
        ->get()
        ->keyBy('devices_id')
        ->toArray();

    // Passed to
    $gains = DB::table('device_round_robin_devices')
        ->join('device_round_robins', 'device_round_robins.id', '=', 'device_round_robin_devices.device_round_robin_id')
        ->whereNull('device_round_robins.deleted_at')
        ->where('device_round_robins.status', CommonStatus::APPROVED)
        ->where('device_round_robins.to_project_id', $refProjectId)
        ->select('devices_id', DB::raw("SUM(quantity) AS sum_quantity"))
        ->groupBy('devices_id')
        ->get()
        ->keyBy('devices_id')
        ->toArray();

    return [$gains, $losses];
}
function get_task_by_user($idUser,$idProject)
{
    if($idProject != 0){
        // Passed from
        $taskUsers = DB::table('tasks')
            ->where('to_user', $idUser)->where('project_id',$idProject)->count();
        $taskUsersDone = DB::table('tasks')
            ->where('to_user', $idUser)->where('project_id',$idProject)->where('status',3)->count();
        $percentDone = @(round(($taskUsersDone*100)/$taskUsers));

        $userLate = DB::table('tasks')->where('to_user', $idUser)->where('project_id',$idProject)->get();
        $now = time();
        $arrToGroup = array();
        foreach($userLate as $key=>$value_ul){
            $target_ul = strtotime($value_ul->due_date);
            $diff_ul = $now - $target_ul;
            if ( $diff_ul > 900 ) {
                $arrToGroup[$key]['to_user'] =  $value_ul->to_user;

            }
        }
        $userDue = count($arrToGroup);
    }else{
        // Passed from
        $taskUsers = DB::table('tasks')
            ->where('to_user', $idUser)->count();
        $taskUsersDone = DB::table('tasks')
            ->where('to_user', $idUser)->where('status',3)->count();
        $percentDone = @(round(($taskUsersDone*100)/$taskUsers));

        $userLate = DB::table('tasks')->where('to_user', $idUser)->get();
        $now = time();
        $arrToGroup = array();
        foreach($userLate as $key=>$value_ul){
            $target_ul = strtotime($value_ul->due_date);
            $diff_ul = $now - $target_ul;
            if ( $diff_ul > 900 ) {
                $arrToGroup[$key]['to_user'] =  $value_ul->to_user;

            }
        }
        $userDue = count($arrToGroup);
    }


    return [$taskUsers,$taskUsersDone,$percentDone,$userDue];
}


