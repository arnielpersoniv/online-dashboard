<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $table = 'tasks';
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:m/d/Y h:i:s',
        'updated_at' => 'datetime:m/d/Y h:i:s',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id')->withTrashed();
    }

    public function createdby()
    {
        return $this->belongsTo(User::class,'created_by')->withTrashed();
    }

    public function updatedby()
    {
        return $this->belongsTo(User::class,'updated_by')->withTrashed();
    }
}
