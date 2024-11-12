<?php
class ModelExtensionCompleteYourLook extends Model {

    public function setLook($product_id, $data) {
        $cyl_id = $data['cyl_id'];

        if (!$cyl_id) {
            $this->db->query("INSERT INTO `" . DB_PREFIX . "complete_your_look` SET `data` = '" . $this->db->escape(json_encode($data['cyl_data'])) . "', `status` = '" . (int)$data['cyl_status'] . "'");

            $cyl_id = $this->db->getLastId();
        } else {
            $this->db->query("UPDATE `" . DB_PREFIX . "complete_your_look` SET `data` = '" . $this->db->escape(json_encode($data['cyl_data'])) . "', `status` = '" . (int)$data['cyl_status'] . "' WHERE `cyl_id` = '" . (int)$cyl_id . "'");
        }

        $this->db->query("UPDATE `" . DB_PREFIX . "product` SET `cyl_id` = '" . (int)$cyl_id . "' WHERE `product_id` = '" . (int)$product_id . "'");
    }

    public function getLook($cyl_id) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "complete_your_look` WHERE `cyl_id` = '" . (int)$cyl_id . "'");

        return $query->row;
    }
}
