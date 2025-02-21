<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'address', 'phone_number'];

    /**
     * Relationship with Order (One-to-Many).
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

