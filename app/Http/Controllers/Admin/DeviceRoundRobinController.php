<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\DeviceRoundRobin;

class DeviceRoundRobinController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.device.round_robin.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('device_round_robin.create')) 
        {
            return redirect(route('admin.error')); //abort(403);
        }

        $code = generate_code_for_model(new DeviceRoundRobin);
        $canApprove = (int) auth()->user()->can('device_round_robin.approve');

        return view('admin.device.round_robin.create', compact('code', 'canApprove'));
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
    public function show(Request $request, $projectId, $id)
    {
        if (!auth()->user()->can('device_round_robin.read')) 
        {
            return redirect(route('admin.error')); //abort(403);
        }

        $canApprove = (int) auth()->user()->can('device_round_robin.approve');

        return view('admin.device.round_robin.edit', compact('id', 'canApprove'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $projectId, $id)
    {
        if (!auth()->user()->can('device_round_robin.update')) 
        {
            return redirect(route('admin.error')); //abort(403);
        }

        $canApprove = (int) auth()->user()->can('device_round_robin.approve');

        return view('admin.device.round_robin.show', compact('id', 'canApprove'));
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
