<?php

namespace App\Models;

class ContractSubContractor extends BaseModel
{
    protected $table = 'contract_subcontractors';

    protected $fillable = [
        'subcontractor_id',
        'project_id',
        'contract_sign_date',
        'process', //( số ngày hoàn phải hoàn thành hợp đồng)
        'contract_form',
        'contract_number',
        'contract_annex_value',// 'Giá trị phụ lục hợp đồng,
        'contract_value',// 'Giá trị hợp đồng( có vat )(VND)',
        'contract_value_vat',// 'Giá trị Đã Thanh Toán(VND)',
        'value_custody_warranty', // giá trị tạm giữ bảo hành
        'content',
        'date_warranty',
        'status',
        'created_by',
        'construction_items'
    ];

    protected $casts = [
        'date_warranty' => 'datetime:Y-m-d H:i:s',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function subContractor()
    {
        return $this->belongsTo(Subcontractor::class, 'subcontractor_id');
    }

    public function paymentOrder()
    {
        return $this->hasMany(PaymentOrder::class, 'contract_subcontractor_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function files()
    {
        return $this->morphMany(AttachFile::class, 'fileable');
    }

    public function setDateWarrantyAttribute($value)
    {
        $this->attributes['date_warranty'] = $value->format('Y-m-d 23:59:59');
    }

    public function getRemainValueAttribute()
    {
        $contractValue = $this->contract_value_vat ?? $this->contract_value;
        $contractValue += $this->contract_annex_value;

        return $contractValue - $this->paymentOrder->sum('settlement_value');
    }
}
