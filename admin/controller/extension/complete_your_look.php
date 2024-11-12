<?php
class ControllerExtensionCompleteYourLook extends Controller {

    public function index($data) {
        $this->load->language('extension/complete_your_look');

        $this->load->model('extension/complete_your_look');

        if ($data['cyl_id']) {
            $look_info = $this->model_extension_complete_your_look->getLook($data['cyl_id']);
        }

        if (isset($this->request->post['cyl_status'])) {
            $data['cyl_status'] = $this->request->post['cyl_status'];
        } elseif (!empty($look_info)) {
            $data['cyl_status'] = $look_info['status'];
        } else {
            $data['cyl_status'] = 0;
        }

        if (isset($this->request->post['cyl_data'])) {
            $cyl_data = $this->request->post['cyl_data'];
        } elseif (!empty($look_info['data'])) {
            $cyl_data = json_decode($look_info['data'], true);
        } else {
            $cyl_data = [];
        }

        $data['tl_product_name'] = '';
        $data['bl_product_name'] = '';
        $data['tr_product_name'] = '';
        $data['br_product_name'] = '';

        $default_KEYS = [
            'tl'        => [
                'p_id'  => '',
                'im'    => '',
                'ps'    => 0,
                'pt'    => '14%',
                'pr'    => '56%',
            ],
            'bl'        => [
                'p_id'  => '',
                'im'    => '',
                'ps'    => 0,
                'pb'    => '7%',
                'pr'    => '51%',
            ],
            'c'         => [
                'im'    => '',
                'bg'    => '',
            ],
            'tr'        => [
                'p_id'  => '',
                'im'    => '',
                'ps'    => 0,
                'con'    => 1,
                'pt'    => '19%',
                'pl'    => '52%',
            ],
            'br'        => [
                'p_id'  => '',
                'im'    => '',
                'ps'    => 0,
                'pb'    => '32%',
                'pl'    => '55%',
            ],
        ];

        foreach ($default_KEYS as $position_key => $result) {
            if (!isset($cyl_data[$position_key])) {
                $cyl_data[$position_key] = [];
            }

            foreach ($result as $key => $value) {
                if (!isset($cyl_data[$position_key][$key])) {
                    $cyl_data[$position_key][$key] = $value;

                    if ($key == 'im') {
                        $cyl_data[$position_key]['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
                    }
                } else {

                    if ($key == 'p_id' && $cyl_data[$position_key][$key]) {
                        $product_id = $cyl_data[$position_key][$key];
                        $product_info = $product_info = $this->model_catalog_product->getProduct($product_id);

                        if ($product_info) {
                            $data[$position_key . '_product_name'] = $product_info['name'];
                        }
                    }

                    if ($key == 'im') {
                        $image = $cyl_data[$position_key][$key];

                        if (!empty($image) && is_file(DIR_IMAGE . $image)) {
                            $cyl_data[$position_key]['thumb'] = $this->model_tool_image->resize($image, 100, 100);
                        } else {
                            $cyl_data[$position_key]['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
                        }
                    }
                }
            }
        }

        $data['cyl_data'] = $cyl_data;

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        $data['user_token'] = $this->session->data['user_token'];

        return $this->load->view('extension/module/cyl/complete_your_look_form', $data);
    }

    public function autocomplete() {
        $json = [];

        if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model'])) {
            $this->load->model('catalog/product');

            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            if (isset($this->request->get['filter_model'])) {
                $filter_model = $this->request->get['filter_model'];
            } else {
                $filter_model = '';
            }

            if (isset($this->request->get['limit'])) {
                $limit = (int)$this->request->get['limit'];
            } else {
                $limit = $this->config->get('config_limit_autocomplete');
            }

            $filter_data = [
                'filter_name'   => $filter_name,
                'filter_model'  => $filter_model,
                'filter_status' => 1,
                'start'         => 0,
                'limit'         => $limit
            ];

            if (isset($this->request->get['filter_cyl'])) {
                $filter_data['filter_cyl'] = true;
            }

            $results = $this->model_catalog_product->getProducts($filter_data);

            foreach ($results as $result) {
                $json[] = array(
                    'product_id' => $result['product_id'],
                    'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
                    'model'      => $result['model'],
                );
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
