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

class Install extends Command
{

    protected static $defaultName = 'install';
    protected static $defaultDescription = 'Install Sterc CSP extra for MODX 3';

    public function run(InputInterface $input, OutputInterface $output): void
    {
        /** @var modX $modx */
        global $modx;

        $srcPath = MODX_CORE_PATH . 'vendor/sterc/csp';
        $corePath = MODX_CORE_PATH . 'components/sterc-csp';
        $assetsPath = MODX_ASSETS_PATH . 'components/sterc-csp';

        if (!is_dir($corePath)) {
            symlink($srcPath . '/core', $corePath);
            $output->writeln('<info>Created symlink for "core"</info>');
        }
        if (!is_dir($assetsPath)) {
            symlink($srcPath . '/assets/dist', $assetsPath);
            $output->writeln('<info>Created symlink for "assets"</info>');
        }

        if (!$modx->getObject(modNamespace::class, ['name' => 'sterc-csp'])) {
            $namespace = new modNamespace($modx);
            $namespace->name = 'sterc-csp';
            $namespace->path = '{core_path}components/sterc-csp/';
            $namespace->assets_path = '{assets_path}components/sterc-csp/';
            $namespace->save();
            $output->writeln('<info>Created namespace "sterc-csp"</info>');
        }

        if (!$modx->getObject(modMenu::class, ['namespace' => 'sterc-csp', 'action' => 'home'])) {
            $menu = new modMenu($modx);
            $menu->namespace = 'sterc-csp';
            $menu->action = 'home';
            $menu->parent = 'topnav';
            $menu->text = 'StercCSP';
            $menu->menuindex = $modx->getCount(modMenu::class, ['parent' => 'topnav']);
            $menu->save();
            $output->writeln('<info>Created menu "StercCSP"</info>');
        }

        if (!$plugin = $modx->getObject(modPlugin::class, ['name' => 'StercCSP'])) {
            $plugin = new modPlugin($modx);
            $plugin->name = 'StercCSP';
            $plugin->plugincode = preg_replace('#^<\?php#', '', file_get_contents($corePath . '/elements/plugin.php'));
            $plugin->save();
            $output->writeln('<info>Created plugin "StercCSP"</info>');
        }

        $pluginEvents = [
            // 'OnMODXInit',
            'OnHandleRequest',
        ];
        foreach ($pluginEvents as $name) {
            $key = ['pluginid' => $plugin->id, 'event' => $name];
            if (!$modx->getObject('modPluginEvent', $key)) {
                $event = new modPluginEvent($modx);
                $event->fromArray($key, '', true, true);
                $event->save();
                $output->writeln('Added event "' . $name . '" to plugin "StercCSP"');
            }
        }


        $output->writeln('<info>Run Phinx migrations</info>');
        $phinx = new TextWrapper(new PhinxApplication(), ['configuration' => $srcPath . '/core/phinx.php']);
        if ($res = $phinx->getMigrate('local')) {
            $output->writeln(explode(PHP_EOL, $res));
        }

        $modx->runProcessor('system/clearcache');
        $output->writeln('<info>Cleared MODX cache</info>');
    }
}
