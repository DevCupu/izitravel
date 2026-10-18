<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class LeadStage extends Model
{
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['key', 'label', 'kind', 'position', 'color'];

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('position')->orderBy('key');
    }

    public static function labels(): array
    {
        return static::ordered()->pluck('label', 'key')->all();
    }
}
