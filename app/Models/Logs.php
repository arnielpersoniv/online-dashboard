<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DateTimeInterface;

class Logs extends Model
{
    use HasFactory;

    protected $table = 'logs';
    protected $guarded = [];

    // protected $casts = [
    //     'created_at' => 'datetime:m/d/Y h:i:s',
    //     'updated_at' => 'datetime:m/d/Y h:i:s',
    // ];

    protected $dates = ['created_at','updated_at'];

    protected function serializeDate(DateTimeInterface $dates)
    {

        return Carbon::createFromFormat('Y-m-d H:i:s', $dates)->format('Y-m-d h:i:s');
    }

    public function users()
    {
        return $this->belongsTo(User::class,'user_id')->withTrashed();
    }
}
