<?php

namespace App\Models;

use App\Enums\UserProfile;
use App\Traits\HasUuid;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasUuid, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile',
    ];

    protected $hidden = [
        'id',
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'profile' => UserProfile::class,
        ];
    }

    public function isAdmin(): bool
    {
        return $this->profile === UserProfile::ADMINISTRADOR;
    }

    public function isAttendant(): bool
    {
        return $this->profile === UserProfile::ATENDENTE;
    }

    public function availabilities(): HasMany
    {
        return $this->hasMany(Availability::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'attendant_id');
    }
}
