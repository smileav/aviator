<?php
class ControllerInformationContact extends Controller {
    public function index() {
		$this->load->language('information/contact');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('information/contact')
		);

        $this->document->addLink($this->url->link('information/contact'), 'canonical');

        $this->load->model('tool/image');

        $data['thumb_store_kiev'] = '';

        $image = 'catalog/information/store_kiev_lg.jpg';

        if (is_file(DIR_IMAGE . $image)) {
            $image_size = getimagesize(DIR_IMAGE . $image);

            if (!empty($image_size[0]) && !empty($image_size[1]) && !empty($image_size[3])) {
                $data['thumb_store_kiev']           = $this->model_tool_image->resize($image, $image_size[0], $image_size[1]);
                $data['thumb_store_kiev_wh']        = $image_size[3];
            }
        }

        $data['thumb_store_odessa'] = '';

        $image = 'catalog/information/store_odessa_lg.jpg';

        if (is_file(DIR_IMAGE . $image)) {
            $image_size = getimagesize(DIR_IMAGE . $image);

            if (!empty($image_size[0]) && !empty($image_size[1]) && !empty($image_size[3])) {
                $data['thumb_store_odessa']         = $this->model_tool_image->resize($image, $image_size[0], $image_size[1]);
                $data['thumb_store_odessa_wh']      = $image_size[3];
            }
        }

        $data['thumb_store_kiev2'] = '';

        $image = 'catalog/information/store_kiev2_lg.jpg';

        if (is_file(DIR_IMAGE . $image)) {
            $image_size = getimagesize(DIR_IMAGE . $image);

            if (!empty($image_size[0]) && !empty($image_size[1]) && !empty($image_size[3])) {
                $data['thumb_store_kiev2']          = $this->model_tool_image->resize($image, $image_size[0], $image_size[1]);
                $data['thumb_store_kiev2_wh']       = $image_size[3];
            }
        }

        $data['thumb_store_kiev3'] = '';

        $image = 'catalog/information/store_kiev3_lg.jpg';

        if (is_file(DIR_IMAGE . $image)) {
            $image_size = getimagesize(DIR_IMAGE . $image);

            if (!empty($image_size[0]) && !empty($image_size[1]) && !empty($image_size[3])) {
                $data['thumb_store_kiev3']          = $this->model_tool_image->resize($image, $image_size[0], $image_size[1]);
                $data['thumb_store_kiev3_wh']       = $image_size[3];
            }
        }

		$data['content_top'] = $this->load->controller('common/content_top');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('information/contact', $data));
	}
}
