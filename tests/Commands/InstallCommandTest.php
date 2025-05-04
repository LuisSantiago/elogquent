<?php

it('the command install the service provider and the migration file', function () {
    $this->artisan('elogquent:install')
        ->expectsOutput('Publishing Elogquent Configuration...')
        ->expectsOutput('Publishing Elogquent Migrations...')
        ->expectsOutput('Publishing Elogquent ServiceProvider...')
        ->expectsOutput('Elogquent installed successfully.')
        ->expectsQuestion('Do you want to run the database migrations now?', 0)
        ->assertExitCode(0);
});
