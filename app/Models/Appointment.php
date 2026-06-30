<?php

namespace App\Models;

use App\Enums\AppointmentStatus;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'attendant_id',
        'client_name',
        'client_email',
        'date',
        'start_time',
        'end_time',
        'status',
    ];

    protected $hidden = [
        'id',
        'attendant_id',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'start_time' => 'datetime:H:i',
            'end_time' => 'datetime:H:i',
            'status' => AppointmentStatus::class,
        ];
    }

    public function scopeScheduled(Builder $query): Builder
    {
        return $query->where('status', AppointmentStatus::AGENDADO);
    }

    public function scopeCancelled(Builder $query): Builder
    {
        return $query->where('status', AppointmentStatus::CANCELADO);
    }

    public function scopeByDate(Builder $query, string $date): Builder
    {
        return $query->where('date', $date);
    }

    public function scopeByAttendant(Builder $query, int|string $attendantId): Builder
    {
        return $query->where('attendant_id', $attendantId);
    }

    public function attendant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'attendant_id');
    }

    public function cancel(): void
    {
        $this->update(['status' => AppointmentStatus::CANCELADO]);
    }
}
