<?php
class ModelIeCliIE extends Model {

    private $product_IDs    = [];
    private $memory_limit   = 10485760;
    // private $memory_limit   = 700000;

    public function compareLanguages() {
        $languages_query        = $this->db->query("SELECT `language_id`, `code` FROM `" . DB_PREFIX . "language`");
        $languages_don_query    = $this->db_don->query("SELECT `language_id`, `code` FROM `" . DB_PREFIX . "language`");

        $language_data = [];

        foreach ($languages_don_query->rows as $language_don) {
            foreach ($languages_query->rows as $language) {
                if (preg_match ('/' . $language_don['code'] . '/i', $language['code'])) {
                    $language_data[$language_don['language_id']] = $language['language_id'];
                }
            }
        }

        asort($language_data);

        return $language_data;
    }

    public function clearCategoriesData() {
        $this->db->query("TRUNCATE `" . DB_PREFIX . "category`");
        $this->db->query("TRUNCATE `" . DB_PREFIX . "category_description`");
        $this->db->query("TRUNCATE `" . DB_PREFIX . "category_path`");
        $this->db->query("TRUNCATE `" . DB_PREFIX . "category_to_layout`");
        $this->db->query("TRUNCATE `" . DB_PREFIX . "category_to_store`");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `query` LIKE 'category_id=%'");
    }

