<?php
class ControllerProductMSStock extends Controller {
    private $api_url = 'https://api.moysklad.ru/api/remap/1.2/';
    private $login = 'julia@glazursales1';
    private $password = 'katya2023';

    public function stockByStore($product_id) {
        $data = [];

        $config_language_id = $this->config->get('config_language_id');

        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "language WHERE language_id = '" . (int)$config_language_id . "'");

        $language_code = $query->row['code'];

        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ms_store_custom` ORDER BY `sort_order` ASC");

        foreach ($query->rows as $row) {
            if (isset($row[$language_code])) {
                $store = explode('|', $row[$language_code]);

                $data[$row['store_id']] = [
                    'store_address'     => isset($store[0]) ? $store[0] : '',
                    'store_work'        => isset($store[1]) ? $store[1] : ''
                ];
            }
        }

        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ms_variant` `msv` LEFT JOIN `" . DB_PREFIX . "product_option_value` `pov` ON (`msv`.`product_option_value_id` = `pov`.`product_option_value_id`) LEFT JOIN `" . DB_PREFIX . "option_value` `ov` ON (`pov`.`option_value_id` = `ov`.`option_value_id`) LEFT JOIN `" . DB_PREFIX . "option_value_description` `ovd` ON (`pov`.`option_value_id` = `ovd`.`option_value_id`) WHERE `msv`.`product_id` = '" . (int)$product_id . "' AND `ovd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' ORDER BY `ov`.`sort_order` ASC");

        if ($query->num_rows) {
            foreach ($query->rows as $row) {
                $curl_url = $this->api_url . 'report/stock/bystore?filter=variant=https://api.moysklad.ru/api/remap/1.2/entity/variant/' . $row['var_id'];

                $results = $this->ms_curl($curl_url);

                if (!empty($results['rows'][0]['stockByStore']) && is_array($results['rows'][0]['stockByStore'])) {
                    foreach ($results['rows'][0]['stockByStore'] as $result) {
                        $info = pathinfo($result['meta']['href']);

                        if (isset($data[$info['filename']])) {
                            $option_quantity = (int)$result['stock'] - (int)$result['reserve'];

                            if ($option_quantity) {
                                $data[$info['filename']]['values'][] = [
                                    'product_option_value_id' => $row['product_option_value_id'],
                                    'name' => $row['name'],
                                    'quantity' => $option_quantity
                                ];
                            }

                            if (!isset($data[$info['filename']]['quantity'])) {
                                $data[$info['filename']]['quantity'] = (int)$result['stock'];
                            } else {
                                $data[$info['filename']]['quantity'] += (int)$result['stock'];
                            }

                            if ($result['reserve']) {
                                $data[$info['filename']]['quantity'] -= (int)$result['reserve'];
                            }
                        }
                    }
                }
            }

        } else {
            $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ms_product` WHERE `product_id` = '" . (int)$product_id . "'");

            if ($query->num_rows) {
                $curl_url = $this->api_url . 'report/stock/bystore?filter=product=https://api.moysklad.ru/api/remap/1.2/entity/product/' . $query->row['ms_id'];

                $results = $this->ms_curl($curl_url);

                if (!empty($results['rows'][0]['stockByStore']) && is_array($results['rows'][0]['stockByStore'])) {
                    foreach ($results['rows'][0]['stockByStore'] as $result) {
                        $info = pathinfo($result['meta']['href']);

                        $quantity = (int)$result['stock'] - (int)$result['reserve'];

                        if ($quantity && isset($data[$info['filename']])) {
                            $data[$info['filename']]['quantity'] = $quantity;
                        }
                    }
                }
            }
        }

        return $data;
    }

    public function ms_curl($curl_url) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $curl_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_USERPWD, $this->login . ':' . $this->password);

        $headers[] = 'Accept-Encoding: gzip';
        $headers[] = 'Lognex-Pretty-Print-Json: true';

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $results = curl_exec($ch);

        if ($errors = $this->isJson($results)) {
            $this->log->write($errors);
        } else {
            $results = json_decode(gzdecode($results), true);
        }

        curl_close($ch);

        return $results;
    }

    private function isJson($string) {
        if (!empty($string) && !is_array($string)) {
            if ($string == '{}') {
                return [];
            }

            $res = json_decode($string, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                return $res;
            }
        }

        return false;
    }

    public function modal() {
        $data = [];
        if ($this->request->server['REQUEST_METHOD'] == 'GET' && !empty($this->request->get['p_id'])) {
            $this->load->language('product/ms_stock');

            if (!empty($this->request->get['pov_id'])) {
                $data['product_option_value_id'] = $this->request->get['p_id'];
            } else {
                $data['product_option_value_id'] = 0;
            }

            $data['stockByStore'] = $this->stockByStore($this->request->get['p_id']);
        }


        $this->response->setOutput($this->load->view('product/ms_stock', $data));
    }
}
