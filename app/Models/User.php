<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'User';
    
    protected $fillable = [
        'name', 'phone', 'email', 'login', 'password_hash', 'address', 'role_id'
    ];
    
    public $timestamps = false;
    
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
    
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }
    
    public function isAdmin()
    {
        return $this->role_id === 1;
    }
    
    public function isClient()
    {
        return $this->role_id === 2;
    }
}