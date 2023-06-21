<?php

class StercCspHomeManagerController extends \MODX\Revolution\modExtraManagerController
{
    public function loadCustomCssJs(): void
    {
        $server = '127.0.0.1';
        $port = getenv('NODE_DEV_PORT') ?: '9090';
        $connection = @fsockopen($server, $port);
        $baseUrl = MODX_ASSETS_URL . 'components/sterc-csp/';
        if (is_resource($connection)) {
            // Development mode
            $path = $server . ':' . $port . $baseUrl;
            $this->addHtml('<script type="module" src="//' . $path . '@vite/client"></script>');
            $this->addHtml('<script type="module" src="//' . $path . 'src/main.ts"></script>');
        } else {
            // Production mode
            $manifest = MODX_ASSETS_PATH . 'components/sterc-csp/manifest.json';
            if (file_exists($manifest) && $files = json_decode(file_get_contents($manifest), true)) {
                foreach ($files as $file) {
                    if (preg_match('#\.js$#', $file['file'])) {
                        if (!empty($file['isEntry'])) {
                            $this->addHtml('<script type="module" src="' . $baseUrl . $file['file'] . '"></script>');
                        }
                    } elseif (preg_match('#\.css$#', $file['file'])) {
                        $this->addCss($baseUrl . $file['file']);
                    }
                }
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