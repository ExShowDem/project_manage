<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class SupplyEachRequest extends BaseModel
{
    protected $table = 'supply_each_request';

    public function supply()
    {
        return $this->belongsTo(Supplies::class, 'supply_id');
    }

    /*
     * Hang muc
     */
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function request()
    {
        return $this->belongsTo(RequestSupply::class, 'supplies_request_id');
    }

    public function getDateArrivalAttribute($value)
    {
        return $this->getSerializeDateAttribute($value);
    }

    /**
     * So luong hang ton trong kho
     */
    public function inventory()
    {
        return $this->hasMany(Inventory::class, 'supply_id', 'supply_id')
            ->where('project_id', $this->project_id)
            ->limit(1);
    }

    public function getItemSupplyType($id)
    {
        $suppliesModel = resolve('App\Models\Supplies');

        return $suppliesModel->getItemSupplyType($id);
    }
}
