<?php

// Configuration for Elogquent: Model Change History Tracking
return [
    'enabled' => env('ELOGQUENT_ENABLED', true),

    /**
     * List of columns to include in the change history.
     * If empty, all model attributes will be tracked (except those defined in 'excluded_columns').
     */
    'included_columns' => [],

    /**
     * Columns to exclude from the change history.
     * Ideal for sensitive or irrelevant data such as passwords or tokens.
     */
    'excluded_columns' => [
        'password',
        'remember_token',
        'api_token',
        'secret',
        'token',
        'updated_at',
    ],

    /**
     * If enabled, consecutive changes for models with identical values will be removed,
     * keeping only the most recent record for that state.
     * Helps to prevent redundant entries and reduce log noise.
     */
    'remove_previous_duplicates' => env('ELOGQUENT_REMOVE_DUPLICATES', true),
];
