<?php

use Elogquent\Models\ElogquentEntry;
use Elogquent\Repositories\ElogquentDatabaseRepository;

it('create new entry', function () {
    Config::set('elogquent.included_columns', ['name']);
    Config::set('elogquent.excluded_columns', []);

    $sut = new ElogquentDatabaseRepository();
    $sut->create('SomeClass', 1, ['name' => 'Luis'], 1);

    $this->assertDatabaseHas('elogquent_entries', [
        'model_id' => 1,
        'model_type' => 'SomeClass',
        'column' => 'name',
        'value' => 'Luis',
    ]);
});

it('delete exceeded limit', function () {
    $modelClass = 'SomeClass';
    $modelKey = '1';

    ElogquentEntry::factory(2)->create([
        'model_id' => $modelKey,
        'model_type' => $modelClass,
    ]);

    $sut = new ElogquentDatabaseRepository();
    $sut->removeExceededLimit($modelClass, $modelKey, 1);

    $this->assertDatabaseCount('elogquent_entries', 1);
});

it('delete duplicates', function () {
    $modelClass = 'SomeClass';
    $modelKey = '1';
    $column = 'name';
    $value = 'Luis';

    ElogquentEntry::factory(2)->create([
        'model_id' => $modelKey,
        'model_type' => $modelClass,
        'column' => $column,
        'value' => $value,
    ]);

    $this->assertDatabaseCount('elogquent_entries', 2);

    $sut = new ElogquentDatabaseRepository();
    $sut->removeChanges($modelClass, $modelKey, [$column => $value]);

    $this->assertDatabaseCount('elogquent_entries', 0);
});
