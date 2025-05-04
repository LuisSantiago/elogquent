<?php

namespace Elogquent\Contracts;

use Illuminate\Database\Eloquent\Model;

interface ElogquentRepositoryInterface
{
    public function create(
        string $modelClass,
        string $modelKey,
        array $changes,
        ?string $userId = null,
    ): void;

    public function removeChanges(
        string $modelClass,
        string $modelKey,
        array $changes,
    ): void;

    public function removeExceededLimit(
        string $modelClass,
        string $modelKey,
        int $limit,
    ): void;

    public function restoreChanges(
        Model $model,
        array $changes,
    ): void;
}
