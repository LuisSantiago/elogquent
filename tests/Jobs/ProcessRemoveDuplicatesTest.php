<?php

namespace Elogquent\Tests\Jobs;

use Elogquent\Jobs\ProcessRemoveDuplicates;
use Elogquent\Models\ElogquentEntry;

it('execute the job', function () {

    $model = ElogquentEntry::factory()
        ->create();

    ProcessRemoveDuplicates::dispatchSync(
        modelClass: $model->getModelClassName(),
        modelKey: $model->getModelId(),
        changes: [$model->getColumn() => $model->getValue()]
    );

    $this->assertDatabaseEmpty('elogquent_entries');
});