    public function clearProductsData() {
        /*
        $this->db->query("TRUNCATE `" . DB_PREFIX . "manufacturer`");
        $this->db->query("TRUNCATE `" . DB_PREFIX . "manufacturer_description`");
        $this->db->query("TRUNCATE `" . DB_PREFIX . "manufacturer_to_layout`");
        $this->db->query("TRUNCATE `" . DB_PREFIX . "manufacturer_to_store`");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `query` LIKE 'manufacturer_id=%'");
        */

        $this->db->query("TRUNCATE `" . DB_PREFIX . "product`");
        $this->db->query("TRUNCATE `" . DB_PREFIX . "product_attribute`");
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_complementary` (
                `product_id` int(11) NOT NULL,
                `complementary_id` int(11) NOT NULL,
                PRIMARY KEY (`product_id`,`complementary_id`) USING BTREE
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
        ");
        $this->db->query("TRUNCATE `" . DB_PREFIX . "product_complementary`");
        $this->db->query("TRUNCATE `" . DB_PREFIX . "product_description`");
        $this->db->query("TRUNCATE `" . DB_PREFIX . "product_discount`");
        $this->db->query("TRUNCATE `" . DB_PREFIX . "product_image`");

        $this->db->query("TRUNCATE `" . DB_PREFIX . "product_option`");
        $this->db->query("TRUNCATE `" . DB_PREFIX . "product_option_value`");
        $this->db->query("TRUNCATE `" . DB_PREFIX . "option`");
        $this->db->query("TRUNCATE `" . DB_PREFIX . "option_description`");
        $this->db->query("TRUNCATE `" . DB_PREFIX . "option_value`");
        $this->db->query("TRUNCATE `" . DB_PREFIX . "option_value_description`");

        $this->db->query("TRUNCATE `" . DB_PREFIX . "product_related`");
        $this->db->query("TRUNCATE `" . DB_PREFIX . "product_reward`");
        $this->db->query("TRUNCATE `" . DB_PREFIX . "product_special`");
        $this->db->query("TRUNCATE `" . DB_PREFIX . "product_to_category`");
        $this->db->query("TRUNCATE `" . DB_PREFIX . "product_to_layout`");
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_to_lookbook` (
                `lookbook_id` int(11) NOT NULL,
                `product_id` int(11) NOT NULL,
                PRIMARY KEY (`lookbook_id`,`product_id`) USING BTREE
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
        ");
        $this->db->query("TRUNCATE `" . DB_PREFIX . "product_to_lookbook`");
        $this->db->query("TRUNCATE `" . DB_PREFIX . "product_to_store`");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `query` LIKE 'product_id=%'");

        $this->db->query("TRUNCATE `" . DB_PREFIX . "attribute_group`");
        $this->db->query("TRUNCATE `" . DB_PREFIX . "attribute_group_description`");
        $this->db->query("TRUNCATE `" . DB_PREFIX . "attribute`");
        $this->db->query("TRUNCATE `" . DB_PREFIX . "attribute_description`");


        // $this->db->query("TRUNCATE " . DB_PREFIX . "category_path");
        // $this->db->query("TRUNCATE " . DB_PREFIX . "category_to_layout");
        // $this->db->query("TRUNCATE " . DB_PREFIX . "category_to_store");
        // $this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `query` LIKE 'category_id=%'");
    }

    public function getProducts() {
        $products_data = [];

        $query = $this->db->query("SELECT `product_id`, `image` FROM `" . DB_PREFIX . "product` ORDER BY `product_id`");

        foreach ($query->rows as $row) {
            $products_data[$row['product_id']] = [
                'product_id'    => $row['product_id'],
                'image'         => $row['image'],
            ];
        }

        return $products_data;
    }

    public function get_attribute_group() {
        $data = [];

        // 7 == Подробнее о товаре
        $query = $this->db_don->query("SELECT `attribute_group_id` FROM `" . DB_PREFIX . "attribute_group` WHERE `attribute_group_id` = '7'");

        foreach ($query->rows as $row) {
            $data[$row['attribute_group_id']] = 1;
        }

        return $data;
    }


    public function get_product_attribute() {
        $data = [];

        $query = $this->db->query("SELECT `attribute_id` FROM `" . DB_PREFIX . "product_attribute` ORDER BY `product_id`");

        foreach ($query->rows as $row) {
            $data[$row['attribute_id']] = 1;
        }

        return $data;
    }

    public function get_product_option() {
        $options_data = [];

        $query = $this->db->query("SELECT `product_id`, `option_id` FROM `" . DB_PREFIX . "product_option` ORDER BY `product_id`");

        foreach ($query->rows as $row) {
            $options_data[$row['option_id']] = 1;
        }

        return $options_data;
    }

    public function get_manufacturer() {
        $data = [];

        $query = $this->db_don->query("SELECT `manufacturer_id` FROM `" . DB_PREFIX . "manufacturer` ORDER BY `manufacturer_id`");

        foreach ($query->rows as $row) {
            $data[$row['manufacturer_id']] = 1;
        }

        return $data;
    }

    public function get_address() {
        $data = [];

        $query = $this->db_don->query("SELECT `address_id` FROM `" . DB_PREFIX . "address` ORDER BY `address_id`");

        foreach ($query->rows as $row) {
            $data[$row['address_id']] = [
                'address_id'    => $row['address_id']
            ];
        }

        return $data;
    }

    public function get_customer() {
        $data = [];

        $query = $this->db_don->query("SELECT `customer_id` FROM `" . DB_PREFIX . "customer` ORDER BY `customer_id`");

        foreach ($query->rows as $row) {
            $data[$row['customer_id']] = [
                'customer_id'    => $row['customer_id']
            ];
        }

        return $data;
    }

    public function get_order() {
        $data = [];

        $query = $this->db_don->query("SELECT `order_id` FROM `" . DB_PREFIX . "order` ORDER BY `order_id`");

        foreach ($query->rows as $row) {
            $data[$row['order_id']] = [
                'order_id'    => $row['order_id']
            ];
        }

        return $data;
    }

    public function get_order_history() {
        $data = [];

        $query = $this->db_don->query("SELECT `order_history_id` FROM `" . DB_PREFIX . "order_history` ORDER BY `order_history_id`");

        foreach ($query->rows as $row) {
            $data[$row['order_history_id']] = [
                'order_history_id'    => $row['order_history_id']
            ];
        }

        return $data;
    }

    public function get_order_option() {
        $data = [];

        $query = $this->db_don->query("SELECT `order_option_id` FROM `" . DB_PREFIX . "order_option` ORDER BY `order_option_id`");

        foreach ($query->rows as $row) {
            $data[$row['order_option_id']] = [
                'order_option_id'    => $row['order_option_id']
            ];
        }

        return $data;
    }

    public function get_order_product() {
        $data = [];

        $query = $this->db_don->query("SELECT `order_product_id` FROM `" . DB_PREFIX . "order_product` ORDER BY `order_product_id`");

        foreach ($query->rows as $row) {
            $data[$row['order_product_id']] = [
                'order_product_id'    => $row['order_product_id']
            ];
        }

        return $data;
    }

    public function get_order_status() {
        $data = [];

        $query = $this->db_don->query("SELECT `order_status_id` FROM `" . DB_PREFIX . "order_status` ORDER BY `order_status_id`");

        foreach ($query->rows as $row) {
            $data[$row['order_status_id']] = [
                'order_status_id'    => $row['order_status_id']
            ];
        }

        return $data;
    }

    public function get_order_total() {
        $data = [];

        $query = $this->db_don->query("SELECT `order_total_id` FROM `" . DB_PREFIX . "order_total` ORDER BY `order_total_id`");

        foreach ($query->rows as $row) {
            $data[$row['order_total_id']] = [
                'order_total_id'    => $row['order_total_id']
            ];
        }

        return $data;
    }

    public function getTableData($table, $order = '', $sort = 'ASC') {
        $sql = "SELECT * FROM `" . DB_PREFIX . $table . "`";

        if ($order) {
            $sql .= " ORDER BY `" . $order . "` " . $sort;
        }

        $query = $this->db_don->query($sql);

        return $query->rows;
    }

    public function importTable($data, $table, $fields, $languages) {
        if ($table == 'product' && !isset($fields['archive'])) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `archive` TINYINT(1) NOT NULL DEFAULT '0' AFTER `date_modified`");

            $fields = $this->getColumnsTable($table);
        }

        $sql = "INSERT INTO `" . DB_PREFIX . $table . "` (";

        foreach ($fields as $field => $value) {
            $sql .= "`" . $field . "`,";
        }

        $sql = rtrim($sql, ',');

        $sql .= ") VALUES";

        $values = '';

        foreach ($data as $key => $row) {
            $result = $row;

            unset($data[$key]);

            if ($table == 'product') {
                if ($result['product_id']) {
                    $this->product_IDs[$result['product_id']] = 1;

                    /*
                    $r = $this->db_don->query("SELECT * FROM `" . DB_PREFIX . "product_description` WHERE `product_id` = '" . (int)$row['product_id'] . "'");

                    if (!$r->num_rows) {
                        print_r($r);

                        die();
                    }
                    */

                    $this->imageCopy($result['image']);
                } else {
                    continue;
                }
            }

            if (!empty($this->product_IDs) && !isset($this->product_IDs[$result['product_id']])) {
                continue;
            }

            $values .= " (";

            foreach ($fields as $field => $type) {
                $value2sql  = '';

                if ($table == 'product' && $field == 'archive') {
                    $value2sql = $this->value2sql($result['_archive'], $type);
                }

                if (!$value2sql) {
                    $value2sql = $this->value2sql($result[$field], $type);
                }

                if ($field != 'noindex') {
                    if ($field == 'language_id') {
                        $values .= "'" . $languages[$value2sql] . "',";
                    } else {
                        $values .= "'" . $value2sql . "',";
                    }
                } else {
                    $values .= "'1'";
                }
            }

            $values = rtrim($values, ',');

            $values .= "),";

            if ($this->getMemoryUsage($values) >= $this->memory_limit) {
                $values = rtrim($values, ',') . ';';

                $this->db->query($sql . $values);

                $this->importTable($data, $table, $fields, $languages);

                return false;
            }
        }

        if ($values) {
            $values = rtrim($values, ',') . ';';

            $this->db->query($sql . $values);
        }
    }

