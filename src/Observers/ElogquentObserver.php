<?php

namespace Elogquent\Observers;

use Elogquent\Contracts\ElogquentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class ElogquentObserver
{
    public function __construct(private ElogquentRepositoryInterface $repository) {}

    public function created(Model $model): void
    {
        $this->repository->create($model);
    }

    public function updating(Model $model): void
    {
        $this->repository->create($model);
    }

    public function deleted(Model $model): void
    {
        $this->repository->create($model);
    }
}
