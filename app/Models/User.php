<?php

namespace App\Models;

use App\Models\Traits\EloquentTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use NotificationChannels\WebPush\HasPushSubscriptions;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\Traits\ActionLogTrait;

class User extends Authenticatable implements JWTSubject
{
    use HasPushSubscriptions, Notifiable, EloquentTrait, HasRoles, SoftDeletes;
    use ActionLogTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'image',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function projects()
    {
        return $this->belongsToMany(
            Project::class,
            'user_project',
            'user_id',
            'project_id');
    }

    public function getHierarchicalRoles()
    {
        $ownRoles = [];

        foreach ($this->roles->all() as $ownRole) 
        {
            $ownRoles[] = $ownRole->getAttributes();
        }
        
        $ownRoleIds = array_column($ownRoles, 'id');

        $roleIds = array_unique(array_merge($ownRoleIds, get_child_roles($ownRoleIds)));

        return Role::find($roleIds);
    }
}
