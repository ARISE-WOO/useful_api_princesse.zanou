<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'user_modules')
                    ->withPivot('active')
                    ->withTimestamps();
    }

    public function hasModuleActive($moduleId)
    {
        return $this->modules()
                    ->where('module_id', $moduleId)
                    ->wherePivot('active', true)
                    ->exists();
    }
}