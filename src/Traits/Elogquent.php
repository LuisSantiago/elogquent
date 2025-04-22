<?php

namespace Elogquent\Traits;

use Elogquent\Models\ModelChange;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Elogquent
{
    protected static function booted(): void
    {
        if (!config('elogquent.enabled')) {
            return;
        }

        self::observe('Elogquent\Observers\ElogquentObserver');
    }

    public function allChanges(): MorphMany
    {
        return $this->morphMany(ModelChange::class, 'model');
    }
}
