<?php
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerProductCategory extends Controller {
	public function index() {
		$this->load->language('product/category');

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

        $data['text_empty'] = $this->language->get('text_empty');

        $disallow_params = [];

        if ($this->config->get('config_noindex_disallow_params')) {
            $params = explode ("\r\n", $this->config->get('config_noindex_disallow_params'));
            if(!empty($params)) {
                $disallow_params = $params;
            }
        }

		if (isset($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
			if (!in_array('filter', $disallow_params, true) && $this->config->get('config_noindex_status')){
                $this->document->setRobots('noindex,follow');
            }
		} else {
			$filter = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
            if (!in_array('sort', $disallow_params, true) && $this->config->get('config_noindex_status')) {
                $this->document->setRobots('noindex,follow');
            }
		} else {
			$sort = 'p.sort_order';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
            if (!in_array('order', $disallow_params, true) && $this->config->get('config_noindex_status')) {
                $this->document->setRobots('noindex,follow');
            }
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
            if (!in_array('page', $disallow_params, true) && $this->config->get('config_noindex_status')) {
                $this->document->setRobots('noindex,follow');
            }
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
            if (!in_array('limit', $disallow_params, true) && $this->config->get('config_noindex_status')) {
                $this->document->setRobots('noindex,follow');
            }
		} else {
			$limit = $this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit');
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		if (isset($this->request->get['path'])) {
			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$path = '';

			$parts = explode('_', (string)$this->request->get['path']);

			$category_id = (int)array_pop($parts);

			foreach ($parts as $path_id) {
				if (!$path) {
					$path = (int)$path_id;
				} else {
					$path .= '_' . (int)$path_id;
				}

				$category_info = $this->model_catalog_category->getCategory($path_id);

				if ($category_info) {
					$data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $this->url->link('product/category', 'path=' . $path . $url)
					);
				}
			}
		} else {
			$category_id = 0;
		}

        // Filter A


        // print_r($data);


        /*
        $fiaS_tmp = array_flip($fiaS);

        // print_r($data['fia']['S']);
        foreach ($data['fia']['S'] as $key => $result) {
            if (isset($fiaS_tmp[$result['option_value_id']])) {

                $data['fia']['S'][$key]['a'] = 1;

                $data['fia']['results'][] = [
                    'fia'               => 'S',
                    'text'              => $data['text_fia_size'],
                    'id'                => $result['option_value_id'],
                    'name'              => $result['name']
                ];
            } else {
                $data['fia']['S'][$key]['a'] = 0;
            }
        }

        //print_r($data['fia']);

        unset($fiaS_tmp);
        */

		$category_info = $this->model_catalog_category->getCategory($category_id);

		if ($category_info) {
			if ($category_info['meta_title']) {
                if ($page > 1) {
				    $this->document->setTitle($this->language->get('text_page') . ' ' . $page . '. ' . $category_info['meta_title']);
                } else {
                    $this->document->setTitle($category_info['meta_title']);
                }
			} else {
				$this->document->setTitle($category_info['name']);
			}

			if ($category_info['noindex'] <= 0 && $this->config->get('config_noindex_status')) {
				$this->document->setRobots('noindex,follow');
			}

			if ($category_info['meta_h1']) {
				$data['heading_title'] = $category_info['meta_h1'];
			} else {
				$data['heading_title'] = $category_info['name'];
			}

            if ($page > 1) {
                $this->document->setDescription($this->language->get('text_page') . ' ' . $page . '. ' . $category_info['meta_description']);
            } else {
                $this->document->setDescription($category_info['meta_description']);
            }

			$this->document->setKeywords($category_info['meta_keyword']);

			$data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));

			// Set the last category breadcrumb
			$data['breadcrumbs'][] = array(
				'text' => $category_info['name'],
				'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'])
			);

            /*
			if ($category_info['image']) {
				$data['thumb'] = $this->model_tool_image->resize($category_info['image'], 1392, 200, 'auto');
			} else {
				$data['thumb'] = '';
			}
            */
            if ($category_id == 206) {
                if ($category_info['image']) {
                    $data['thumb'] = $this->model_tool_image->resize($category_info['image'], 1392, 800, 'auto');
                } else {
                    $data['thumb'] = '';
                }
            }

            if ($category_info['banner']) {
                $data['banner'] = $this->model_tool_image->resize($category_info['banner'], 1392, 200, 'auto');
            } else {
                $data['banner'] = $this->model_tool_image->resize('category_no_image.png', 1392, 200, 'auto');
            }

            $data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');

			$data['compare'] = $this->url->link('product/compare');

			$url = '';

            if (isset($this->request->get['G'])) {
                $url .= '&G=' . $this->request->get['G'];
            }

            if (isset($this->request->get['C'])) {
                $url .= '&C=' . $this->request->get['C'];
            }

            if (isset($this->request->get['M'])) {
                $url .= '&M=' . $this->request->get['M'];
            }

            if (isset($this->request->get['P'])) {
                $url .= '&P=' . $this->request->get['P'];
            }

            if (isset($this->request->get['S'])) {
                $url .= '&S=' . $this->request->get['S'];
            }

            if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['categories'] = array();

			$results = $this->model_catalog_category->getCategories($category_id);

			foreach ($results as $result) {
				$filter_data = array(
					'filter_category_id'  => $result['category_id'],
					'filter_sub_category' => true
				);

				$data['categories'][] = array(
					'name' => $result['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
					'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '_' . $result['category_id'] . $url)
				);
			}

			$data['products'] = array();

			$filter_data = array(
				'filter_category_id'    => $category_id,
				'filter_filter'         => $filter,
				'sort'                  => $sort,
				'order'                 => $order,
				'start'                 => ($page - 1) * $limit,
				'limit'                 => $limit
			);

            if ($category_id != 206) {
                // Filter A
                $data['Filter_A'] = $this->load->controller('extension/module/filter_a');

                $filter_data['fia_GET'] = $data['Filter_A']['fia_GET'];

                switch ($data['Filter_A']['grid']) {
                    case 1:
                        $image_w = 720;
                        $image_h = 1079;
                        break;
                    case 2:
                        $image_w = 688;
                        $image_h = 1031;
                        break;
                    case 3:
                        $image_w = 454;
                        $image_h = 681;
                        break;
                    default:
                        $image_w = 336;
                        $image_h = 504;
                }
            }

            $product_total = $this->model_catalog_product->getTotalProducts($filter_data);

			$results = $this->model_catalog_product->getProducts($filter_data);

			foreach ($results as $result) {

                if ($category_id != 206) {
                    if ($result['image']) {
                        $image = $this->model_tool_image->resize($result['image'], $image_w, $image_h, 'auto');
                    } else {
                        $image = $this->model_tool_image->resize('placeholder.png', $image_w, $image_h, 'auto');
                    }
                } else {
                    $image_w = 287;
                    $image_h = 170;

                    if ($result['image']) {
                        $image = $this->model_tool_image->resize($result['image'], $image_w, $image_h, 'auto');
                    } else {
                        $image = $this->model_tool_image->resize('placeholder.png', $image_w, $image_h, 'auto');
                    }
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
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'options'     => $product_options,
					'href'        => $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'] . $url)
				);
			}

			$url = '';

            if (isset($this->request->get['G'])) {
                $url .= '&G=' . $this->request->get['G'];
            }

            if (isset($this->request->get['C'])) {
                $url .= '&C=' . $this->request->get['C'];
            }

            if (isset($this->request->get['M'])) {
                $url .= '&M=' . $this->request->get['M'];
            }

            if (isset($this->request->get['P'])) {
                $url .= '&P=' . $this->request->get['P'];
            }

            if (isset($this->request->get['S'])) {
                $url .= '&S=' . $this->request->get['S'];
            }

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['sorts'] = array();

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_default'),
				'value' => 'p.sort_order-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.sort_order&order=ASC' . $url)
			);

            $data['sorts'][] = array(
                'text'  => $this->language->get('text_date_added_desc'),
                'value' => 'p.date_added-DESC',
                'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.date_added&order=DESC' . $url)
            );

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_asc'),
				'value' => 'pd.name-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=DESC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_asc'),
				'value' => 'p.price-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_desc'),
				'value' => 'p.price-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=DESC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_asc'),
				'value' => 'p.model-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_desc'),
				'value' => 'p.model-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=DESC' . $url)
			);

			$url = '';

            if (isset($this->request->get['G'])) {
                $url .= '&G=' . $this->request->get['G'];
            }

            if (isset($this->request->get['C'])) {
                $url .= '&C=' . $this->request->get['C'];
            }

            if (isset($this->request->get['M'])) {
                $url .= '&M=' . $this->request->get['M'];
            }

            if (isset($this->request->get['P'])) {
                $url .= '&P=' . $this->request->get['P'];
            }

            if (isset($this->request->get['S'])) {
                $url .= '&S=' . $this->request->get['S'];
            }

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$data['limits'] = array();

            /* ORIGINAL
			$limits = array_unique(array($this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit'), 25, 50, 75, 100));

			sort($limits);

			foreach($limits as $value) {
				$data['limits'][] = array(
					'text'  => $value,
					'value' => $value,
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=' . $value)
				);
			}
            */

            // Filter A
            /*
            switch ($data['Filter_A']['grid']) {
                case 1:
                    $limits = [12, 24, 48, 72];
                    break;
                case 2:
                    $limits = [22, 24, 48, 72];
                    break;
                case 3:
                    $limits = [12, 24, 48, 72];
                    break;
                default:
                    $limits = [12, 24, 48, 72];
            }
            */

            $limits = [12, 24, 48, 72, 96];

            foreach($limits as $value) {
                $data['limits'][] = array(
                    // 'text'  => sprintf($this->language->get('text_fia_limit'), $value),
                    'value' => $value,
                    'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=' . $value)
                );
            }

			$url = '';

            if (isset($this->request->get['G'])) {
                $url .= '&G=' . $this->request->get['G'];
            }

            if (isset($this->request->get['C'])) {
                $url .= '&C=' . $this->request->get['C'];
            }

            if (isset($this->request->get['M'])) {
                $url .= '&M=' . $this->request->get['M'];
            }

            if (isset($this->request->get['S'])) {
                $url .= '&S=' . $this->request->get['S'];
            }


            if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&page={page}');

			// $data['pagination'] = $pagination->render();
            $data['pagination'] = $pagination->render_to_front();

			$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

            if (!$this->config->get('config_canonical_method')) {
                // http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
                if ($page == 1) {
                    $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id']), 'canonical');
                } elseif ($page == 2) {
                    $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id']), 'prev');
                } else {
                    $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'] . '&page=' . ($page - 1)), 'prev');
                }

                if ($limit && ceil($product_total / $limit) > $page) {
                    $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'] . '&page=' . ($page + 1)), 'next');
                }
            } else {

                if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
                    $server = $this->config->get('config_ssl');
                } else {
                    $server = $this->config->get('config_url');
                };

                $request_url = rtrim($server, '/') . $this->request->server['REQUEST_URI'];
                $canonical_url = $this->url->link('product/category', 'path=' . $category_info['category_id']);

                if (($request_url != $canonical_url) || $this->config->get('config_canonical_self')) {
                    $this->document->addLink($canonical_url, 'canonical');
                }

                if ($this->config->get('config_add_prevnext')) {

                    if ($page == 2) {
                        $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id']), 'prev');
                    } elseif ($page > 2)  {
                        $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'] . '&page=' . ($page - 1)), 'prev');
                    }

                    if ($limit && ceil($product_total / $limit) > $page) {
                        $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'] . '&page=' . ($page + 1)), 'next');
                    }
                }
            }

			$data['sort'] = $sort;
			$data['order'] = $order;
			$data['limit'] = $limit;

            if (empty($data['products'])) {
                $this->document->addStyle('catalog/view/javascript/OwlCarousel2/assets/owl.carousel.min.css');
                $this->document->addStyle('catalog/view/javascript/OwlCarousel2/assets/owl.theme.default.min.css');
                $this->document->addScript('catalog/view/javascript/OwlCarousel2/owl.carousel.min.js');
                $this->load->model('setting/module');
                $module_id = '36';
                $setting_info = $this->model_setting_module->getModule($module_id);
                $data['latest'] = $this->load->controller('extension/module/latest', $setting_info);
                $module_id = '37';
                $setting_info = $this->model_setting_module->getModule($module_id);
                $data['special'] = $this->load->controller('extension/module/special', $setting_info);
            } else {
                if ($category_id != 206) {
                    $this->document->addStyle('catalog/view/theme/aviator/stylesheet/filter_a.css');
                    $this->document->addStyle('catalog/view/theme/aviator/js/noUiSlider/dist/nouislider.min.css');

                    $this->document->addScript('catalog/view/theme/aviator/js/filter_a.js');
                    $this->document->addScript('catalog/view/theme/aviator/js/noUiSlider/dist/nouislider.min.js');
                    $this->document->addScript('catalog/view/theme/aviator/js/wNumb.min.js');

                    $data['latest'] = '';
                    $data['special'] = '';
                }
            }

			$data['continue'] = $this->url->link('common/home');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

            if ($category_id != 206) {
                $this->response->setOutput($this->load->view('product/category', $data));
            } else {
                $this->load->language('product/gift_card');

                $this->response->setOutput($this->load->view('product/category_gift_card', $data));
            }
		} else {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('product/category', $url)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}
}
