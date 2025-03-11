<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'is_subscribed',
        'profile_photo',
        'two_factor_code',
        'two_factor_expires_at',
        'google_id',
        'facebook_id',
        'github_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


public function sendPasswordResetNotification($token)
{
    $this->notify(new ResetPasswordNotification($token));
}


    public function regenerateTwoFactorCode(): void
    {
        $this->timestamps = false;
        $this->two_factor_code = random_int(100000, 999999);
        $this->two_factor_expires_at = now()->addMinutes(5);
        $this->email_verified_at = null;
        $this->save();
    }

    public function cleanTwoFactorCode()
    {
        $this->timestamps = false;
        $this->two_factor_code = null;
        $this->two_factor_expires_at = null;
        $this->save();
    }
}
