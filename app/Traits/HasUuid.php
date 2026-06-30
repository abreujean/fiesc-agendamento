<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasUuid
{
    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->public_id)) {
                $model->public_id = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'public_id';
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $field ? parent::resolveRouteBinding($value, $field) : parent::resolveRouteBinding($value, 'public_id');
    }
}
