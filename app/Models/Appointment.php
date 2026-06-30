<?php

namespace App\Models;

use App\Enums\AppointmentStatus;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Scopes\Scope;
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

    #[Hidden(['id', 'attendant_id'])]

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'start_time' => 'datetime:H:i',
            'end_time' => 'datetime:H:i',
            'status' => AppointmentStatus::class,
        ];
    }

    #[Scope]
    public function scheduled(Builder $query): Builder
    {
        return $query->where('status', AppointmentStatus::AGENDADO);
    }

    #[Scope]
    public function cancelled(Builder $query): Builder
    {
        return $query->where('status', AppointmentStatus::CANCELADO);
    }

    #[Scope]
    public function byDate(Builder $query, string $date): Builder
    {
        return $query->where('date', $date);
    }

    #[Scope]
    public function byAttendant(Builder $query, int|string $attendantId): Builder
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
