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
    protected modX $modx;

    public function __construct($modx, ?string $name = null)
    {
        parent::__construct($name);
        $this->modx = $modx;
    }

    public function run(InputInterface $input, OutputInterface $output): void
    {
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

        if (!$this->modx->getObject(modNamespace::class, ['name' => 'sterc-csp'])) {
            $namespace = new modNamespace($this->modx);
            $namespace->name = 'sterc-csp';
            $namespace->path = '{core_path}components/sterc-csp/';
            $namespace->assets_path = '{assets_path}components/sterc-csp/';
            $namespace->save();
            $output->writeln('<info>Created namespace "sterc-csp"</info>');
        }

        if (!$this->modx->getObject(modMenu::class, ['namespace' => 'sterc-csp', 'action' => 'home'])) {
            $menu = new modMenu($this->modx);
            $menu->namespace = 'sterc-csp';
            $menu->action = 'home';
            $menu->parent = 'topnav';
            $menu->text = 'StercCSP';
            $menu->menuindex = $this->modx->getCount(modMenu::class, ['parent' => 'topnav']);
            $menu->save();
            $output->writeln('<info>Created menu "StercCSP"</info>');
        }

        if (!$plugin = $this->modx->getObject(modPlugin::class, ['name' => 'StercCSP'])) {
            $plugin = new modPlugin($this->modx);
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
            if (!$this->modx->getObject('modPluginEvent', $key)) {
                $event = new modPluginEvent($this->modx);
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

        $this->modx->getCacheManager()->refresh();
        $output->writeln('<info>Cleared MODX cache</info>');
    }
}
