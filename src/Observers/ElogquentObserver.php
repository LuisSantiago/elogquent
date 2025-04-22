<?php

namespace Elogquent\Observers;

use Elogquent\Contracts\ElogquentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class ElogquentObserver
{
    public function __construct(private ElogquentRepositoryInterface $repository) {}

    public function updating(Model $model): void
    {
        $changes = $this->getChanges($model);
        if (empty($changes)) {
            return;
        }
        $userId = config('elogquent.store_user_id') ? auth()->user()?->getAuthIdentifier() : null;

        $this->repository->create(model: $model, userId: $userId);
    }

    private function getChanges(Model $model): array
    {
        $dirty = $model->getDirty();

        $included = config('elogquent.included_columns', []);
        if (! empty($included)) {
            $dirty = array_intersect_key($dirty, array_flip($included));
        }
        $excluded = config('elogquent.excluded_columns', []);
        if (! empty($excluded)) {
            $dirty = array_diff_key($dirty, array_flip($excluded));
        }

        return $dirty;
    }
}
