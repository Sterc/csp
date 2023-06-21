<?php

namespace Sterc\CSP\Console\Command;

use MODX\Revolution\modMenu;
use MODX\Revolution\modNamespace;
use MODX\Revolution\modPlugin;
use MODX\Revolution\modPluginEvent;
use MODX\Revolution\modX;
use Phinx\Console\PhinxApplication;
use Phinx\Wrapper\TextWrapper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Remove extends Command
{
    protected static $defaultName = 'remove';
    protected static $defaultDescription = 'Remove Sterc CSP extra from MODX 3';

    public function run(InputInterface $input, OutputInterface $output): void
    {
        /** @var modX $modx */
        global $modx;

        $srcPath = MODX_CORE_PATH . 'vendor/sterc/csp';
        $corePath = MODX_CORE_PATH . 'components/sterc-csp';
        $assetsPath = MODX_ASSETS_PATH . 'components/sterc-csp';

        if (is_dir($corePath)) {
            unlink($corePath);
            $output->writeln('<info>Removed symlink for "core"</info>');
        }
        if (is_dir($assetsPath)) {
            unlink($assetsPath);
            $output->writeln('<info>Removed symlink for "assets"</info>');
        }

        if ($namespace = $modx->getObject(modNamespace::class, ['name' => 'sterc-csp'])) {
            $namespace->remove();
            $output->writeln('<info>Removed namespace "sterc-csp"</info>');
        }

        if ($menu = $modx->getObject(modMenu::class, ['namespace' => 'sterc-csp'])) {
            $menu->remove();
            $output->writeln('<info>Removed menu "StercCSP"</info>');
        }

        if ($plugin = $modx->getObject(modPlugin::class, ['name' => 'StercCSP'])) {
            /** @var modPluginEvent $event */
            foreach ($plugin->getMany('PluginEvents') as $event) {
                $event->remove();
                $output->writeln('Removed event "' . $event->event . '" from plugin "StercCSP"');
            }
            $plugin->remove();
            $output->writeln('<info>Removed plugin "StercCSP"</info>');
        }

        $output->writeln('<info>Rollback Phinx migrations</info>');
        $phinx = new TextWrapper(new PhinxApplication(), ['configuration' => $srcPath . '/core/phinx.php']);
        if ($res = $phinx->getRollback('local', 0)) {
            $output->writeln(explode(PHP_EOL, $res));
        }

        $modx->runProcessor('system/clearcache');
        $output->writeln('<info>Cleared MODX cache</info>');
    }
}
