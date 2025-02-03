<?php
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerAccountReturn extends Controller {
	private $error = array();

	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/return', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('account/return');

		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->setRobots('noindex,follow');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/return', $url, true)
		);

		$this->load->model('account/return');

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

        $this->load->model('catalog/product');
        $this->load->model('account/order');
        $this->load->model('tool/image');


		$data['returns'] = array();

		$return_total = $this->model_account_return->getTotalReturns();

		$results = $this->model_account_return->getReturns(($page - 1) * 10, 10);

		foreach ($results as $result) {

           // $order_products = $this->model_account_order->getOrderProducts($result['order_id']);
			$order_products = $this->model_account_return->getReturnProducts($result['return_id']);
            $image = '';
            $total = 0;
			$return_products=[];

            foreach($order_products as $product){

                    $product_info = $this->model_catalog_product->getProduct($product['product_id']);

                    if ($product_info['image']) {
                        $image = $this->model_tool_image->resize($product_info['image'], 100, 140, 'auto');
                    } else {
                        $image = $this->model_tool_image->resize('placeholder.png', 100, 140, 'auto');
                    }

                    $total += ((float)$product_info['price']) * $product['quantity'];
				$return_products[]=[
					'product_id' => $product['product_id'],
					'quantity' => $product['quantity'],
					'image' => $image,
					'name' => $product_info['name'],
					'price' => $product_info['price'],

				];

            }

			$data['returns'][] = array(
				'return_id'  => $result['return_id'],
				'order_id'   => $result['order_id'],
				'name'       => $result['firstname'] . ' ' . $result['lastname'],
                'image' => $image,
                'total' => $this->currency->format($total,$this->session->data['currency']),
				'status'     => $result['status'],
				'return_products' => $return_products,
                'date_added' => $this->language->date_current_lang($result['date_added'],$this->language->get('month')),
				'href'       => $this->url->link('account/return/info', 'return_id=' . $result['return_id'] . $url, true)
			);
		}

		$pagination = new Pagination();
		$pagination->total = $return_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit');
		$pagination->url = $this->url->link('account/return', 'page={page}', true);

        $data['pagination'] = $pagination->render_to_front();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($return_total) ? (($page - 1) * $this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit')) + 1 : 0, ((($page - 1) * $this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit')) > ($return_total - $this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit'))) ? $return_total : ((($page - 1) * $this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit')) + $this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit')), $return_total, ceil($return_total / $this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit')));

		$data['continue'] = $this->url->link('account/account', '', true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/return_list', $data));
	}

	public function info() {
		$this->load->language('account/return');

		if (isset($this->request->get['return_id'])) {
			$return_id = $this->request->get['return_id'];
		} else {
			$return_id = 0;
		}

		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/return/info', 'return_id=' . $return_id, true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->model('account/return');

		$return_info = $this->model_account_return->getReturn($return_id);

		if ($return_info) {
			$this->document->setTitle($this->language->get('text_return'));

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home', '', true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/account', '', true)
			);

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('account/return', $url, true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_return'),
				'href' => $this->url->link('account/return/info', 'return_id=' . $this->request->get['return_id'] . $url, true)
			);

			$data['return_id'] = $return_info['return_id'];
			$data['order_id'] = $return_info['order_id'];
			$data['status'] = $return_info['status'];
			$data['date_ordered'] = $this->language->date_current_lang($return_info['date_ordered'],$this->language->get('month'));
			$data['date_added'] = $this->language->date_current_lang($return_info['date_added'],$this->language->get('month'));
			$data['firstname'] = $return_info['firstname'];
			$data['lastname'] = $return_info['lastname'];
			$data['email'] = $return_info['email'];
			$data['telephone'] = $return_info['telephone'];

            $this->load->model('tool/image');
            $this->load->model('catalog/product');
			$data['return_products']=[];
			$return_products = $this->model_account_return->getReturnProducts($return_info['return_id']);
			foreach ($return_products as $product) {
                $product_info = $this->model_catalog_product->getProduct($product['product_id']);

                if ($product_info['image']) {
                    $image = $this->model_tool_image->resize($product_info['image'], 100, 140, 'auto');
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', 100, 140, 'auto');
                }
				$data['return_products'][]=[
					'product_id' => $product['product_id'],
					'quantity' => $product['quantity'],
                    'image'=> $image,
					'name' => $product['name'],
					'model' => $product['model'],
				];
			}

			//$data['product'] = $return_info['product'];
			//$data['model'] = $return_info['model'];
			//$data['quantity'] = $return_info['quantity'];
			$data['reason'] = $return_info['reason'];
			$data['opened'] = $return_info['opened'] ? $this->language->get('text_yes') : $this->language->get('text_no');
			$data['comment'] = nl2br($return_info['comment']);
			$data['action'] = $return_info['action'];

			$data['histories'] = array();

			$results = $this->model_account_return->getReturnHistories($this->request->get['return_id']);

			foreach ($results as $result) {
				$data['histories'][] = array(
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'status'     => $result['status'],
					'comment'    => nl2br($result['comment'])
				);
			}

			$data['continue'] = $this->url->link('account/return', $url, true);

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('account/return_info', $data));
		} else {
			$this->document->setTitle($this->language->get('text_return'));

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/account', '', true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('account/return', '', true)
			);

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_return'),
				'href' => $this->url->link('account/return/info', 'return_id=' . $return_id . $url, true)
			);

			$data['continue'] = $this->url->link('account/return', '', true);

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}

	public function add() {
		$this->load->language('account/return');

		$this->load->model('account/return');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_account_return->addReturn($this->request->post);

			$this->response->redirect($this->url->link('account/return/success', '', true));
		}

		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->setRobots('noindex,follow');
		/*$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment.min.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment-with-locales.min.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');*/

        $this->document->addScript('catalog/view/javascript/opc/select2.min.js');
        $this->document->addStyle('catalog/view/javascript/opc/select2.min.css');

        $this->document->addScript('catalog/view/javascript/opc/opc.js');
        $this->document->addStyle('catalog/view/javascript/opc/style.css');

        $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment.min.js');
        $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment-with-locales.min.js');
        $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
        $this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

        $data['opc_mask'] = $this->config->get('opc_mask');

        if(!empty($data['opc_mask'])){
            $this->document->addScript('catalog/view/javascript/opc/maskedinput.js');
        }

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/return/add', '', true)
		);

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['order_id'])) {
			$data['error_order_id'] = $this->error['order_id'];
		} else {
			$data['error_order_id'] = '';
		}

		if (isset($this->error['firstname'])) {
			$data['error_firstname'] = $this->error['firstname'];
		} else {
			$data['error_firstname'] = '';
		}

		if (isset($this->error['lastname'])) {
			$data['error_lastname'] = $this->error['lastname'];
		} else {
			$data['error_lastname'] = '';
		}

		if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
		} else {
			$data['error_email'] = '';
		}

		if (isset($this->error['telephone'])) {
			$data['error_telephone'] = $this->error['telephone'];
		} else {
			$data['error_telephone'] = '';
		}

		if (isset($this->error['product'])) {
			$data['error_product'] = $this->error['product'];
		} else {
			$data['error_product'] = '';
		}

		if (isset($this->error['model'])) {
			$data['error_model'] = $this->error['model'];
		} else {
			$data['error_model'] = '';
		}

		if (isset($this->error['reason'])) {
			$data['error_reason'] = $this->error['reason'];
		} else {
			$data['error_reason'] = '';
		}

		if (isset($this->error['receiver'])) {
			$data['error_receiver'] = $this->error['receiver'];
		} else {
			$data['error_receiver'] = '';
		}

		if (isset($this->error['inn'])) {
			$data['error_inn'] = $this->error['inn'];
		} else {
			$data['error_inn'] = '';
		}

		if (isset($this->error['iban'])) {
			$data['error_iban'] = $this->error['iban'];
		} else {
			$data['error_iban'] = '';
		}

		$data['action'] = $this->url->link('account/return/add', '', true);

		$this->load->model('account/order');

		if (isset($this->request->get['order_id'])) {
			$order_info = $this->model_account_order->getOrder($this->request->get['order_id']);
		}

		$this->load->model('catalog/product');

		$product_ids=[];

		if (isset($this->request->get['products_id'])) {
			$product_ids[$this->request->get['products_id']]=[];
			//$product_info = $this->model_catalog_product->getProduct($this->request->get['products_id']);
		}


		if (isset($this->request->post['order_id'])) {
			$data['order_id'] = $this->request->post['order_id'];
		} elseif (!empty($order_info)) {
			$data['order_id'] = $order_info['order_id'];
		} else {
			$data['order_id'] = '';
		}

		if (isset($this->request->post['products'])) {
			$data['products'] = $this->request->post['products'];
		} elseif (!empty($product_ids)) {
			$data['products']=$product_ids;
		} else {
			$data['products'] = [];
		}

		if (isset($this->request->post['date_ordered'])) {
			$data['date_ordered'] = $this->request->post['date_ordered'];
		} elseif (!empty($order_info)) {
			$data['date_ordered'] = date('Y-m-d', strtotime($order_info['date_added']));
		} else {
			$data['date_ordered'] = '';
		}

		if (isset($this->request->post['firstname'])) {
			$data['firstname'] = $this->request->post['firstname'];
		} elseif (!empty($order_info)) {
			$data['firstname'] = $order_info['firstname'];
		} else {
			$data['firstname'] = $this->customer->getFirstName();
		}

		if (isset($this->request->post['lastname'])) {
			$data['lastname'] = $this->request->post['lastname'];
		} elseif (!empty($order_info)) {
			$data['lastname'] = $order_info['lastname'];
		} else {
			$data['lastname'] = $this->customer->getLastName();
		}

		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} elseif (!empty($order_info)) {
			$data['email'] = $order_info['email'];
		} else {
			$data['email'] = $this->customer->getEmail();
		}

		if (isset($this->request->post['telephone'])) {
			$data['telephone'] = $this->request->post['telephone'];
		} elseif (!empty($order_info)) {
			$data['telephone'] = $order_info['telephone'];
		} else {
			$data['telephone'] = $this->customer->getTelephone();
		}

		/*if (isset($this->request->post['products'])) {
			$data['products'] = $this->request->post['products'];
		} elseif (!empty($product_info)) {
			$data['products'] = $product_info['name'];
		} else {
			$data['products'] = [];
		}*/



		foreach($data['products'] as $product_id=>$post_data) {
			$product_info = $this->model_catalog_product->getProduct($product_id);
			$data['products'][$product_id]=[
				'product_id' => $product_info['product_id'],
				'name'       => $product_info['name'],
				'model'      => $product_info['model'],
				'quantity'   => ((isset($post_data['quantity']))?$post_data['quantity']:1),
			];
		}

		/*if (isset($this->request->post['model'])) {
			$data['model'] = $this->request->post['model'];
		} elseif (!empty($product_info)) {
			$data['model'] = $product_info['model'];
		} else {
			$data['model'] = '';
		}

		if (isset($this->request->post['quantity'])) {
			$data['quantity'] = $this->request->post['quantity'];
		} else {
			$data['quantity'] = 1;
		}*/

		if (isset($this->request->post['opened'])) {
			$data['opened'] = $this->request->post['opened'];
		} else {
			$data['opened'] = false;
		}

		if (isset($this->request->post['return_reason_id'])) {
			$data['return_reason_id'] = $this->request->post['return_reason_id'];
		} else {
			$data['return_reason_id'] = '';
		}

		if (isset($this->request->post['receiver'])) {
			$data['receiver'] = $this->request->post['receiver'];
		} else {
			$data['receiver'] = '';
		}
		if (isset($this->request->post['inn'])) {
			$data['inn'] = $this->request->post['inn'];
		} else {
			$data['inn'] = '';
		}
		if (isset($this->request->post['iban'])) {
			$data['iban'] = $this->request->post['iban'];
		} else {
			$data['iban'] = '';
		}

		$this->load->model('localisation/return_reason');

		$data['return_reasons'] = $this->model_localisation_return_reason->getReturnReasons();

		if (isset($this->request->post['comment'])) {
			$data['comment'] = $this->request->post['comment'];
		} else {
			$data['comment'] = '';
		}

		// Captcha
		if ($this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('return', (array)$this->config->get('config_captcha_page'))) {
			$data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'), $this->error);
		} else {
			$data['captcha'] = '';
		}

		if ($this->config->get('config_return_id')) {
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_return_id'));

			if ($information_info) {
				$data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_return_id'), true), $information_info['title']);
			} else {
				$data['text_agree'] = '';
			}
		} else {
			$data['text_agree'] = '';
		}

		if (isset($this->request->post['agree'])) {
			$data['agree'] = $this->request->post['agree'];
		} else {
			$data['agree'] = false;
		}
		if($this->customer->isLogged()) {
			$data['back'] = $this->url->link('account/account', '', true);

		}else{
			$data['back'] = $this->url->link('common/home', '', true);

		}

		$iso_code_2 = 'UA';
		$this->load->language('checkout/sms_validator');

		$rinvex = new rinvex\country;

		$country_data = $rinvex->getData($iso_code_2);

		$data['iso_code_2']             = $country_data['iso_code_2'];
		$data['calling_code']           = $country_data['calling_code'];
		$data['number_lengths_mask']    = $country_data['number_lengths_mask'];
		$data['flag']                   = $country_data['flag'];

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/return_form', $data));
	}

	protected function validate() {
		if (!$this->request->post['order_id']) {
			$this->error['order_id'] = $this->language->get('error_order_id');
		}

		if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
			$this->error['firstname'] = $this->language->get('error_firstname');
		}
		if ((utf8_strlen(trim($this->request->post['receiver'])) < 1) || (utf8_strlen(trim($this->request->post['receiver'])) > 32)) {
			$this->error['receiver'] = $this->language->get('error_receiver');
		}
		if ((utf8_strlen(trim($this->request->post['inn'])) < 10) || (utf8_strlen(trim($this->request->post['inn'])) > 12)) {
			$this->error['inn'] = $this->language->get('error_inn');
		}

		if (!$this->validateIban($this->request->post['iban'])) {
			$this->error['iban'] = $this->language->get('error_iban');
			//$json['error']['iban'] = 'Неправильний формат IBAN! Використовуйте формат: UA XX XXXXXX XXXXX XXXX XXXX XXXX XX';
		}

		if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
			$this->error['lastname'] = $this->language->get('error_lastname');
		}

		if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
			$this->error['email'] = $this->language->get('error_email');
		}

		if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
			$this->error['telephone'] = $this->language->get('error_telephone');
		}
		$this->load->language('checkout/sms_validator');
		$iso_code_2 = 'UA';
		$rinvex = new rinvex\country;

		$country_data = $rinvex->getData($iso_code_2, $this->request->post['telephone'], true);

		if ($country_data['valid']) {
			$telephone = $country_data['telephone'];

			if ($country_data['iso_code_2'] == 'ua') {
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "sms_validator` WHERE `telephone` = '" . $this->db->escape($telephone) . "'");

				if (!$query->num_rows) {
					$this->error['telephone'] = $this->language->get('error_sms_please');
				}
			}

		} else {
			$this->error['telephone'] =  $this->language->get('error_telephone');
		}

		if (empty($this->request->post['products'])) {
			$this->error['product'] = $this->language->get('error_product');
		}


		if (empty($this->request->post['return_reason_id'])) {
			$this->error['reason'] = $this->language->get('error_reason');
		}

		if ($this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('return', (array)$this->config->get('config_captcha_page'))) {
			$captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');

			if ($captcha) {
				$this->error['captcha'] = $captcha;
			}
		}

		if ($this->config->get('config_return_id')) {
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_return_id'));

			if ($information_info && !isset($this->request->post['agree'])) {
				$this->error['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
			}
		}

		return !$this->error;
	}

	private function validateIban($iban) {

		$iban = str_replace(' ', '', $iban);

		$pattern = '/^UA\d{2}\d{6}\d{19}$/';

		if (!preg_match($pattern, $iban)) {
			return false;
		}

		// Перевірка контрольних цифр IBAN
		if (!$this->validateIbanChecksum($iban)) {
			return false;
		}

		return true;
	}

		// Перевірка контрольних цифр IBAN
		private function validateIbanChecksum($iban) {
			// Переміщуємо перші чотири символи в кінець
			$iban = substr($iban, 4) . substr($iban, 0, 4);

			// Замінюємо літери на їх числові значення (A=10, B=11, ..., Z=35)
			$iban = preg_replace_callback('/[A-Z]/', function ($match) {
				return ord($match[0]) - 55;
			}, $iban);

			// Рахуємо модуль 97
			$mod = intval(substr($iban, 0, 1));
			for ($i = 1, $len = strlen($iban); $i < $len; $i++) {
				$mod = ($mod * 10 + intval($iban[$i])) % 97;
			}

			return $mod === 1; // Валідний, якщо залишок дорівнює 1
		}


	public function success() {
		$this->load->language('account/return');

		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->setRobots('noindex,follow');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/return', '', true)
		);

		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('common/success', $data));
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['order_id']) && (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model']))) {
			$this->load->model('checkout/order');


			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			if (isset($this->request->get['filter_model'])) {
				$filter_model = $this->request->get['filter_model'];
			} else {
				$filter_model = '';
			}

			if (isset($this->request->get['limit'])) {
				$limit = (int)$this->request->get['limit'];
			} else {
				$limit = $this->config->get('config_limit_autocomplete');
			}

			if (isset($this->request->get['filter_color'])) {
				$filter_color = $this->request->get['filter_name'];
			} else {
				$filter_color = '';
			}

			$filter_data = array(
				'order_id' => $this->request->get['order_id'],
				'filter_name'  => $filter_name,
				'filter_model' => $filter_model,
				'filter_color' => $filter_color,
				'start'        => 0,
				'limit'        => $limit
			);

			if (isset($this->request->get['filter_kit'])) {
				$filter_data['filter_kit'] = $this->request->get['filter_name'];
				$filter_data['filter_name'] = '';
			}

			$results = $this->model_checkout_order->getOrderProductsFilter($this->request->get['order_id'], $filter_data);

			foreach ($results as $result) {

				$json[] = array(
					'product_id' => $result['product_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'model'      => $result['model'],

				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
