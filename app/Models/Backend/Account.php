<?php
namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Account extends Model
{
	use SoftDeletes;
	protected $table = 'backend_accounts';
	protected $fillable = ['name', 'super_user_id', 'contact_phone', 'contact_name', 'status', 'display_name', 'description'];
	protected $hidden = ['deleted_at'];
    protected $dates = ['deleted_at'];

    public function administrator()
    {
        return $this->belongsTo('App\BackendUser', 'super_user_id');
    }
}