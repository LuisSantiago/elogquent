<?php

namespace Elogquent\Tests\Jobs;

use Elogquent\Jobs\ProcessCreateEntry;
use Elogquent\Models\ElogquentEntry;

it('execute the job', function () {
    $model = ElogquentEntry::factory()->create();

    ProcessCreateEntry::dispatchSync(
        modelClass: $model->getModelClassName(),
        modelKey: $model->getModelId(),
        changes: [$model->getColumn() => $model->getValue()]
    );

    $this->assertDatabaseHas('elogquent_entries', [
        'model_type' => $model->getModelClassName(),
        'model_id' => $model->getModelId(),
        'column' => $model->getColumn(),
        'value' => $model->getValue(),
    ]);
});