    public function createAdditionalTableData($data, $table, $columns_data) {
        $fields = $this->getColumnsTable($table);

        $diff_key = array_diff_key($columns_data, $fields);

        if (!empty($diff_key)) {
            echo 'array_diff_key error 1' . PHP_EOL;

            return false;
        }

        $sql = "INSERT INTO `" . DB_PREFIX . $table . "` (";

        foreach ($columns_data as $field => $value) {
            $sql .= "`" . $field . "`,";
        }

        $sql = rtrim($sql, ',');

        $sql .= ") VALUES";

        foreach ($data as $row) {
            $sql .= " (";

            foreach ($columns_data as $field => $value) {
                if ($field == $value) {
                    $value2sql = $this->value2sql($row[$field]);
                } else {
                    $value2sql = $this->value2sql($value);
                }

                $sql .= "'" . $value2sql . "',";
            }

            $sql = rtrim($sql, ',');

            $sql .= "),";

        }

        $sql = rtrim($sql, ',') . ';';

        $this->db->query($sql);
    }

    public function addAdditionalTableData($data, $table, $index, $columns_data) {
        $languages = $this->compareLanguages();

        if ($table == 'manufacturer_to_layout') {
            $table_data = $this->getTableData('manufacturer_to_store', $index);

            $fields = $this->getColumnsTable('manufacturer_to_store');
        } else {
            $table_data = $this->getTableData($table, $index);

            $fields = $this->getColumnsTable($table);
        }

        if ($table == 'manufacturer_description' && !isset($fields['name'])) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "manufacturer_description` ADD `name` VARCHAR(64) NOT NULL AFTER `language_id`");

