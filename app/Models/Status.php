<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Model
{
    use SoftDeletes;

    protected $table = 'statuses';
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:m/d/Y h:i:s',
        'updated_at' => 'datetime:m/d/Y h:i:s',
    ];

    public function createdby()
    {
        return $this->belongsTo(User::class,'created_by','id')->withTrashed();
    }

    public function updatedby()
    {
        return $this->belongsTo(User::class,'updated_by','id')->withTrashed();
    }
}