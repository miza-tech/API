<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Cms\Permission;
use App\Models\Cms\Menu;
use App\Models\Cms\Role;
use App\Models\Cms\Department;

class CmsUser extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $table = 'cms_users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['phone', 'password', 'realname', 'idCard', 'department_id', 'age', 'gender', 'password_reset_needed', 'status'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','deleted_at'
    ];

    /**
     * 需要被转换成日期的属性。
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function isSuper()
    {
        $supers = explode(',', config('miza.super'));
        return in_array($this->phone, $supers);
    }

    public function roles()
    {
        return $this->belongsToMany('App\Models\Cms\Role', 'cms_role_user', 'user_id', 'role_id');
    }

    public function permissions($roles = null)
    {
        $roleIds = $roles ? $roles->pluck('id') : $this->roles()->pluck('id');
        $permissions = DB::table('cms_permission_role')->whereIn('role_id', $roleIds)->pluck('permission_id');
        return Permission::whereIn('id', $permissions);
    }

    public function menus($permissions = null)
    {
        $permissions = $permissions ? $permissions : $this->permissions();
        $menuIds = DB::table('cms_permission_menu')->whereIn('permission_id', $permissions->pluck('id'))->pluck('menu_id');

        return Menu::whereIn('id', $menuIds);
    }

    public function department()
    {
        return $this->belongsTo('App\Models\Cms\Department');
    }

    public function getRolesAttribute()
    {
        if ($this->isSuper()) {
            return collect(Role::list())->pluck('id')->toArray();
        } else {
            return $this->roles()->pluck('id');
        }
    }

    public static function profile()
    {
        $user = Auth::guard('cms')->user();
        if ($user) {
            if ($user->isSuper()) {
                $user->roles = collect(Role::list())->pluck('id')->toArray();
                $user->permissions = collect(Permission::list())->pluck('id')->toArray();
                $user->menus = collect(Menu::list())->pluck('id')->toArray();
            } else {
                $roles = $user->roles();
                $permissions = $user->permissions($roles);
                $menus = $user->menus($permissions);

                $user->roles = $roles->pluck('id');
                $user->permissions = $permissions->pluck('id');
                $user->menus = $menus->pluck('id');
            }

            $department = $user->department()->first();
            $idCard = $user->idCard . '';
            $idCardLen = strlen($idCard);
            if ($idCardLen >= 15) {
                $user->idCard = substr_replace($idCard, '************', 0, $idCardLen-4);
            }
            $user->phone = substr_replace($user->phone,'****',3,4);
            $user->department_name = $department ? $department->display_name : null;
        }
        return $user;
    }
}
