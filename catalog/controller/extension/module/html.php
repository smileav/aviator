<?php
class ControllerExtensionModuleHTML extends Controller {
	public function index($setting) {
		if (isset($setting['module_description'][$this->config->get('config_language_id')])) {
			$data['heading_title'] = html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['title'], ENT_QUOTES, 'UTF-8');
			$data['html'] = html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['description'], ENT_QUOTES, 'UTF-8');

            $data['image']      = $this->model_tool_image->resize($setting['image'], $setting['width'], $setting['height'], 'auto');

            $data['width']      = $setting['width'];
            $data['height']     = $setting['height'];

            $data['about_us'] = $this->url->link('information/information', 'information_id=4');

            if ($setting['template']) {
                $template = $setting['template'];
            } else {
                $template = 'html';
            }

            return $this->load->view('extension/module/' . $template, $data);
		}
	}
}
