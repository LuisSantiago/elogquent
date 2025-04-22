<?php

namespace Elogquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ModelChange extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    protected $casts = ['changes' => 'json'];

    protected array $dates = ['created_at'];

    public function modelChanged(): MorphTo
    {
        return $this->morphTo('model', 'model_type', 'model_id');
    }

    public function restore(): void
    {
        $changes = $this->getAttribute('changes');
        $this->getRelation('modelChanged')->update($changes);
    }
}
