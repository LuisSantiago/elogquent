<?php

use Elogquent\Exceptions\ElogquentDatabaseError;
use Elogquent\Models\ElogquentEntry;
use Elogquent\Repositories\ElogquentDatabaseRepository;
use Elogquent\Tests\Models\TestFakeModel;
use Illuminate\Support\Facades\Schema;

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

it('restore changes', function () {
    $column = 'name';
    $value = 'foo';

    $model = TestFakeModel::create(['name' => 'Luis']);

    $sut = new ElogquentDatabaseRepository();
    $sut->restoreChanges($model, [$column => $value]);

    $this->assertDatabaseHas('fake_models', [
        'id' => $model->id,
        $column => $value,
    ]);
});

it('fail create', function () {
    Schema::drop('elogquent_entries');

    $sut = new ElogquentDatabaseRepository();
    $sut->create(1, 1, [1 => 2]);

})->throws(ElogquentDatabaseError::class, 'Error storing in database:');

it('fail remove changes', function () {
    Schema::drop('elogquent_entries');

    $sut = new ElogquentDatabaseRepository();
    $sut->removeChanges('Model', 1, ['name' => 'Luis']);

})->throws(ElogquentDatabaseError::class, 'Elogquent remove changes error:');

it('fail remove exceded limit', function () {
    Schema::drop('elogquent_entries');

    $sut = new ElogquentDatabaseRepository();
    $sut->removeExceededLimit('Model', 1, 1);

})->throws(ElogquentDatabaseError::class, 'Get the last model for remove duplicates error:');

it('fail restore changes', function () {
    $model = TestFakeModel::create(['name' => 'Luis']);
    $sut = new ElogquentDatabaseRepository();
    $sut->restoreChanges($model, ['foo' => 'bar']);

})->throws(ElogquentDatabaseError::class, 'Restore changes error:');
