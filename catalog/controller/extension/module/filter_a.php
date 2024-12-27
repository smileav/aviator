<?php
class ControllerExtensionModuleFilterA extends Controller {

    private $fia;
    private $fia_ALL;
    private $fia_GET = [];
    private $fia_GET_flip = [];

    private $cache_unique = '';
    private $category_id = 0;
    private $search = false;
    private $special = false;

    private $min_price_ALL = 0;
    private $max_price_ALL = 0;
    private $min_price = 0;
    private $max_price = 0;

    public function index(): array
    {
        $this->load->language('extension/module/filter_a');

        $this->load->model('extension/module/filter_a');

        /*
        $this->document->addStyle('catalog/view/theme/aviator/stylesheet/filter_a.css');
        $this->document->addStyle('catalog/view/theme/aviator/js/noUiSlider/dist/nouislider.min.css');

        $this->document->addScript('catalog/view/theme/aviator/js/filter_a.js');
        $this->document->addScript('catalog/view/theme/aviator/js/noUiSlider/dist/nouislider.min.js');
        $this->document->addScript('catalog/view/theme/aviator/js/wNumb.min.js');
        */

        if (isset($this->request->get['path'])) {
            $parts = explode('_', (string)$this->request->get['path']);
            $this->category_id = (int)array_pop($parts);
        } else {
            $this->category_id = 0;
        }

        if (isset($this->request->get['search'])) {
            $this->search = $this->request->get['search'];
        }

        if (isset($this->request->get['route']) && $this->request->get['route'] == 'product/special') {
            $this->special = true;
        }

        foreach ($this->request->get as $key => $value) {
            switch ($key) {
                case 'G':
                    $this->fia['G'] = [];
                    $this->fia_GET['G'] = explode(',', str_replace ( '/', '', $this->request->get['G']));
                    $this->fia_GET_flip['G'] = array_flip($this->fia_GET['G']);
                    break;
                case 'C':
                    $this->fia['C'] = [];
                    $this->fia_GET['C'] = explode(',', str_replace ( '/', '', $this->request->get['C']));
                    $this->fia_GET_flip['C'] = array_flip($this->fia_GET['C']);
                    break;
                case 'M':
                    $this->fia['M'] = [];
                    $this->fia_GET['M'] = explode(',', str_replace ( '/', '', $this->request->get['M']));
                    $this->fia_GET_flip['M'] = array_flip($this->fia_GET['M']);
                    break;
                case 'P':
                    $this->fia['P'] = [];
                    $this->fia_GET['P'] = explode(',', str_replace ( '/', '', $this->request->get['P']));
                    $this->fia_GET_flip['P'] = array_flip($this->fia_GET['P']);
                    break;
                case 'S':
                    $this->fia['S'] = [];
                    $this->fia_GET['S'] = explode(',', str_replace ( '/', '', $this->request->get['S']));
                    $this->fia_GET_flip['S'] = array_flip($this->fia_GET['S']);
                    break;
				case 'CO':
					$this->fia['CO'] = [];
					$this->fia_GET['CO'] = explode(',', str_replace ( '', '', $this->request->get['CO']));
					$this->fia_GET_flip['CO'] = array_flip($this->fia_GET['CO']);
					break;
            }
        }

        if (!empty($this->fia_GET)) {
            $this->document->setRobots('noindex,follow');
        }

        $data['fia_GET'] = $this->fia_GET;

        $config_language_id = (int)$this->config->get('config_language_id');

        $this->fia = [];

        $this->cache_unique = $this->category_id;

        if (!$this->cache_unique && $this->special) {
            //$this->cache_unique = 'special';
        }

        if ($this->cache_unique) {
            $fia_ALL_cache = $this->cache->get('fia_ALL_cache_' . $this->cache_unique . '_' . $config_language_id);
        }

        if (empty($fia_ALL_cache)) {
            $filter_data = [
                'attribute_id' => 59,
                'category_id' => $this->category_id,
                'search' => $this->search,
                'special' => $this->special
            ];

            $this->fia_ALL['G'] = $this->model_extension_module_filter_a->getFilterA_G($filter_data);

			//color
			$filter_data = [
				'attribute_id' => 13,
				'category_id' => $this->category_id,
				'search' => $this->search,
				'special' => $this->special
			];

			$this->fia_ALL['CO'] = $this->model_extension_module_filter_a->getFilterA_CO($filter_data);

            $filter_data = [
                'attribute_id' => 60,
                'category_id' => $this->category_id,
                'search' => $this->search,
                'special' => $this->special
            ];

            $this->fia_ALL['C'] = $this->model_extension_module_filter_a->getFilterA_C($filter_data);

            $filter_data = [
                'option_id' => 1,
                'category_id' => $this->category_id,
                'search' => $this->search,
                'special' => $this->special
            ];

            $this->fia_ALL['S'] = $this->model_extension_module_filter_a->getFilterA_S($filter_data);

            $filter_data = [
                'category_id' => $this->category_id,
                'search' => $this->search,
                'special' => $this->special
            ];

            $this->fia_ALL['M'] = $this->model_extension_module_filter_a->getFilterA_M($filter_data);

            if ($this->cache_unique) {
                $this->cache->set('fia_ALL_cache_' . $this->cache_unique . '_' . $config_language_id, $this->fia_ALL);
            }

        } else {
            $this->fia_ALL = $fia_ALL_cache;
        }

        foreach ($this->fia_GET as $fia_GET_key => $fia_GET_value) {
            if ($fia_GET_key == 'P') {
                continue;
            }

            $this->{'formatFilterA_Data_' . $fia_GET_key}($fia_GET_key);
        }

        if (empty($this->fia_GET) && $this->cache_unique) {
            $fia_EMPTY_cache = $this->cache->get('fia_EMPTY_cache_' . $this->cache_unique . '_' . $config_language_id);
        }

        if (empty($fia_EMPTY_cache)) {
            if (empty($this->fia_GET['G'])) {
                $this->formatFilterA_Data_G('G');
            }

			if (empty($this->fia_GET['CO'])) {
				$this->formatFilterA_Data_CO('CO');
			}
            if (empty($this->fia_GET['C'])) {
                $this->formatFilterA_Data_C('C');
            }

            if (empty($this->fia_GET['S'])) {
                $this->formatFilterA_Data_S('S');
            }

            if (empty($this->fia_GET['M'])) {
                $this->formatFilterA_Data_M('M');
            }

            unset($this->fia['G']['G=']);
			unset($this->fia['CO']['CO=']);
			unset($this->fia['C']['C=']);
            unset($this->fia['S']['S=']);
            unset($this->fia['M']['M=']);

            if (empty($this->fia_GET) && $this->cache_unique) {
                $this->cache->set('fia_EMPTY_cache_' . $this->cache_unique . '_' . $config_language_id, $this->fia);
            }
        } else {
            $this->fia = $fia_EMPTY_cache;
        }

        if ($this->category_id) {
            $fia_PRICE_cache = $this->cache->get('fia_PRICE_cache');

            if (!$fia_PRICE_cache) {
                $this->getFilterA_Data_priceALL();

                $fia_PRICE_cache[$this->category_id] = [
                    'min_price' => $this->min_price_ALL,
                    'max_price' => $this->max_price_ALL
                ];

                $this->cache->set('fia_PRICE_cache', $fia_PRICE_cache);
            } else {
                if (!isset($fia_PRICE_cache[$this->category_id])) {
                    $this->getFilterA_Data_priceALL();

                    $fia_PRICE_cache[$this->category_id] = [
                        'min_price' => $this->min_price_ALL,
                        'max_price' => $this->max_price_ALL
                    ];

                    $this->cache->set('fia_PRICE_cache', $fia_PRICE_cache);
                } else {
                    $this->min_price_ALL = $fia_PRICE_cache[$this->category_id]['min_price'];
                    $this->max_price_ALL = $fia_PRICE_cache[$this->category_id]['max_price'];
                }
            }
        } else {
            $this->getFilterA_Data_priceALL();
        }



        $this->fia['min_price_ALL'] = $this->min_price_ALL;
        $this->fia['max_price_ALL'] = $this->max_price_ALL;

        $this->fia['min_price_ALL'] = floor($this->fia['min_price_ALL'] / 100) * 100;
        $this->fia['max_price_ALL'] = ceil($this->fia['max_price_ALL'] / 100) * 100;


        if (!$this->min_price || !$this->max_price) {
            $this->fia['min_price'] = $this->min_price_ALL;
            $this->fia['max_price'] = $this->max_price_ALL;
        } else {
            $this->fia['min_price'] = $this->min_price;
            $this->fia['max_price'] = $this->max_price;
        }

        $this->fia['min_price'] = floor($this->fia['min_price'] / 100) * 100;
        $this->fia['max_price'] = ceil($this->fia['max_price'] / 100) * 100;

        // $this->fia['min_price_ALL_nf'] = number_format($this->fia['min_price_ALL'], 0, '', ' ');
        // $this->fia['max_price_ALL_nf'] = number_format($this->fia['max_price_ALL'], 0, '', ' ');

        $data['min_price_nf'] = number_format($this->fia['min_price'], 0, '', ' ');
        $data['max_price_nf'] = number_format($this->fia['max_price'], 0, '', ' ');

        $data['fia'] = $this->fia;

        // Limit
        if (isset($this->request->get['limit'])) {
            $limit = $this->request->get['limit'];
        } else {
            $limit = 24;
        }

        $data['limit'] = sprintf($this->language->get('text_fia_limit'), $limit);

        // Grid
        if (isset($this->session->data['fiaGrid'])) {
            $fiaGrid = (int)$this->session->data['fiaGrid'];
        } else {
            $fiaGrid = 4;
        }

        $data['grid'] = $fiaGrid;

        $this->session->data['fiaGrid'] = $fiaGrid;

        return $data;
    }

