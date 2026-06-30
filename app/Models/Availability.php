<?php

namespace App\Models;

use App\Enums\DayOfWeekEnum;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Availability extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'user_id',
        'day_of_week',
        'start_time',
        'end_time',
        'is_active',
    ];

    protected $hidden = [
        'id',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'day_of_week' => 'integer',
            'start_time' => 'datetime:H:i',
            'end_time' => 'datetime:H:i',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeByDayOfWeek(Builder $query, int $dayNumber): Builder
    {
        return $query->where('day_of_week', $dayNumber);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dayOfWeekLabel(): string
    {
        return DayOfWeekEnum::tryFrom($this->day_of_week)?->label() ?? '';
    }
}
