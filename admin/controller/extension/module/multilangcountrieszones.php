<?php
class ControllerExtensionModuleMultilangCountriesZones extends Controller {
	private $error = array();

	public function index() {
		$this->load->model('extension/module/multilangcountrieszones');

		$this->load->language('extension/module/multilangcountrieszones');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$mlcz_status = $this->config->get('module_multilangcountrieszones_status');
			$this->model_setting_setting->editSetting('module_multilangcountrieszones', $this->request->post);
					
			if (isset($this->request->post['module_multilangcountrieszones_status']) ) {
				$this->cache->delete('country.catalog');
				$this->cache->delete('zone');
				$this->load->model('setting/modification');
				$row = $this->model_setting_modification->getModificationByCode('multilangcountrieszones');
				if ($this->request->post['module_multilangcountrieszones_status'] == 0 && $mlcz_status!=0) {
					$this->model_setting_modification->disableModification($row['modification_id']);
					$data['redirect'] = 'marketplace/extension&type=module';
					$this->load->controller('marketplace/modification/refresh', $data);

				} elseif ($this->request->post['module_multilangcountrieszones_status'] == 1 && $mlcz_status!=1) {
					$this->model_setting_modification->enableModification($row['modification_id']);
					$data['redirect'] = 'marketplace/extension&type=module';
					$this->load->controller('marketplace/modification/refresh', $data);
				}
			}

			$this->session->data['success'] = $this->language->get('text_success_refresh');
			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/multilangcountrieszones', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/multilangcountrieszones', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->post['module_multilangcountrieszones_status'])) {
			$data['module_multilangcountrieszones_status'] = $this->request->post['module_multilangcountrieszones_status'];
		} else {
			$data['module_multilangcountrieszones_status'] = $this->config->get('module_multilangcountrieszones_status');
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/multilangcountrieszones', $data));
	}

	public function install() {
		$this->load->model('extension/module/multilangcountrieszones');
		$this->model_extension_module_multilangcountrieszones->install();
	}

	public function uninstall() {
		$this->load->model('extension/module/multilangcountrieszones');
		$this->model_extension_module_multilangcountrieszones->uninstall();
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/multilangcountrieszones')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}