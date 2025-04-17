<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $fillable = ['name', 'admin_created_id', 'admin_updated_id'];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function adminCreated()
    {
        return $this->belongsTo(User::class, 'admin_created_id');
    }

    public function adminUpdated()
    {
        return $this->belongsTo(User::class, 'admin_updated_id');
    }
}
