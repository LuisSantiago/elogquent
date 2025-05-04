<?php

use Elogquent\Models\ElogquentEntry;

it('the command select a model and a id to restore', function () {
    $modelClass = ElogquentEntry::class;
    ElogquentEntry::factory()->create([
        'model_id' => 1,
        'model_type' => $modelClass,
        'column' => 'name',
        'value' => 'Luis',
        'created_at' => '2025-05-07 10:00:00',
    ]);

    $this->artisan('elogquent:restore-changes')
        ->expectsChoice('Select the Model you want to restore:', $modelClass, [$modelClass => $modelClass])
        ->expectsQuestion('Which model id would you like to restore?', 1)
        ->expectsChoice('Which model attributes you like to restore?', ['name'], ['name'])
        ->expectsChoice('Which change for the column name would you like to restore?',
            'Luis', ['<fg=green>Luis</> (2025-05-07 10:00:00)', 'Luis']
        )
        ->expectsConfirmation('Do you want to restore the changes?', true)
        ->assertExitCode(0);

    $this->assertDatabaseHas('elogquent_entries', [
        'model_id' => 1,
        'model_type' => $modelClass,
        'column' => 'name',
        'value' => 'Luis',
    ]);
});
