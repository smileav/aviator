<?php
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerProductProduct extends Controller {
	private $error = array();

	public function index() {

        // $rinvex = new rinvex\country;
        // $country_data = $rinvex->getCountryData('tm');

        /*
        $n = '';
        for ($i = 1; $i <= $country_data['number_lengths'] - $country_data['code_lengths']; $i++) {
            $n .= '9';
        }
        print_r($n);
        */

        //# <!-- Архивный товар
        if (isset($this->request->get['route']) && $this->request->get['route'] == 'product/product' && !empty($this->request->get['product_id'])) {
            $product_id = $this->request->get['product_id'];

            $archive = $this->db->query("SELECT `archive` FROM `" . DB_PREFIX . "product` WHERE `product_id` = '" . (int)$product_id . "' AND `archive` = '1' AND `status` = '0'")->row;

            if (!empty($archive['archive'])) {
                $this->db->query("UPDATE `" . DB_PREFIX . "product` SET `status` = '1' WHERE `product_id` = '" . (int)$product_id . "'");

                $data['archive'] = true;
            } else {
                $data['archive'] = false;
            }
        }
        //# Архивный товар -->

		$this->load->language('product/product');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$this->load->model('catalog/category');

		if (isset($this->request->get['path'])) {
			$path = '';

			$parts = explode('_', (string)$this->request->get['path']);

			$category_id = (int)array_pop($parts);

			foreach ($parts as $path_id) {
				if (!$path) {
					$path = $path_id;
				} else {
					$path .= '_' . $path_id;
				}

				$category_info = $this->model_catalog_category->getCategory($path_id);

				if ($category_info) {
					$data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $this->url->link('product/category', 'path=' . $path)
					);
				}
			}

			// Set the last category breadcrumb
			$category_info = $this->model_catalog_category->getCategory($category_id);

			if ($category_info) {
				$url = '';

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
					'text' => $category_info['name'],
					'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url)
				);
			}
		}

		$this->load->model('catalog/manufacturer');

		if (isset($this->request->get['manufacturer_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_brand'),
				'href' => $this->url->link('product/manufacturer')
			);

			$url = '';

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

			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($this->request->get['manufacturer_id']);

			if ($manufacturer_info) {
				$data['breadcrumbs'][] = array(
					'text' => $manufacturer_info['name'],
					'href' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . $url)
				);
			}
		}

		if (isset($this->request->get['search']) || isset($this->request->get['tag'])) {
			$url = '';

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
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
				'text' => $this->language->get('text_search'),
				'href' => $this->url->link('product/search', $url)
			);
		}

		if (isset($this->request->get['product_id'])) {
			$product_id = (int)$this->request->get['product_id'];
		} else {
			$product_id = 0;
		}

		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id);

		if ($product_info) {

            //# <!-- Архивный товар
            $data['archive_category_id'] = 0;

            if (isset($category_id) && !empty($archive['archive']) && isset($this->request->get['path'])) {
                $parts = explode('_', (string)$this->request->get['path']);

                rsort($parts);

                foreach ($parts as $part) {
                    $query = $this->db->query("SELECT `category_id` FROM `" . DB_PREFIX . "category` WHERE `category_id` = '" . (int)$part . "' AND `status` = '1'");

                    if ($query->num_rows) {
                        $data['archive_category_id'] = $query->row['category_id'];

                        break;
                    }
                }

                if ($data['archive_category_id']) {
                    $data['archive_not_available_btn_category'] = $this->url->link('product/category', 'path=' . $data['archive_category_id'], true);
                }

                $this->db->query("UPDATE `" . DB_PREFIX . "product` SET `status` = '0' WHERE `product_id` = '" . (int)$product_id . "'");
            }
            //# Архивный товар -->

            /*if (isset($_SERVER['REMOTE_ADDR']) and $_SERVER['REMOTE_ADDR'] == '46.150.81.158') {
                $data['dev'] = true;
            } else {
                $data['dev'] = false;
            }*/

            $this->document->addStyle('catalog/view/javascript/OwlCarousel2/assets/owl.carousel.min.css');
            $this->document->addStyle('catalog/view/javascript/OwlCarousel2/assets/owl.theme.default.min.css');
            $this->document->addScript('catalog/view/javascript/OwlCarousel2/owl.carousel.min.js');

            $url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
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
				'text' => $product_info['name'],
				'href' => $this->url->link('product/product', $url . '&product_id=' . $this->request->get['product_id'])
			);

			if ($product_info['meta_title']) {
				$this->document->setTitle($product_info['meta_title']);
			} else {
				$this->document->setTitle($product_info['name']);
			}

			if ($product_info['noindex'] <= 0 && $this->config->get('config_noindex_status')) {
				$this->document->setRobots('noindex,follow');
			}

			if ($product_info['meta_h1']) {
				$data['heading_title'] = $product_info['meta_h1'];
			} else {
				$data['heading_title'] = $product_info['name'];
			}

			$this->document->setDescription($product_info['meta_description']);
			$this->document->setKeywords($product_info['meta_keyword']);
			$this->document->addLink($this->url->link('product/product', 'product_id=' . $this->request->get['product_id']), 'canonical');
			//$this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
			//$this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
			//$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment.min.js');
			//$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment-with-locales.min.js');
			//$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
			//$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

			$data['text_minimum'] = sprintf($this->language->get('text_minimum'), $product_info['minimum']);
			$data['text_login'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', true), $this->url->link('account/register', '', true));

			$this->load->model('catalog/review');

			// $data['tab_review'] = sprintf($this->language->get('tab_review'), $product_info['reviews']);

			$data['product_id'] = (int)$this->request->get['product_id'];
			$data['manufacturer'] = $product_info['manufacturer'];
			// $data['manufacturers'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $product_info['manufacturer_id']);
            $manufacturer_info = $this->model_catalog_manufacturer->getManufacturerByCategory($product_info['manufacturer_id']);
            if ($manufacturer_info) {
                $data['manufacturers'] = $this->url->link('product/category', 'path=' . $manufacturer_info['category_id'], true);
            }
			$data['model'] = $product_info['model'];
			$data['reward'] = $product_info['reward'];
			$data['points'] = $product_info['points'];
			$data['description'] = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');

			if ($product_info['quantity'] <= 0) {
				$data['stock'] = $product_info['stock_status'];
			} elseif ($this->config->get('config_stock_display')) {
				$data['stock'] = $product_info['quantity'];
			} else {
				$data['stock'] = $this->language->get('text_instock');
			}

			$this->load->model('tool/image');

            /*
			if ($product_info['image']) {
				$data['popup'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_height'));
			} else {
				$data['popup'] = '';
			}

			if ($product_info['image']) {
				$data['thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_height'));
			} else {
				$data['thumb'] = '';
			}

			$data['images'] = array();

			$results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);

			foreach ($results as $result) {
				$data['images'][] = array(
					'popup' => $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_height')),
					'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_additional_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_additional_height'))
				);
			}
            */

            $data['gift_card'] = false;

            $data['image_w'] = 737;
            $data['image_h'] = 1105;

            $data['images'] = [];
            $data['images_o'] = [];

            if ($product_info['image']) {
                if (isset($category_id) && $category_id == 206) {
                    $data['gift_card'] = true;

                    $data['image_w'] = 287;
                    $data['image_h'] = 170;
                }

                $data['images'][0] = $this->model_tool_image->resize($product_info['image'], $data['image_w'], $data['image_h'], 'auto');
                $data['images_o'][0] = HTTPS_SERVER . 'image/' . $product_info['image'];
            }

            $results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);

            foreach ($results as $result) {
                $data['images'][$result['product_image_id']] = $this->model_tool_image->resize($result['image'], $data['image_w'], $data['image_h'], 'auto');
                $data['images_o'][$result['product_image_id']] = HTTPS_SERVER . 'image/' . $result['image'];
            }

            $data['currency_symbol'] = $this->currency->getSymbolRight($this->session->data['currency']);

			if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
				$data['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                $data['price_not_format'] = $this->currency->format(
                    $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')),
                    $this->session->data['currency'],
                    '',
                    false
                );
			} else {
				$data['price'] = false;
                $data['price_not_format'] = 0;
			}

			if (!is_null($product_info['special']) && (float)$product_info['special'] >= 0) {
				$data['special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                $data['special_not_format'] = $this->currency->format(
                    $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')),
                    $this->session->data['currency'],
                    '',
                    false
                );
			} else {
				$data['special'] = false;
                $data['special_not_format'] = 0;
			}

            /*
			$discounts = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);

			$data['discounts'] = array();

			foreach ($discounts as $discount) {
				$data['discounts'][] = array(
					'quantity' => $discount['quantity'],
					'price'    => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'])
				);
			}
            */

			$data['options'] = array();

			foreach ($this->model_catalog_product->getProductOptions($this->request->get['product_id']) as $option) {
				$product_option_value_data = array();

				foreach ($option['product_option_value'] as $option_value) {
					if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
						if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
							$price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false), $this->session->data['currency']);
                            $price_not_format = $this->currency->format(
                                $this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false),
                                $this->session->data['currency'],
                                '',
                                false
                            );
						} else {
							$price = false;
                            $price_not_format = 0;
						}

						$product_option_value_data[] = array(
							'product_option_value_id' => $option_value['product_option_value_id'],
							'option_value_id'         => $option_value['option_value_id'],
							'name'                    => $option_value['name'],
							'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),
							'price'                   => $price,
                            'price_not_format'        => $price_not_format,
							'price_prefix'            => $option_value['price_prefix']
						);
					}
				}

				$data['options'][] = array(
					'product_option_id'    => $option['product_option_id'],
					'product_option_value' => $product_option_value_data,
					'option_id'            => $option['option_id'],
					'name'                 => $option['name'],
					'type'                 => $option['type'],
					'value'                => $option['value'],
					'required'             => $option['required']
				);
			}

			if ($product_info['minimum']) {
				$data['minimum'] = $product_info['minimum'];
			} else {
				$data['minimum'] = 1;
			}

            /*
			$data['review_status'] = $this->config->get('config_review_status');

			if ($this->config->get('config_review_guest') || $this->customer->isLogged()) {
				$data['review_guest'] = true;
			} else {
				$data['review_guest'] = false;
			}
            */

			if ($this->customer->isLogged()) {
				$data['customer_name'] = $this->customer->getFirstName() . '&nbsp;' . $this->customer->getLastName();
			} else {
				$data['customer_name'] = '';
			}

			// $data['reviews'] = sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']);
			// $data['rating'] = (int)$product_info['rating'];

			// Captcha
            /*
			if ($this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
				$data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'));
			} else {
				$data['captcha'] = '';
			}
            */

			// $data['share'] = $this->url->link('product/product', 'product_id=' . (int)$this->request->get['product_id']);

			$data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);

            $data['product_colors'] = [];

            $results = $this->model_catalog_product->getProductColor($product_id);

            foreach ($results as $result) {
                if ($result['image']) {
                    $image = $this->model_tool_image->resize($result['image'], 235, 352, 'auto');
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', 235, 352, 'auto');
                }

                $data['product_colors'][] = array(
                    'name'          => $result['name'],
                    'thumb'         => $image,
                    'href'          => $this->url->link('product/product', 'product_id=' . $result['product_id'])
                );
            }

            $data['kit_products'] = [];

            $results = $this->model_catalog_product->getProductKit($product_id);

            if ($results) {
                $total      = 0;
                $image_w    = 365;
                $image_h    = 547;

                if ($product_info['image']) {
                    $image = $this->model_tool_image->resize($product_info['image'], $image_w, $image_h, 'auto');
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', $image_w, $image_h, 'auto');
                }

                if (!is_null($product_info['special']) && (float)$product_info['special'] >= 0) {
                    $total += $product_info['special'];
                } else {
                    $total += $product_info['price'];
                }

                $product_options = $this->model_catalog_product->getProductThumbOptions($product_id);

                $data['kit_products'][] = [
                    'product_id'  => $product_id,
                    'thumb'       => $image,
                    'thumb_w'     => $image_w,
                    'thumb_h'     => $image_h,
                    'name'        => $product_info['name'],
                    'price'       => $data['price'],
                    'special'     => $data['special'],
                    'options'     => $product_options,
                    'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
                ];

                foreach ($results as $kit_id => $id) {
                    if ($kit_id == $product_id) {
                        $kit_product = $this->model_catalog_product->getProduct($id);
                    } else {
                        $kit_product = $this->model_catalog_product->getProduct($kit_id);
                    }

                    if ($kit_product) {
                        if ($kit_product['image']) {
                            $image = $this->model_tool_image->resize($kit_product['image'], $image_w, $image_h, 'auto');
                        } else {
                            $image = $this->model_tool_image->resize('placeholder.png', $image_w, $image_h, 'auto');
                        }

                        if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                            $price = $this->currency->format($this->tax->calculate($kit_product['price'], $kit_product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                        } else {
                            $price = false;
                        }

                        if (!is_null($kit_product['special']) && (float)$kit_product['special'] >= 0) {
                            $special = $this->currency->format($this->tax->calculate($kit_product['special'], $kit_product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                        } else {
                            $special = false;
                        }

                        if ($special) {
                            $total += $kit_product['special'];
                        } else {
                            $total += $kit_product['price'];
                        }

                        $product_options = $this->model_catalog_product->getProductThumbOptions($kit_product['product_id']);

                        $data['kit_products'][] = [
                            'product_id' => $kit_product['product_id'],
                            'thumb' => $image,
                            'thumb_w' => $image_w,
                            'thumb_h' => $image_h,
                            'name' => $kit_product['name'],
                            'price' => $price,
                            'special' => $special,
                            'options' => $product_options,
                            'href' => $this->url->link('product/product', 'product_id=' . $kit_product['product_id'])
                        ];
                    } else {
                        $data['kit_products'] = [];
                    }
                }

                $data['kit_total'] = $this->currency->format($total, $this->session->data['currency']);
            }

            $image_w    = 336;
            $image_h    = 504;

			$data['products'] = array();

			$results = $this->model_catalog_product->getProductRelated($this->request->get['product_id']);

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
					$tax_price = (float)$result['special'];
				} else {
					$special = false;
					$tax_price = (float)$result['price'];
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

            // Complete Your Look
            $data['cyl_data'] = [];

            // if (isset($this->request->server['REMOTE_ADDR']) && $this->request->server['REMOTE_ADDR'] == '93.72.114.145') {
            if (!empty($product_info['cyl_id'])) {
                $data['cyl_data'] = $this->getLook($product_info['cyl_id']);
            }
            // }

            $this->load->model('catalog/information');

            $data['payment_and_delivery'] = $this->model_catalog_information->getInformation(9);

            if (!empty($data['payment_and_delivery']['description'])) {
                $data['payment_and_delivery'] = html_entity_decode($data['payment_and_delivery']['description'], ENT_QUOTES, 'UTF-8');
            } else {
                $data['payment_and_delivery'] = '';
            }

            $data['size_description'] = '';

            if (isset($this->request->get['path'])) {
                $path = '';

                $parts = explode('_', (string)$this->request->get['path']);

                $category_id = (int)array_pop($parts);

                $this->load->model('extension/module/size');

                $size_id = $this->model_extension_module_size->getCategorySize($category_id);

                $size = $this->model_extension_module_size->getSizeDescriptions($size_id);

                $data['size_description'] = $size ? html_entity_decode($size['description'], ENT_QUOTES, 'UTF-8') : '';
            }

			$this->load->model('account/wishlist');

			$data['is_wishlist']=$this->model_account_wishlist->checkProductInWishlist($this->request->get['product_id']);

            $this->model_catalog_product->updateViewed($this->request->get['product_id']);

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('product/product', $data));
		} else {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
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
				'href' => $this->url->link('product/product', $url . '&product_id=' . $product_id)
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

	public function review() {
		$this->load->language('product/product');

		$this->load->model('catalog/review');

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['reviews'] = array();

		$review_total = $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['product_id']);

		$results = $this->model_catalog_review->getReviewsByProductId($this->request->get['product_id'], ($page - 1) * 5, 5);

		foreach ($results as $result) {
			$data['reviews'][] = array(
				'author'     => $result['author'],
				'text'       => nl2br($result['text']),
				'rating'     => (int)$result['rating'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}

		$pagination = new Pagination();
		$pagination->total = $review_total;
		$pagination->page = $page;
		$pagination->limit = 5;
		$pagination->url = $this->url->link('product/product/review', 'product_id=' . $this->request->get['product_id'] . '&page={page}');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($review_total) ? (($page - 1) * 5) + 1 : 0, ((($page - 1) * 5) > ($review_total - 5)) ? $review_total : ((($page - 1) * 5) + 5), $review_total, ceil($review_total / 5));

		$this->response->setOutput($this->load->view('product/review', $data));
	}

	public function write() {
		$this->load->language('product/product');

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
				$json['error'] = $this->language->get('error_name');
			}

			if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 1000)) {
				$json['error'] = $this->language->get('error_text');
			}

			if (empty($this->request->post['rating']) || $this->request->post['rating'] < 0 || $this->request->post['rating'] > 5) {
				$json['error'] = $this->language->get('error_rating');
			}

			// Captcha
			if ($this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
				$captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');

				if ($captcha) {
					$json['error'] = $captcha;
				}
			}

			if (!isset($json['error'])) {
				$this->load->model('catalog/review');

				$this->model_catalog_review->addReview($this->request->get['product_id'], $this->request->post);

				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function getRecurringDescription() {
		$this->load->language('product/product');
		$this->load->model('catalog/product');

		if (isset($this->request->post['product_id'])) {
			$product_id = $this->request->post['product_id'];
		} else {
			$product_id = 0;
		}

		if (isset($this->request->post['recurring_id'])) {
			$recurring_id = $this->request->post['recurring_id'];
		} else {
			$recurring_id = 0;
		}

		if (isset($this->request->post['quantity'])) {
			$quantity = $this->request->post['quantity'];
		} else {
			$quantity = 1;
		}

		$product_info = $this->model_catalog_product->getProduct($product_id);

		$recurring_info = $this->model_catalog_product->getProfile($product_id, $recurring_id);

		$json = array();

		if ($product_info && $recurring_info) {
			if (!$json) {
				$frequencies = array(
					'day'        => $this->language->get('text_day'),
					'week'       => $this->language->get('text_week'),
					'semi_month' => $this->language->get('text_semi_month'),
					'month'      => $this->language->get('text_month'),
					'year'       => $this->language->get('text_year'),
				);

				if ($recurring_info['trial_status'] == 1) {
					$price = $this->currency->format($this->tax->calculate($recurring_info['trial_price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					$trial_text = sprintf($this->language->get('text_trial_description'), $price, $recurring_info['trial_cycle'], $frequencies[$recurring_info['trial_frequency']], $recurring_info['trial_duration']) . ' ';
				} else {
					$trial_text = '';
				}

				$price = $this->currency->format($this->tax->calculate($recurring_info['price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);

				if ($recurring_info['duration']) {
					$text = $trial_text . sprintf($this->language->get('text_payment_description'), $price, $recurring_info['cycle'], $frequencies[$recurring_info['frequency']], $recurring_info['duration']);
				} else {
					$text = $trial_text . sprintf($this->language->get('text_payment_cancel'), $price, $recurring_info['cycle'], $frequencies[$recurring_info['frequency']], $recurring_info['duration']);
				}

				$json['success'] = $text;
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

    public function getLook($cyl_id) {
        $return_data = [];

        $cyl_image_w = 336;
        $cyl_image_h = 504;

        $cyl_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "complete_your_look` WHERE `cyl_id` = '" . (int)$cyl_id . "' AND status = '1'");

        if ($cyl_query->num_rows) {
            $cyl_data = json_decode($cyl_query->row['data'] , true);

            foreach ($cyl_data as $position_key => $cyl) {
                if ($position_key != 'c' && $cyl['p_id']) {
                    $cyl_product_info = $this->model_catalog_product->getProduct($cyl['p_id']);

                    if ($cyl_product_info) {
                        if (!empty($cyl['im']) && is_file(DIR_IMAGE . $cyl['im'])) {
                            $cyl_image = $this->model_tool_image->resize($cyl['im'], $cyl_image_w, $cyl_image_h, 'auto');
                        } else {

                            if (!empty($cyl_product_info['image']) && is_file(DIR_IMAGE . $cyl_product_info['image'])) {
                                $cyl_image = $this->model_tool_image->resize($cyl_product_info['image'], $cyl_image_w, $cyl_image_h, 'auto');
                            } else {
                                $cyl_image = $this->model_tool_image->resize('no_image.png', $cyl_image_w, $cyl_image_h, 'auto');
                            }
                        }

                        if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                            $price = $this->currency->format($this->tax->calculate($cyl_product_info['price'], $cyl_product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                        } else {
                            $price = false;
                        }

                        if (!is_null($cyl_product_info['special']) && (float)$cyl_product_info['special'] >= 0) {
                            $special = $this->currency->format($this->tax->calculate($cyl_product_info['special'], $cyl_product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                        } else {
                            $special = false;
                        }

                        $product_options = $this->model_catalog_product->getProductThumbOptions($cyl_product_info['product_id']);

                        $return_data[$position_key] = [
                            'product_id'    => $cyl_product_info['product_id'],
                            'thumb'         => $cyl_image,
                            'thumb_w'       => $cyl_image_w,
                            'thumb_h'       => $cyl_image_h,
                            'name'          => $cyl_product_info['name'],
                            'price'         => $price,
                            'special'       => $special,
                            'options'       => $product_options,
                            'href'          => $this->url->link('product/product', 'product_id=' . $cyl_product_info['product_id'])
                        ];

                        foreach ($cyl as $key => $value) {
                            $return_data[$position_key][$key] = $value;
                        }
                    }
                }

                if ($position_key == 'c') {
                    if (!empty($cyl['im']) && is_file(DIR_IMAGE . $cyl['im'])) {
                            $image_size = getimagesize(DIR_IMAGE . $cyl['im']);

                            if (!empty($image_size[0]) && !empty($image_size[1]) && !empty($image_size[3])) {
                                $cyl_image = $this->model_tool_image->resize($cyl['im'], $image_size[0], $image_size[1]);
                            } else {
                                $cyl_image = '';
                            }

                    } else {
                        $cyl_image = '';
                    }

                    $return_data[$position_key] = [
                        'thumb'     => $cyl_image,
                        'height'    => !empty($image_size[1]) ? ($image_size[1]) : '522'
                    ];
                }
            }
        }

        if (count($return_data) >= 4) {
            return $return_data;
        }

        return false;
    }
}