    public function getFilterA_Data_priceALL() {
        $filter_data = [
            'category_id'   => $this->category_id,
            'search'   => $this->search,
            'special'   => $this->special,
            'key'           => 'G',
            'price_ALL'     => true,
            'attribute_id'  => 59
        ];

        $result = $this->model_extension_module_filter_a->getFilterA_CD($filter_data);

        $this->min_price_ALL = $result['min_price'];
        $this->max_price_ALL = $result['max_price'];

        unset($result);
    }

    public function formatFilterA_Data_G($fia_GET_key) {
        $this->fia[$fia_GET_key] = [];
        $this->fia[$fia_GET_key]['G='] = [];

        if (isset($this->fia_GET['G'])) {
            $fia_GET_G_flip = array_flip($this->fia_GET['G']);
        } else {
            $fia_GET_G_flip = [];
        }

        if (!empty($this->fia_GET['P'][0]) && !empty($this->fia_GET['P'][1])) {
            $price_MIN_MAX = true;
        } else {
            $price_MIN_MAX = false;
        }

        $filter_data = [
            'category_id'   => $this->category_id,
            'search'   => $this->search,
            'special'   => $this->special,
            'fia_GET'       => $this->fia_GET,
            'key'           => 'G',
            'price_MIN_MAX' => $price_MIN_MAX,
            'attribute_id'  => 59
        ];

        $this->fia[$fia_GET_key]['G='] = $this->model_extension_module_filter_a->getFilterA_CD($filter_data);

        if ($price_MIN_MAX) {
            $this->min_price = (float)$this->fia_GET['P'][0];
            $this->max_price = (float)$this->fia_GET['P'][1];

            unset($this->fia[$fia_GET_key]['G=']['min_price']);
            unset($this->fia[$fia_GET_key]['G=']['max_price']);
        }

        foreach ($this->fia_ALL[$fia_GET_key] as $name => $result) {
            $id = reset($result);

            if (!isset($this->fia[$fia_GET_key][$name])) {
                $this->fia[$fia_GET_key][$name]['id'] = $id;
                $this->fia[$fia_GET_key][$name]['='] = [];
                $this->fia[$fia_GET_key][$name]['+'] = [];
            }

            if (isset($fia_GET_G_flip[$name])) {
                $prefix = '=';
            } else {
                $prefix = '+';
            }

            if (!empty($this->fia[$fia_GET_key]['G='][$name])) {
                foreach ($result as $product_id => $result_id) {

                    if (isset($this->fia[$fia_GET_key]['G='][$name][$product_id])) {
                        $this->fia[$fia_GET_key][$name][$prefix][$product_id] = $id;
                    }

                }
            }
        }
    }

