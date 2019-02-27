<?php

/*return [
    'namespace'       => 'App',
    'base_class_name' => null,
    'output_path'     => '/app/app/Models',
    'no_timestamps'   => null,
    'date_format'     => null,
    'connection'      => null,
    'backup'          => null,
];*/

return [
    'model_defaults' => [
        'output_path'     => '/app/app/Models',
    ],
    
    'db_types' => [
        'enum' => 'string',
    ]
];