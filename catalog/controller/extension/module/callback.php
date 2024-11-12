<?php
class ControllerExtensionModuleCallback extends Controller {
	public function index() {
	}

    public function modal() {
        $this->load->language('extension/module/callback');

        $data = [];

        $data['suggest'] = false;

        if (isset($this->request->get['suggest'])) {
            $data['suggest'] = true;
        }

        $this->response->setOutput($this->load->view('extension/module/callback', $data));
    }

    public function confirm() {
        $this->load->language('extension/module/callback');

        $json = [];

        if ((utf8_strlen(trim($this->request->post['name'])) < 1) || (utf8_strlen(trim($this->request->post['name'])) > 32)) {
            $json['error']['name'] = $this->language->get('error_name');
        }

        if (isset($this->request->post['phone'])) {
            $phone = preg_replace(['~^\+38~', '~^38~', '~\(~', '~\)~', '~-~', '~x~', '~Х~', '~\ ~'], '', $this->request->post['phone']);

            if (strlen($phone) != 10) {
                $json['error']['phone'] = $this->language->get('error_phone');
            } else {
                $phone = '+38' . $phone;
            }
        }

        if (isset($this->request->post['email'])) {
            if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
                $json['error']['email'] = $this->language->get('error_email');
            }
        }

        if (isset($this->request->get['suggest']) && empty($this->request->post['comment'])) {
            $json['error']['comment'] = $this->language->get('error_comment_suggest');
        }

        if (!isset($json['error'])) {
            $mail = new Mail($this->config->get('config_mail_engine'));
            $mail->parameter = $this->config->get('config_mail_parameter');
            $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
            $mail->smtp_username = $this->config->get('config_mail_smtp_username');
            $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
            $mail->smtp_port = $this->config->get('config_mail_smtp_port');
            $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

            $mail->setTo($this->config->get('config_email'));
            $mail->setFrom($this->config->get('config_email'));

            if (isset($this->request->post['email'])) {
                $mail->setReplyTo($this->request->post['email']);
            } else {
                $mail->setReplyTo($this->config->get('config_email'));
            }

            $mail->setSender(html_entity_decode($this->request->post['name'], ENT_QUOTES, 'UTF-8'));

            if (!isset($this->request->get['suggest']) && isset($phone)) {
                $mail->setSubject(html_entity_decode($this->language->get('text_head') . ': ' . $this->request->post['name'] . ' (' . $phone . ')'), ENT_QUOTES, 'UTF-8');
            } else {
                $mail->setSubject(html_entity_decode($this->language->get('text_head_suggest') . ': ' . $this->request->post['name'] . ' (' . $this->request->post['email'] . ')'), ENT_QUOTES, 'UTF-8');
            }

            $text  = $this->language->get('text_form_name') . ': ' . html_entity_decode($this->request->post['name'], ENT_QUOTES, 'UTF-8') . PHP_EOL;

            if (isset($this->request->post['phone'])) {
                $text .= $this->language->get('text_form_phone') . ': ' . html_entity_decode($phone, ENT_QUOTES, 'UTF-8') . PHP_EOL;
            }

            if (isset($this->request->post['email'])) {
                $text .= 'E-mail: ' . $this->request->post['email'] . PHP_EOL;
            }

            $text .= $this->language->get('text_form_comment') . ': ' . html_entity_decode($this->request->post['comment'], ENT_QUOTES, 'UTF-8');

            if (isset($this->request->get['suggest']) && !empty($this->request->post['product_id'])) {
                $this->load->model('catalog/product');

                $product_info = $this->model_catalog_product->getProduct($this->request->post['product_id']);
                $text .= PHP_EOL;
                $text .= '--' . PHP_EOL;
                $text .= 'Товар: ' . html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8') .  PHP_EOL;

                if (!is_null($product_info['special']) && (float)$product_info['special'] >= 0) {
                    $text .= 'Цена: ' . $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']) .  PHP_EOL;
                } else {
                    $text .= 'Цена: ' . $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']) .  PHP_EOL;
                }

                $text .= 'Url: ' . $this->url->link('product/product', 'product_id=' . $product_info['product_id']) .  PHP_EOL;
            }

            $mail->setText($text);
            $mail->send();

            // Send to additional alert emails
            $emails = explode(',', $this->config->get('config_mail_alert_email'));

            foreach ($emails as $email) {
                $email = trim($email);
                if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $mail->setTo($email);
                    $mail->send();
                }
            }

            $json['success_head']       = $this->language->get('text_success_head');
            $json['success_text']       = $this->language->get('text_success_text');
            $json['success_thank']      = $this->language->get('text_success_thank');

            $json['success']            = 1;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
