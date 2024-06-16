<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OpenInfraTask extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'open_infra_tasks';
    protected $guarded = [];
    protected $casts = [
        'time_start' => 'datetime:m/d/Y h:i:s',
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'datetime:m/d/Y h:i:s',
    ];

    public function agent()
    {
        return $this->belongsTo(User::class,'agent_id')->withTrashed();
    }
}
