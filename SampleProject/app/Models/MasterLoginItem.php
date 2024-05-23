<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterLoginItem extends Model
{
	protected $table =  'master_login_item';
	public $incrementing = false;
	protected $primaryKey = 'login_day';
	public $timestamps = false;
}
