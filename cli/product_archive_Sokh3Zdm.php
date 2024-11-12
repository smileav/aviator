<?php
ini_set("display_errors",1);
error_reporting(E_ALL);

// Config file
if (file_exists('/home/aviato02/aviator.shop/www/config.php')) {
    require_once('/home/aviato02/aviator.shop/www/config.php');
} else {
    die('ERROR: cli cannot access to config.php' . PHP_EOL);
}

// Database
require_once(DIR_SYSTEM . 'library/db/mysqli.php');
require_once(DIR_SYSTEM . 'library/db.php');

$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);

$products = $db->query("SELECT `product_id`, `archive`, `quantity`, `stock_status_id`, `status` FROM `" . DB_PREFIX . "product`")->rows;

foreach ($products as $product) {
    $product_update = false;

    if ($product['quantity'] <= 0) {
        if (!$product['archive']) {
            $product_update = true;
        }

        if ($product['stock_status_id'] != 12) {
            $product_update = true;
        }

        if ($product['status']) {
            $product_update = true;
        }

        if ($product_update) {
            $db->query("
                UPDATE `" . DB_PREFIX . "product` SET
                    `archive`          = '1',
                    `quantity`          = '0',
                    `stock_status_id`   = '12',
                    `status`            = '0'
                WHERE `product_id` = '" . (int)$product['product_id'] . "'
            ");
        }
    } else {
        if ($product['archive']) {
            $product_update = true;
        }

        if ($product['stock_status_id'] == 12) {
            $product_update = true;
        }

        if ($product_update) {
            $db->query("
                UPDATE `" . DB_PREFIX . "product` SET
                    `archive`          = '0',
                    `stock_status_id`   = '5',
                    `status`            = '1'
                WHERE `product_id` = '" . (int)$product['product_id'] . "'
            ");
        }
    }
}
