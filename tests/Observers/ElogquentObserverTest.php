<?php

use Elogquent\Jobs\ProcessClearExceededLimit;
use Elogquent\Jobs\ProcessCreateEntry;
use Elogquent\Jobs\ProcessRemoveDuplicates;
use Elogquent\Observers\ElogquentObserver;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Config;

beforeEach(function () {
    Config::set('elogquent.enabled', true);
    Bus::fake();
});

it('execute the process when the model has changes in configured columns with default config', function () {
    $model = $this->mockModel();

    Config::set('elogquent.included_columns', ['name']);
    Config::set('elogquent.excluded_columns', []);

    $sut = new ElogquentObserver();
    $sut->updating($model);

    Bus::assertChained([
        ProcessRemoveDuplicates::class,
        ProcessCreateEntry::class,
    ]);
});

it('ignore changes when the model has no changes in configured columns', function () {
    $model = $this->mockModel();

    Config::set('elogquent.included_columns', []);
    Config::set('elogquent.excluded_columns', ['name']);

    $sut = new ElogquentObserver();
    $sut->updating($model);
});

it('process limit entries if its configured globally', function () {
    $model = $this->mockModel();
    Config::set('elogquent.changes_limit', 1);

    $sut = new ElogquentObserver();
    $sut->updating($model);

    Bus::assertChained([
        ProcessRemoveDuplicates::class,
        ProcessCreateEntry::class,
        ProcessClearExceededLimit::class,
    ]);
});

it('process limit entries if its configured for the model', function () {
    $model = $this->mockModel();
    Config::set('elogquent.remove_previous_duplicates', true);
    Config::set('elogquent.model_changes_limit', [
        get_class($model) => 1,
    ]);

    $sut = new ElogquentObserver();
    $sut->updating($model);

    Bus::assertChained([
        ProcessRemoveDuplicates::class,
        ProcessCreateEntry::class,
        ProcessClearExceededLimit::class,
    ]);
});
