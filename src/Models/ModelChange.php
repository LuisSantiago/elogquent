<?php

namespace Elogquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class ModelChange extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    protected $casts = ['changes' => 'array'];

    protected $dates = ['created_at'];

    public function model(): MorphToMany
    {
        return $this->morphedByMany(Model::class, 'model');
    }
}
