<?php
class ModelExtensionModuleSize extends Model {

	public function getSizeDescriptions($size_id) {
		$data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "size_description WHERE size_id = '" . (int)$size_id . "' AND language_id='" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getProductSize($product_id) {
        $sql = "SELECT size_id FROM " . DB_PREFIX . "size_to_product WHERE product_id = '".$product_id."'";

        $results = $this->db->query($sql);
        $result = $results->row;

        if (!empty($result)) {
            return $result['size_id'];
        } else {
            return 0;
        }
    }

	public function getCategorySize($cat_id) {
        $sql = "SELECT size_id FROM " . DB_PREFIX . "size_to_store WHERE store_id = '".$cat_id."'";

        $results = $this->db->query($sql);
        $result = $results->row;

        if (!empty($result)) {
            return $result['size_id'];
        } else {
            return 0;
        }

    }

}
