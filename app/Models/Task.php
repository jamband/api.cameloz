<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Expression;

/**
 * @property int $id
 * @property string $name
 * @property int $priority_id
 * @property int $project_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property TaskPriority $priority
 * @property Project $project
 *
 * @method Builder byPriorityId(int $priority_id)
 * @method Builder byProjectId(int $project_id)
 * @method Builder latest(string|Expression $column = null)
 */
class Task extends Model
{
    use HasFactory;

    /**
     * @return BelongsTo
     */
    public function priority(): BelongsTo
    {
        return $this->belongsTo(TaskPriority::class);
    }

    /**
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @param Builder $query
     * @param int $priority_id
     * @return Builder
     * @noinspection PhpUnused
     */
    public function scopeByPriorityId(Builder $query, int $priority_id): Builder
    {
        return $query->where('priority_id', $priority_id);
    }

    /**
     * @param Builder $query
     * @param int $project_id
     * @return Builder
     * @noinspection PhpUnused
     */
    public function scopeByProjectId(Builder $query, int $project_id): Builder
    {
        return $query->where('project_id', $project_id);
    }

    /**
     * @param Builder $query
     * @return Builder
     * @noinspection PhpUnused
     */
    public function scopeLatest(Builder $query): Builder
    {
        return $query->latest();
    }
}