	public function formatFilterA_Data_CO($fia_GET_key) {
		$this->fia[$fia_GET_key] = [];
		$this->fia[$fia_GET_key]['CO='] = [];

		if (isset($this->fia_GET['CO'])) {
			$fia_GET_G_flip = array_flip($this->fia_GET['CO']);
		} else {
			$fia_GET_G_flip = [];
		}

		if (!empty($this->fia_GET['P'][0]) && !empty($this->fia_GET['P'][1])) {
			$price_MIN_MAX = true;
		} else {
			$price_MIN_MAX = false;
		}

		$filter_data = [
			'category_id'   => $this->category_id,
			'search'   => $this->search,
			'special'   => $this->special,
			'fia_GET'       => $this->fia_GET,
			'key'           => 'CO',
			'price_MIN_MAX' => $price_MIN_MAX,
			'attribute_id'  => 13
		];

		$this->fia[$fia_GET_key]['CO='] = $this->model_extension_module_filter_a->getFilterA_CD($filter_data);
//var_dump($this->fia[$fia_GET_key]['CO=']);
		if ($price_MIN_MAX) {
			$this->min_price = (float)$this->fia_GET['P'][0];
			$this->max_price = (float)$this->fia_GET['P'][1];

			unset($this->fia[$fia_GET_key]['CO=']['min_price']);
			unset($this->fia[$fia_GET_key]['CO=']['max_price']);
		}
//var_dump($this->fia_ALL[$fia_GET_key]);
		foreach ($this->fia_ALL[$fia_GET_key] as $name => $result) {
			$id = reset($result);

			if (!isset($this->fia[$fia_GET_key][$name])) {
				$this->fia[$fia_GET_key][$name]['id'] = $id;
				$this->fia[$fia_GET_key][$name]['='] = [];
				$this->fia[$fia_GET_key][$name]['+'] = [];
			}

			if (isset($fia_GET_G_flip[$name])) {
				$prefix = '=';
			} else {
				$prefix = '+';
			}

			if (!empty($this->fia[$fia_GET_key]['CO='][$name])) {
				foreach ($result as $product_id => $result_id) {
					if (isset($this->fia[$fia_GET_key]['CO='][$name][$product_id])) {
						$this->fia[$fia_GET_key][$name][$prefix][$product_id] = $id;
					}

				}
			}
		}
	}

