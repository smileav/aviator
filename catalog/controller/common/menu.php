<?php
class ControllerCommonMenu extends Controller {
	public function index() {
		$this->load->language('common/menu');

		// Menu
		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

        $data['href_aeronautica_militare'] = $this->url->link('product/category', 'path=186', true);
        $data['href_brand'] = $this->url->link('product/manufacturer', '', true);
        $data['special'] = $this->url->link('product/special', '', true);;

        $data['categories'] = array();

		$categories = $this->model_catalog_category->getCategories(0);

		foreach ($categories as $category) {
			if ($category['top']) {
				// Level 2
				$children_data = array();

				$children = $this->model_catalog_category->getCategories($category['category_id']);

				foreach ($children as $child) {
                    if ($child['top']) {
                        $filter_data = array(
                            'filter_category_id'  => $child['category_id'],
                            'filter_sub_category' => true
                        );

                        $children_data[] = array(
                            'name'  => $child['name'],
                            'thumb' => $this->model_tool_image->resize($child['image'], 226, 150),
                            'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
                        );
				    }
				}

				// Level 1
				$data['categories'][] = array(
					'category_id'   => $category['category_id'],
					'name'          => $category['name'],
					'children'      => $children_data,
					'column'        => $category['column'] ? $category['column'] : 1,
					'href'          => $this->url->link('product/category', 'path=' . $category['category_id'])
				);
			}
		}

        $data['manufacturers'] = $this->url->link('product/manufacturer', '', true);

        $data['about_us'] = $this->url->link('information/information', 'information_id=4');
        $data['delivery'] = $this->url->link('information/information', 'information_id=6');
        $data['contact'] = $this->url->link('information/contact', '', true);
        // $data['blog'] = $this->url->link('blog/latest', '', true);
        $data['gift_card'] = $this->url->link('product/category', 'path=206', true);

        $data['language'] = $this->load->controller('common/language');

        return $this->load->view('common/menu', $data);
	}

    public function getChildren() {
        $data = [];

        if (isset($this->request->get['id'])) {
            $category_id = (int)$this->request->get['id'];

            $data['children'] = [];

            if ($category_id) {
                $this->load->model('catalog/category');

                $results = $this->model_catalog_category->getCategories($category_id);
            } else {
                $this->load->model('catalog/manufacturer');

                $results = $this->model_catalog_manufacturer->getManufacturersByCategory();
            }

            foreach ($results as $child) {
                if ($category_id) {
                    $data['children'][] = [
                        'name' => $child['name'],
                        'href' => $this->url->link('product/category', 'path=' . $category_id . '_' . $child['category_id'])
                    ];
                } else {
                    $this->load->model('tool/image');

                    if ($child['image']) {
                        $image = $this->model_tool_image->resize($child['image'], 150, 84);
                    } else {
                        $image = $this->model_tool_image->resize('placeholder.png', 150, 84);
                    }

                    $data['children'][] = [
                        'name' => $child['name'],
                        'image'     => $image,
                        'href'      => $this->url->link('product/category', 'path=' . $child['category_id'])
                    ];
                }
            }

            $data['category_id'] = $category_id;
        }

        $this->response->setOutput($this->load->view('common/menu_children', $data));
    }
}
