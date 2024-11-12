<?php
class ControllerExtensionFeedRozetkaFeed extends Controller {

    private $error = array();

    public function index() {

		$this->load->language('extension/feed/rozetka_feed');

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->load->model('extension/feed/rozetka_feed');

			if (isset($this->request->post['banned_vendors']) && !empty($this->request->post['banned_vendors'])) {
				$this->model_extension_feed_rozetka_feed->banVendors($this->request->post['banned_vendors']);
			} else {
				$this->model_extension_feed_rozetka_feed->unbanVendors();
			}

			$this->model_setting_setting->editSetting('rozetka_feed', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=feed', true));
		}

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=feed', 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/feed/rozetka_feed', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

        $data['action'] = $this->url->link('extension/feed/rozetka_feed', 'user_token=' . $this->session->data['user_token'], true);

        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=feed', true);

		if (isset($this->request->post['rozetka_feed_status'])) {
			$data['rozetka_feed_status'] = $this->request->post['rozetka_feed_status'];
		} else {
			$data['rozetka_feed_status'] = $this->config->get('rozetka_feed_status');
		}

		if (isset($this->request->post['rozetka_feed_store_url'])) {
			$data['rozetka_feed_store_url'] = $this->request->post['rozetka_feed_store_url'];
		} else {
			$data['rozetka_feed_store_url'] = $this->config->get('rozetka_feed_store_url');
		}

		if (isset($this->request->post['rozetka_feed_store_name'])) {
			$data['rozetka_feed_store_name'] = $this->request->post['rozetka_feed_store_name'];
		} else {
			$data['rozetka_feed_store_name'] = $this->config->get('rozetka_feed_store_name');
		}
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'agd.name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->load->model('catalog/attribute_group');

		$data['attribute_groups'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$results = $this->model_catalog_attribute_group->getAttributeGroups($filter_data);

		foreach ($results as $result) {
			$data['attribute_groups'][] = array(
				'attribute_group_id' => $result['attribute_group_id'],
				'name'               => $result['name'],
				'sort_order'         => $result['sort_order']
			);
		}

		$this->load->model('extension/feed/rozetka_feed');

		if (isset($this->request->post['disabled_vendors'])) {
			$data['disabled_vendors'] = $this->request->post['disabled_vendors'];
		} else {
			$data['disabled_vendors'] = $this->model_extension_feed_rozetka_feed->getDisabledVendors();
		}

		$data['store_url'] = HTTPS_CATALOG;
		$data['user_token'] = $this->session->data['user_token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/feed/rozetka_feed', $data));
    }

    public function vendorList() {
        $json = array();

        if (isset($this->request->get['filter_name'])) {
            $this->load->model('extension/feed/rozetka_feed');

            $filter_data = array(
                'filter_name' => $this->request->get['filter_name'],
                'start'       => 0,
                'limit'       => 5
            );

            $results = $this->model_extension_feed_rozetka_feed->getManufacturers($filter_data);

            foreach ($results as $result) {
                $json[] = array(
                    'manufacturer_id' => $result['manufacturer_id'],
                    'name'            => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
                );
            }

        }

        $sort_order = array();

        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['name'];
        }

        array_multisort($sort_order, SORT_ASC, $json);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));

    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/feed/rozetka_feed')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}
