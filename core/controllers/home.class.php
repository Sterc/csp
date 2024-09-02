<?php

use MODX\Revolution\modNamespace;

class StercCspHomeManagerController extends \MODX\Revolution\modExtraManagerController
{
    public function loadCustomCssJs(): void
    {
        $nsKey = 'sterc-csp';

        $namespace = $this->modx->getObject(modNamespace::class, ['name' => $nsKey]);
        $assetsPath = $namespace ? $namespace->getAssetsPath() : MODX_ASSETS_PATH . 'components/' . $nsKey . '/';
        $baseUrl = str_replace(MODX_BASE_PATH, '/', $assetsPath) . 'dist/';
        $manifest = $assetsPath . 'dist/manifest.json';
        if (file_exists($manifest) && $files = json_decode(file_get_contents($manifest), true)) {
            // Production mode
            foreach ($files as $file) {
                if (preg_match('#\.js$#', $file['file'])) {
                    if (!empty($file['isEntry'])) {
                        $this->addHtml('<script type="module" src="' . $baseUrl . $file['file'] . '"></script>');
                    }
                } elseif (preg_match('#\.css$#', $file['file'])) {
                    $this->addCss($baseUrl . $file['file']);
                }
            }
        } else {
            // Development mode
            $server = '127.0.0.1';
            $port = getenv('NODE_DEV_PORT') ?: '9090';
            $connection = @fsockopen($server, $port);
            if (is_resource($connection)) {
                $path = $server . ':' . $port . $baseUrl;
                $this->addHtml('<script type="module" src="//' . $path . '@vite/client"></script>');
                $this->addHtml('<script type="module" src="//' . $path . 'src/main.ts"></script>');
            }
        }
    }

    public function getPageTitle(): string
    {
        return 'Sterc CSP';
    }

    public function getTemplateFile(): string
    {
        $this->content .= '<div id="app-root"></div>';

        return '';
    }
}