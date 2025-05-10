<?php

namespace Elogquent\Repositories;

use Elogquent\Contracts\ElogquentRepositoryInterface;
use Elogquent\Exceptions\ElogquentDatabaseError;
use Elogquent\Models\ElogquentEntry;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Override;

class ElogquentDatabaseRepository implements ElogquentRepositoryInterface
{
    #[Override]
    public function create(
        string $modelClass,
        int $modelKey,
        array $changes,
        ?string $userId = null,
    ): void {
        try {
            DB::transaction(function () use ($modelClass, $modelKey, $changes, $userId): void {
                $inserts = collect($changes)->map(fn ($value, $column) => [
                    'model_type' => $modelClass,
                    'model_id' => $modelKey,
                    'user_id' => $userId,
                    'column' => $column,
                    'value' => $value,
                    'created_at' => now(),
                ])->toArray();

                ElogquentEntry::query()
                    ->insert($inserts);
            });
        } catch (Exception $e) {
            throw new ElogquentDatabaseError(
                "Elogquent storing model change error: {$e->getMessage()}"
            );
        }
    }

    #[Override]
    public function removeChanges(
        string $modelClass,
        int $modelKey,
        array $changes,
    ): void {
        try {
            foreach ($changes as $column => $value) {
                ElogquentEntry::query()
                    ->where('model_id', $modelKey)
                    ->where('model_type', $modelClass)
                    ->where('column', $column)
                    ->where('value', $value)
                    ->delete();
            }
        } catch (Exception $e) {
            throw new ElogquentDatabaseError(
                "Elogquent remove changes error: {$e->getMessage()}"
            );
        }
    }

    #[Override]
    public function removeExceededLimit(string $modelClass, int $modelKey, int $limit): void
    {
        try {
            $oldestElogquentEntryId = ElogquentEntry::query()
                ->where('model_id', $modelKey)
                ->where('model_type', $modelClass)
                ->offset($limit)
                ->limit(1)
                ->orderBy('id', 'desc')
                ->pluck('id')
                ->pop();
        } catch (Exception $e) {
            throw new ElogquentDatabaseError(
                "Get the last model for remove duplicates error: {$e->getMessage()}"
            );
        }

        if (empty($oldestElogquentEntryId)) {
            return;
        }

        ElogquentEntry::query()
            ->where('id', '<=', $oldestElogquentEntryId)
            ->where('model_id', $modelKey)
            ->where('model_type', $modelClass)
            ->delete();
    }

    #[Override]
    public function restoreChanges(Model $model, array $changes): void
    {
        try {
            $model->update($changes);
        } catch (Exception $e) {
            throw new ElogquentDatabaseError(
                "Restore changes error: {$e->getMessage()}"
            );
        }
    }
}
