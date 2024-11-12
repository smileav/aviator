<?php
ini_set("display_errors",1);
error_reporting(E_ALL);

define('DIR_ADMIN', dirname(__FILE__) . '/../admin/');

// Config file
if ( file_exists(DIR_ADMIN . 'config.php') ) {
    require_once (DIR_ADMIN . 'config.php');
} else {
    die("ERROR: cli cannot access to config.php");
}

// Database
require_once(DIR_SYSTEM . 'library/db/mysqli.php');
require_once(DIR_SYSTEM . 'library/db.php');

$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);

$db->query("DELETE FROM `" . DB_PREFIX . "product_to_category` WHERE `category_id` = '224'");

$query = $db->query("SELECT `product_id` FROM `" . DB_PREFIX . "product` WHERE `status` = '1' AND `quantity` > '0' AND `model` NOT LIKE 'GIFT-CARD-%'");

foreach ($query->rows as $row) {
    $db->query("INSERT INTO `" . DB_PREFIX . "product_to_category` SET `product_id` = '" . (int)$row['product_id'] . "', `category_id` = '224'");
}

$pattern = '/^cache\.fia_(ALL|EMPTY|PRICE)_cache(_224_\d+\.\d+|\.\d+)$/';

$cache_files = array_filter(scandir(DIR_CACHE), function($file) use ($pattern) {
    return preg_match($pattern, $file);
});

foreach ($cache_files as $cache_file) {
    if (file_exists(DIR_CACHE . $cache_file)) {
        unlink(DIR_CACHE . $cache_file);
    }
}
