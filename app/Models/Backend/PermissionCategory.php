<?php
namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class PermissionCategory extends Model
{
	protected $table = 'backend_permission_categories';
	protected $fillable = ['display_name'];
}