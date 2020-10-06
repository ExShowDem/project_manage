<?php

namespace App\Models\Traits;

trait DeleteRelationsTrait
{
    public static function boot()
    {
        parent::boot();

        static::deleted(function ($model) {
            $model->supplies()->delete();
            $model->tasks()->delete();
        });
    }
}
