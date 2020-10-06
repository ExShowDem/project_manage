<?php

namespace App\Models\Traits;

use App\Events\ActionLogEvent;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

trait ActionLogTrait
{
    /**
     * Insert the given attributes and set the ID on the model.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  array  $attributes
     * @return void
     */
    protected function insertAndSetId(\Illuminate\Database\Eloquent\Builder $query, $attributes)
    {
        $keyName              = $this->getKeyName();
        $id                   = $query->insertGetId($attributes, $keyName);
        $attributes[$keyName] = $id;
        $table                = $this->getTable();
        $model                = static::class;
        $params               = [
                                    'before' => [], 
                                    'after'  => $attributes
                                ];

        event(new ActionLogEvent($model, $table, 'create', $keyName, $id, $params));

        $this->setAttribute($keyName, $id);
    } 

    /**
     * Perform a model update operation.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return bool
     */
    protected function performUpdate(\Illuminate\Database\Eloquent\Builder $query)
    {
        $keyName      = $this->getKeyName();
        $afterStates  = $this->getAttributes();
        $id           = $afterStates[$keyName];
        $table        = $this->getTable();
        $model        = static::class;
        $beforeStates = $this->getPreviousState($table, $keyName, $id);
        $result       = parent::performUpdate($query);
        $params       = [
                            'before' => $beforeStates,
                            'after'  => $afterStates
                        ];

        if ($result)
        {
            event(new ActionLogEvent($model, $table, 'update', $keyName, $id, $params));
        }

        return $result;
    }

    private function getPreviousState($table, $keyName, $keyValue)
    {
        $beforeStates = DB::table($table)->where($keyName, $keyValue)->first();

        return (array) $beforeStates;
    }

    /**
     * Delete the model from the database.
     *
     * @return bool|null
     *
     * @throws \Exception
     */
    public function delete()
    {
        $uses         = class_uses(static::class);
        $softDelete   = 'Illuminate\Database\Eloquent\SoftDeletes';
        $isSoftDelete = isset($uses[$softDelete]) && ($uses[$softDelete] === $softDelete);
        $method       = $isSoftDelete ? 'soft_delete' : 'delete';
        $keyName      = $this->getKeyName();
        $state        = $this->getAttributes();
        $id           = $state[$keyName];
        $table        = $this->getTable();
        $beforeStates = $softDelete ? DB::table($table)->where($keyName, $id)->first() : $state;
        $model        = static::class;
        $result       = parent::delete();
        $afterStates  = $softDelete ? DB::table($table)->where($keyName, $id)->first() : [];
        $params       = [
                            'before' => $beforeStates, 
                            'after'  => $afterStates
                        ];

        if ($result)
        {
            event(new ActionLogEvent($model, $table, $method, $keyName, $id, $params));
        }

        return $result;
    }
}
