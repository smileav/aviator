<?php

	class ModelExtensionFeedRozetkaFeed extends Controller {

		public function getCategories() {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.category_id, LCASE(cd.name)");
			return $query->rows;
		}

		public function getRozetkaCategory($category_id) {
			$query = $this->db->query("SELECT rz_id FROM " . DB_PREFIX . "category_to_rozetka WHERE category_id = '" . (int)$category_id . "'");
			return $query->row;
		}

		public function getProducts($data = array()) {
			$sql = "SELECT DISTINCT *";
			$sql .= " FROM " . DB_PREFIX . "product p";
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
			$query = $this->db->query($sql);
			return  $query->rows;
		}

		public function getProductCategories($product_id) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "' AND main_category = 1 ORDER BY category_id");
			if ($query->num_rows > 0) {
				return $query->rows;
			} else {
				return false;
			}
		}

		public function getProductImages($product_id) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");

			return $query->rows;
		}

		public function getProductSpecial($product_id) {
			$query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");

			return $query->row;
		}

		public function getBannedVendors() {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "rozetka_banlist");

			return $query->row;
		}

	}
