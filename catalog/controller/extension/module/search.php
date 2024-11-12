<?php
class ControllerExtensionModuleSearch extends Controller {
	public function ajax() {
        $json = [];

        if (isset($this->request->get['keyword'])) {
            $keywords = mb_strtolower($this->request->get['keyword'], 'UTF-8');

            $parts = explode(' ', $keywords);

            $addP = '';
            $addC = '';

            foreach( $parts as $part ) {
                $_len = utf8_strlen($part);

                if ($_len >= 3 && $_len <= 7) {
                    $addP .= ' AND (LOWER(pd.name) LIKE "' . $this->db->escape($part) . '%" OR LOWER(pd.name) LIKE "% ' . $this->db->escape($part) . '%"';
                    $addC .= ' AND (LOWER(cd2.name) LIKE "' . $this->db->escape($part) . '%" OR LOWER(cd2.name) LIKE "% ' . $this->db->escape($part) . '%")';
                } else {
                    $addP .= ' AND (LOWER(pd.name) LIKE "%' . $this->db->escape($part) . '%"';
                    $addC .= ' AND LOWER(cd2.name) LIKE "%' . $this->db->escape($part) . '%"';
                }

                $addP .= ' OR LOWER(p.model) LIKE "%' . $this->db->escape($part) . '%"';
                $addP .= ')';
            }

            $addP = substr($addP, 4);

            if ($this->customer->isLogged()) {
                $customer_group_id = $this->customer->getGroupId();
            } else {
                $customer_group_id = $this->config->get('config_customer_group_id');
            }

            $this->NOW = date('Y-m-d H:i') . ':00';

            $sql  = " SELECT DISTINCT p.*, pd.name AS name,";
            $sql .= " (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < '" . $this->NOW . "') AND (ps.date_end = '0000-00-00' OR ps.date_end > '" . $this->NOW . "')) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special";
            $sql .= " FROM " . DB_PREFIX . "product p";
            $sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";
            $sql .= ' WHERE ' . $addP;
            $sql .= ' AND p.status = 1 ';
            $sql .= ' AND pd.language_id = ' . (int)$this->config->get('config_language_id');
            $sql .= ' GROUP BY p.product_id ';
            // $sql .= ' ORDER BY LOWER(pd.name) ASC';
            $sql .= ' ORDER BY p.date_available DESC, LOWER(pd.name) ASC';
            $sql .= ' LIMIT 3';

            $query = $this->db->query($sql);

            $json['products'] = [];

            if ($query->num_rows) {
                $json['text_all_search'] = html_entity_decode($this->language->get('text_all_search'), ENT_QUOTES, 'UTF-8');

                $this->load->model('tool/image');

                $json['text_go_to_category'] = $this->language->get('text_go_to_category');

                $image_w = 46;
                $image_h = 69;

                foreach ($query->rows as $result) {
                    if ($result['image']) {
                        $image = $this->model_tool_image->resize($result['image'], $image_w, $image_h, 'auto');
                    } else {
                        $image = $this->model_tool_image->resize('placeholder.png', $image_w, $image_h, 'auto');
                    }

                    if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                        $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                    } else {
                        $price = false;
                    }

                    if (!is_null($result['special']) && (float)$result['special'] >= 0) {
                        $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                    } else {
                        $special = false;
                    }

                    $json['products'][] = [
                        'name'      => html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'),
                        'thumb'     => $image,
                        'price'     => $price,
                        'special'   => $special,
                        'href'      => $this->url->link('product/product&product_id=' . $result['product_id'])
                    ];
                }
            }

            $sql  = "SELECT cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR ' <span class=\"caret\"></span> ') AS name, c1.parent_id FROM " . DB_PREFIX . "category_path cp";
            $sql .= " LEFT JOIN " . DB_PREFIX . "category c1 ON (cp.category_id = c1.category_id)";
            $sql .= " LEFT JOIN " . DB_PREFIX . "category c2 ON (cp.path_id = c2.category_id)";
            $sql .= " LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id)";
            $sql .= " LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id)";
            $sql .= " WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "'";
            $sql .= " AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";
            $sql .= " AND c1.`status` = 1 AND c2.`status` = 1";
            $sql .= $addC;
            $sql .= " GROUP BY cp.category_id";
            // $sql .= " ORDER BY c1.sort_order, c2.sort_order, LOWER(cd1.name) ASC, LOWER(cd2.name) ASC";
            $sql .= " ORDER BY c2.sort_order, c1.sort_order, LOWER(cd1.name) ASC, LOWER(cd2.name) ASC";
            $sql .= " LIMIT 5";

            $query = $this->db->query($sql);

            $json['categories'] = [];

            if ($query->num_rows) {
                $json['text_go_to_category'] = $this->language->get('text_go_to_category');

                foreach ($query->rows as $result) {
                    $json['categories'][] = [
                        'name' => $result['name'],
                        'href' => $this->url->link('product/category&path=' . $result['category_id'])
                    ];
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
	}

    public function getAllCategories($addC) {
        //$result = $this->cache->get('category.all.' . $this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'));

        //if (!$result || !is_array($result)) {


        $query = $this->db->query("SELECT c.category_id, c.parent_id, name FROM " . DB_PREFIX . "category c
                LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id)
                LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id)
                WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "'
                AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' " .  $addC . " ORDER BY c.parent_id, c.sort_order, cd.name");



            $categories = array();

            foreach ($query->rows as $row) {
                $categories[$row['parent_id']][$row['category_id']] = $row;
            }
            

            $result = $this->getCategories($categories);

            //$this->cache->set('category.all.' . $this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'), $result);
       //}

        return $result;
    }

    public function getCategories($data = array()) {
        $sql = "SELECT cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS name, c1.parent_id
        FROM " . DB_PREFIX . "category_path cp
        LEFT JOIN " . DB_PREFIX . "category c1 ON (cp.category_id = c1.category_id)
        LEFT JOIN " . DB_PREFIX . "category c2 ON (cp.path_id = c2.category_id)
        LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id)
        LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id)
        WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "'
        AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";
        $sql .= " AND c1.`status` = 1 AND c2.`status` = 1";
        $addC   = ' AND (LOWER(cd2.name) LIKE "фут%" OR LOWER(cd2.name) LIKE "% фут%")';
        $sql .= $addC;
        $sql .= " GROUP BY cp.category_id";
        $sql .= " ORDER BY c1.sort_order, c2.sort_order, LOWER(cd1.name) ASC, LOWER(cd2.name) ASC";

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
}
?>
