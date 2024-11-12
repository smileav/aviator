<?php

	class ControllerExtensionFeedRozetkaFeed extends Controller {
        private $get = [];
        private $file = '';

		public function index() {

			if ($this->config->get('rozetka_feed_status')) {

				$this->load->model('extension/feed/rozetka_feed');

                if (!empty($this->request->get['m'])) {
                    $this->get = explode(',', $this->request->get['m']);

                    $this->file = 'rozetka_feed_';

                    foreach ($this->get as $key => $value) {
                        $this->get[$key] = (int)$value;

                        $this->file .= (int)$value . '_';
                    }

                    $this->file = rtrim($this->file, '_') . '.xml';
                }

				$store_name = $this->config->get('rozetka_feed_store_name');
				$store_url = $this->config->get('rozetka_feed_store_url');
				$base_url = $this->config->get('config_url');
				$export_date = date('Y-m-d H:i');
				$banned_vendors = $this->model_extension_feed_rozetka_feed->getBannedVendors();


				$yml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
				$yml .= '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">' . "\n";
				$yml .= '<yml_catalog date="' . $export_date . '">' . "\n";
				$yml .= '<shop>' . "\n";
				$yml .= '<name>' . $store_name . '</name>' . "\n";
				$yml .= '<company>' . $store_name . '</company>' . "\n";
				$yml .= '<url>' . $store_url . '</url>' . "\n";
				$yml .= '<currencies>' . "\n";
				$yml .= "\t" . '<currency id="UAH" rate="1"/>' . "\n";
				$yml .= '</currencies>' . "\n";
				$yml .= '<categories>' . "\n";

				$category_list = $this->model_extension_feed_rozetka_feed->getCategories();

				foreach ($category_list as $category) {

                    if ($category['manufacturer_id'] || $category['sort_order'] == '9999') {
                        continue;
                    }

					$rozetka_category = $this->model_extension_feed_rozetka_feed->getRozetkaCategory($category['category_id']);

					if (isset($rozetka_category) && !empty($rozetka_category)) {
						$rz_id = 'rz_id = "' . $rozetka_category['rz_id'] . '" ';
					} else {
						$rz_id = '';
					}

					if ($category['parent_id'] > 0) {

						$category_parent_attr = ' parentId="' . $category['parent_id'] . '"';

					} else {

						$category_parent_attr = '';

					}

					$yml .= "\t" . '<category id="' . $category['category_id'] . '" '. $rz_id . $category_parent_attr .'>' . $category['name'] . '</category>' . "\n";

				}

				$yml .= '</categories>' . "\n";

				$this->load->model('catalog/manufacturer');
				$this->load->model('catalog/product');

				$product_list = $this->model_extension_feed_rozetka_feed->getProducts();

				$yml .= '<offers>' . "\n";

				foreach ($product_list as $product) {
					$product_link = $this->url->link('product/product', 'product_id=' . $product['product_id']);
					$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product['manufacturer_id']);
					$product_special = $this->model_extension_feed_rozetka_feed->getProductSpecial($product['product_id']);
					$product_attributes_array = $this->model_catalog_product->getProductAttributes($product['product_id']);
					$product_oprions_array = $this->model_catalog_product->getProductOptions($product['product_id']);

                    if (!isset($manufacturer_info['manufacturer_id'])) {
                        continue;
                    }

					//# if (in_array($manufacturer_info['manufacturer_id'], $banned_vendors)) {
					if (in_array($manufacturer_info['manufacturer_id'], $banned_vendors) && !$this->get) {
						continue;
					}

                    if (!in_array($manufacturer_info['manufacturer_id'], $this->get) && $this->get) {
                        continue;
                    }

					if ($product['quantity'] == 0 || $product['archive'] == 1) {
						$product_availability = 'false';
					} else {
						$product_availability = 'true';
					}

					$product_vendor = $manufacturer_info['name'];

					if (!empty($product_oprions_array)) {
						foreach ($product_oprions_array as $product_option) {
							foreach ($product_option['product_option_value'] as $product_option_value) {
							if ($product_option_value['quantity'] > 0) {
								$option_availability = 'true';
							} else {
								$option_availability = 'false';
							}
							$yml .= '<offer id="999' . $product['product_id'] . $product_option_value['name'] .'" available="' . $option_availability . '">' . "\n";
							$yml .= "\t" . '<url>' . $product_link . '</url>' . "\n";
							if ($product_special) {
								$yml .= "\t" . '<price>' . round($product_special['price']) . '</price>' . "\n";
								$yml .= "\t" . '<price_old>' . round($product['price']) . '</price_old>' . "\n";
							} else {
								$yml .= "\t" . '<price>' . round($product['price']) . '</price>' . "\n";
							}
							$yml .= "\t" . '<currencyId>UAH</currencyId>' . "\n";
							$yml .= "\t" . '<stock_quantity>' . $product_option_value['quantity'] . '</stock_quantity>' . "\n";
							$yml .= "\t" . '<name>' . $this->utf2xml($product['name']) . ' ' . $product_option_value['name'] . '</name>' . "\n";
							$yml .= "\t" . '<description>' . $this->cdata($this->utf2xml($product['description'])) . '</description>' . "\n";
							$yml .= "\t" . '<vendor>' . htmlspecialchars($this->utf2xml($product_vendor)) . '</vendor>' . "\n";
							$yml .= "\t" . '<picture>' . $base_url . 'image/' . $this->removespace($product['image']) . '</picture>' . "\n";
							$yml .= "\t" . '<vendorCode>' . $product['model'] . '</vendorCode>' . "\n";

                            //# ++
                            $yml .= '<param name="Модель">' . $product['model'] . '</param>' . "\n";
                            //# ++

                            $yml .= '<param name="' . $product_option['name'] . '">' . $product_option_value['name'] . '</param>' . "\n";
							foreach($product_attributes_array as $attribute_group) {
								foreach($attribute_group['attribute'] as $attribute) {
									$yml .= '<param name="' . $attribute['name'] . '">' . $attribute['text'] . '</param>' . "\n";
								}
							}

							$product_additional_images = $this->model_extension_feed_rozetka_feed->getProductImages($product['product_id']);

							foreach($product_additional_images as $product_additional_image) {
								$yml .= "\t" . '<picture>' . $base_url . 'image/' . $this->removespace($product_additional_image['image']) . '</picture>' . "\n";
							}

							$product_categories_array = $this->model_extension_feed_rozetka_feed->getProductCategories($product['product_id']);

							$product_categories = array();

							if ($product_categories_array) {
								foreach($product_categories_array as $product_category) {
									$product_categories[] = $product_category['category_id'];
								}
							}

							$yml .= "\t" . '<categoryId>' . $product_categories[0] . '</categoryId>' . "\n";

							$yml .= '</offer>' . "\n";
						}
					}
					} else {
						$yml .= '<offer id="999' . $product['product_id'] . '" available="' . $product_availability . '">' . "\n";
							$yml .= "\t" . '<url>' . $product_link . '</url>' . "\n";
							if ($product_special) {
								$yml .= "\t" . '<price>' . round($product_special['price']) . '</price>' . "\n";
								$yml .= "\t" . '<price_old>' . round($product['price']) . '</price_old>' . "\n";
							} else {
								$yml .= "\t" . '<price>' . round($product['price']) . '</price>' . "\n";
							}

							$yml .= "\t" . '<currencyId>UAH</currencyId>' . "\n";
							$yml .= "\t" . '<stock_quantity>' . $product['quantity'] . '</stock_quantity>' . "\n";
							$yml .= "\t" . '<name>' . $this->utf2xml($product['name']) . '</name>' . "\n";
							$yml .= "\t" . '<description>' . $this->cdata($this->utf2xml($product['description'])) . '</description>' . "\n";
							$yml .= "\t" . '<vendor>' . htmlspecialchars($this->utf2xml($product_vendor)) . '</vendor>' . "\n";
							$yml .= "\t" . '<picture>' . $base_url . 'image/' . $this->removespace($product['image']) . '</picture>' . "\n";
							$yml .= "\t" . '<vendorCode>' . $product['model'] . '</vendorCode>' . "\n";
							foreach($product_attributes_array as $attribute_group) {

								foreach($attribute_group['attribute'] as $attribute) {

									$yml .= '<param name="' . $attribute['name'] . '">' . $attribute['text'] . '</param>' . "\n";

								}

							}

							$product_additional_images = $this->model_extension_feed_rozetka_feed->getProductImages($product['product_id']);

							foreach($product_additional_images as $product_additional_image) {

								$yml .= "\t" . '<picture>' . $base_url . 'image/' . $this->removespace($product_additional_image['image']) . '</picture>' . "\n";

							}

							$product_categories_array = $this->model_extension_feed_rozetka_feed->getProductCategories($product['product_id']);

							$product_categories = array();

							if ($product_categories_array) {

								foreach($product_categories_array as $product_category) {

									$product_categories[] = $product_category['category_id'];

								}

							}

							$yml .= "\t" . '<categoryId>' . $product_categories[0] . '</categoryId>' . "\n";

							$yml .= '</offer>' . "\n";
					}

				}

				$yml .= '</offers>' . "\n";
				$yml .= '</shop>' . "\n";
				$yml .= '</yml_catalog>';

				$this->write($yml);

			} else {

				exit("Модуль отключён.");

			}

		}

		private function recount($value) {
/*
			$arg = 0;

			if ($value > 0 && $value <= 35) {

				$arg = 22;

			} elseif ($value > 35 && $value <= 70) {

				$arg = 29;

			} elseif ($value > 70 && $value <= 100) {

				$arg = 40;

			} elseif ($value > 100 && $value <= 150) {

				$arg = 53;

			} elseif ($value > 150 && $value <= 250) {

				$arg = 62;

			} elseif ($value > 250 && $value <= 325) {

				$arg = 75;

			} elseif ($value > 325 && $value <= 400) {

				$arg = 83;

			} elseif ($value > 400 && $value <= 500) {
+
				$arg = 91;

			} elseif ($value > 500) {

				$arg = 110;

			} else {

				return $price = $value;

			}
*/
			$price = round(($value / 0.75) + 20, 0);

			return $price;

		}

		private function utf2xml($value) {
			return preg_replace ('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $value);
		}

		private function removespace($value) {
			return str_replace(' ', '%20', $value);
		}

		private function write($xml) {
			$path = $_SERVER['DOCUMENT_ROOT'] . '/xml/';

			$file = 'rozetka_feed.xml';

            if ($this->file) {
                $file = $this->file;
            }

			$feed = $path . $file;

			$url = '<a href="' . $this->config->get('config_url') . 'xml/' . $file . '">' . $this->config->get('config_url') . 'xml/' . $file . '</a>';

			if (!file_exists($path)) {
				mkdir($path, 0755, true);
			}

			$stream = file_put_contents($feed, $xml);

			echo 'Файл был успешно записан! Прямая ссылка на файл: ' . $url;

		}

		private function cdata($string) {
			$string = html_entity_decode($string);
			return sprintf('<![CDATA[%s]]>',$string);
		}

	}

?>
