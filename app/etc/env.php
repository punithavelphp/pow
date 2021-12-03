<?php
return [
    'backend' => [
        'frontName' => 'powadmin'
    ],
    'crypt' => [
        'key' => '3ea8956552cca5c0e92e60ffd33f1546'
    ],
    'db' => [
        'table_prefix' => '',
        'connection' => [
            'default' => [
                'host' => '80.74.154.100',
                'dbname' => 'pow',
                'username' => 'punithavel_db',
                'password' => 'punithavel1@#',
                'active' => '1',
                'driver_options' => [

                ]
            ]
        ]
    ],
    'resource' => [
        'default_setup' => [
            'connection' => 'default'
        ]
    ],
    'x-frame-options' => 'SAMEORIGIN',
    'MAGE_MODE' => 'developer',
    'session' => [
        'save' => 'files'
    ],
    'cache' => [
        'frontend' => [
            'default' => [
                'id_prefix' => 'c5c_'
            ],
            'page_cache' => [
                'id_prefix' => 'c5c_'
            ]
        ]
    ],
    'lock' => [
        'provider' => 'db',
        'config' => [
            'prefix' => null
        ]
    ],
    'cache_types' => [
        'config' => 1,
        'layout' => 1,
        'block_html' => 1,
        'collections' => 1,
        'reflection' => 1,
        'db_ddl' => 1,
        'compiled_config' => 1,
        'eav' => 1,
        'customer_notification' => 1,
        'config_integration' => 1,
        'config_integration_api' => 1,
        'full_page' => 1,
        'config_webservice' => 1,
        'translate' => 1,
        'vertex' => 1
    ],
    'downloadable_domains' => [
        'pow.thestagings.com'
    ],
    'install' => [
        'date' => 'Thu, 25 Mar 2021 18:43:26 +0000'
    ]
];
