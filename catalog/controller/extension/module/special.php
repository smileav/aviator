<?php
class ControllerExtensionModuleSpecial extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/special');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

        $image_w    = $setting['width'];
        $image_h    = $setting['height'];

		$data['products'] = array();

		$filter_data = array(
			'sort'  => 'p.sort_order',
			'order' => 'ASC',
			'start' => 0,
			'limit' => $setting['limit']
		);

		$results = $this->model_catalog_product->getProductSpecials($filter_data);

		if ($results) {
			foreach ($results as $result) {
				if ($result['image']) {
                    $image = $this->model_tool_image->resize($result['image'], $image_w, $image_h, 'auto');
				} else {
                    $image = $this->model_tool_image->resize('placeholder.png', $image_w, $image_h, 'auto');
				}

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				if (!is_null($result['special']) && (float)$result['special'] >= 0) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$special = false;
				}

                $product_options = $this->model_catalog_product->getProductThumbOptions($result['product_id']);

				$data['products'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
                    'thumb_w'     => $image_w,
                    'thumb_h'     => $image_h,
					'name'        => $result['name'],
					'price'       => $price,
					'special'     => $special,
                    'options'     => $product_options,
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}

			return $this->load->view('extension/module/special', $data);
		}
	}
}
