<?php

use Elogquent\Tests\Models\TestFakeModel;
use Elogquent\Traits\Elogquent;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;

beforeEach(function () {
    Config::set('elogquent.enabled', true);
    Event::fake();
});

it('model uses the trait', function () {
    expect(TestFakeModel::class)->toUseTraits(Elogquent::class);
});

it('registers event listeners when package is enabled', function () {
    $model = new TestFakeModel();
    $model::bootTraits();
    $model::boot();

    expect(Event::hasListeners('eloquent.updating: '.TestFakeModel::class))->toBeTrue();
});

it('model with the trait has the allChanges the relationship', function () {
    $model = new TestFakeModel();
    $model::bootTraits();
    $model::boot();

    expect(TestFakeModel::class)->toHaveMethod('allChanges');
});
