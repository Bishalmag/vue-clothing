<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Correct import
use Illuminate\Notifications\Notifiable; // Correct import
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'admins';
    protected $primaryKey = 'id';

    protected $fillable = ['username', 'email', 'password', 'api_token'];

    protected $hidden = ['password', 'remember_token'];
}
