<?php
class ControllerCheckoutQuickOrder extends Controller {
    public function index() {
    }

    public function modal() {
        $this->load->language('checkout/quick_order');
        $this->load->language('checkout/sms_validator');

        $data = [];

        if (isset($this->session->data['shipping_address']['iso_code_2'])) {
            $iso_code_2 = $this->session->data['shipping_address']['iso_code_2'];
        } else {
            $iso_code_2 = 'UA';
        }

        $rinvex = new rinvex\country;

        $country_data = $rinvex->getData($iso_code_2);

        if ($country_data) {
            $data['iso_code_2']             = $country_data['iso_code_2'];
            $data['calling_code']           = $country_data['calling_code'];
            $data['number_lengths_mask']    = $country_data['number_lengths_mask'];
            $data['flag']                   = $country_data['flag'];
        }


        /*
        $data['flag']           = $country_data['flag'];
        $data['calling_code']   = '+' . $country_data['calling_code'];

        $data['code_lengths'] = '';

        for ($i = 1; $i <= $country_data['code_lengths']; $i++) {
            $data['code_lengths'] .= '9';
        }

        $data['number_lengths'] = '';

        for ($i = 1; $i <= $country_data['number_lengths'] - $country_data['code_lengths']; $i++) {
            $data['number_lengths'] .= '9';
        }

        $this->load->model('localisation/country');
        $countries = $this->model_localisation_country->getCountries();

        foreach ($countries as $country) {
            $country_data = $rinvex->getCountryData($country['iso_code_2']);
        }

        $this->log->write(print_r($countries,1));
        */

        $this->load->model('localisation/country');

        $data['countries'] = [];

        $countries = $this->model_localisation_country->getCountries();

        foreach ($countries as $key => $country) {
            $country_data = $rinvex->getData($country['iso_code_2']);

            if (!empty($country_data['valid'])) {
                $data['countries'][$key]            = $country_data;
                $data['countries'][$key]['name']    = $country['name'];
            }
        }

        // Remarketing All in One Quick Order
        if (isset($this->request->get['product_id']) && $this->config->get('remarketing_status')) {
            $this->load->model('catalog/product');
            $product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);
            $this->load->model('tool/remarketing');
            $data['quick'] = json_encode($this->model_tool_remarketing->getQuickOrderOpen($product_info));
        }

