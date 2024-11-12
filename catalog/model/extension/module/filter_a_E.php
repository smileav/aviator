<?php
class ModelExtensionModuleFilterA extends Model {

    public function getFilterA_DataByCategory($category_id): array
    {
        $product_data = [];

        $sql  = "SELECT `p`.`product_id`, `p`.`price`,
            (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS ps_price
            FROM `" . DB_PREFIX . "product_to_category` `p2c`
            LEFT JOIN `" . DB_PREFIX . "product` `p` ON (`p2c`.`product_id` = `p`.`product_id`)
            LEFT JOIN `" . DB_PREFIX . "product_special` `ps` ON (`ps`.`product_id` = `p`.`product_id`) 
            LEFT JOIN `" . DB_PREFIX . "product_attribute` `paG` ON (`paG`.`product_id` = `p`.`product_id`)";
        $sql .= " WHERE p.status = '1' AND p.date_available <= NOW()";
        $sql .= " AND `p2c`.`category_id`     = '" . (int)$category_id . "'";
        $sql .= " GROUP BY p.product_id";

        $query = $this->db->query($sql);

        $product_data['min_price'] = 0;
        $product_data['max_price'] = 0;

        foreach ($query->rows as $result) {
            if (!is_null($result['ps_price']) && (float)$result['ps_price'] > 0) {
                $price = $result['ps_price'];
            } else {
                $price = $result['price'];
            }

            if (!$product_data['min_price']) {
                $product_data['min_price'] = $price;
            } elseif ($product_data['min_price'] > $price) {
                $product_data['min_price'] = $price;
            }

            if (!$product_data['max_price']) {
                $product_data['max_price'] = $price;
            } elseif ($product_data['max_price'] < $price) {
                $product_data['max_price'] = $price;
            }

            $product_data[$result['product_id']] = $price;
        }

        return $product_data;
    }

    public function getFilterA_G($data) {
        $query = $this->db->query("SELECT DISTINCT `p`.`product_id`, pa.attribute_id as `id`, `pa`.`text` as `name`
                FROM `" . DB_PREFIX . "product_to_category` `p2c`
				LEFT JOIN `oc_product` p ON (p2c.product_id = p.product_id)
                LEFT JOIN `oc_product_attribute` `pa` ON (`pa`.`product_id` = `p`.`product_id`)
                LEFT JOIN `oc_attribute` `a` ON (`a`.`attribute_id` = `pa`.`attribute_id`)
            WHERE
                `pa`.`language_id`      = '" . (int)$this->config->get('config_language_id') . "' AND
                `pa`.`attribute_id`     = '59' AND
                `p2c`.`category_id`     = '" . (int)$data['category_id'] . "' AND
                `p`.`quantity`          > '0' AND
                `p`.`status`            = '1'
                GROUP BY `p`.`product_id`
                ORDER BY `a`.`sort_order`;
                ");

        return $query->rows;
    }

    public function getFilterA_C($data) {
        $query = $this->db->query("SELECT DISTINCT `p`.`product_id`, pa.attribute_id as `id`, `pa`.`text` as `name`
                FROM `" . DB_PREFIX . "product_to_category` `p2c`
				LEFT JOIN `oc_product` p ON (p2c.product_id = p.product_id)
                LEFT JOIN `oc_product_attribute` `pa` ON (`pa`.`product_id` = `p`.`product_id`)
                LEFT JOIN `oc_attribute` `a` ON (`a`.`attribute_id` = `pa`.`attribute_id`)
            WHERE
                `pa`.`language_id`      = '" . (int)$this->config->get('config_language_id') . "' AND
                `pa`.`attribute_id`     = '60' AND
                `p2c`.`category_id`     = '" . (int)$data['category_id'] . "' AND
                `p`.`quantity`          > '0' AND
                `p`.`status`            = '1'
                GROUP BY `p`.`product_id`
                ORDER BY `a`.`sort_order`;
                ");

        return $query->rows;
    }

    public function getFilterA_M($data) {
        $query = $this->db->query("
            SELECT DISTINCT `p`.`product_id`, `md`.`manufacturer_id` as `id`, `md`.`name`
            FROM `" . DB_PREFIX . "product_to_category` `p2c`
                LEFT JOIN `" . DB_PREFIX . "product` p ON (p2c.product_id = p.product_id)
                LEFT JOIN `" . DB_PREFIX . "manufacturer_description` `md` ON (`md`.`manufacturer_id` = `p`.`manufacturer_id`)
            WHERE
                `md`.`language_id`      = '" . (int)$this->config->get('config_language_id') . "' AND
                `p2c`.`category_id`     = '" . (int)$data['category_id'] . "' AND
                `p`.`quantity`          > '0' AND
                `p`.`status`            = '1'
            GROUP BY `p`.`product_id`
            ORDER BY `md`.`name`
        ");

        return $query->rows;
    }

    public function getFilterA_S($data) {
        $query = $this->db->query("
            SELECT DISTINCT `p`.`product_id`, `ovd`.`option_value_id` as `id`, `ovd`.`name`
            FROM `" . DB_PREFIX . "product_to_category` `p2c`
                LEFT JOIN `" . DB_PREFIX . "product` p ON (p2c.product_id = p.product_id)
                LEFT JOIN `" . DB_PREFIX . "product_option_value` `pov` ON (pov.product_id = p.product_id)
                LEFT JOIN `" . DB_PREFIX . "option_value` `ov` ON (`ov`.`option_value_id` = `pov`.`option_value_id`)
                LEFT JOIN `" . DB_PREFIX . "option_value_description` `ovd` ON (`ovd`.`option_value_id` = `ov`.`option_value_id`)
            WHERE
                `ovd`.`language_id`     = '" . (int)$this->config->get('config_language_id') . "' AND
                `pov`.`option_id`       = '1' AND
                `pov`.`quantity`        > '0' AND
                `p2c`.`category_id`     = '" . (int)$data['category_id'] . "' AND
                `p`.`quantity`          > '0' AND
                `p`.`status`            = '1' 
            ORDER BY `ov`.`sort_order`
        ");

        return $query->rows;
    }
















    public function getFilterA_Genders($data) {
        $query = $this->db->query("
            SELECT `pa`.`attribute_id` AS `id`, `pa`.`text` AS `name` FROM `" . DB_PREFIX . "product_attribute` `pa`
                LEFT JOIN `" . DB_PREFIX . "attribute` `a` ON (`a`.`attribute_id` = `pa`.`attribute_id`)
                LEFT JOIN `" . DB_PREFIX . "product_to_category` `p2c` ON (`pa`.`product_id` = `p2c`.`product_id`)
                LEFT JOIN `" . DB_PREFIX . "product` p ON (p2c.product_id = p.product_id)
            WHERE
                `pa`.`language_id`      = '" . (int)$this->config->get('config_language_id') . "' AND
                `pa`.`attribute_id`     = '" . (int)$data['attribute_id'] . "' AND
                `p2c`.`category_id`     = '" . (int)$data['category_id'] . "' AND
                `p`.`quantity`          > '0' AND
                `p`.`status`            = '1'
            GROUP BY `pa`.`text`
            ORDER BY `a`.`sort_order`
        ");

        return $query->rows;
    }

    public function getFilterA_AllData($data): array
    {
        $product_data = [];

        $sql  = "SELECT `p`.`product_id`, `p`.`price`,
            (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS ps_price
            FROM `" . DB_PREFIX . "product_to_category` `p2c`
            LEFT JOIN `" . DB_PREFIX . "product` `p` ON (`p2c`.`product_id` = `p`.`product_id`)
            LEFT JOIN `" . DB_PREFIX . "product_special` `ps` ON (`ps`.`product_id` = `p`.`product_id`) 
            LEFT JOIN `" . DB_PREFIX . "product_attribute` `paG` ON (`paG`.`product_id` = `p`.`product_id`)";
        $sql .= " WHERE p.status = '1' AND p.date_available <= NOW()";
        $sql .= " AND `p2c`.`category_id`     = '" . (int)$data['category_id'] . "'";
        $sql .= " AND `paG`.`text` = '" .   $this->db->escape($data['fiaG']) . "'";
        $sql .= " GROUP BY p.product_id";

        $query = $this->db->query($sql);

        $product_data['min_price'] = 0;
        $product_data['max_price'] = 0;

        foreach ($query->rows as $result) {
            if (!is_null($result['ps_price']) && (float)$result['ps_price'] > 0) {
                $price = $result['ps_price'];
            } else {
                $price = $result['price'];
            }

            if (!$product_data['min_price']) {
                $product_data['min_price'] = $price;
            } elseif ($product_data['min_price'] > $price) {
                $product_data['min_price'] = $price;
            }

            if (!$product_data['max_price']) {
                $product_data['max_price'] = $price;
            } elseif ($product_data['max_price'] < $price) {
                $product_data['max_price'] = $price;
            }

            $product_data[$result['product_id']] = '';
        }

        return $product_data;
    }

    public function getFilterA_Data($data): array
    {
        $product_data = [];

        $sql  = "SELECT `p`.`product_id`, `p`.`price`, `ps`.`price` AS `ps_price` FROM `" . DB_PREFIX . "product_to_category` `p2c`
            LEFT JOIN `" . DB_PREFIX . "product` `p` ON (`p2c`.`product_id` = `p`.`product_id`)
            LEFT JOIN `" . DB_PREFIX . "product_special` `ps` ON (`ps`.`product_id` = `p`.`product_id`)";

        if (!empty($data['fiaG'])) {
            $sql .= " LEFT JOIN `" . DB_PREFIX . "product_attribute` `paG` ON (`paG`.`product_id` = `p`.`product_id`)";
        }

        if (!empty($data['fia_GET']['C'])) {
            $sql .= " LEFT JOIN `" . DB_PREFIX . "product_attribute` `paC` ON (`paC`.`product_id` = `p`.`product_id`)";
        }

        if (!empty($data['fia_GET']['S'])) {
            $sql .= " LEFT JOIN `" . DB_PREFIX . "product_option_value` `pov` ON (`pov`.`product_id` = `p`.`product_id`)";
        }

        $sql .= " WHERE p.status = '1' AND p.date_available <= NOW()";
        $sql .= " AND `p2c`.`category_id`     = '" . (int)$data['category_id'] . "'";
        // $sql .= " AND `paG`.`text` = '" .   $this->db->escape($data['fiaG']) . "'";

        if (!empty($data['fiaG'])) {
            $implode = [];

            foreach ($data['fiaG'] as $value) {
                $implode[] = $this->db->escape($value);
            }

            $sql .= " AND `paG`.`text` IN ('" . implode('\',\'', $implode) . "')";
        }

        if (!empty($data['fia_GET']['C'])) {
            $implode = [];

            foreach ($data['fia_GET']['C'] as $value) {
                $implode[] = $this->db->escape($value);
            }

            $sql .= " AND `paC`.`text` IN ('" . implode('\',\'', $implode) . "')";
        }

        if (!empty($data['fia_GET']['M'])) {
            $implode = [];

            foreach ($data['fia_GET']['M'] as $value) {
                $implode[] = (int)$value;
            }

            $sql .= " AND `p`.`manufacturer_id` IN ('" . implode('\',\'', $implode) . "')";
        }

        if (!empty($data['fia_GET']['P'][0]) && !empty($data['fia_GET']['P'][1])) {
            $sql .= " AND IF (ps.price,";
            $sql .= " (`ps`.`price` >= '" . (float)$data['fia_GET']['P'][0] . "' AND `ps`.`price` <= '" . (float)$data['fia_GET']['P'][1] . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))),";
            $sql .= " (`p`.`price` >= '" . (float)$data['fia_GET']['P'][0] . "' AND `p`.`price` <= '" . (float)$data['fia_GET']['P'][1] . "')";
            $sql .= ")";
        }

        if (!empty($data['fia_GET']['S'])) {
            $implode = [];

            foreach ($data['fia_GET']['S'] as $value) {
                $implode[] = (int)$value;
            }

            $sql .= " AND `pov`.`option_value_id` IN ('" . implode('\',\'', $implode) . "') AND `pov`.`quantity` > '0'";
        }

        $sql .= " GROUP BY p.product_id";

        $query = $this->db->query($sql);


        // print_r($query->rows);

        // print_r($sql);

        $product_data['min_price'] = 0;
        $product_data['max_price'] = 0;

        foreach ($query->rows as $result) {
            if (!is_null($result['ps_price']) && (float)$result['ps_price'] > 0) {
                $price = $result['ps_price'];
            } else {
                $price = $result['price'];
            }

            if (!$product_data['min_price']) {
                $product_data['min_price'] = $price;
            } elseif ($product_data['min_price'] > $price) {
                $product_data['min_price'] = $price;
            }

            if (!$product_data['max_price']) {
                $product_data['max_price'] = $price;
            } elseif ($product_data['max_price'] < $price) {
                $product_data['max_price'] = $price;
            }

            $product_data[$result['product_id']] = '';
        }


        return $product_data;
    }

    public function getFilterA_DataNew($data) {
        $product_data = [];

        $sql  = "SELECT `p`.`product_id`, `p`.`price`, `ps`.`price` AS `ps_price` FROM `" . DB_PREFIX . "product_to_category` `p2c`
            LEFT JOIN `" . DB_PREFIX . "product` `p` ON (`p2c`.`product_id` = `p`.`product_id`)
            LEFT JOIN `" . DB_PREFIX . "product_special` `ps` ON (`ps`.`product_id` = `p`.`product_id`)";

        if ($data['key'] == 'C' && !empty($data['value'])) {
            $sql .= " LEFT JOIN `" . DB_PREFIX . "product_attribute` `paC` ON (`paC`.`product_id` = `p`.`product_id`)";
        }

        if ($data['key'] == 'S' && !empty($data['value'])) {
            $sql .= " LEFT JOIN `" . DB_PREFIX . "product_option_value` `pov` ON (`pov`.`product_id` = `p`.`product_id`)";
        }

        $sql .= " WHERE p.status = '1' AND p.date_available <= NOW()";
        $sql .= " AND `p2c`.`category_id`     = '" . (int)$data['category_id'] . "'";
        //$sql .= " AND `paG`.`text` = '" .   $this->db->escape($data['fiaG']) . "'";

        if ($data['key'] == 'C' && !empty($data['value'])) {
            $implode = [];

            foreach ($data['value'] as $value) {
                $implode[] = $this->db->escape($value);
            }

            $sql .= " AND `paC`.`text` IN ('" . implode('\',\'', $implode) . "')";
        }

        if (!empty($data['fiaM'])) {
            $implode = [];

            foreach ($data['fiaM'] as $value) {
                $implode[] = (int)$value;
            }

            $sql .= " AND `p`.`manufacturer_id` IN ('" . implode('\',\'', $implode) . "')";
        }

        if (!empty($data['fiaP']) && !empty($data['fiaP'][0]) && !empty($data['fiaP'][1])) {
            $sql .= " AND IF (ps.price,";
            $sql .= " (`ps`.`price` >= '" . (float)$data['fiaP'][0] . "' AND `ps`.`price` <= '" . (float)$data['fiaP'][1] . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))),";
            $sql .= " (`p`.`price` >= '" . (float)$data['fiaP'][0] . "' AND `p`.`price` <= '" . (float)$data['fiaP'][1] . "')";
            $sql .= ")";
        }

        if ($data['key'] == 'S' && !empty($data['value'])) {
            $implode = [];

            foreach ($data['value'] as $value) {
                $implode[] = (int)$value;
            }

            $sql .= " AND `pov`.`option_value_id` IN ('" . implode('\',\'', $implode) . "') AND `pov`.`quantity` > '0'";
        }

        $sql .= " GROUP BY p.product_id";

        $query = $this->db->query($sql);


        //print_r($query->rows);

        //print_r($sql);

        $product_data['min_price'] = 0;
        $product_data['max_price'] = 0;

        foreach ($query->rows as $result) {
            if (!is_null($result['ps_price']) && (float)$result['ps_price'] > 0) {
                $price = $result['ps_price'];
            } else {
                $price = $result['price'];
            }

            if (!$product_data['min_price']) {
                $product_data['min_price'] = $price;
            } elseif ($product_data['min_price'] > $price) {
                $product_data['min_price'] = $price;
            }

            if (!$product_data['max_price']) {
                $product_data['max_price'] = $price;
            } elseif ($product_data['max_price'] < $price) {
                $product_data['max_price'] = $price;
            }

            $product_data[$result['product_id']] = '';
        }


        echo PHP_EOL;
        echo count($product_data) - 2;
        echo PHP_EOL;

        // print_r($product_data);

        return $product_data;
    }

    public function getFilterA_Categories($data) {
        $query = $this->db->query("SELECT DISTINCT `p`.`product_id`, pa.attribute_id as `id`, `pa`.`text` as `name`
                FROM `" . DB_PREFIX . "product_to_category` `p2c`
				LEFT JOIN `oc_product` p ON (p2c.product_id = p.product_id)
                LEFT JOIN `oc_product_attribute` `pa` ON (`pa`.`product_id` = `p`.`product_id`)
                LEFT JOIN `oc_attribute` `a` ON (`a`.`attribute_id` = `pa`.`attribute_id`)
            WHERE
                `pa`.`language_id`      = '" . (int)$this->config->get('config_language_id') . "' AND
                `pa`.`attribute_id`     = '" . (int)$data['attribute_id'] . "' AND
                `p2c`.`category_id`     = '" . (int)$data['category_id'] . "' AND
                `p`.`quantity`          > '0' AND
                `p`.`status`            = '1'
                GROUP BY `p`.`product_id`
                ORDER BY `a`.`sort_order`;
                ");

        return $query->rows;
    }

    public function getFilterA_Manufacturers($data) {
        $query = $this->db->query("
            SELECT DISTINCT `p`.`product_id`, `md`.`manufacturer_id` as `id`, `md`.`name`
            FROM `" . DB_PREFIX . "product_to_category` `p2c`
                LEFT JOIN `" . DB_PREFIX . "product` p ON (p2c.product_id = p.product_id)
                LEFT JOIN `" . DB_PREFIX . "manufacturer_description` `md` ON (`md`.`manufacturer_id` = `p`.`manufacturer_id`)
            WHERE
                `md`.`language_id`      = '" . (int)$this->config->get('config_language_id') . "' AND
                `p2c`.`category_id`     = '" . (int)$data['category_id'] . "' AND
                `p`.`quantity`          > '0' AND
                `p`.`status`            = '1'
            GROUP BY `p`.`product_id`
            ORDER BY `md`.`name`
        ");

        return $query->rows;
    }


    public function getFilterA_Sizes($data) {

        $query = $this->db->query("
            SELECT DISTINCT `p`.`product_id`, `ovd`.`option_value_id` as `id`, `ovd`.`name`
            FROM `" . DB_PREFIX . "product_to_category` `p2c`
                LEFT JOIN `" . DB_PREFIX . "product` p ON (p2c.product_id = p.product_id)
                LEFT JOIN `" . DB_PREFIX . "product_option_value` `pov` ON (pov.product_id = p.product_id)
                LEFT JOIN `" . DB_PREFIX . "option_value` `ov` ON (`ov`.`option_value_id` = `pov`.`option_value_id`)
                LEFT JOIN `" . DB_PREFIX . "option_value_description` `ovd` ON (`ovd`.`option_value_id` = `ov`.`option_value_id`)
            WHERE
                `ovd`.`language_id`     = '" . (int)$this->config->get('config_language_id') . "' AND
                `pov`.`option_id`       = '" . (int)$data['option_id'] . "' AND
                `pov`.`quantity`        > '0' AND
                `p2c`.`category_id`     = '" . (int)$data['category_id'] . "' AND
                `p`.`quantity`          > '0' AND
                `p`.`status`            = '1' 
            ORDER BY `ov`.`sort_order`
        ");

        return $query ->rows;
    }


    public function getFilterA_SizesOLD($data) {
        $query = $this->db->query("
            SELECT DISTINCT `p`.`product_id`, `ovd`.`option_value_id` as `id`, `ovd`.`name`
            FROM `" . DB_PREFIX . "product_to_category` `p2c`
                LEFT JOIN `" . DB_PREFIX . "product` p ON (p2c.product_id = p.product_id)
                LEFT JOIN `" . DB_PREFIX . "product_option_value` `pov` ON (pov.product_id = p.product_id)
                LEFT JOIN `" . DB_PREFIX . "option_value` `ov` ON (`ov`.`option_value_id` = `pov`.`option_value_id`)
                LEFT JOIN `" . DB_PREFIX . "option_value_description` `ovd` ON (`ovd`.`option_value_id` = `ov`.`option_value_id`)
            WHERE
                `ovd`.`language_id`     = '" . (int)$this->config->get('config_language_id') . "' AND
                `pov`.`option_id`       = '" . (int)$data['option_id'] . "' AND
                `pov`.`quantity`        > '0' AND
                `p2c`.`category_id`     = '" . (int)$data['category_id'] . "' AND
                `p`.`quantity`          > '0' AND
                `p`.`status`            = '1'
            GROUP BY `p`.`product_id`
            ORDER BY `ov`.`sort_order`
        ");

        return $query->rows;
    }














    public function getFilterAAttributes($data) {
        $query = $this->db->query("SELECT DISTINCT `p`.`product_id`, pa.attribute_id as `id`, `pa`.`text` as `name`
                FROM `" . DB_PREFIX . "product_to_category` `p2c`
				LEFT JOIN `oc_product` p ON (p2c.product_id = p.product_id)
                LEFT JOIN `oc_product_attribute` `pa` ON (`pa`.`product_id` = `p`.`product_id`)
                LEFT JOIN `oc_attribute` `a` ON (`a`.`attribute_id` = `pa`.`attribute_id`)
            WHERE
                `pa`.`language_id`      = '" . (int)$this->config->get('config_language_id') . "' AND
                `pa`.`attribute_id`     = '" . (int)$data['attribute_id'] . "' AND
                `p2c`.`category_id`     = '" . (int)$data['category_id'] . "' AND
                `p`.`quantity`          > '0' AND
                `p`.`status`            = '1'
                GROUP BY `p`.`product_id`
                ORDER BY `a`.`sort_order`;
                ");

        return $this->formatFilterAData($query->rows, $data['prefix']);
    }


    public function getFilterAAttributes2($data) {
        $query = $this->db->query("
            SELECT `pa`.`attribute_id`, `pa`.`text`, COUNT(`pa`.`text`) as total FROM `" . DB_PREFIX . "product_attribute` `pa`
                LEFT JOIN `" . DB_PREFIX . "attribute` `a` ON (`a`.`attribute_id` = `pa`.`attribute_id`)
                LEFT JOIN `" . DB_PREFIX . "product_to_category` `p2c` ON (`pa`.`product_id` = `p2c`.`product_id`)
                LEFT JOIN `" . DB_PREFIX . "product` p ON (p2c.product_id = p.product_id)
            WHERE
                `pa`.`language_id`      = '" . (int)$this->config->get('config_language_id') . "' AND
                `pa`.`attribute_id`     = '" . (int)$data['attribute_id'] . "' AND
                `p2c`.`category_id`     = '" . (int)$data['category_id'] . "' AND
                `p`.`quantity`          > '0' AND
                `p`.`status`            = '1'
            GROUP BY `pa`.`text`
            ORDER BY `a`.`sort_order`
        ");

        print_r($query->rows);
        die();

        return $query ->rows;
    }

    public function getFilterA_Sizes2222($data) {
        $query = $this->db->query("
            SELECT DISTINCT `p`.`product_id`, `ovd`.`option_value_id` as `id`, `ovd`.`name`
            FROM `" . DB_PREFIX . "product_to_category` `p2c`
                LEFT JOIN `" . DB_PREFIX . "product` p ON (p2c.product_id = p.product_id)
                LEFT JOIN `" . DB_PREFIX . "product_option_value` `pov` ON (pov.product_id = p.product_id)
                LEFT JOIN `" . DB_PREFIX . "option_value` `ov` ON (`ov`.`option_value_id` = `pov`.`option_value_id`)
                LEFT JOIN `" . DB_PREFIX . "option_value_description` `ovd` ON (`ovd`.`option_value_id` = `ov`.`option_value_id`)
            WHERE
                `ovd`.`language_id`     = '" . (int)$this->config->get('config_language_id') . "' AND
                `pov`.`option_id`       = '" . (int)$data['option_id'] . "' AND
                `pov`.`quantity`        > '0' AND
                `p2c`.`category_id`     = '" . (int)$data['category_id'] . "' AND
                `p`.`quantity`          > '0' AND
                `p`.`status`            = '1'
            GROUP BY `p`.`product_id`
            ORDER BY `ov`.`sort_order`
        ");



        // return $this->formatFilterAData($query->rows, $data['prefix']);;
        return $query ->rows;
    }








    public function getFilterAResults($data) {
        $sql = "
            SELECT `pa`.`attribute_id`, `pa`.`text`, COUNT(`pa`.`text`) as total FROM `" . DB_PREFIX . "product_attribute` `pa`
                LEFT JOIN `" . DB_PREFIX . "attribute` `a` ON (`a`.`attribute_id` = `pa`.`attribute_id`)
                LEFT JOIN `" . DB_PREFIX . "product_to_category` `p2c` ON (`pa`.`product_id` = `p2c`.`product_id`)
                LEFT JOIN `" . DB_PREFIX . "product` p ON (p2c.product_id = p.product_id)";

        $sql .= " LEFT JOIN `" . DB_PREFIX . "product_option_value` `pov` ON (`p`.product_id = `pov`.product_id)";
        $sql .= "
            WHERE
                `pa`.`language_id`      = '" . (int)$this->config->get('config_language_id') . "' AND
                `pa`.`attribute_id`     = '" . (int)$data['attribute_id'] . "' AND
                `p2c`.`category_id`     = '" . (int)$data['category_id'] . "' AND
                `p`.`quantity`          > '0' AND
                `p`.`status`            = '1'  AND";

        $sql .= " `pov`.`option_value_id`     IN (" . implode(',', [ 71 ]) . ")";
        $sql .= " `pa`.`text`     IN (" . implode(',', $data['G']) . ")";

        $sql .= "
            GROUP BY `pa`.`text`
            ORDER BY `a`.`sort_order`
        ";

        print_r($sql);

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function formatFilterAData($data, $prefix) {
        $return_data = [];

        foreach ($data as $result) {
            $return_data[$prefix][$result['id']][$result['name']][] = $result['product_id'];

            if (!isset($return_data[$prefix][$result['id']][$result['name']]['count'])) {
                $return_data[$prefix][$result['id']][$result['name']]['count'] = 1;
            } else {
                $return_data[$prefix][$result['id']][$result['name']]['count'] = $return_data[$prefix][$result['id']][$result['name']]['count'] + 1;
            }
        }

        return $return_data;
    }
}
