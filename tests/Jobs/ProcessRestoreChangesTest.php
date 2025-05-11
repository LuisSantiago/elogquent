<?php

namespace Elogquent\Tests\Jobs;

use Elogquent\Jobs\ProcessRestoreChanges;
use Elogquent\Models\ElogquentEntry;

it('execute the job', function () {
    $model = ElogquentEntry::factory()
        ->create();

    ProcessRestoreChanges::dispatchSync(
        model: $model,
        changes: ['column' => 'foo', 'value' => 'bar'],
    );

    $this->assertDatabaseHas('elogquent_entries', [
        'column' => 'foo',
        'value' => 'bar',
    ]);
});
