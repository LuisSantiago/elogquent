<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Elogquent: Change History Tracking
    |--------------------------------------------------------------------------
    |
    | Main switch to enable or disable Elogquent globally.
    |
    */
    'enabled' => env('ELOGQUENT_ENABLED', true),

    /*
    |
    | Should store the user who changed the model?
    |
    */
    'store_user_id' => env('ELOGQUENT_STORE_USER_ID', true),

    /*
    |--------------------------------------------------------------------------
    | Database Connection
    |--------------------------------------------------------------------------
    |
    | Define the database connection to use for storing the change history.
    |
    */
    'database_connection' => env('ELOGQUENT_DATABASE_CONNECTION', config('database.default')),

    /*
    |--------------------------------------------------------------------------
    | Included Columns
    |--------------------------------------------------------------------------
    |
    | Specify the list of columns to track in the change history.
    | If empty, all model attributes will be tracked (except excluded ones).
    |
    */
    'included_columns' => [],

    /*
    |--------------------------------------------------------------------------
    | Excluded Columns
    |--------------------------------------------------------------------------
    |
    | Columns to exclude from change history. Useful for sensitive or irrelevant data.
    |
    */
    'excluded_columns' => [
        'password',
        'remember_token',
        'api_token',
        'secret',
        'token',
        'updated_at',
    ],

    /*
    |--------------------------------------------------------------------------
    | Remove Duplicate States
    |--------------------------------------------------------------------------
    |
    | When enabled, it will automatically remove consecutive entries with identical
    | attribute values, keeping only the latest one, to avoid redundant logs.
    |
    */
    'remove_previous_duplicates' => env('ELOGQUENT_REMOVE_DUPLICATES', true),

    /*
    |--------------------------------------------------------------------------
    | Global Changes Limit
    |--------------------------------------------------------------------------
    |
    | Set a maximum number of total historical changes.
    | You can override this per model using "model_changes_limit".
    |
    */
    'changes_limit' => env('ELOGQUENT_CHANGES_LIMIT'),

    /*
    |--------------------------------------------------------------------------
    | Model-specific Changes Limit
    |--------------------------------------------------------------------------
    |
    | Set a limit on the number of change records per model.
    | If a model is not listed here, the global "changes_limit" will apply.
    |
    | Example:
    | 'model_changes_limit' => [
    |     \App\Models\Post::class => 100,
    |     \App\Models\User::class => 10,
    | ],
    |
    */
    'model_changes_limit' => env('ELOGQUENT_MODEL_CHANGES_LIMIT', []),

    /*
    |--------------------------------------------------------------------------
    | Elogquent Queue
    |--------------------------------------------------------------------------
    |
    | Configure the queue settings for processing pending updates asynchronously.
    | You can customize the connection, delay, and queue name as needed.
    |
    */
    'queue' => [
        'delay' => env('ELOGQUENT_QUEUE_DELAY', 0),
        'connection' => env('ELOGQUENT_QUEUE_CONNECTION', config('queue.default')),
        'queue' => env('ELOGQUENT_QUEUE_QUEUE'),
    ],
];
