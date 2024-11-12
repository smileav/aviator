<?php
class ControllerExtensionModuleSlideshow extends Controller {
	public function index($setting) {
		static $module = 0;
        $this->load->language('extension/module/slideshow');

		$this->load->model('design/banner');
		$this->load->model('tool/image');

        $this->document->addStyle('catalog/view/javascript/OwlCarousel2/assets/owl.carousel.min.css');
        $this->document->addStyle('catalog/view/javascript/OwlCarousel2/assets/owl.theme.default.min.css');
        $this->document->addScript('catalog/view/javascript/OwlCarousel2/owl.carousel.min.js');

		$data['banners'] = [];

		$results = $this->model_design_banner->getBanner($setting['banner_id']);

        foreach ($results as $result) {
            if (is_file(DIR_IMAGE . $result['image'])) {

                if (is_file(DIR_IMAGE . $result['image_m'])) {
                    $image_m = $this->model_tool_image->resize($result['image_m'], 767, 724);
                } else {
                    $image_m = $this->model_tool_image->resize($result['image'], 767, 724, 'auto');
                }

                $data['banners'][] = array(
                    'title' => html_entity_decode($result['title'], ENT_QUOTES, 'UTF-8'),
                    'link'      => $result['link'],
                    'image'     => $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']),
                    'image_w'   => $setting['width'],
                    'image_h'   => $setting['height'],
                    'image_m'   => $image_m,
                    'image_m_w' => 767,
                    'image_m_h' => 724
                );
            }
        }

		$data['module'] = $module++;

		return $this->load->view('extension/module/slideshow', $data);
	}
}
