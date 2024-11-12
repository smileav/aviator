<?php
class ModelExtensionFeedRozetkaFeed extends Model {
	public function getManufacturers($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "manufacturer";

        if (!empty($data['filter_name'])) {
			$sql .= " WHERE name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sort_data = array(
			'name',
			'sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 50;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
    }

	public function getDisabledVendors() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "rozetka_banlist rb LEFT JOIN " . DB_PREFIX . "manufacturer m ON (rb.manufacturer_id = m.manufacturer_id)");
		return $query->rows;
	}

	public function banVendors($data = array()) {

		$this->db->query("DELETE FROM " . DB_PREFIX . "rozetka_banlist");

		foreach ($data as $manufacturer_id) {
			$query = $this->db->query("INSERT INTO " . DB_PREFIX . "rozetka_banlist (`manufacturer_id`) VALUES ('" . (int)$manufacturer_id . "')");
		}

		return $query->rows;
	}

	public function unbanVendors() {
		$this->db->query("DELETE FROM " . DB_PREFIX . "rozetka_banlist");
		return $query->row;
	}

	public function unbanVendor($manufacturer_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "rozetka_banlist WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		return $query->row;
	}

}