    public function formatFilterA_Data_C($fia_GET_key) {
        $this->fia[$fia_GET_key] = [];
        $this->fia[$fia_GET_key]['C='] = [];

        if (isset($this->fia_GET['C'])) {
            $fia_GET_C_flip = array_flip($this->fia_GET['C']);
        } else {
            $fia_GET_C_flip = [];
        }

        $filter_data = [
            'category_id' => $this->category_id,
            'search' => $this->search,
            'special'   => $this->special,
            'fia_GET' => $this->fia_GET,
            'key' => 'C',
            'attribute_id' => 60
        ];

        $this->fia[$fia_GET_key]['C='] = $this->model_extension_module_filter_a->getFilterA_CD($filter_data);

        foreach ($this->fia_ALL[$fia_GET_key] as $name => $result) {
            $id = reset($result);

            if (!isset($this->fia[$fia_GET_key][$name])) {
                $this->fia[$fia_GET_key][$name]['id'] = $id;
                $this->fia[$fia_GET_key][$name]['='] = [];
                $this->fia[$fia_GET_key][$name]['+'] = [];
            }

            if (isset($fia_GET_C_flip[$name])) {
                $prefix = '=';
            } else {
                $prefix = '+';
            }

            if (!empty($this->fia[$fia_GET_key]['C='][$name])) {

                foreach ($result as $product_id => $result_id) {
                    if (isset($this->fia[$fia_GET_key]['C='][$name][$product_id])) {
                        $this->fia[$fia_GET_key][$name][$prefix][$product_id] = $id;
                    }
                }

            }
        }
    }

    public function formatFilterA_Data_S($fia_GET_key) {
        $this->fia[$fia_GET_key]['S='] = [];

        if (isset($this->fia_GET['S'])) {
            $fia_GET_S_flip = array_flip($this->fia_GET['S']);
        } else {
            $fia_GET_S_flip = [];
        }

        $filter_data = [
            'category_id' => $this->category_id,
            'search' => $this->search,
            'special'   => $this->special,
            'fia_GET' => $this->fia_GET,
            'key' => 'S',
            'attribute_id' => 60
        ];

        $this->fia[$fia_GET_key]['S='] = $this->model_extension_module_filter_a->getFilterA_CD($filter_data);

        foreach ($this->fia_ALL[$fia_GET_key] as $name => $result) {
            $id = reset($result);

            if (!isset($this->fia[$fia_GET_key][$name])) {
                $this->fia[$fia_GET_key][$name]['id']   = $id;
                $this->fia[$fia_GET_key][$name]['=']    = [];
                $this->fia[$fia_GET_key][$name]['+']    = [];
            }

            if (isset($fia_GET_S_flip[$id])) {
                $prefix = '=';
            } else {
                $prefix = '+';
            }

            if (!empty($this->fia[$fia_GET_key]['S='][$name])) {
                foreach ($result as $product_id => $result_id) {
                    if (isset($this->fia[$fia_GET_key]['S='][$name][$product_id])) {
                        $this->fia[$fia_GET_key][$name][$prefix][$product_id] = $id;
                    }
                }
            }
        }
    }

