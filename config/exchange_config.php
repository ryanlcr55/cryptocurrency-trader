<?php

return [
    'binance' => [
        'response_type' => 'array',
        'base_uri' => env('EXCHANGE_BINANCE_BASE_URL', 'https://testnet.binance.vision'),
        'app_key' => null,
        'secret' => null,
//        'proxy' => [
//            'http' => 'socks5h://127.0.0.1:1080', // Use this proxy with "http"
//            'https' => 'socks5h://127.0.0.1:1080', // Use this proxy with "https"
//            'no' => ['.mit.edu', 'foo.com'],   // Don't use a proxy with these
//        ],
//        'websocket' => [
//            'base_uri' => 'ws://stream.binance.com:9443',
//            'listen_ip' => '127.0.0.1', // listen ip
//            'listen_port' => 2207, // listen port
//            'heartbeat_time' => 20, // Heartbeat detection time, seconds
//            'timer_time' => 3, // Scheduled task time，seconds
//            'max_size' => 100, // Data retention，1～1000，Data is stored by channel name
//            'data_time' => 1, // Time interval for getting data，seconds
//            'debug' => true,
//        ],
        'log' => [
            'level' => 'debug',
            'file'  => storage_path('logs/exchange_binance-' . date('Y-m-d') . '.log'),
        ],

        'purchase_lower_bounds' => [
            'usdt' => env('EXCHANGE_BINANCE_PURCHASE_LOWER_BOUND_USDT', 10),
        ]
    ],
];
