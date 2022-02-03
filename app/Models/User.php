<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'exchange_api_key',
        'exchange_secret_key',
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
        'email_verified_at' => 'datetime',
    ];

    public function setExchangeApiKeyAttribute(?string $value)
    {
        $this->attributes['exchange_api_key'] = $value ? Crypt::encrypt($value) : $value;
    }

    public function setExchangeSecretKeyAttribute(?string $value)
    {
        $this->attributes['exchange_secret_key'] = $value ? Crypt::encrypt($value) : $value;
    }

    public function getExchangeApiKeyAttribute($value)
    {
        return $value ? Crypt::decrypt($value) : null;
    }

    public function getExchangeSecretKeyAttribute($value)
    {
        return $value ? Crypt::decrypt($value) : null;
    }

    public function setPasswordAttribute(string $value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
}
