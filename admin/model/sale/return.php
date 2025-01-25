<?php
class ModelSaleReturn extends Model {
	public function addReturn($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "return` SET 
		order_id = '" . (int)$data['order_id'] . "', 
		
		customer_id = '" . (int)$data['customer_id'] . "', 
		firstname = '" . $this->db->escape($data['firstname']) . "', 
		lastname = '" . $this->db->escape($data['lastname']) . "', 
		email = '" . $this->db->escape($data['email']) . "', 
		telephone = '" . $this->db->escape($data['telephone']) . "', 
		receiver = '" . $this->db->escape($data['receiver']) . "',
		inn = '" . $this->db->escape($data['inn']) . "',
		iban = '" . $this->db->escape($data['iban']) . "',
		opened = '" . (int)$data['opened'] . "', 
		return_reason_id = '" . (int)$data['return_reason_id'] . "', 
		return_action_id = '" . (int)$data['return_action_id'] . "',
		 return_status_id = '" . (int)$data['return_status_id'] . "', 
		 comment = '" . $this->db->escape($data['comment']) . "', 
		 date_ordered = '" . $this->db->escape($data['date_ordered']) . "', 
		 date_added = NOW(), date_modified = NOW()");

		$return_id=$this->db->getLastId();
		$this->load->model('catalog/product');
		foreach($data['products'] as $product) {
			$product_info=$this->model_catalog_product->getProduct($product['product_id']);
			$this->db->query("INSERT INTO `" . DB_PREFIX . "return_products` SET
			return_id = '" . (int)$return_id . "',
			product_id = '" . (int)$product['product_id'] . "',
			quantity = '" . (int)$product['quantity'] . "',
			name = '" . $this->db->escape($product_info['name']) . "',
			model = '" . $this->db->escape($product_info['model']) . "'");
		}

		return $return_id;
	}

	public function editReturn($return_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "return` SET 
		order_id = '" . (int)$data['order_id'] . "', 
		
		customer_id = '" . (int)$data['customer_id'] . "', 
		firstname = '" . $this->db->escape($data['firstname']) . "', 
		lastname = '" . $this->db->escape($data['lastname']) . "', 
		email = '" . $this->db->escape($data['email']) . "', 
		telephone = '" . $this->db->escape($data['telephone']) . "', 
		receiver = '" . $this->db->escape($data['receiver']) . "',
		inn = '" . $this->db->escape($data['inn']) . "',
		iban = '" . $this->db->escape($data['iban']) . "',
		 opened = '" . (int)$data['opened'] . "', 
		 return_reason_id = '" . (int)$data['return_reason_id'] . "', 
		 return_action_id = '" . (int)$data['return_action_id'] . "', 
		 comment = '" . $this->db->escape($data['comment']) . "', 
		 date_ordered = '" . $this->db->escape($data['date_ordered']) . "', 
		 date_modified = NOW() WHERE return_id = '" . (int)$return_id . "'");

		$this->load->model('catalog/product');
		$this->db->query("DELETE FROM `" . DB_PREFIX . "return_products` WHERE return_id = '" . (int)$return_id . "'");
		foreach($data['products'] as $product) {
			$product_info=$this->model_catalog_product->getProduct($product['product_id']);
			$this->db->query("INSERT INTO `" . DB_PREFIX . "return_products` SET
			return_id = '" . (int)$return_id . "',
			product_id = '" . (int)$product['product_id'] . "',
			quantity = '" . (int)$product['quantity'] . "',
			name = '" . $this->db->escape($product_info['name']) . "',
			model = '" . $this->db->escape($product_info['model']) . "'");
		}

	}

	public function deleteReturn($return_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "return` WHERE `return_id` = '" . (int)$return_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "return_history` WHERE `return_id` = '" . (int)$return_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "return_products` WHERE return_id = '" . (int)$return_id . "'");
	}

	public function getReturn($return_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = r.customer_id) AS customer, (SELECT rs.name FROM " . DB_PREFIX . "return_status rs WHERE rs.return_status_id = r.return_status_id AND rs.language_id = '" . (int)$this->config->get('config_language_id') . "') AS return_status FROM `" . DB_PREFIX . "return` r WHERE r.return_id = '" . (int)$return_id . "'");

		return $query->row;
	}

