<?php

namespace App\Models;

use App\Enums\CommonStatus;
use App\Models\Traits\DeleteRelationsTrait;
use App\Models\Traits\TaskableTrait;
use BenSampo\Enum\Traits\CastsEnums;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfferBuy extends BaseModel
{
    use SoftDeletes, CastsEnums, TaskableTrait, DeleteRelationsTrait;

    protected $fillable = [
        'name',
        'date_offer',
        'ticket_number',
        'project_id',
        'supplier_id',
        'request_id',
        'created_by',
        'status',
    ];

    protected $casts = [
        'date_offer' => 'date:d/m/Y',
    ];

    protected $enumCasts = [
        'status' => CommonStatus::class,
    ];

    protected $with = [
        'creator',
    ];

    public function supplies()
    {
        return $this->belongsToMany(Supplies::class, 'offer_buy_supplies', 'offer_buy_id', 'supplies_id')
            ->withPivot([
                'quantity',
                'unit_price',
                'date_arrival',
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

    public function setDateOfferAttribute($value)
    {
        if ($value instanceof \DateTime) {
            $this->attributes['date_offer'] = $value;
        } else {
            $this->attributes['date_offer'] = carbon_date($value);
        }
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function request()
    {
        return $this->belongsTo(RequestSupply::class, 'request_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

}
