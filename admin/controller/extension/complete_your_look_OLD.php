<?php
class ControllerExtensionCompleteYourLook extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('extension/complete_your_look');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/complete_your_look');

        $this->getList();
    }

    public function add() {
        $this->load->language('extension/complete_your_look');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/complete_your_look');

        $this->load->model('catalog/product');

        $this->load->model('tool/image');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_extension_complete_your_look->addLook($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('extension/complete_your_look', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getForm();
    }

    public function edit() {
        $this->load->language('extension/complete_your_look');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/complete_your_look');

        $this->load->model('catalog/product');

        $this->load->model('tool/image');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_extension_complete_your_look->editLook($this->request->get['cyl_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('extension/complete_your_look', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getForm();
    }

    public function delete() {
        $this->load->language('extension/complete_your_look');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/complete_your_look');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $cyl_id) {
                $this->model_extension_complete_your_look->deleteLook($cyl_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('extension/complete_your_look', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getList();
    }

    protected function getList() {
        $url = '';

        if (isset($this->request->get['page'])) {
            $page = (int)$this->request->get['page'];
            $url .= '&page=' . $page;
        } else {
            $page = 1;
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/complete_your_look', 'user_token=' . $this->session->data['user_token'] . $url, true)
        ];

        $data['add']        = $this->url->link('extension/complete_your_look/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['delete']     = $this->url->link('extension/complete_your_look/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

        $data['complete_your_looks'] = [];

        $complete_your_look_total = $this->model_extension_complete_your_look->getTotalLooks();

        $results = $this->model_extension_complete_your_look->getLooks();

        foreach ($results as $result) {
            $data['complete_your_looks'][] = [
                'cyl_id'    => $result['cyl_id'],
                'name'      => $result['name'],
                'status'    => $result['status'] ? $this->language->get('text_enabled_short') : $this->language->get('text_disabled_short'),
                'edit'      => $this->url->link('extension/complete_your_look/edit', 'user_token=' . $this->session->data['user_token'] . '&cyl_id=' . $result['cyl_id'] . $url, true)
            ];
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array)$this->request->post['selected'];
        } else {
            $data['selected'] = [];
        }

        $url = '';

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $pagination = new Pagination();
        $pagination->total = $complete_your_look_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('catalog/product', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($complete_your_look_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($complete_your_look_total - $this->config->get('config_limit_admin'))) ? $complete_your_look_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $complete_your_look_total, ceil($complete_your_look_total / $this->config->get('config_limit_admin')));

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/cyl/complete_your_look_list', $data));
    }

    protected function getForm() {
        $data['text_form'] = !isset($this->request->get['cyl_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        $url = '';

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/complete_your_look', 'user_token=' . $this->session->data['user_token'] . $url, true)
        ];

        if (!isset($this->request->get['cyl_id'])) {
            $data['action'] = $this->url->link('extension/complete_your_look/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
        } else {
            $data['action'] = $this->url->link('extension/complete_your_look/edit', 'user_token=' . $this->session->data['user_token'] . '&cyl_id=' . $this->request->get['cyl_id'] . $url, true);
        }

        $data['cancel'] = $this->url->link('extension/complete_your_look', 'user_token=' . $this->session->data['user_token'] . $url, true);

        if (isset($this->request->get['cyl_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $look_info = $this->model_extension_complete_your_look->getLook($this->request->get['cyl_id']);
        }

        $data['user_token'] = $this->session->data['user_token'];

        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (!empty($look_info)) {
            $data['name'] = $look_info['name'];
        } else {
            $data['name'] = '';
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($look_info)) {
            $data['status'] = $look_info['status'];
        } else {
            $data['status'] = 0;
        }

        // Data
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

        if (isset($this->request->post['show_in_product'])) {
            $show_in_products = $this->request->post['show_in_product'];
        } elseif (isset($this->request->get['cyl_id'])) {
            $show_in_products = $this->model_extension_complete_your_look->getShowInProducts($this->request->get['cyl_id']);
        } else {
            $show_in_products = [];
        }

        $data['show_in_products'] = [];

        foreach ($show_in_products as $product_id) {
            $show_in_product_info = $this->model_catalog_product->getProduct($product_id);

            if ($show_in_product_info) {
                $data['show_in_products'][] = [
                    'product_id' => $show_in_product_info['product_id'],
                    'name'       => $show_in_product_info['name']
                ];
            }
        }

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/cyl/complete_your_look_form', $data));
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'extension/complete_your_look')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
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

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'extension/complete_your_look')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['name']) < 2) || (utf8_strlen($this->request->post['name']) > 255)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        return !$this->error;
    }
}