            $fields = $this->getColumnsTable($table);
        }

        $diff_key = array_diff_key($columns_data, $fields);

        if (!empty($diff_key)) {
            if ($table != 'manufacturer_to_layout') {
                //print_r($table_data);
                //print_r($columns_data);
                echo 'array_diff_key error 1' . PHP_EOL;

                return false;
            }
        }

        $sql = "INSERT INTO `" . DB_PREFIX . $table . "` (";

        foreach ($columns_data as $field => $value) {
            $sql .= "`" . $field . "`,";
        }

        $sql = rtrim($sql, ',');

        $sql .= ") VALUES";

        foreach ($table_data as $result) {

            // print_r($result);
            /*if ($table == 'option') {
                echo '===' . PHP_EOL;
                print_r($data[$result[$index]]);
                echo '===' . PHP_EOL;
                print_r($result);
                die();
            }*/

            if (!isset($data[$result[$index]])) {
                continue;
            }

            if ($table == 'product_image' || $table == 'manufacturer') {
                $this->imageCopy($result);
            }

            $sql .= " (";

            foreach ($columns_data as $field => $value) {
                if ($field != 'noindex') {
                    if ($field == $value) {
                        if ($field == 'language_id') {
                            $value2sql = $this->value2sql($languages[$result[$field]]);
                        } else {
                            $value2sql = $this->value2sql($result[$field], $fields[$field]);
                        }
                    } else {
                        $value2sql = $this->value2sql($value);
                    }
                } else {
                    $value2sql = '1';
                }

                $sql .= "'" . $value2sql . "',";
            }

            $sql = rtrim($sql, ',');

            $sql .= "),";

        }

        $sql = rtrim($sql, ',') . ';';

        //echo PHP_EOL;
        //echo $sql;
        //echo PHP_EOL;
        //echo PHP_EOL;

        //die();

        $this->db->query($sql);
    }

    public function importManufacturerSeoUrl($data, $id_name, $languages) {
        foreach ($data as $manufacturer_id => $null) {
            $seo_url_query = $id_name . '=' . (int)$manufacturer_id;

            $query = $this->db_don->query("SELECT `keyword` FROM `" . DB_PREFIX . "url_alias` WHERE `query` = '" . $this->db->escape($seo_url_query) ."'");

            if ($query->num_rows == 1 && !empty($query->row['keyword'])) {
                $seo_url_keyword = '__MAN__' . $query->row['keyword'];
                // $seo_url_keyword = $query->row['keyword'];

                $sql = "INSERT INTO `" . DB_PREFIX . "seo_url` (`seo_url_id`, `store_id`, `language_id`, `query`, `keyword`) VALUES";

                foreach ($languages as $language_id) {
                    $sql .= " ('', 0, '" . (int)$language_id . "', '" . $this->db->escape($seo_url_query) . "', '" . $this->db->escape($seo_url_keyword) . "'),";
                }

                $sql = rtrim($sql, ',') . ';';

                $this->db->query($sql);
            }
        }
    }

    public function importSeoUrl($data, $id_name, $languages) {
        foreach ($data as $row) {
            $seo_url_query = $id_name . '=' . (int)$row[$id_name];

            $query = $this->db_don->query("SELECT `keyword` FROM `" . DB_PREFIX . "url_alias` WHERE `query` = '" . $this->db->escape($seo_url_query) ."'");

            if ($query->num_rows == 1 && !empty($query->row['keyword'])) {
                $seo_url_keyword = $query->row['keyword'];

                $sql = "INSERT INTO `" . DB_PREFIX . "seo_url` (`seo_url_id`, `store_id`, `language_id`, `query`, `keyword`) VALUES";

                foreach ($languages as $language_id) {
                    $sql .= " ('', 0, '" . (int)$language_id . "', '" . $this->db->escape($seo_url_query) . "', '" . $this->db->escape($seo_url_keyword) . "'),";
                }

                $sql = rtrim($sql, ',') . ';';

                $this->db->query($sql);
            }
        }
    }

    public function imageCopy($data) {
        if (!is_array($data)) {
            $image_don      = DIR_IMAGE_DON     . $data;
            $image          = DIR_IMAGE         . $data;
        } else {
            if (isset($data['image'])) {
                $image_don      = DIR_IMAGE_DON     . $data['image'];
                $image          = DIR_IMAGE         . $data['image'];
            }
        }

        // if (!empty($image_don) && !empty($image) && !file_exists($image) && $this->imageValidate($image_don)) {
        if (!empty($image_don) && !empty($image) && $this->imageValidate($image_don)) {
            $path = pathinfo($image);

            if (!file_exists($path['dirname'])) {
                mkdir($path['dirname'], 0775, true);
            }

            copy($image_don, $image);
        }
    }

    public function imageValidate($image) {
        if(@is_array(getimagesize($image))) {
            return true;
        } else {
            return false;
        }
    }

    public function getColumnsTable($table, $db = 'db') {
        $query = $this->{$db}->query("SHOW COLUMNS FROM " . DB_PREFIX . $table);

        $columns_data = [];

        foreach ($query->rows as $row) {
            $fieldName = '';

            foreach ($row as $field => $value) {
                if ($field == 'Field') {
                    $fieldName = $value;
                }

                if ($field == 'Type') {
                    if (preg_match('/^(int|tinyint)/', $value)) {
                        $type = 'int';
                    } elseif (preg_match('/^decimal/', $value)) {
                        $type = 'float';
                    } else {
                        $type = 'escape';
                    }

                    $columns_data[$fieldName] = $type;
                }
            }
        }

        return $columns_data;
    }

    public function value2sql($value, $type = 'int') {
        switch ($type) {
            case 'int':
                $value2sql = (int)$value;
                break;
            case 'float':
                $value2sql = (float)$value;
                break;
            default:
                $value2sql = $this->db->escape($value);
        }

        return $value2sql;
    }

    private function getMemoryUsage($var) {
        $mem = memory_get_usage();
        $tmp = unserialize(serialize($var));

        // Return the unserialized memory usage
        return memory_get_usage() - $mem;
    }
}