        $this->response->setOutput($this->load->view('checkout/quick_order', $data));
    }

    public function confirm() {
        $this->load->language('checkout/quick_order');
        $this->load->language('checkout/sms_validator');

        $json = [];

        if ((utf8_strlen(trim($this->request->post['name'])) < 1) || (utf8_strlen(trim($this->request->post['name'])) > 32)) {
            $json['error']['name'] = $this->language->get('error_name');
        }

        if (!empty($this->request->get['iso_code'])) {
            $rinvex = new rinvex\country;

            $country_data = $rinvex->getData($this->request->get['iso_code'], $this->request->post['phone'], true);

            if ($country_data['valid']) {
                $telephone = $country_data['telephone'];

                if ($country_data['iso_code_2'] == 'ua') {
                    $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "sms_validator` WHERE `telephone` = '" . $this->db->escape($telephone) . "'");

                    if (!$query->num_rows) {
                        $json['error']['phone'] = $this->language->get('error_sms_please');
                    }
                }

            } else {
                $json['error']['phone'] = $this->language->get('error_phone');
            }

        } else {
            $json['error']['phone'] = $this->language->get('error_phone');
        }

        /* Не обязательный E-mail
        if (!empty($this->request->post['email'])) {
            if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
                $json['error']['email'] = $this->language->get('error_email');
            }
        } else {
            $this->request->post['email'] = 'empty' . time() . '@localhost.net';
        }
        */

        //# Обязательный E-mail
        if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
            $json['error']['email'] = $this->language->get('error_email');
        }

        if (!isset($json['error'])) {
            $order_data = [];

            $totals = [];
            $total = 0;

            $total_data = array(
                'totals' => &$totals,
                'total'  => &$total
            );

            $this->load->model('extension/total/sub_total');
            $this->model_extension_total_sub_total->getTotal($total_data);

            $order_data['totals'] = $totals;

            $order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
            $order_data['store_id'] = $this->config->get('config_store_id');
            $order_data['store_name'] = $this->config->get('config_name');

            if ($order_data['store_id']) {
                $order_data['store_url'] = $this->config->get('config_url');
            } else {
                if ($this->request->server['HTTPS']) {
                    $order_data['store_url'] = HTTPS_SERVER;
                } else {
                    $order_data['store_url'] = HTTP_SERVER;
                }
            }

            $order_data['customer_id'] = 0;
            $order_data['customer_group_id'] = 1;
            $order_data['firstname'] = $this->request->post['name'];
            $order_data['lastname'] = '';
            $order_data['email'] = $this->request->post['email'];
            $order_data['telephone'] = $this->request->post['phone'];
            $order_data['custom_field'] = [];

            $order_data['payment_firstname'] = $this->request->post['name'];
            $order_data['payment_lastname'] = '';
            $order_data['payment_company'] = '';
            $order_data['payment_address_1'] = '';
            $order_data['payment_address_2'] = '';
            $order_data['payment_city'] = '';
            $order_data['payment_postcode'] = '';
            $order_data['payment_zone'] = '';
            $order_data['payment_zone_id'] = '';
            $order_data['payment_country'] = '';
            $order_data['payment_country_id'] = '';
            $order_data['payment_address_format'] = '';
            $order_data['payment_custom_field'] = [];
            $order_data['payment_method'] = '';
            $order_data['payment_code'] = '';

            $order_data['shipping_firstname'] = $this->request->post['name'];
            $order_data['shipping_lastname'] = '';
            $order_data['shipping_company'] = '';
            $order_data['shipping_address_1'] = '';
            $order_data['shipping_address_2'] = '';
            $order_data['shipping_city'] = '';
            $order_data['shipping_postcode'] = '';
            $order_data['shipping_zone'] = '';
            $order_data['shipping_zone_id'] = '';
            $order_data['shipping_country'] = '';
            $order_data['shipping_country_id'] = '';
            $order_data['shipping_address_format'] = '';
            $order_data['shipping_custom_field'] = [];
            $order_data['shipping_method'] = '';
            $order_data['shipping_code'] = '';

            $order_data['products'] = [];

            foreach ($this->cart->getProducts() as $product) {
                $option_data = [];

                foreach ($product['option'] as $option) {
                    $option_data[] = [
                        'product_option_id' => $option['product_option_id'],
                        'product_option_value_id' => $option['product_option_value_id'],
                        'option_id' => $option['option_id'],
                        'option_value_id' => $option['option_value_id'],
                        'name' => $option['name'],
                        'value' => $option['value'],
                        'type' => $option['type']
                    ];
                }

                $order_data['products'][] = [
                    'product_id' => $product['product_id'],
                    'name' => $product['name'],
                    'model' => $product['model'],
                    'option' => $option_data,
                    'download' => $product['download'],
                    'quantity' => $product['quantity'],
                    'subtract' => $product['subtract'],
                    'price' => $product['price'],
                    'total' => $product['total'],
                    'tax' => $this->tax->getTax($product['price'], $product['tax_class_id']),
                    'reward' => $product['reward']
                ];
            }

            $order_data['vouchers'] = [];

            $order_data['comment'] = $this->request->post['comment'];
            $order_data['total'] = $total_data['total'];

            $order_data['affiliate_id'] = 0;
            $order_data['commission'] = 0;
            $order_data['marketing_id'] = 0;
            $order_data['tracking'] = '';

            $order_data['language_id'] = $this->config->get('config_language_id');
            $order_data['currency_id'] = $this->currency->getId($this->session->data['currency']);
            $order_data['currency_code'] = $this->session->data['currency'];
            $order_data['currency_value'] = $this->currency->getValue($this->session->data['currency']);
            $order_data['ip'] = $this->request->server['REMOTE_ADDR'];

            if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
                $order_data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
            } elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
                $order_data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
            } else {
                $order_data['forwarded_ip'] = '';
            }

            if (isset($this->request->server['HTTP_USER_AGENT'])) {
                $order_data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
            } else {
                $order_data['user_agent'] = '';
            }

            if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
                $order_data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
            } else {
                $order_data['accept_language'] = '';
            }

            $this->load->model('checkout/order');

            $this->session->data['order_id'] = $this->model_checkout_order->addOrder($order_data);

            $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('config_order_status_id'));

            if (isset($this->session->data['order_id'])) {

                // Remarketing All in One Quick Order
                if ($this->config->get('remarketing_status')) {
                    $this->load->model('tool/remarketing');
                    $json['remarketing'] = $this->model_tool_remarketing->getQuickOrderSuccess($this->session->data['order_id'], true);
                }

                $json['success_head'] = $this->language->get('text_success_head');
                $json['success_text'] = sprintf($this->language->get('text_success_text'), $this->session->data['order_id'], $this->url->link('information/contact'));
                $json['success_thank'] = $this->language->get('text_success_thank');

                $json['success'] = 1;

                $this->session->data['last_order_id'] = $this->session->data['order_id'];
                $this->cart->clear();

                unset($this->session->data['shipping_method']);
                unset($this->session->data['shipping_methods']);
                unset($this->session->data['payment_method']);
                unset($this->session->data['payment_methods']);
                unset($this->session->data['guest']);
                unset($this->session->data['comment']);
                unset($this->session->data['order_id']);
                unset($this->session->data['coupon']);
                unset($this->session->data['reward']);
                unset($this->session->data['voucher']);
                unset($this->session->data['vouchers']);
                unset($this->session->data['totals']);
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
