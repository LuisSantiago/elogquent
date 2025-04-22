<?php

namespace Elogquent\Repositories;

use Elogquent\Contracts\ElogquentRepositoryInterface;
use Elogquent\Exceptions\ElogquentDatabaseError;
use Elogquent\Models\ModelChange;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ElogquentDatabaseRepository implements ElogquentRepositoryInterface
{
    public function create(
        Model   $model,
        ?string $userId = null,
    ): void
    {
        $changes = $this->getChanges($model);
        if (empty($changes)) {
            return;
        }

        try {
            DB::transaction(function () use ($model, $changes, $userId) {
                $this->removePreviousDuplicates($model, $changes);

                ModelChange::create([
                    'model_type' => get_class($model),
                    'model_id' => $model->getAttribute('id'),
                    'changes' => $changes,
                    'user_id' => $userId,
                ]);
            });
        } catch (Exception $e) {
            throw new ElogquentDatabaseError("Elogquent storing model change error: {$e->getMessage()}");
        }
    }

    private function removePreviousDuplicates(
        Model $model,
        array $changes,
    ): void
    {
        if (!config('elogquent.remove_previous_duplicates', false)) {
            return;
        }

        $query = $model->getRelation('allChanges')
            ->where('changes', $changes)
            ->select('id');

        if ($query->exists()) {
            ModelChange::whereIn('id', $query->pluck('id')->toArray())
                ->delete();
        }
    }

    private function getChanges(Model $model): array
    {
        $dirty = $model->getDirty();
        $included = config('elogquent.included_columns', []);
        $excluded = config('elogquent.excluded_columns', []);

        if (!empty($included)) {
            $dirty = array_intersect_key($dirty, array_flip($included));
        }
        if (!empty($excluded)) {
            $dirty = array_diff_key($dirty, array_flip($excluded));
        }

        return $dirty;
    }
}
