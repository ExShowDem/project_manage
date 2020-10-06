<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DeviceToProject;
use App\Models\DeviceReturnToCompany;

class DeviceStatsController extends BaseController
{
    protected $module = 'device_stats';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $deviceModel  = resolve('App\Models\Devices');
        $params       = $request->only('search_option');
        $searchOption = $params['search_option'] ?? null;
        $tally1       = $deviceModel->getExistingTally($searchOption);

        $urlPath   = 'api/devices/stats';
        $projectId = $request->input('project_id') ? : 1;
        $page      = (int) $request->input('page') ? : 1;
        $pageSize  = ($pageSizeInput = (int) $request->input('page_size') > 0) ? min($pageSizeInput, config('api.pagination.max_per_page')) : config('api.pagination.per_page');
        $noOfPages = ceil((float) count($tally1) / (float) $pageSize);

        $prevPageNo = ($page - 1 === 0) ? null : $page - 1;
        $nextPageNo = ($page + 1 > $noOfPages) ? null : $page + 1;
        $prevPageLink = is_null($prevPageNo) ? null : config('app.url') . $urlPath . '?project_id=' . $projectId . '&page=' . $prevPageNo;
        $nextPageLink = is_null($nextPageNo) ? null : config('app.url') . $urlPath . '?project_id=' . $projectId . '&page=' . $nextPageNo;

        $links = [
            'first' => config('app.url') . $urlPath . '?project_id=' . $projectId . '&page=1',
            'last'  => config('app.url') . $urlPath . '?project_id=' . $projectId . '&page=' . $noOfPages,
            'prev'  => ($noOfPages === 1) ? null : $prevPageLink,
            'next'  => ($noOfPages === 1) ? null : $nextPageLink,
        ];

        $meta = [
            'from' => ($noOfPages === 1) ? 1 : (($page - 1) * $pageSize) + 1,
            'to'   => ($noOfPages === 1) ? count($tally1) : ($page * $pageSize > count($tally1)) ? count($tally1) : $page * $pageSize,
            'current_page' => $page,
            'last_page'    => $noOfPages,
            'path'         => config('app.url') . $urlPath,
            'per_page'     => $pageSize,
            'total'        => ($noOfPages === 1) ? count($tally1) % $pageSize : ($page * $pageSize > count($tally1)) ? count($tally1) % $pageSize : $pageSize,
            'project_filtered' => isset($searchOption["project"]),
            'filtered_project_id' => isset($searchOption["project"]) ? (int)$searchOption["project"] : 0,
        ];

        $response          = [];
        $response['data']  = array_slice($tally1, $meta['from'] - 1, ($meta['to'] - $meta['from'] + 1), false);        
        $response['links'] = (object) $links;        
        $response['meta']  = (object) $meta;        

        return $this->responseSuccess($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
