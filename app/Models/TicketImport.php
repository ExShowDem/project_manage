<?php

namespace App\Models;

class TicketImport extends BaseModel
{
    protected $fillable = [
        'supplier_id',
        'project_id',
        'invoice_id',
        'date_import',
        'number_ticket',
        'reason',
        'contract_number',
        'status',
        'created_by',
    ];

    public function supplies()
    {
        return $this->belongsToMany(Supplies::class, 'ticket_import_supplies', 'ticket_import_id', 'supplies_id')
            ->withPivot([
                'quantity',
                'unit_price',
                'reason',
            ])
            ->withTimestamps();
    }

    public function files()
    {
        return $this->morphMany(AttachFile::class, 'fileable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
