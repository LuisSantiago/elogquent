<?php

namespace Elogquent\Repositories;

use Elogquent\Contracts\ElogquentRepositoryInterface;
use Elogquent\Models\ModelChange;
use Illuminate\Database\Eloquent\Model;

class ElogquentDatabaseRepository implements ElogquentRepositoryInterface
{
    public function create(Model $model): void
    {
        $changesToStore = array_map([$model, 'getOriginal'], array_keys($model->getDirty()));
        ModelChange::create([
            'model_id' => $model->getAttribute('id'),
            'model_type' => get_class($model),
            'changes' => $changesToStore,
        ]);
    }
}
