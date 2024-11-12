<?php
class ControllerExtensionDashboardOpenMinify extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/dashboard/open_minify');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('dashboard_open_minify', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=dashboard', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=dashboard', true)
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/dashboard/open_minify', 'user_token=' . $this->session->data['user_token'], true)
		];

		$data['action'] = $this->url->link('extension/dashboard/open_minify', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=dashboard', true);

		if (isset($this->request->post['dashboard_open_minify_width'])) {
			$data['dashboard_open_minify_width'] = $this->request->post['dashboard_open_minify_width'];
		} else {
			$data['dashboard_open_minify_width'] = $this->config->get('dashboard_open_minify_width');
		}

		$data['columns'] = array();

		for ($i = 3; $i <= 12; $i++) {
			$data['columns'][] = $i;
		}

		if (isset($this->request->post['dashboard_open_minify_status'])) {
			$data['dashboard_open_minify_status'] = $this->request->post['dashboard_open_minify_status'];
		} else {
			$data['dashboard_open_minify_status'] = $this->config->get('dashboard_open_minify_status');
		}

		if (isset($this->request->post['dashboard_open_minify_sort_order'])) {
			$data['dashboard_open_minify_sort_order'] = $this->request->post['dashboard_open_minify_sort_order'];
		} else {
			$data['dashboard_open_minify_sort_order'] = $this->config->get('dashboard_open_minify_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/dashboard/open_minify_form', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/dashboard/open_minify')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function dashboard() {
		$this->load->language('extension/dashboard/open_minify');

        $this->load->model('setting/setting');

        $setting = $this->model_setting_setting->getSetting('open_minify');

        if (empty($setting)) {
            $setting = [
                'open_minify_css'   => 0,
                'open_minify_js'    => 0,
                'open_minify_dev'   => 0
            ];

            $this->model_setting_setting->editSetting('open_minify', $setting);
        }

		$data['user_token'] = $this->session->data['user_token'];

        $this->document->addStyle('view/javascript/bootstrap-toggle/css/bootstrap-toggle.min.css');
        $this->document->addScript('view/javascript/bootstrap-toggle/js/bootstrap-toggle.min.js');

		$data['open_minify_css']     = $setting['open_minify_css'];
		$data['open_minify_js']      = $setting['open_minify_js'];
		$data['open_minify_dev']     = $setting['open_minify_dev'];

		return $this->load->view('extension/dashboard/open_minify_info', $data);
	}

    public function ajax() {
        $json = [];

        if (isset($this->request->post['action'])) {
            $action = $this->request->post['action'];

            $this->load->model('setting/setting');

            $setting = $this->model_setting_setting->getSetting('open_minify');

            if ($action == 'open_minify_cc') {
                require_once(DIR_SYSTEM . 'open_minify/open_minify_startup.php');

                $openMinify = new OpenMinify();

                $this->registry->set('openMinify', $openMinify);

                $openMinify->cache      = new openMinifyCache($this->registry);

                $openMinify->cache->deleteCache();

                $json['success'] = 'open_minify_cc';

            } else
            if (isset($this->request->post['value'])) {
                foreach ($setting as $key => $value) {
                    if ($key == $action) {
                        $setting[$key] = (int)$this->request->post['value'];
                    }
                }

                $this->model_setting_setting->editSetting('open_minify', $setting);
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
