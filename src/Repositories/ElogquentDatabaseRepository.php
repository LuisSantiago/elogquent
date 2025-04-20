<?php

namespace Elogquent\Repositories;

use Elogquent\Contracts\ElogquentRepositoryInterface;
use Elogquent\Exceptions\ElogquentDatabaseError;
use Elogquent\Models\ModelChange;
use Exception;
use Illuminate\Database\Eloquent\Model;

class ElogquentDatabaseRepository implements ElogquentRepositoryInterface
{
    public function create(Model $model): void
    {
        try {
            $changesToStore = array_map([$model, 'getOriginal'], array_keys($model->getDirty()));
            ModelChange::create([
                'model_id' => $model->getAttribute('id'),
                'model_type' => get_class($model),
                'changes' => $changesToStore,
            ]);
        } catch (Exception $e) {
            throw new ElogquentDatabaseError($e->getMessage());
        }
    }
}
