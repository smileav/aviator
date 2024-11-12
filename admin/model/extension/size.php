<?php
class ModelExtensionSize extends Model {
	public function addSize($data) {
		$this->event->trigger('pre.admin.size.add', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "size SET name = '" . $this->db->escape($data['name']) . "', sort_order = '" . (int)$data['sort_order'] . "'");

		$size_id = $this->db->getLastId();


		foreach ($data['size_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "size_description SET size_id = '" . (int)$size_id . "', language_id = '" . (int)$language_id . "', description = '" . $this->db->escape($value['description']) . "'");
		}

		if (isset($data['size_store'])) {
			foreach ($data['size_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "size_to_store SET size_id = '" . (int)$size_id . "', store_id = '" . (int)$store_id . "'");
			}
		}


		$this->cache->delete('size');

		$this->event->trigger('post.admin.size.add', [$size_id]);

		return $size_id;
	}

	public function editSize($size_id, $data) {
		$this->event->trigger('pre.admin.size.edit', $data);

		$this->db->query("UPDATE " . DB_PREFIX . "size SET name = '" . $this->db->escape($data['name']) . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE size_id = '" . (int)$size_id . "'");


		$this->db->query("DELETE FROM " . DB_PREFIX . "size_description WHERE size_id = '" . (int)$size_id . "'");

		foreach ($data['size_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "size_description SET size_id = '" . (int)$size_id . "', language_id = '" . (int)$language_id . "', description = '" . $this->db->escape($value['description']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "size_to_store WHERE size_id = '" . (int)$size_id . "'");

		if (isset($data['size_store'])) {
			foreach ($data['size_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "size_to_store SET size_id = '" . (int)$size_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		// $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'size_id=" . (int)$size_id . "'");


		$this->cache->delete('size');

		$this->event->trigger('post.admin.size.edit', [$size_id]);
	}

	public function deleteSize($size_id) {
		$this->event->trigger('pre.admin.size.delete', [$size_id]);

		$this->db->query("DELETE FROM " . DB_PREFIX . "size WHERE size_id = '" . (int)$size_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "size_to_store WHERE size_id = '" . (int)$size_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "size_description WHERE size_id = '" . (int)$size_id . "'");

		$this->cache->delete('size');

		$this->event->trigger('post.admin.size.delete', [$size_id]);
	}

	public function getSize($size_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "size WHERE size_id = '" . (int)$size_id . "'");
		return $query->row;
	}

	public function getSizeDescriptions($size_id) {
		$size_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "size_description WHERE size_id = '" . (int)$size_id . "'");

		foreach ($query->rows as $result) {
			$size_description_data[$result['language_id']] = array(
				'description'      => $result['description']
			);
		}

		return $size_description_data;
	}

	public function getSizes($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "size";

		$sql = "SELECT c.size_id, c.name, c.sort_order FROM " . DB_PREFIX . "size c LEFT JOIN " . DB_PREFIX . "size_description md ON (c.size_id = md.size_id) WHERE md.language_id = '" . (int)$this->config->get('config_language_id') . "'";



		if (!empty($data['filter_name'])) {
			$sql .= " WHERE name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
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
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getSizeStores($size_id) {
		$size_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "size_to_store WHERE size_id = '" . (int)$size_id . "'");

		foreach ($query->rows as $result) {
			$size_store_data[] = $result['store_id'];
		}

		return $size_store_data;
	}

	 public function editSizeToProduct($product_id, $size_id)
    {
        $sql = "DELETE FROM " . DB_PREFIX . "size_to_product WHERE product_id = '".$product_id."'";
        $this->db->query($sql);

        if ($size_id > 0) {
            $sql = "INSERT INTO " . DB_PREFIX . "size_to_product SET size_id = '".$size_id."', product_id = '".$product_id."'";
            $this->db->query($sql);
        }

    }

	public function getSizeProducts($product_id){
		$res = $this->db->query("SELECT * FROM `" . DB_PREFIX . "size_to_product` where product_id='" . (int)$product_id."' limit 1")->row;
		return $res?$res['size_id']:null;
	}

	public function getSizeByProductId($product_id)
    {
        $sql = "SELECT size_id FROM " . DB_PREFIX . "size_to_product WHERE product_id = '".$product_id."'";

        $results = $this->db->query($sql);
        $result = $results->row;

        if (!empty($result)) {
            return $result['size_id'];
        } else {
            return 0;
        }

    }

	public function getTotalSizes() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "size");
		return $query->row['total'];
	}
}
