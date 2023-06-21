<?php

namespace Sterc\CSP\Services;

use MODX\Revolution\modX;

class Eloquent extends \Vesp\Services\Eloquent
{
    public function __construct(modX $modx)
    {
        $config = $modx->getConnection()->config;

        putenv('DB_DRIVER=' . $config['dbtype']);
        putenv('DB_HOST=' . $config['host']);
        putenv('DB_PORT=3306');
        putenv('DB_PREFIX=' . $config['table_prefix']);
        putenv('DB_DATABASE=' . $config['dbname']);
        putenv('DB_USERNAME=' . $config['username']);
        putenv('DB_PASSWORD=' . $config['password']);
        putenv('DB_CHARSET=' . $config['charset']);
        putenv('DB_COLLATION=' . $config['charset'] . '_general_ci');
        putenv('DB_FOREIGN_KEYS=1');

        parent::__construct();
    }
}