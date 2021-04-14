<?php

$config = [
    'commands' => [
        /*'Route cache'      => ['run' => 'route:cache', 'type' => 'info', 'group' => 'Cache'],
        'Config cache'     => ['run' => 'config:cache', 'type' => 'info', 'group' => 'Cache'],
        'View cache'       => ['run' => 'view:cache', 'type' => 'info', 'group' => 'Cache'],

        'Route clear'     => ['run' => 'route:clear', 'type' => 'warning', 'group' => 'Clear Cache'],
        'Config clear'    => ['run' => 'config:clear', 'type' => 'warning', 'group' => 'Clear Cache'],
        'View clear'      => ['run' => 'view:clear', 'type' => 'warning', 'group' => 'Clear Cache'],

        'Up'   => ['run' => 'up', 'type' => 'success', 'group' => 'Maintenance'],
        'Down' => ['run' => 'down', 'options' => ['--allow' => ['127.0.0.1']], 'type' => 'dark', 'group' => 'Maintenance'],*/
        'Seed text questions' => ['run' => 'db:seed --class=TextQuestionSeeder', 'type' => 'danger', 'group' => 'Database'],
    ],
    'history' => 10,
];

if (env('APP_ENV') !== 'production') {
    $config['commands']['Rerun Migrations'] = [
        'run' => 'migrate:fresh --seed', 'type' => 'danger', 'group' => 'Database',
    ];
}

return $config;
