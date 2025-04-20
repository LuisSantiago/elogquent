<?php

namespace Elogquent\Traits;

use Elogquent\Models\ModelChange;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Elogquent
{
    protected static function booted(): void
    {
        self::observe('Elogquent\Observers\ElogquentObserver');
    }

    public function versions(): MorphMany
    {
        return $this->morphMany(ModelChange::class, 'model');
    }
}
