<?php
class ModelExtensionCompleteYourLook extends Model {

    public function addLook($data) {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "complete_your_look` SET `cyl_id` = '" . (int)$data['cyl_id'] . "', `name` = '" . $this->db->escape($data['name']) . "', `data` = '" . $this->db->escape(json_encode($data['cyl_data'])) . "', `status` = '" . (int)$data['status'] . "'");

        $cyl_id = $this->db->getLastId();

        if (!empty($data['show_in_product'])) {
            foreach ($data['show_in_product'] as $product_id) {
                $this->db->query("UPDATE `" . DB_PREFIX . "product` SET `cyl_id` = '" . (int)$cyl_id . "' WHERE `product_id` = '" . (int)$product_id . "'");
            }
        }

        return $cyl_id;
    }

    public function editLook($cyl_id, $data) {
        $this->db->query("UPDATE `" . DB_PREFIX . "complete_your_look` SET `name` = '" . $this->db->escape($data['name']) . "', `data` = '" . $this->db->escape(json_encode($data['cyl_data'])) . "', `status` = '" . (int)$data['status'] . "' WHERE `cyl_id` = '" . (int)$cyl_id . "'");

        $this->db->query("UPDATE `" . DB_PREFIX . "product` SET `cyl_id` = '0' WHERE `cyl_id` = '" . (int)$cyl_id . "'");

        if (!empty($data['show_in_product'])) {
            foreach ($data['show_in_product'] as $product_id) {
                $this->db->query("UPDATE `" . DB_PREFIX . "product` SET `cyl_id` = '" . (int)$cyl_id . "' WHERE `product_id` = '" . (int)$product_id . "'");
            }
        }
    }

    public function getLooks() {
        $sql = "SELECT * FROM `" . DB_PREFIX . "complete_your_look` ORDER BY `name` ASC";

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalLooks() {
        $sql = "SELECT COUNT(DISTINCT `cyl_id`) AS `total` FROM `" . DB_PREFIX . "complete_your_look`";

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getLook($cyl_id) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "complete_your_look` WHERE `cyl_id` = '" . (int)$cyl_id . "'");

        return $query->row;
    }

    public function getShowInProducts($cyl_id) {
        $return_data = [];

        $query = $this->db->query("SELECT `product_id` FROM " . DB_PREFIX . "product WHERE cyl_id = '" . (int)$cyl_id . "'");

        foreach ($query->rows as $result) {
            $return_data[] = $result['product_id'];
        }

        return $return_data;
    }
}
