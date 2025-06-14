<?php

return [
    'default' => 'default',

    'documentations' => [
        'default' => [
            'api' => [
                'title' => 'L5 Swagger UI',
            ],
            'routes' => [
                // URL untuk mengakses dokumentasi Swagger UI
                'api' => 'api/documentation',
            ],
            'paths' => [
                // Gunakan URL absolut untuk aset Swagger UI
                'use_absolute_path' => env('L5_SWAGGER_USE_ABSOLUTE_PATH', true),

                // Path ke aset Swagger UI
                'swagger_ui_assets_path' => env('L5_SWAGGER_UI_ASSETS_PATH', 'vendor/swagger-api/swagger-ui/dist/'),

                // Nama file dokumentasi JSON
                'docs_json' => 'api-docs.json',

                // Nama file dokumentasi YAML
                'docs_yaml' => 'api-docs.yaml',

                // Format dokumentasi yang digunakan pada UI (json/yaml)
                'format_to_use_for_docs' => env('L5_FORMAT_TO_USE_FOR_DOCS', 'json'),

                // Direktori yang berisi anotasi Swagger
                'annotations' => [
                    base_path('app'),
                ],
            ],
        ],
    ],

    'defaults' => [
        'routes' => [
            // Route untuk file dokumentasi JSON
            'docs' => 'docs',

            // Callback untuk autentikasi OAuth2
            'oauth2_callback' => 'api/oauth2-callback',

            // Middleware untuk proteksi route dokumentasi
            'middleware' => [
                'api' => [],
                'asset' => [],
                'docs' => [],
                'oauth2_callback' => [],
            ],

            // Opsi tambahan untuk route group
            'group_options' => [],
        ],

        'paths' => [
            // Lokasi penyimpanan file dokumentasi
            'docs' => storage_path('api-docs'),

            // Direktori tempat export views Swagger
            'views' => base_path('resources/views/vendor/l5-swagger'),

            // Base path API
            'base' => env('L5_SWAGGER_BASE_PATH', null),

            // Direktori yang dikecualikan saat pemindaian (tidak disarankan, gunakan scanOptions.exclude)
            'excludes' => [],
        ],

        'scanOptions' => [
            // Konfigurasi tambahan untuk processors swagger-php
            'default_processors_configuration' => [],

            'analyser' => null,
            'analysis' => null,

            // Tambahkan processor kustom jika diperlukan
            'processors' => [
                // Contoh: new \App\SwaggerProcessors\SchemaQueryParameter(),
            ],

            // Pola file yang dipindai (*.php secara default)
            'pattern' => null,

            // Direktori yang dikecualikan dari pemindaian
            'exclude' => [],

            // Versi spesifikasi OpenAPI
            'open_api_spec_version' => env(
                'L5_SWAGGER_OPEN_API_SPEC_VERSION',
                \L5Swagger\Generator::OPEN_API_DEFAULT_SPEC_VERSION
            ),
        ],

        // Definisi keamanan API
        'securityDefinitions' => [
            'securitySchemes' => [
                /*
                Contoh:
                'sanctum' => [
                    'type' => 'apiKey',
                    'description' => 'Enter token in format (Bearer <token>)',
                    'name' => 'Authorization',
                    'in' => 'header',
                ],
                */
            ],
            'security' => [
                // Contoh implementasi keamanan jika diperlukan
            ],
        ],

        // Regenerasi otomatis dokumentasi setiap request (aktifkan hanya untuk development)
        'generate_always' => env('L5_SWAGGER_GENERATE_ALWAYS', false),

        // Buat salinan dokumentasi dalam format YAML
        'generate_yaml_copy' => env('L5_SWAGGER_GENERATE_YAML_COPY', false),

        // Digunakan jika aplikasi di belakang load balancer seperti AWS
        'proxy' => false,

        // URL konfigurasi tambahan Swagger UI (opsional)
        'additional_config_url' => null,

        // Urutan operasi pada dokumentasi ('alpha', 'method', atau null)
        'operations_sort' => env('L5_SWAGGER_OPERATIONS_SORT', null),

        // URL validator Swagger (null untuk menonaktifkan)
        'validator_url' => null,

        'ui' => [
            'display' => [
                'dark_mode' => env('L5_SWAGGER_UI_DARK_MODE', false),

                // Pengaturan tampilan default dokumentasi: 'list', 'full', atau 'none'
                'doc_expansion' => env('L5_SWAGGER_UI_DOC_EXPANSION', 'none'),

                // Aktifkan fitur pencarian/penyaringan di UI Swagger
                'filter' => env('L5_SWAGGER_UI_FILTERS', true),
            ],
            'authorization' => [
                // Simpan data otorisasi meski browser ditutup/direfresh
                'persist_authorization' => env('L5_SWAGGER_UI_PERSIST_AUTHORIZATION', false),

                'oauth2' => [
                    // Aktifkan PKCE untuk OAuth2
                    'use_pkce_with_authorization_code_grant' => false,
                ],
            ],
        ],

        // Konstanta yang bisa digunakan dalam anotasi Swagger
        'constants' => [
            'L5_SWAGGER_CONST_HOST' => env('L5_SWAGGER_CONST_HOST', 'http://my-default-host.com'),
        ],
    ],
];
