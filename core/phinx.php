<?php

use Sterc\CSP\Services\Eloquent;
use Vesp\Services\Migration;

/** @var MODX\Revolution\modX $modx */
require __DIR__ . '/bootstrap.php';

$connection = (new Eloquent($modx))->getConnection();
$config = $connection->getConfig();

return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/db',
    ],
    'migration_base_class' => Migration::class,
    'templates' => [
        'style' => 'up_down',
    ],
    'environments' => [
        'default_migration_table' => $config['prefix'] . 'csp_migrations',
        'default_environment' => 'local',
        'local' => [
            'name' => $config['database'],
            'connection' => $connection->getPdo(),
        ],
    ],
];
