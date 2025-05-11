<?php

namespace Elogquent\Tests\Jobs;

use Elogquent\Jobs\ProcessClearExceededLimit;
use Elogquent\Models\ElogquentEntry;

it('execute the job', function () {
    $model = ElogquentEntry::factory()->create();

    ProcessClearExceededLimit::dispatchSync(
        modelClass: $model->getModelClassName(),
        modelKey: $model->getModelId(),
        limit: 1
    );

    $this->assertDatabaseCount('elogquent_entries', 1);
});
