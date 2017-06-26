<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BackendUser extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $table = 'backend_users';
    protected $fillable = ['phone', 'username', 'backend_id','password', 'realname', 'idCard', 'department_id', 'age', 'gender', 'password_reset_needed', 'status'];
    protected $hidden = ['password', 'remember_token','deleted_at'];
    protected $dates = ['deleted_at'];

    public function roles()
    {
        return $this->belongsToMany('App\Models\Backend\Role', 'backend_role_user', 'user_id', 'role_id');
    }

    public function department()
    {
        return $this->belongsTo('App\Models\Backend\Department');
    }

    public function backend()
    {
    	return $this->belongsTo('App\Models\Backend\Account', 'backend_id');
    }

    public function getRolesAttribute()
    {
        return $this->roles()->pluck('id');
    }
}
