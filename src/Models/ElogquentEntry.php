<?php

namespace Elogquent\Models;

use Elogquent\Database\Factories\ElogquentEntryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Override;

class ElogquentEntry extends Model
{
    use HasFactory;

    protected $table = 'elogquent_entries';

    protected $guarded = [];

    public $timestamps = false;

    protected array $dates = ['created_at'];

    public function modelChanged(): MorphTo
    {
        return $this->morphTo('model', 'model_type', 'model_id');
    }

    public function restore(): void
    {
        $this->getRelation('modelChanged')
            ->update([$this->getAttribute('column') => $this->getAttribute('value')]);
    }

    #[Override]
    public function getConnectionName(): ?string
    {
        return config('elogquent.database_connection');
    }

    public static function newFactory(): ElogquentEntryFactory
    {
        return ElogquentEntryFactory::new();
    }
}
