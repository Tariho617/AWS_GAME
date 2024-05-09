<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterQuest extends Model
{
    protected $table = 'master_quest';
    public $incrementing = false;
    protected $primaryKey = 'quest_id';
    public $timestamps = false;
}
