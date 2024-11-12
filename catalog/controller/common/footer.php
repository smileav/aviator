<?php
class ControllerCommonFooter extends Controller {
	public function index() {
		$this->load->language('common/footer');

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['logo'] = $server . 'image/catalog/aviator_logo_w.svg';

        $data['home'] = $this->url->link('common/home');

        $host = isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1')) ? HTTPS_SERVER : HTTP_SERVER;

        if ($this->request->server['REQUEST_URI'] == '/') {
            $data['og_url'] = $this->url->link('common/home');
        } else {
            $data['og_url'] = $host . substr($this->request->server['REQUEST_URI'], 1, (strlen($this->request->server['REQUEST_URI'])-1));
        }

		$this->load->model('catalog/information');

		$data['information'] = array();

		foreach ($this->model_catalog_information->getInformations() as $result) {
			if ($result['bottom']) {
                if ($result['information_id'] == 4 || $result['information_id'] == 6 || $result['information_id'] == 7 || $result['information_id'] == 8 || $result['information_id'] == 10) {
                    $data['information'][$result['information_id']] = [
                        'title'     => $result['title'],
                        'meta_h1'   => $result['meta_h1'],
                        'href'      => $this->url->link('information/information', 'information_id=' . $result['information_id'])
                    ];
                }

			}
		}

        $data['blog'] = $this->url->link('blog/latest', '', true);
        $data['gift_card'] = $this->url->link('product/category', 'path=206', true);

        /*
		$data['contact'] = $this->url->link('information/contact');
		$data['return'] = $this->url->link('account/return/add', '', true);
		$data['sitemap'] = $this->url->link('information/sitemap');
		$data['tracking'] = $this->url->link('information/tracking');
		$data['manufacturer'] = $this->url->link('product/manufacturer');
		$data['voucher'] = $this->url->link('account/voucher', '', true);
		$data['affiliate'] = $this->url->link('affiliate/login', '', true);
		$data['special'] = $this->url->link('product/special');
		$data['account'] = $this->url->link('account/account', '', true);
		$data['order'] = $this->url->link('account/order', '', true);
		$data['wishlist'] = $this->url->link('account/wishlist', '', true);
		$data['newsletter'] = $this->url->link('account/newsletter', '', true);
        */

		// $data['powered'] = sprintf($this->language->get('text_powered'), $this->config->get('config_name'), date('Y', time()));
		$data['powered'] = sprintf($this->language->get('text_powered'), date('Y', time()));

		// Whos Online
        /*
		if ($this->config->get('config_customer_online')) {
			$this->load->model('tool/online');

			if (isset($this->request->server['REMOTE_ADDR'])) {
				$ip = $this->request->server['REMOTE_ADDR'];
			} else {
				$ip = '';
			}

			if (isset($this->request->server['HTTP_HOST']) && isset($this->request->server['REQUEST_URI'])) {
				$url = ($this->request->server['HTTPS'] ? 'https://' : 'http://') . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];
			} else {
				$url = '';
			}

			if (isset($this->request->server['HTTP_REFERER'])) {
				$referer = $this->request->server['HTTP_REFERER'];
			} else {
				$referer = '';
			}

			$this->model_tool_online->addOnline($ip, $this->customer->getId(), $url, $referer);
		}
        */

		//# $data['scripts'] = $this->document->getScripts('footer');
		//# $data['styles'] = $this->document->getStyles('footer');

        // uk-ua, en-gb, ru-ru
        $data['this_session_data_language'] = $this->session->data['language'];

		return $this->load->view('common/footer', $data);
	}
}
