<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use SoftDeletes;

    protected $table = 'activities';
    protected $guarded = [];

    protected $casts = [
        'time_start' => 'datetime:m/d/Y h:i:s',
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'datetime:m/d/Y h:i:s',
    ];


    public function releasedby()
    {
        return $this->belongsTo(User::class,'released_by')->withTrashed();
    }

    public function status()
    {
        return $this->belongsTo(Status::class,'status_id')->withTrashed();
    }

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id')->withTrashed();
    }

    public function task()
    {
        return $this->belongsTo(Task::class,'task_id');
    }
}
