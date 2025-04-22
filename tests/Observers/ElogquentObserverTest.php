<?php

use Elogquent\Contracts\ElogquentRepositoryInterface;
use Elogquent\Observers\ElogquentObserver;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

it('stores a change when the model has changes in configured columns', function () {
    $model = Mockery::mock(Model::class);
    $model->expects('getDirty')
        ->andReturn(['name' => 'Foo']);

    $repository = Mockery::mock(ElogquentRepositoryInterface::class);
    $repository->expects('create')
        ->with($model, null);

    $observer = new ElogquentObserver($repository);
    $observer->updating($model);
});

it('ignore changes when the model has no changes in configured columns', function () {
    $model = Mockery::mock(Model::class);
    $model->expects('getDirty')
        ->andReturn([]);

    $repository = Mockery::mock(ElogquentRepositoryInterface::class);
    $repository->expects('create')
        ->never();

    $observer = new ElogquentObserver($repository);
    $observer->updating($model);
});

it('stores a the user id if its configured', function () {
    Config::set('elogquent.store_user_id', true);
    $userId = 'userId';
    $model = Mockery::mock(Model::class);
    $model->expects('getDirty')
        ->andReturn(['column' => 'getChange']);

    $repository = Mockery::mock(ElogquentRepositoryInterface::class);
    $repository->expects('create')
        ->with($model, $userId);

    $authenticatable = Mockery::mock(Authenticatable::class);
    $authenticatable->expects('getAuthIdentifier')
        ->andReturn($userId);

    Auth::expects('user')
        ->andReturn($authenticatable);

    $observer = new ElogquentObserver($repository);
    $observer->updating($model);
});
