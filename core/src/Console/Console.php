<?php

namespace Sterc\CSP\Console;

use Sterc\CSP\Console\Command\Install;
use Sterc\CSP\Console\Command\Remove;
use Symfony\Component\Console\Application;

class Console extends Application
{
    public function __construct()
    {
        parent::__construct('Sterc CSP');

        $this->addCommands([
            new Install(),
            new Remove(),
        ]);
    }
}
