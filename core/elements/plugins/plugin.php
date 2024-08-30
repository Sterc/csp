<?php
/** @var \MODX\Revolution\modX $modx */
if ($modx->services->has('StercCSP')) {
    /** @var \Sterc\CSP\App $app */
    $app = $modx->services->get('StercCSP');
    $app->handleEvent($modx->event);
}