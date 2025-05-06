<?php
class ControllerMailReturn extends Controller {
	// model/account/return/addReturn/after
	public function index(&$route, &$args, &$output) {
		if (isset($args[0])) {
			$input_data = $args[0];
		} else {
			$input_data = [];
		}
		$return_id=$output;
		//$this->log->write($output);
		//$this->log->write($args);exit();
		// Load the language for any mails that might be required to be sent out
		$language = new Language($this->config->get('config_language'));
		$language->load($this->config->get('config_language'));
		$language->load('mail/return_add');

		if(!empty($input_data)) {
			$data['title'] = sprintf($language->get('text_subject'), $this->config->get('config_name'), $return_id);
			$data['text_greeting'] = $language->get('text_greeting');

				if ($this->request->server['HTTPS']) {
					$data['store_url'] = HTTPS_SERVER;
				} else {
					$data['store_url'] = HTTP_SERVER;
				}

			$data['return_id'] = $return_id;
			$data['logo'] = $data['store_url'] . 'image/' . $this->config->get('config_logo');
			$data['store_name'] = $this->config->get('config_name');
			$data['customer_id'] = $this->customer->getId();

			//$lang_mark=$this->config->get('config_langdata');
			//$prefix=isset($lang_mark['langmark_multi']['name'])?strtolower($lang_mark['langmark_multi']['name']).'/':'';

			//$data['link'] = $data['store_url'] .$prefix. 'index.php?route=account/return/info&order_id=' . $return_id;
			$data['link'] = $this->url->link('account/return/info','return_id=' . $return_id);

			$data['text_return_detail'] = $language->get('text_return_detail');
			$data['text_return_id'] = $language->get('text_return_id');
			$data['date_added']=date($this->language->get('date_format_short'));
			$data['text_date_added']=$language->get('text_date_added');

			$data['text_email']=$language->get('text_email');
			$data['text_telephone']=$language->get('text_telephone');
			$data['text_firstname']=$language->get('text_firstname');
			$data['text_lastname']=$language->get('text_lastname');

			$data['firstname']=$input_data['firstname'];
			$data['lastname']=$input_data['lastname'];
			$data['email']=$input_data['email'];
			$data['telephone']=$input_data['telephone'];

			$data['text_comment']=$language->get('text_comment');
			$data['text_receiver']=$language->get('text_receiver');
			$data['text_inn']=$language->get('text_inn');
			$data['text_iban']=$language->get('text_iban');

			$data['comment']=$input_data['comment'];
			$data['receiver']=$input_data['receiver'];
			$data['inn']=$input_data['inn'];
			$data['iban']=$input_data['iban'];

			$data['text_return_reason']=$language->get('text_return_reason');

			$this->load->model('localisation/return_reason');


			$data['return_reason'] = $this->model_localisation_return_reason->getReturnReasonName($input_data['return_reason_id']);

			$data['text_products'] = $language->get('text_products');
			$data['text_product'] = $language->get('text_product');
			$data['text_model'] = $language->get('text_model');
			$data['text_quantity'] = $language->get('text_quantity');

			$data['text_footer'] = $language->get('text_footer');

			$this->load->model('tool/upload');
			$this->load->model('checkout/order');

			// Products
			$data['products'] = array();
			$order_info= $this->model_checkout_order->getOrder($input_data['order_id']);
			$order_products = $this->model_checkout_order->getOrderProducts($input_data['order_id']);
			foreach ($order_products as $order_product) {
				if(isset($input_data['products'][$order_product['product_id']])){

					$option_data = array();

					$order_options = $this->model_checkout_order->getOrderOptions($input_data['order_id'], $order_product['order_product_id']);

					foreach ($order_options as $order_option) {
						if ($order_option['type'] != 'file') {
							$value = $order_option['value'];
						} else {
							$upload_info = $this->model_tool_upload->getUploadByCode($order_option['value']);

							if ($upload_info) {
								$value = $upload_info['name'];
							} else {
								$value = '';
							}
						}

						$option_data[] = array(
							'name'  => $order_option['name'],
							'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
						);
					}

					//#++ Image to Order
					$product_image = $this->db->query("SELECT `image` FROM `" . DB_PREFIX . "product` WHERE `product_id` = '" . (int)$order_product['product_id'] . "'")->row;

					$this->load->model('tool/image');

					if (!empty($product_image['image'])) {
						if (!is_file(DIR_IMAGE . $product_image['image'])) {
							$product_image['image'] = 'no_image.png';
						}

						$image = $this->model_tool_image->resize($product_image['image'], 47, 47);
					} else {
						$image = $this->model_tool_image->resize('no_image.png', 47, 47);
					}

					$data['products'][] = array(
						'name'     => $order_product['name'],
						'model'    => $order_product['model'],
						'image'    => $image,
						'option'   => $option_data,
						'quantity' => $input_data['products'][$order_product['product_id']]['quantity'],
						'price'    => $this->currency->format($order_product['price'] + ($this->config->get('config_tax') ? $order_product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
						//'total'    => $this->currency->format($order_product['total'] + ($this->config->get('config_tax') ? ($order_product['tax'] * $order_product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
					);
				}

			}
			$from = $this->config->get('config_email');
			$mail = new Mail($this->config->get('config_mail_engine'));
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($input_data['email']);
			$mail->setFrom($from);
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(html_entity_decode($data['title'] , ENT_QUOTES, 'UTF-8'));
			$mail->setHtml($this->load->view('mail/return_add', $data));
			$mail->send();


			// Send to additional alert emails
			$mail->setTo($this->config->get('config_email'));

			$mail->setHtml($this->load->view('mail/return_add_admin', $data));
			$mail->send();

			$emails = explode(',', $this->config->get('config_mail_alert_email'));

			foreach ($emails as $email) {
				$email = trim($email);
				if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$mail->setTo($email);
					$mail->send();
				}
			}

		}






	}



}
