<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';
}
