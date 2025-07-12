<?php

return [
    /*
    |--------------------------------------------------------------------------
    | PowerGrid Theme
    |--------------------------------------------------------------------------
    |
    | PowerGrid supports Tailwind and Bootstrap 5 themes.
    | Configure here the theme of your choice.
    |
    */
    'theme' => \PowerComponents\LivewirePowerGrid\Themes\Tailwind::class,
    /*
    |--------------------------------------------------------------------------
    | Plugins
    |--------------------------------------------------------------------------
    |
    | Plugins used: bootstrap-select when bootstrap, flatpickr.js to datepicker.
    |
    */
    'plugins' => [
        'bootstrap-select' => true,
        'flatpickr' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    |
    | Cache is enabled by default to improve search performance when using collections.
    | When enabled, the search will be performed on the cache instead of the collection.
    |
    */
    'cached_data' => true,

    /*
    |--------------------------------------------------------------------------
    | Export
    |--------------------------------------------------------------------------
    |
    | Export configuration.
    |
    */
    'export' => [
        'csv' => [
            'enabled' => true,
            'directory' => 'exports',
        ],
        'xlsx' => [
            'enabled' => true,
            'directory' => 'exports',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | JS Framework
    |--------------------------------------------------------------------------
    |
    | Define here which JS framework you are using.
    | Available options: 'alpinejs', 'livewire'
    |
    */
    'js_framework' => 'alpinejs',

    /*
    |--------------------------------------------------------------------------
    | Date Format
    |--------------------------------------------------------------------------
    |
    | Define here the format of the date to be used.
    |
    */
    'date_format' => 'Y-m-d',

    /*
    |--------------------------------------------------------------------------
    | Time Format
    |--------------------------------------------------------------------------
    |
    | Define the default time format for PowerGrid.
    |
    */
    'time_format' => 'H:i:s',

    /*
    |--------------------------------------------------------------------------
    | DateTime Format
    |--------------------------------------------------------------------------
    |
    | Define the default datetime format for PowerGrid.
    |
    */
    'datetime_format' => 'Y-m-d H:i:s',

    /*
    |--------------------------------------------------------------------------
    | Number Format
    |--------------------------------------------------------------------------
    |
    | Define here the format of the number to be used.
    |
    */
    'number_format' => [
        'decimals' => 2,
        'decimal_separator' => '.',
        'thousands_separator' => ',',
    ],
];
