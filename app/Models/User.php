<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;
use DateTimeInterface;

class User extends Authenticatable
{
    use SoftDeletes, HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'users'; 
    protected $guarded = [];
    protected $dates = ['created_at','updated_at'];

    protected function serializeDate(DateTimeInterface $dates)
    {

        return Carbon::createFromFormat('Y-m-d H:i:s', $dates)->format('Y-m-d h:i:s');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime:m/d/Y h:i:s',
        'updated_at' => 'datetime:m/d/Y h:i:s',
        'email_verified_at' => 'datetime',
    ];
    
    public function createdby()
    {
        return $this->belongsTo(User::class,'created_by')->withTrashed();
    }

    public function updatedby()
    {
        return $this->belongsTo(User::class,'updated_by')->withTrashed();
    }

}
