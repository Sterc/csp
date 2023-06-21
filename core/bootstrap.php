<?php

if (!defined('MODX_CORE_PATH')) {
    $dir = __DIR__;
    while (true) {
        if ($dir === '/') {
            break;
        }
        if (file_exists($dir . '/config.core.php')) {
            require $dir . '/config.core.php';
            break;
        }
        if (file_exists($dir . '/config/config.inc.php')) {
            require $dir . '/config/config.inc.php';
            break;
        }
        $dir = dirname($dir);
    }

    if (!defined('MODX_CORE_PATH')) {
        exit('Could not load MODX core');
    }
    require MODX_CORE_PATH . '/vendor/autoload.php';
}

/** @var MODX\Revolution\modX $modx */
if (!isset($modx)) {
    $modx = new MODX\Revolution\modX();
    $modx->initialize();
}

if (!$modx->services->has('StercCSP')) {
    $modx->services->add(
        'StercCSP',
        $modx->services->factory(function () use ($modx) {
            return new Sterc\CSP\App($modx);
        })
    );
}
