<?php
class ControllerExtensionModuleCustomBlocks extends Controller {
	public function index($setting) {
		$this->load->model('tool/image');

		$language_id = $this->config->get('config_language_id');

		$data['columns']    = $setting['columns'];
        $data['noise']      = $this->model_tool_image->resize('catalog/demo/category/noise_wall.png', $setting['width'], $setting['height'], 'auto');
        $data['width']      = $setting['width'];
        $data['height']     = $setting['height'];

		$results = $setting['custom_blocks_item'];

		foreach ($results as $result) {
			$data['blocks'][] = array(
				'image'         => $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height'], 'auto'),
				'title'         => html_entity_decode($result['title'][$language_id], ENT_QUOTES, 'UTF-8'),
				// 'description'   => $result['description'][$language_id],
				'link'          => $result['link'][$language_id],
				// 'html'          => html_entity_decode($result['html'], ENT_QUOTES, 'UTF-8'),
				'sort'          => $result['sort']
			);
		}

		if (!empty($data['blocks'])){
			foreach ($data['blocks'] as $key => $value) {
				$sort[$key] = $value['sort'];
			}

			array_multisort($sort, SORT_ASC, $data['blocks']);
		}

		return $this->load->view('extension/module/custom_blocks', $data);
	}
}
