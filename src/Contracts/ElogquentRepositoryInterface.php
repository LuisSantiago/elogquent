<?php

namespace Elogquent\Contracts;

use Illuminate\Database\Eloquent\Model;

interface ElogquentRepositoryInterface
{
    public function create(Model $model, ?string $userId = null): void;
}
