<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'dob',
        'address',
        'national_id',
        'photo_url',
        'nid_photo_url',
        'week_schedule',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'week_schedule' => 'array',
    ];

    // 🟢 ADD THIS RELATIONSHIP
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}