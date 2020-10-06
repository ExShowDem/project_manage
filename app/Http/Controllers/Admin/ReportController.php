<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Api\SupplierRequest;
use App\Http\Resources\SuppliesByRequestResource;
use App\Models\Task;
use App\Models\Plan;
use App\Models\Invoice;
use App\Models\DevicePurchase;
use App\Models\CategorySupplies;
use App\Models\RequestSupply;
use App\Models\OfferBuy;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ReportController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index($id){
        if (!auth()->user()->can('dashboard.read')) {
            return redirect(route('admin.error')); //abort(403);
        }
        $results = Task::all()->where('project_id', $id)->toArray();
        $workProgress = count($results);
        $date_have_due = array();
        $work_done = array();
        $now = time();
        foreach ($results as $value) {
            $target = strtotime($value['due_date']);
            $diff = $now - $target;
            if ($diff > 900) {
                $date_have_due[] = $diff;
                if ($value['status'] != 1) {
                    $work_done[] = $diff;
                }

            }
        }
        $date_have_due = count($date_have_due);
        $count_work_done = count($work_done);
        //dd($date_have_due*100/$workProgress);
        $percentTask = @(round(($date_have_due * 100) / $workProgress));
        //$percentTaskDone = 100 - $percentTask;
        $percentTaskDone = @(round(($count_work_done * 100) / $workProgress));


        $contractSubcontractor = DB::table('contract_subcontractors')
            ->select('status', DB::raw('count(*) as total'))->where('project_id', $id)
            ->groupBy('status')
            ->get();
        $totalConSub = DB::table('contract_subcontractors')->where('project_id', $id)->count();
//

        /* $userLate = DB::table('tasks')
             ->select('to_user', DB::raw('count(*) as due_date'))->where('project_id',$id)
             ->groupBy('to_user')
             ->get();
         $arrToGroup = array();
         $userLate = DB::table('tasks')->where('project_id',$id)->get();

         foreach($userLate as $key=>$value_ul){
             $target_ul = strtotime($value_ul->due_date);
             $diff_ul = $now - $target_ul;
             if ( $diff_ul > 900 ) {
                 $arrToGroup[$key]['to_user'] =  $value_ul->to_user;
                 $arrToGroup[$key]['due_date'] =  $value_ul->due_date;

             }
         }
         $userDue = collect($arrToGroup)->groupBy('to_user');*/


        $statistic = [
            'tasks_need_handle' => Task::where('status', 1)->where('project_id', $id)->count(),
            'tasks_have_handle' => Task::where('status', '<>', 1)->count(),
            'date_have_due' => $date_have_due,
            'work_in_progress' => $workProgress,
            'percentTask' => is_nan($percentTask) ? 0 : ($percentTask ?? 0),
            'count_work_done' => $count_work_done,
            'percent_task_done' => is_nan($percentTaskDone) ? 0 : ($percentTaskDone ?? 0),
            'id_project' => $id,
            'contract_subcontractor' => $contractSubcontractor,
            'totalConSub' => $totalConSub,
            'top_subcontractors' => $this->getTopSubcontractors($id),

        ];
        $userExcelent = User::where('email_verified_at', '<>', null)->get();
        return view('admin.report.index', compact('statistic','userExcelent'));
    }

    public function reportAll()
    {

            if (!auth()->user()->can('dashboard.read')) {
                return redirect(route('admin.error')); //abort(403);
            }
            $results = Task::all()->toArray();
            $workProgress = count($results);
            $date_have_due = array();
            $work_done = array();
            $now = time();
            foreach ($results as $value) {
                $target = strtotime($value['due_date']);
                $diff = $now - $target;
                if ($diff > 900) {
                    $date_have_due[] = $diff;
                    if ($value['status'] != 1) {
                        $work_done[] = $diff;
                    }

                }
            }
            $date_have_due = count($date_have_due);
            $count_work_done = count($work_done);
            //dd($date_have_due*100/$workProgress);
            $percentTask = @(round(($date_have_due * 100) / $workProgress));
            //$percentTaskDone = 100 - $percentTask;
            $percentTaskDone = @(round(($count_work_done * 100) / $workProgress));


            $contractSubcontractor = DB::table('contract_subcontractors')
                ->select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->get();
            $totalConSub = DB::table('contract_subcontractors')->count();
//

            /* $userLate = DB::table('tasks')
                 ->select('to_user', DB::raw('count(*) as due_date'))->where('project_id',$id)
                 ->groupBy('to_user')
                 ->get();
             $arrToGroup = array();
             $userLate = DB::table('tasks')->where('project_id',$id)->get();

             foreach($userLate as $key=>$value_ul){
                 $target_ul = strtotime($value_ul->due_date);
                 $diff_ul = $now - $target_ul;
                 if ( $diff_ul > 900 ) {
                     $arrToGroup[$key]['to_user'] =  $value_ul->to_user;
                     $arrToGroup[$key]['due_date'] =  $value_ul->due_date;

                 }
             }
             $userDue = collect($arrToGroup)->groupBy('to_user');*/


            $statistic = [
                'tasks_need_handle' => Task::where('status', 1)->count(),
                'tasks_have_handle' => Task::where('status', '<>', 1)->count(),
                'date_have_due' => $date_have_due,
                'work_in_progress' => $workProgress,
                'percentTask' => is_nan($percentTask) ? 0 : ($percentTask ?? 0),
                'count_work_done' => $count_work_done,
                'percent_task_done' => is_nan($percentTaskDone) ? 0 : ($percentTaskDone ?? 0),
                'contract_subcontractor' => $contractSubcontractor,
                'id_project' => 0,
                'totalConSub' => $totalConSub,
                'top_subcontractors' => $this->getTopSubcontractors(),

            ];
            $userExcelent = User::where('email_verified_at', '<>', null)->get();

        return view('admin.report.index', compact('statistic','userExcelent'));
    }

    private function getTopSubcontractors($projectId = null)
    {
        $tally = DB::table('subcontractors')
            ->join('contract_subcontractors', 'contract_subcontractors.subcontractor_id', '=', 'subcontractors.id')
            ->leftJoin('payment_orders', 'payment_orders.subcontractor_id', '=', 'subcontractors.id');

        if (!is_null($projectId))
        {
            $tally = $tally->where('contract_subcontractors.project_id', '=', $projectId);
        }

        $tally = $tally
            ->groupBy('subcontractors.id', 'subcontractors.name')
            ->select(
                'subcontractors.id AS id', 
                'subcontractors.name',
                DB::raw("SUM(contract_subcontractors.contract_value_vat) AS sum_contract_value"),
                DB::raw("SUM(payment_orders.settlement_value) AS sum_settlement_value")
            )
            ->orderBy('sum_contract_value', 'desc')
            ->get()
            ->keyBy('id')
            ->toArray();

        return $tally;
    }
}
