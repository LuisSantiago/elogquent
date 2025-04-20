<?php

// config for Elogquent
return [
    'enabled' => true,
    /** All columns will be stored by default if the include columns array is empty */
    'include_columns' => [],
    /** all columns will be stored by default if the include columns array is empty */
    'excluded_columns' => [
        'password',
        'remember_token',
        'api_token',
        'secret',
        'token',
        'updated_at',
    ],
    /** Limit the number of logs stored. Set to null to disable the limit
     *  It can be an array to limit the number of logs stored per model,
     *  for example, ['App\Models\User' => 1000]
     */
    'limit' => null,
];
