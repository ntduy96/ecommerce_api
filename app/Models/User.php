<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * User 
     */
    const CAN_CREATE_STORE = 'store:create';
    const CAN_UPDATE_STORE = 'store:update';
    const CAN_CREATE_PRODUCT = 'product:create';
    const CAN_UPDATE_PRODUCT = 'product:update';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

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
        'email_verified_at' => 'timestamp',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp'
    ];

    /**
     * Get the stores for the user.
     */
    public function stores()
    {
        return $this->hasMany(Store::class);
    }

    /**
     * Get the products for the user.
     */
    public function products()
    {
        return $this->hasManyThrough(Product::class, Store::class);
    }
}
