<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MppLink;
use Illuminate\Http\Request;

class MppLinkController extends BaseController
{
    public function store(Request $request, $workPlanId)
    {
        $link = new MppLink();

        $link->work_plan_id = $workPlanId;
        $link->type = $request->type;
        $link->source = $request->source;
        $link->target = $request->target;

        $link->save();

        return response()->json([
            "action" => "inserted",
            "tid" => $link->id
        ]);
    }

    public function update($workPlanId, $id, Request $request)
    {
        $link = MppLink::find($id);

        $link->type = $request->type;
        $link->source = $request->source;
        $link->target = $request->target;

        $link->save();

        return response()->json([
            "action" => "updated"
        ]);
    }

    public function destroy($workPlanId, $id)
    {
        $link = MppLink::find($id);

        $link->delete();

        return response()->json([
            "action" => "deleted"
        ]);
    }
}
