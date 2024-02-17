<?php

define('TERMINAL_AFRICA_VERSION', "1.10.11");
// define('TERMINAL_AFRICA_PLUGIN_FILE', __FILE__);
// define('TERMINAL_AFRICA_PLUGIN_BASENAME', plugin_basename(__FILE__));
// define('TERMINAL_AFRICA_PLUGIN_DIR', untrailingslashit(dirname(__FILE__)));
//TERMINAL_AFRICA_PLUGIN_PATH
// define('TERMINAL_AFRICA_PLUGIN_PATH', untrailingslashit(plugin_dir_path(__FILE__)));
// define('TERMINAL_AFRICA_PLUGIN_URL', untrailingslashit(plugins_url(basename(plugin_dir_path(__FILE__)), basename(__FILE__))));
define('TERMINAL_AFRICA_PLUGIN_URL', rtrim(HTTP_SERVER, '/') . '/' . substr(DIR_APPLICATION, strlen(DIR_SYSTEM)));

define('TERMINAL_AFRICA_PLUGIN_ASSETS_URL', TERMINAL_AFRICA_PLUGIN_URL . '/assets');
//api endpoint
define('TERMINAL_AFRICA_API_ENDPOINT', 'https://api.terminal.africa/v1/');
define('TERMINAL_AFRICA_TEST_API_ENDPOINT', 'https://sandbox.terminal.africa/v1/');
//slug
define('TERMINAL_AFRICA_TEXT_DOMAIN', 'terminal-africa');
//tracking url
define('TERMINAL_AFRICA_TRACKING_URL_LIVE', 'https://app.terminal.africa/shipments/track/');

class Terminal_Africa {
     //checkKeys
    public function checkKeys($pk, $sk)
    {
        //check if keys has test in them
        if (strpos($pk, 'test') !== false || strpos($sk, 'test') !== false) {
            return [
                'endpoint' => TERMINAL_AFRICA_TEST_API_ENDPOINT,
                'mode' => 'test'
            ];
        } else if (strpos($pk, 'live') !== false || strpos($sk, 'live') !== false) {
            return [
                'endpoint' => TERMINAL_AFRICA_API_ENDPOINT,
                'mode' => 'live'
            ];
        }
        return [
            'endpoint' => TERMINAL_AFRICA_TEST_API_ENDPOINT,
            'mode' => 'test'
        ];
    }
}