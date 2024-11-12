<?php
// Admin directory
define('ADMIN_DIR', dirname(__FILE__) . '/../admin/');

// Config file
if (file_exists(ADMIN_DIR . 'config.php')) {
    require_once (ADMIN_DIR . 'config.php');
} else {
    die("ERROR: cli cannot access to config.php");
}

// Config file Donor
if (file_exists('./ie_config_don.php')) {
    require_once ('./ie_config_don.php');
} else {
    die("ERROR: cli cannot access to ie_config_don.php");
}

if(!isset($_SERVER['SERVER_PORT'])) {
    $_SERVER['SERVER_PORT'] = 80;
}

// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Registry
$registry = new Registry();

// Config
$config = new Config();
$config->load('default');
$config->load('admin');
$registry->set('config', $config);

// Database
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
$registry->set('db', $db);

// Database
$db_don = new DB(DB_DRIVER, DB_HOSTNAME_DON, DB_USERNAME_DON, DB_PASSWORD_DON, DB_DATABASE_DON, DB_PORT);
$registry->set('db_don', $db_don);

// Settings
$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0'");
foreach ($query->rows as $setting) {
    if (!$setting['serialized']) {
        $config->set($setting['key'], $setting['value']);
    } else {
        $config->set($setting['key'], json_decode($setting['value'], true));
    }
}

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Url
$url = new Url(HTTP_SERVER, $config->get('config_secure') ? HTTPS_SERVER : HTTP_SERVER);
$registry->set('url', $url);

date_default_timezone_set($config->get('date_timezone'));

// log File
$log = new Log('ei_' . date('Y-m-d_H:i:s') .'.log');
$registry->set('log', $log);

set_error_handler(function($code, $message, $file, $line) use($log, $config) {
    // error suppressed with @
    if (error_reporting() === 0) {
        return false;
    }

    switch ($code) {
        case E_NOTICE:
        case E_USER_NOTICE:
            $error = 'Notice';
            break;
        case E_WARNING:
        case E_USER_WARNING:
            $error = 'Warning';
            break;
        case E_ERROR:
        case E_USER_ERROR:
            $error = 'Fatal Error';
            break;
        default:
            $error = 'Unknown';
            break;
    }

    if ($config->get('error_display')) {
        echo '<b>' . $error . '</b>: ' . $message . ' in <b>' . $file . '</b> on line <b>' . $line . '</b>';
    }

    if ($config->get('error_log')) {
        $log->write('PHP ' . $error . ':  ' . $message . ' in ' . $file . ' on line ' . $line);
    }

    return true;
});

// Request
$request = new Request();
$registry->set('request', $request);

// Response
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$registry->set('response', $response);

// Cache
$cache = new Cache('file');
$registry->set('cache', $cache);

// Session
$session = new Session($config->get('session_engine'), $registry);
$registry->set('session', $session);

// Language
$languages = array();
$query = $db->query("SELECT * FROM `" . DB_PREFIX . "language`");
foreach ($query->rows as $result) {
    $languages[$result['code']] = $result;
}
$config->set('config_language_id', $languages[$config->get('config_admin_language')]['language_id']);

// Language
$language = new Language($languages[$config->get('config_admin_language')]['directory']);
$language->load($languages[$config->get('config_admin_language')]['directory']);
$registry->set('language', $language);

// Document
$registry->set('document', new Document());

// Event
$event = new Event($registry);
$registry->set('event', $event);

// Event Register
if ($config->has('action_event')) {
    foreach ($config->get('action_event') as $key => $value) {
        foreach ($value as $priority => $action) {
            $event->register($key, new Action($action), $priority);
        }
    }
}

// Route
$route = new Router($registry);

// $route->dispatch(new Action('ei/category'), new Action('error/not_found'));
$route->dispatch(new Action('ei/product'), new Action('error/not_found'));

$response->output();
