<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 *
 * @method inLowestPriorityOrder()
 */
class TaskPriority extends Model
{
    /** @var bool */
    public $timestamps = false;

    /**
     * @param Builder $query
     * @return Builder
     * @noinspection PhpUnused
     */
    public function scopeInLowestPriorityOrder(Builder $query): Builder
    {
        return $query->orderBy('priority');
    }

    /**
     * @return array
     */
    public function getIds(): array
    {
        return static::query()
            ->orderBy('priority')
            ->pluck($this->primaryKey)
            ->toArray();
    }
}
