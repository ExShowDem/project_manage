<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use App\Models\Traits\ActionLogTrait;

class AttachFile extends BaseModel
{
    use ActionLogTrait;

    protected $fillable = [
        'path',
        'real_name',
        'extension',
        'fileable_type',
        'fileable_id',
        'created_by',
        'deleted_by',
    ];

    protected $appends = [
        'link'
    ];

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $model->created_by = auth()->user()->id;
            $model->save();
        });

        static::deleted(function ($model) {
            $model->deleted_by = auth()->user()->id;
            $model->save();
        });
    }

    public function getLinkAttribute()
    {
        return Storage::url($this->path);
    }

    public function userCreated()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function userDeleted()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function getUserCreatedNameAttribute()
    {
        if (empty($this->userCreated)) {
            return '';
        }

        return $this->userCreated->name;
    }

    public function getUserDeletedNameAttribute()
    {
        if (empty($this->userDeleted)) {
            return '';
        }

        return $this->userDeleted->name;
    }

}