	public function getReturns($data = array()) {
		$sql = "SELECT *, CONCAT(r.firstname, ' ', r.lastname) AS customer, (SELECT rs.name FROM " . DB_PREFIX . "return_status rs WHERE rs.return_status_id = r.return_status_id AND rs.language_id = '" . (int)$this->config->get('config_language_id') . "') AS return_status FROM `" . DB_PREFIX . "return` r";

		if (!empty($data['filter_product'])||!empty($data['filter_model'])) {
			$sql .= " LEFT JOIN `" . DB_PREFIX . "return_products` rp on rp.return_id = r.return_id ";
		}
		$implode = array();

		if (!empty($data['filter_return_id'])) {
			$implode[] = "r.return_id = '" . (int)$data['filter_return_id'] . "'";
		}

		if (!empty($data['filter_order_id'])) {
			$implode[] = "r.order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$implode[] = "CONCAT(r.firstname, ' ', r.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_product'])) {
			$implode[] = "rp.name = '" . $this->db->escape($data['filter_product']) . "'";
		}

		if (!empty($data['filter_model'])) {
			$implode[] = "rp.model = '" . $this->db->escape($data['filter_model']) . "'";
		}

		if (!empty($data['filter_return_status_id'])) {
			$implode[] = "r.return_status_id = '" . (int)$data['filter_return_status_id'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(r.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			$implode[] = "DATE(r.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'r.return_id',
			'r.order_id',
			'customer',
			'r.product',
			'r.model',
			'status',
			'r.date_added',
			'r.date_modified'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY r.return_id";
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

	public function getTotalReturns($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "return`r";

		$implode = array();

		if (!empty($data['filter_return_id'])) {
			$implode[] = "r.return_id = '" . (int)$data['filter_return_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$implode[] = "CONCAT(r.firstname, ' ', r.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_order_id'])) {
			$implode[] = "r.order_id = '" . $this->db->escape($data['filter_order_id']) . "'";
		}

		if (!empty($data['filter_product'])) {
			$implode[] = "r.product = '" . $this->db->escape($data['filter_product']) . "'";
		}

		if (!empty($data['filter_model'])) {
			$implode[] = "r.model = '" . $this->db->escape($data['filter_model']) . "'";
		}

		if (!empty($data['filter_return_status_id'])) {
			$implode[] = "r.return_status_id = '" . (int)$data['filter_return_status_id'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(r.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			$implode[] = "DATE(r.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalReturnsByReturnStatusId($return_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "return` WHERE return_status_id = '" . (int)$return_status_id . "'");

		return $query->row['total'];
	}

	public function getTotalReturnsByReturnReasonId($return_reason_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "return` WHERE return_reason_id = '" . (int)$return_reason_id . "'");

		return $query->row['total'];
	}

	public function getTotalReturnsByReturnActionId($return_action_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "return` WHERE return_action_id = '" . (int)$return_action_id . "'");

		return $query->row['total'];
	}
	
	public function addReturnHistory($return_id, $return_status_id, $comment, $notify) {
		$this->db->query("UPDATE `" . DB_PREFIX . "return` SET `return_status_id` = '" . (int)$return_status_id . "', date_modified = NOW() WHERE return_id = '" . (int)$return_id . "'");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "return_history` SET `return_id` = '" . (int)$return_id . "', return_status_id = '" . (int)$return_status_id . "', notify = '" . (int)$notify . "', comment = '" . $this->db->escape(strip_tags($comment)) . "', date_added = NOW()");
	}
	public function getReturnProducts($return_id) {
		$query=$this->db->query("SELECT  * FROM `" . DB_PREFIX . "return_products` r where return_id = '" . (int)$return_id . "'");
		return $query->rows;
	}

	public function getOrderProductsFilter($order_id, $filter) {

		$sql="SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'";
		if(isset($filter['filter_name']) && !empty($filter['filter_name'])) {
			$sql.="AND name LIKE '%" . $this->db->escape($filter['filter_name']) . "%'";
		}
		if(isset($filter['filter_model']) && !empty($filter['filter_model'])) {
			$sql.="AND model LIKE '%" . $this->db->escape($filter['filter_model']) . "%'";
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getReturnHistories($return_id, $start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT rh.date_added, rs.name AS status, rh.comment, rh.notify FROM " . DB_PREFIX . "return_history rh LEFT JOIN " . DB_PREFIX . "return_status rs ON rh.return_status_id = rs.return_status_id WHERE rh.return_id = '" . (int)$return_id . "' AND rs.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY rh.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalReturnHistories($return_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "return_history WHERE return_id = '" . (int)$return_id . "'");

		return $query->row['total'];
	}

	public function getTotalReturnHistoriesByReturnStatusId($return_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "return_history WHERE return_status_id = '" . (int)$return_status_id . "'");

		return $query->row['total'];
	}
}