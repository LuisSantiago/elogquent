<?php

use Elogquent\Models\ElogquentEntry;

it('elogquent is not enabled and return a message', function () {
    config(['elogquent.enabled' => false]);

    $this->artisan('elogquent:restore-changes')
        ->expectsOutputToContain('enable elogquent in config/elogquent.php')
        ->assertExitCode(0);
});

it('elogquent is enabled but has no entries', function () {
    $this->artisan('elogquent:restore-changes')
        ->expectsOutputToContain('No models with changes found.')
        ->assertExitCode(0);
});

it('the user is trying to restore a non-existent id', function () {
    $modelClass = ElogquentEntry::class;
    ElogquentEntry::factory()->create([
        'model_id' => 1,
        'model_type' => $modelClass,
    ]);

    $this->artisan('elogquent:restore-changes')
        ->expectsChoice('Select the Model you want to restore:', $modelClass, [$modelClass => $modelClass])
        ->expectsQuestion('Which model id would you like to restore?', 2)
        ->expectsOutputToContain('with id 2 not found')
        ->assertExitCode(0);
});

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
            'Luis', ['<fg=green>Luis</> (<fg=white>2025-05-07 10:00:00</>)', 'Luis']
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
