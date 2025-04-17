<?php
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerAccountLogin extends Controller {
	private $error = array();

	public function index() {
		$this->load->model('account/customer');

		// Login override for admin users
		if (!empty($this->request->get['token'])) {
			$this->customer->logout();
			$this->cart->clear();

			unset($this->session->data['order_id']);
			unset($this->session->data['payment_address']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['shipping_address']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['comment']);
			unset($this->session->data['coupon']);
			unset($this->session->data['reward']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);

			$customer_info = $this->model_account_customer->getCustomerByToken($this->request->get['token']);

			if ($customer_info && $this->customer->login($customer_info['email'], '', true)) {
				// Default Addresses
				$this->load->model('account/address');

				if ($this->config->get('config_tax_customer') == 'payment') {
					$this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
				}

				if ($this->config->get('config_tax_customer') == 'shipping') {
					$this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
				}

				$this->response->redirect($this->url->link('account/account', '', true));
			}
		}

		if ($this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/account', '', true));
		}else{
            $this->response->redirect($this->url->link('common/home', '', true));
        }

		$this->load->language('account/login');

		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->setRobots('noindex,follow');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			// Unset guest
			unset($this->session->data['guest']);

			// Default Shipping Address
			$this->load->model('account/address');

			if ($this->config->get('config_tax_customer') == 'payment') {
				$this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
			}

			if ($this->config->get('config_tax_customer') == 'shipping') {
				$this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
			}

			// Wishlist
			if (isset($this->session->data['wishlist']) && is_array($this->session->data['wishlist'])) {
				$this->load->model('account/wishlist');

				foreach ($this->session->data['wishlist'] as $key => $product_id) {
					$this->model_account_wishlist->addWishlist($product_id);

					unset($this->session->data['wishlist'][$key]);
				}
			}

			// Added strpos check to pass McAfee PCI compliance test (http://forum.opencart.com/viewtopic.php?f=10&t=12043&p=151494#p151295)
			if (isset($this->request->post['redirect']) && $this->request->post['redirect'] != $this->url->link('account/logout', '', true) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)) {
				$this->response->redirect(str_replace('&amp;', '&', $this->request->post['redirect']));
			} else {
				$this->response->redirect($this->url->link('account/account', '', true));
			}
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
			'text' => $this->language->get('text_login'),
			'href' => $this->url->link('account/login', '', true)
		);

		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} elseif (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['action'] = $this->url->link('account/login', '', true);
		$data['register'] = $this->url->link('account/register', '', true);
		$data['forgotten'] = $this->url->link('account/forgotten', '', true);

		// Added strpos check to pass McAfee PCI compliance test (http://forum.opencart.com/viewtopic.php?f=10&t=12043&p=151494#p151295)
		if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)) {
			$data['redirect'] = $this->request->post['redirect'];
		} elseif (isset($this->session->data['redirect'])) {
			$data['redirect'] = $this->session->data['redirect'];

			unset($this->session->data['redirect']);
		} else {
			$data['redirect'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} else {
			$data['email'] = '';
		}

		if (isset($this->request->post['password'])) {
			$data['password'] = $this->request->post['password'];
		} else {
			$data['password'] = '';
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/login', $data));
	}

	public function mini() {
		$this->load->model('account/customer');



		$this->load->language('account/login');


		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			// Unset guest
			unset($this->session->data['guest']);

			// Default Shipping Address
			$this->load->model('account/address');

			if ($this->config->get('config_tax_customer') == 'payment') {
				$this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
			}

			if ($this->config->get('config_tax_customer') == 'shipping') {
				$this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
			}

			// Wishlist
			if (isset($this->session->data['wishlist']) && is_array($this->session->data['wishlist'])) {
				$this->load->model('account/wishlist');

				foreach ($this->session->data['wishlist'] as $key => $product_id) {
					$this->model_account_wishlist->addWishlist($product_id);

					unset($this->session->data['wishlist'][$key]);
				}
			}

			if ($this->customer->isLogged()) {
				$json['success'] = $this->load->controller('common/header');

			}
			// Added strpos check to pass McAfee PCI compliance test (http://forum.opencart.com/viewtopic.php?f=10&t=12043&p=151494#p151295)
		//	if (isset($this->request->post['redirect']) && $this->request->post['redirect'] != $this->url->link('account/logout', '', true) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)) {
		//		$this->response->redirect(str_replace('&amp;', '&', $this->request->post['redirect']));
		//	} else {
		//		$this->response->redirect($this->url->link('account/account', '', true));
		//	}
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
			'text' => $this->language->get('text_login'),
			'href' => $this->url->link('account/login', '', true)
		);

		$data['error_warning']=$this->language->get('error_telephone');

		$data['action'] = $this->url->link('account/login/mini', '', true);
		$data['register'] = $this->url->link('account/register/mini', '', true);
		$data['forgotten'] = $this->url->link('account/forgotten/mini', '', true);

		// Added strpos check to pass McAfee PCI compliance test (http://forum.opencart.com/viewtopic.php?f=10&t=12043&p=151494#p151295)
		if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)) {
			$data['redirect'] = $this->request->post['redirect'];
		} elseif (isset($this->session->data['redirect'])) {
			$data['redirect'] = $this->session->data['redirect'];

			unset($this->session->data['redirect']);
		} else {
			$data['redirect'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} else {
			$data['email'] = '';
		}

		if (isset($this->request->post['telephone'])) {
			$data['telephone'] = $this->request->post['telephone'];
		} else {
			$data['telephone'] = '';
		}

		//if (isset($this->request->post['password'])) {
		//	$data['password'] = $this->request->post['password'];
		//} else {
			$data['password'] = '';
		//}

		if(!empty($this->config->get('config_country_id'))){
			$this->load->model('localisation/country');
			$data['countries'] = $this->model_localisation_country->getCountries();
			$country_info = $this->model_localisation_country->getCountry($this->config->get('config_country_id'));
			$data['iso_code_2']=$country_info ? $country_info['iso_code_2'] : '';

			//$shipping_address['country'] = $this->session->data['shipping_address']['country'] = $country_info ? $country_info['name'] : '';

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
		$json['template']=$this->load->view('account/login_mini', $data);
		$this->response->setOutput(json_encode($json));
	}

	public function validateNumber() {
		$step = 1;

		if (isset($this->request->get['tel'])) {
			$this->session->data['customer_login']['telephone'] = $this->request->get['tel'];

			$telephone = $this->clearTelephoneMask($this->request->get['tel']);
//var_dump($telephone);
			if (utf8_strlen($telephone) == 12 && substr($telephone, 0, 3) == '380' && substr($telephone, 3, 1) > 0) {
				$step = 3;
			} else {
				$step = 1;
			}

			/*if ($step == 2) {
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "sms_validator` WHERE `telephone` = '" . $this->db->escape($telephone) . "'");

				if (!$query->num_rows) {
					$step = 2;
				} else {
					$step = 3;
				}
			}*/
		}

		$this->response->setOutput($step);
	}

	public function clearTelephoneMask($telephone) {
		return preg_replace(['/\+/', '/-/', '/_/', '/\ /', '/\(/', '/\)/', '/X/', '/x/'], '', trim($telephone));
	}

	public function sendSMS() {
		$json = [];
		$this->load->model('account/customer');
		$this->load->language('account/login');
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$code =  rand(1000, 9999);

			$telephone = $this->clearTelephoneMask($this->request->post['telephone']);

			//check customer
			$customer_info = $this->model_account_customer->getCustomerByTelephone($telephone);

			if(!$customer_info){
				//customer not found -  make
				$this->request->post['telephone']=$this->clearTelephoneMask($this->request->post['telephone']);
				$this->request->post['firstname']='';
				$this->request->post['lastname']='';
				$this->request->post['email']='';
				$this->request->post['password']='';
				$customer_id = $this->model_account_customer->addCustomer($this->request->post);
				$customer_info=$this->model_account_customer->getCustomer($customer_id);
			}




			$this->log->write('Send SMS to: ' . $telephone);
			$this->log->write('Code: ' . $code);

			$client = new SoapClient('http://turbosms.in.ua/api/wsdl.html');

			$auth = [
				'login' => 'BlackinWhite',
				'password' => '7460079'
			];

			$client->Auth($auth);

			$sms = [
				'sender'        => 'AVIATOR',
				'destination'   => '+' . $telephone,
				'text'          => sprintf($this->language->get('code'),$code,$code,$code)
			];

			$this->log->write('SMS_Array:');
			$this->log->write($sms);

			$result = $client->SendSMS($sms);

			$this->log->write('SMS result:');
			$this->log->write($result);

			if (!empty($result->SendSMSResult->ResultArray[1])) {
				$this->log->write('SMS SUCCESS!');
				$json['success']    = 1;
			} else {
				$this->log->write('SMS ERROR!');
				$json['error']      = 1;
			}

			$json['help']=$this->language->get('text_enter_code').$this->request->post['telephone'];
			//$json['sms_code'] = $code;
		}

		if(isset($json['success'])){
			//write temp sms to customer
			$this->load->model('account/customer');
			$this->model_account_customer->addSms($telephone,$code,time()+120);
			$this->session->data['telephone']=$telephone;

		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function page(){
		$this->load->model('account/customer');
		$this->load->language('account/login');


		$data['error_warning']=$this->language->get('error_telephone');

		if (isset($this->request->post['telephone'])) {
			$data['telephone'] = $this->request->post['telephone'];
		} else {
			$data['telephone'] = '';
		}

		//if (isset($this->request->post['password'])) {
		//	$data['password'] = $this->request->post['password'];
		//} else {
		$data['password'] = '';
		//}

		if(!empty($this->config->get('config_country_id'))){
			$this->load->model('localisation/country');
			$data['countries'] = $this->model_localisation_country->getCountries();
			$country_info = $this->model_localisation_country->getCountry($this->config->get('config_country_id'));
			$data['iso_code_2']=$country_info ? $country_info['iso_code_2'] : '';

			//$shipping_address['country'] = $this->session->data['shipping_address']['country'] = $country_info ? $country_info['name'] : '';

		}
		$iso_code_2 = 'UA';
		$this->load->language('checkout/sms_validator');

		$rinvex = new rinvex\country;

		$country_data = $rinvex->getData($iso_code_2);

		$data['iso_code_2']             = $country_data['iso_code_2'];
		$data['calling_code']           = $country_data['calling_code'];
		$data['number_lengths_mask']    = $country_data['number_lengths_mask'];
		$data['flag']                   = $country_data['flag'];

		$data['action']=$this->url->link('account/login/sendSMS', '', true);
		$data['action2']=$this->url->link('account/login/code', '', true);

		//$data['register'] = $this->url->link('account/register/mini', '', true);
		//$data['login'] = $this->url->link('account/login/mini', '', true);
		//$data['guest_session_link']=$this->url->link('checkout/onepcheckout','guest=1');
		return $this->load->view('checkout/login', $data);
	}

	public function code(){
		$this->load->model('account/customer');
		$this->load->language('account/login');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			// Unset guest
			unset($this->session->data['guest']);

			// Default Shipping Address
			$this->load->model('account/address');

			if ($this->config->get('config_tax_customer') == 'payment') {
				$this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
			}

			if ($this->config->get('config_tax_customer') == 'shipping') {
				$this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
			}

			// Wishlist
			if (isset($this->session->data['wishlist']) && is_array($this->session->data['wishlist'])) {
				$this->load->model('account/wishlist');

				foreach ($this->session->data['wishlist'] as $key => $product_id) {
					$this->model_account_wishlist->addWishlist($product_id);

					unset($this->session->data['wishlist'][$key]);
				}
			}

		}

		if(isset($this->error['warning'])&&$this->error['warning']){
			$json['error'] = $this->error['warning'];
		}else{
			$json['success'] = 1;
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));

	}
	protected function validate() {
		// Check how many login attempts have been made.
		$telephone=$this->clearTelephoneMask($this->session->data['telephone']);
		$login_info = $this->model_account_customer->getLoginAttempts($this->session->data['telephone']);

		if ($login_info && ($login_info['total'] >= $this->config->get('config_login_attempts')) && strtotime('-1 hour') < strtotime($login_info['date_modified'])) {
			$this->error['warning'] = $this->language->get('error_attempts');
		}

		// Check if customer has been approved.
		//$customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);
		$customer_info = $this->model_account_customer->getCustomerByTelephone($telephone);


		if($customer_info) {
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "sms_validator` WHERE `telephone` = '" . $this->db->escape($telephone) . "'");

			if (!$query->num_rows) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "sms_validator` SET `telephone` = '" . $this->db->escape($telephone) . "', status = '1', date_added = NOW()");
			}
		}

		if ($customer_info && !$customer_info['status']) {
			$this->error['warning'] = $this->language->get('error_approved');
		}

		if (!$this->error) {
			//if (!$this->customer->login($this->request->post['email'], $this->request->post['password'])) {
			if (!$this->customer->loginByTel($telephone, $this->request->post['sms_password'])) {
				$this->error['warning'] = $this->language->get('error_login_tel');

				$this->model_account_customer->addLoginAttempt($telephone);
			} else {
				$this->model_account_customer->deleteLoginAttempts($telephone);
			}
		}

		return !$this->error;
	}
}
