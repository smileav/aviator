<?php
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerCommonHeader extends Controller {
	public function index() {
        // Webp
        if (!isset($this->session->data['webp'])) {
            if (isset($_SERVER['HTTP_ACCEPT']) && isset($_SERVER['HTTP_USER_AGENT'])) {
                if (strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false || strpos($_SERVER['HTTP_USER_AGENT'], ' Chrome/') !== false) {
                    $this->session->data['webp'] = 1;
                }
            }
        }
		$data['require_login']=false;
		if(isset($this->session->data['redirect'])){
			$data['require_login']=true;
		}
		// Analytics
		$this->load->model('setting/extension');

		$data['analytics'] = array();

		$analytics = $this->model_setting_extension->getExtensions('analytics');

		foreach ($analytics as $analytic) {
			if ($this->config->get('analytics_' . $analytic['code'] . '_status')) {
				$data['analytics'][] = $this->load->controller('extension/analytics/' . $analytic['code'], $this->config->get('analytics_' . $analytic['code'] . '_status'));
			}
		}

		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->document->addLink($server . 'image/' . $this->config->get('config_icon'), 'icon');
		}

		$data['title'] = $this->document->getTitle();

		$data['base'] = $server;
		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();
		$data['links'] = $this->document->getLinks();
		$data['robots'] = $this->document->getRobots();
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts('header');
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');

		$data['name'] = $this->config->get('config_name');

        /*
		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$data['logo'] = '';
		}
        */

        $data['logo'] = $server . 'image/catalog/aviator_logo.svg';

		$this->load->language('common/header');


		$host = isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1')) ? HTTPS_SERVER : HTTP_SERVER;
		if ($this->request->server['REQUEST_URI'] == '/') {
			$data['og_url'] = $this->url->link('common/home');
		} else {
			$data['og_url'] = $host . substr($this->request->server['REQUEST_URI'], 1, (strlen($this->request->server['REQUEST_URI'])-1));
		}

		$data['og_image'] = $this->document->getOgImage();

        // TOP Line >> banner_id = 6
        $this->load->model('design/banner');
        $data['top_lines'] = $this->model_design_banner->getBanner(6);

        if (isset($this->request->get['route']) && $this->request->get['route']) {
            $data['route'] = $this->request->get['route'];
        } else {
            $data['route'] = '';
        }

		// Wishlist
		if ($this->customer->isLogged()) {
			$this->load->model('account/wishlist');
            $this->load->model('account/customer');
            $this->load->model('account/order');
            $this->load->model('account/wishlist');
            $this->load->model('account/return');
            $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
            $data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), $this->model_account_wishlist->getTotalWishlist());
		} else {
			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		}

		$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', true), $this->customer->getFirstName(), $this->url->link('account/logout', '', true));

		$data['home'] = $this->url->link('common/home');
		$data['wishlist'] = $this->url->link('account/wishlist', '', true);
		$data['logged'] = $this->customer->isLogged();
		if($this->customer->getFirstName()){
            $data['text_account'] = $this->customer->getFirstName();
        }else{
            $data['text_account'] = '';
        }

        if ($customer_info) {
            $data['customer_name'] = $customer_info['firstname'] . ' ' . $customer_info['lastname'];
            $data['contact_info'] = implode('<br>', [$this->customer->formatTelephone($customer_info['telephone']), $customer_info['email']]);
            $data['order_total'] = $this->model_account_order->getTotalOrders();
            $data['order_total_wishlist'] = $this->model_account_wishlist->getTotalWishlist();
            $data['return_total'] = $this->model_account_return->getTotalReturns();
            $data['discont'] = $customer_info['discont'];
        }

		$data['account'] = $this->url->link('account/account', '', true);
		$data['register'] = $this->url->link('account/register/mini', '', true);
		$data['login'] = $this->url->link('account/login/mini', '', true);
		$data['order'] = $this->url->link('account/order', '', true);
        $data['return'] = $this->url->link('account/return', '', true);
        $data['edit'] = $this->url->link('account/edit', '', true);

        $data['transaction'] = $this->url->link('account/transaction', '', true);
		$data['download'] = $this->url->link('account/download', '', true);
		$data['logout'] = $this->url->link('account/logout', '', true);
		$data['shopping_cart'] = $this->url->link('checkout/cart');
		$data['checkout'] = $this->url->link('checkout/checkout', '', true);
		// $data['contact'] = $this->url->link('information/contact');
		$data['telephone'] = $this->config->get('config_telephone');

		$data['language'] = $this->load->controller('common/language');
		$data['currency'] = $this->load->controller('common/currency');


        $data['about_us'] = $this->url->link('information/information', 'information_id=4');
        $data['delivery'] = $this->url->link('information/information', 'information_id=6');
        $data['contact'] = $this->url->link('information/contact', '', true);
        // $data['blog'] = $this->url->link('blog/latest', '', true);
        $data['gift_card'] = $this->url->link('product/category', 'path=206', true);

		$data['search'] = $this->load->controller('common/search');
		$data['cart'] = $this->load->controller('common/cart');
		$data['menu'] = $this->load->controller('common/menu');

		return $this->load->view('common/header', $data);
	}
}