    public function formatFilterA_Data_M($fia_GET_key) {
        $this->fia[$fia_GET_key] = [];
        $this->fia[$fia_GET_key]['M='] = [];

        if (isset($this->fia_GET['M'])) {
            $fia_GET_C_flip = array_flip($this->fia_GET['M']);
        } else {
            $fia_GET_C_flip = [];
        }


        $filter_data = [
            'category_id'   => $this->category_id,
            'search'   => $this->search,
            'special'   => $this->special,
            'special'   => $this->special,
            'fia_GET'       => $this->fia_GET,
            'key'           => 'M'
        ];

        $this->fia[$fia_GET_key]['M='] = $this->model_extension_module_filter_a->getFilterA_CD($filter_data);

        // print_r($this->fia[$fia_GET_key]['M=']);
        // die();

        foreach ($this->fia_ALL[$fia_GET_key] as $name => $result) {
            $id = reset($result);

            if (!isset($this->fia[$fia_GET_key][$name])) {
                $this->fia[$fia_GET_key][$name]['id']   = $id;
                $this->fia[$fia_GET_key][$name]['=']    = [];
                $this->fia[$fia_GET_key][$name]['+']    = [];
            }

            if (isset($fia_GET_C_flip[$name])) {
                $prefix = '=';
            } else {
                $prefix = '+';
            }


            if (!empty($this->fia[$fia_GET_key]['M='][$name])) {

                foreach ($result as $product_id => $result_id) {
                    if (isset($this->fia[$fia_GET_key]['M='][$name][$product_id])) {
                        $this->fia[$fia_GET_key][$name][$prefix][$product_id] = $id;
                    }
                }

            }
        }
    }

    public function getMinMaxPrice($stop, $price, $prefix) {
        switch ($prefix) {
            case 'min':
                if ($price && $stop > $price) {
                    $stop = $price;
                }

                break;

            case 'max':
                if ($price && $stop < $price) {
                    $stop = $price;
                }

                break;
        }

        return $stop;
    }

    public function fiaGrid() {
        $json = [];

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && isset($this->request->post['fiaGrid'])) {
            $this->session->data['fiaGrid'] = (int)$this->request->post['fiaGrid'];

            $json['success'] = true;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function query() {
        $json = [];

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && isset($this->request->post['fia'])) {
            $query = '';

            $fia = $this->request->post['fia'];

            if (!empty($this->request->post['filtered'])) {
                $filtered = explode(',', $this->request->post['filtered']);
            } else {
                $filtered = [];
            }

            if (!empty($this->request->post['query'])) {
                $parts = explode('&', htmlspecialchars_decode(rawurldecode($this->request->post['query'])));
            } else {
                $parts = [];
            }

            $fia_query = false;

            if ($parts) {
                foreach ($parts as $part) {
                    $part_array = explode('=', $part);

                    if ($part_array[0] == 'page') {
                        continue;
                    }

                    if ($part_array[0] == $fia) {
                        $fia_query = true;

                        if ($fia == 'P') {
                            if (!empty($filtered[0]) && !empty($filtered[1])) {
                                $build_query = join(',', $filtered);
                            } else {
                                continue;
                            }
                        } else {
                            $result = array_flip(explode(',', $part_array[1]));

                            $query_eq = '';
                            $query_ad = '';

                            foreach ($filtered as $filter) {
                                if (isset($result[$filter])) {
                                    $query_eq .= $filter . ',';
                                } else {
                                    $query_ad .= $filter . ',';
                                }
                            }

                            $build_query = rtrim($query_eq . $query_ad, ',');
                        }

                        if (!empty($build_query)) {
                            $query .= urldecode(http_build_query([$fia => $build_query])) . '&';
                        }

                    } else {
                        if ($fia == 'CA') {
                            $fia_keywords = ['CA', 'G', 'C', 'M', 'P', 'S','CO'];

                            if (!in_array($part_array[0], $fia_keywords)) {
                                $query .= urldecode(http_build_query([$part_array[0] => $part_array[1]])) . '&';
                            }
                        } else {
                            $query .= urldecode(http_build_query([$part_array[0] => $part_array[1]])) . '&';
                        }
                    }
                }
            } else {
                $fia_query = true;

                if ($filtered) {
                    $query .= urldecode(http_build_query([$fia => join(',', $filtered)])) . '&';
                }
            }

            if (!$fia_query && $fia != 'CA') {
                $query .= rawurldecode(http_build_query([$fia => join(',', $filtered)])) . '&';
            }

            $json['query'] = rtrim($query, '&');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
