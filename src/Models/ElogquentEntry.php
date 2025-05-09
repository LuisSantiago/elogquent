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
            ->update([$this->getColumn() => $this->getValue()]);
    }

    #[Override]
    public function getConnectionName(): ?string
    {
        return config('elogquent.database_connection');
    }

    public function getModelClassName(): string
    {
        return $this->getAttribute('model_type');
    }

    public function getModelId(): int
    {
        return $this->getAttribute('model_id');
    }

    public function getColumn(): string
    {
        return $this->getAttribute('column');
    }

    public function getValue(): string
    {
        return $this->getAttribute('value');
    }

    public static function newFactory(): ElogquentEntryFactory
    {
        return ElogquentEntryFactory::new();
    }
}
