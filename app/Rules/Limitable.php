<?php

declare(strict_types=1);

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;

class Limitable implements Rule
{
    private Builder $query;
    private int $limit;

    /**
     * @param Builder $query
     * @param int $limit
     */
    public function __construct(Builder $query, int $limit)
    {
        $this->query = $query;
        $this->limit = $limit;
    }

    /**
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return $this->limit > $this->query->count();
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return trans('validation.limitable');
    }
}
