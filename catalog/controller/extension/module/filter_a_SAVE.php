<?php
class ControllerExtensionModuleFilterA extends Controller {

    private $fia;
    private $fia_ALL;
    private $fia_ALL_format;
    private $fia_GET = [];
    private $fia_GET_flip;

    private $min_price_all;
    private $max_price_all;
    private $min_price;
    private $max_price;

    public function index() {
        $this->load->language('extension/module/filter_a');

        $this->load->model('extension/module/filter_a');

        $this->document->addStyle('catalog/view/theme/aviator/stylesheet/filter_a.css');
        $this->document->addStyle('catalog/view/theme/aviator/js/noUiSlider/dist/nouislider.min.css');

        $this->document->addScript('catalog/view/theme/aviator/js/filter_a.js');
        $this->document->addScript('catalog/view/theme/aviator/js/noUiSlider/dist/nouislider.min.js');
        $this->document->addScript('catalog/view/theme/aviator/js/wNumb.min.js');

        if (isset($this->request->get['path'])) {
            $parts          = explode('_', (string)$this->request->get['path']);
            $category_id    = (int)array_pop($parts);
        } else {
            $category_id = 0;
        }

        if (isset($this->request->get['G'])) {
            $fiaG = explode(',', $this->request->get['G']);
        } else {
            $fiaG = [];
        }

        if (isset($this->request->get['C'])) {
            $fiaC = explode(',', $this->request->get['C']);
        } else {
            $fiaC = [];
        }

        if (isset($this->request->get['M'])) {
            $fiaM = explode(',', $this->request->get['M']);
        } else {
            $fiaM = [];
        }

        if (isset($this->request->get['P'])) {
            $fiaP = explode('-', $this->request->get['P']);
        } else {
            $fiaP = [];
        }

        if (isset($this->request->get['S'])) {
            $fiaS = explode(',', $this->request->get['S']);
        } else {
            $fiaS = [];
        }

        foreach ($this->request->get as $key => $value) {
            switch ($key) {
                case 'G':
                    $this->fia['G'] = [];
                    $this->fia_GET['G'] = explode(',', $this->request->get['G']);
                    $this->fia_GET_flip['G'] = array_flip($this->fia_GET['G']);
                    break;
                case 'C':
                    $this->fia['C'] = [];
                    $this->fia_GET['C'] = explode(',', $this->request->get['C']);
                    $this->fia_GET_flip['C'] = array_flip($this->fia_GET['C']);
                    break;
                case 'M':
                    $this->fia['M'] = [];
                    $this->fia_GET['M'] = explode(',', $this->request->get['M']);
                    $this->fia_GET_flip['M'] = array_flip($this->fia_GET['M']);
                    break;
                case 'P':
                    $this->fia['P'] = [];
                    $this->fia_GET['P'] = explode('-', $this->request->get['P']);
                    $this->fia_GET_flip['P'] = array_flip($this->fia_GET['P']);
                    break;
                case 'S':
                    $this->fia['S'] = [];
                    $this->fia_GET['S'] = explode(',', $this->request->get['S']);
                    $this->fia_GET_flip['S'] = array_flip($this->fia_GET['S']);
                    break;
            }
        }

        $data['fia_GET'] = $this->fia_GET;

        $results_EMPTY = true;

        foreach ($this->fia_GET as $fia_GET_key => $fia_GET_value) {
            $filter_data = [
                'category_id'   => $category_id,
                'key'           => $fia_GET_key,
                'fia_GET'       => $this->fia_GET
            ];

            ${'results_' . $fia_GET_key} = $this->model_extension_module_filter_a->getFilterA_CD($filter_data);

            if (!empty(${'results_' . $fia_GET_key})) {
                $results_EMPTY = false;
            }
        }

        $this->fia = [];

        $i = 0;
        foreach ($this->fia_GET as $fia_GET_key => $fia_GET_value) {
            if (!isset($fia_GET_key_p)) {
                $fia_GET_key_p = $fia_GET_key;
            }

            foreach (${'results_' . $fia_GET_key} as $name => $value) {
                if (!$i) {
                    $this->fia[$fia_GET_key][$name] = $value;

                } else {
                    foreach ($this->fia[$fia_GET_key_p] as $_fia_GET_key_p => $_fia_GET_key_value) {
                        foreach ($value as $product_id => $id) {
                            if (isset($_fia_GET_key_value[$product_id])) {
                                $this->fia[$fia_GET_key][$name][$product_id] = $id;
                            }
                        }
                    }
                }
            }

            $i++;
        }


        $filter_data = [
            'attribute_id'  => 59,
            'category_id'   => $category_id,
        ];

        $this->fia_ALL['G'] = $this->model_extension_module_filter_a->getFilterA_G($filter_data);

        $filter_data = [
            'attribute_id'  => 60,
            'category_id'   => $category_id,
        ];

        $this->fia_ALL['C'] = $this->model_extension_module_filter_a->getFilterA_C($filter_data);

        $filter_data = [
            'option_id'     => 1,
            'category_id'   => $category_id,
        ];

        $this->fia_ALL['S'] = $this->model_extension_module_filter_a->getFilterA_S($filter_data);

        $filter_data = [
            'category_id'   => $category_id,
        ];

        $this->fia_ALL['M'] = $this->model_extension_module_filter_a->getFilterA_M($filter_data);

        /*foreach ($this->fia_ALL as $key => $results) {
            foreach ($results as $result) {
                //echo $key . PHP_EOL;
                //print_r($result) . PHP_EOL;
                $this->fia_ALL_format[$key][$result['product_id']][$result['name']] = '';
                //foreach ($results as $name => $values)

            }
                // echo $name .PHP_EOL;
                // print_r($values) .PHP_EOL;

            //$fia_ALL_format->;
        }

        print_r($this->fia_ALL_format);
        die();


        */


        foreach ($this->fia_GET as $fia_GET_key => $fia_GET_value) {
            $this->{'format_' . $fia_GET_key}($fia_GET_key);

        }

        if (empty($this->fia_GET['M'])) {
            $filter_data = [
                'category_id'   => $category_id,
                'key'           => $fia_GET_key,
                'fia_GET'       => $this->fia_GET
            ];

            $results_M = $this->model_extension_module_filter_a->getFilterA_CD($filter_data);

            // $this->{'format_' . $fia_GET_key}($fia_GET_key);
            print_r($results_M);

            die();
        }



        print_r($this->fia);
        die();



        $this->fia['C'] = [];

        /*
        if (!isset($this->fia['C']['='])) {
            $this->fia['C']['='] = [];
        }*/

        if (!isset($this->fia['C']['-'])) {
            $this->fia['C']['-'] = [];
        }

        $filter_data = [
            'category_id'   => $category_id,
            'fia_GET'       => $this->fia_GET,
            'option_id'     => 1,
        ];

        $this->fia['C']['S'] = $this->model_extension_module_filter_a->getFilterA_Sizes($filter_data);

        foreach ($this->fia_ALL['C'] as $result) {
            if (!isset($this->fia['C'][$result['name']]['id'])) {
                $this->fia['C'][$result['name']]['id'] = $result['id'];
            }

            if (!isset($this->fia['C'][$result['name']]['='])) {
                $this->fia['C'][$result['name']]['='] = [];
            }

            if (!isset($this->fia['C'][$result['name']]['+'])) {
                $this->fia['C'][$result['name']]['+'] = [];
            }

            /*
            if (!isset($this->fia['C'][$result['name']]['-'])) {
                $this->fia['C'][$result['name']]['-'] = [];
            }
            */

            if (isset($temp[$result['name']][$result['product_id']])) {
                $this->fia['C'][$result['name']]['='][$result['product_id']] = 'C+';
            } else {
                $this->fia['C']['-'][$result['product_id']] = [$result['name']];

                                //if (!empty($this->fia['S']['=']) && isset($this->fia['S']['='][$result['product_id']])) {
                  //  $this->fia['C'][$result['name']]['+'][$result['product_id']] = 'from SIZE';
                   // unset($this->fia['C'][$result['name']]['-'][$result['product_id']]);
                //}
            }

            /*
            if (isset($this->fia['S']['='][$result['product_id']])) {
                foreach ($this->fia['S']['='][$result['product_id']] as $name) {
                    $this->fia['S'][$name]['+'][$result['product_id']] = 'NEW from SIZE';
                    unset($this->fia['S'][$name]['-'][$result['product_id']]);
                }
            }*/
        }



        $this->fia['S'] = [];

        if (!isset($this->fia['S']['='])) {
            $this->fia['S']['='] = [];
        }

        foreach ($this->fia_ALL['S'] as $result) {



            if (!isset($this->fia['S'][$result['name']]['id'])) {
                $this->fia['S'][$result['name']]['id'] = $result['id'];
            }

            if (!isset($this->fia['S'][$result['name']]['+'])) {
                $this->fia['S'][$result['name']]['+'] = [];
            }

            if (!isset($this->fia['S'][$result['name']]['-'])) {
                $this->fia['S'][$result['name']]['-'] = [];
            }

            if (isset($temp[$result['name']][$result['product_id']])) {
                $this->fia['S'][$result['name']]['+'][$result['product_id']] = '';
            } else {
                if (isset($this->fia['C']['S'][$result['name']])) {
                    if (isset($this->fia['C']['-'][$result['product_id']])) {


                        foreach ($this->fia['C']['-'][$result['product_id']] as $name) {
                            $this->fia['C'][$name]['+'][$result['product_id']] = 'NEW from SIZE';
                        }

                        // $this->fia['S'][$result['name']]['+'][$result['product_id']] = '';
                    }

                    // $this->fia['S'][$result['name']]['+'][$result['product_id']] = '';
                }
                // $this->fia['S']['='][$result['product_id']][] = $result['name'];
            }
        }




        print_r($this->fia);
        die();




        $filter_data = [
            'attribute_id'  => 59,
            'category_id'   => $category_id,
        ];

        $results = $this->model_extension_module_filter_a->getFilterA_G($filter_data);
        // print_r($results);

        if (!empty($this->fia_GET && !$results_EMPTY)) {
            foreach ($this->fia_GET as $keyG => $nameG) {
                foreach ($this->fia[$keyG] as $name => $values) {
                    foreach ($values as $value_key => $value) {

                        foreach ($results as $result) {
                            if (!isset($this->fia['G'][$result['name']]['id'])) {
                                $this->fia['G'][$result['name']]['id'] = $result['id'];
                            }

                            if (!isset($this->fia['G'][$result['name']]['+'])) {
                                $this->fia['G'][$result['name']]['+'] = [];
                            }

                            if (!isset($this->fia['G'][$result['name']]['-'])) {
                                $this->fia['G'][$result['name']]['-'] = [];
                            }

                            if ($result['product_id'] == $value_key) {
                                $this->fia['G'][$result['name']]['+'][$result['product_id']] = '';
                            } else {
                                $this->fia['G'][$result['name']]['-'][$result['product_id']] = '';
                            }
                        }

                    }
                }
            }
        } else {

            foreach ($results as $result) {
                if (!isset($this->fia['G'][$result['name']]['id'])) {
                    $this->fia['G'][$result['name']]['id'] = $result['id'];
                }

                if (!isset($this->fia['G'][$result['name']]['+'])) {
                    $this->fia['G'][$result['name']]['+'] = [];
                }

                if (!isset($this->fia['G'][$result['name']]['-'])) {
                    $this->fia['G'][$result['name']]['-'] = [];
                }

                $this->fia['G'][$result['name']]['+'][$result['product_id']] = '';
            }
        }



















        /*

        // Category
        $filter_data = [
            'attribute_id'  => 60,
            'category_id'   => $category_id,
        ];

        $results = $this->model_extension_module_filter_a->getFilterA_C($filter_data);

        if (!empty($this->fia_GET) && !$results_EMPTY) {
            foreach ($this->fia_GET as $keyG => $nameG) {


                print_r($this->fia[$keyG]);

                foreach ($this->fia[$keyG] as $name => $values) {

                    foreach ($values as $value_key => $value) {

                        foreach ($results as $result) {
                            if (!isset($this->fia['C'][$result['name']]['id'])) {
                                $this->fia['C'][$result['name']]['id'] = $result['id'];
                            }

                            if (!isset($this->fia['C'][$result['name']]['+'])) {
                                $this->fia['C'][$result['name']]['+'] = [];
                            }

                            if (!isset($this->fia['C'][$result['name']]['-'])) {
                                $this->fia['C'][$result['name']]['-'] = [];
                            }

                            if ($result['product_id'] == $value_key) {

                                //print_r($result);
                                //print_r($values);
                                $this->fia['C'][$result['name']]['+'][$result['product_id']] = '';
                            } else {
                                $this->fia['C'][$result['name']]['-'][$result['product_id']] = '';
                            }


                        }
                    }
                }
            }

        } else {
            foreach ($results as $result) {
                if (!isset($this->fia['C'][$result['name']]['id'])) {
                    $this->fia['C'][$result['name']]['id'] = $result['id'];
                }

                if (!isset($this->fia['C'][$result['name']]['+'])) {
                    $this->fia['C'][$result['name']]['+'] = [];
                }

                if (!isset($this->fia['C'][$result['name']]['-'])) {
                    $this->fia['C'][$result['name']]['-'] = [];
                }

                $this->fia['C'][$result['name']]['+'][$result['product_id']] = '';
            }
        }
        */


        $filter_data = [
            'category_id'   => $category_id,
        ];

        $results = $this->model_extension_module_filter_a->getFilterA_M($filter_data);

        if (!empty($this->fia_GET) && !$results_EMPTY) {
            foreach ($this->fia_GET as $keyG => $nameG) {
                foreach ($this->fia[$keyG] as $name => $values) {
                    foreach ($values as $value_key => $value) {

                        foreach ($results as $result) {
                            if (!isset($this->fia['M'][$result['name']]['id'])) {
                                $this->fia['M'][$result['name']]['id'] = $result['id'];
                            }

                            if (!isset($this->fia['M'][$result['name']]['+'])) {
                                $this->fia['M'][$result['name']]['+'] = [];
                            }

                            if (!isset($this->fia['M'][$result['name']]['-'])) {
                                $this->fia['M'][$result['name']]['-'] = [];
                            }

                            if ($result['product_id'] == $value_key) {
                                $this->fia['M'][$result['name']]['+'][$result['product_id']] = '';
                            } else {
                                $this->fia['M'][$result['name']]['-'][$result['product_id']] = '';
                            }
                        }

                    }
                }
            }
        } else {

            foreach ($results as $result) {
                if (!isset($this->fia['M'][$result['name']]['id'])) {
                    $this->fia['M'][$result['name']]['id'] = $result['id'];
                }

                if (!isset($this->fia['M'][$result['name']]['+'])) {
                    $this->fia['M'][$result['name']]['+'] = [];
                }

                if (!isset($this->fia['M'][$result['name']]['-'])) {
                    $this->fia['M'][$result['name']]['-'] = [];
                }

                $this->fia['M'][$result['name']]['+'][$result['product_id']] = '';
            }

        }


        /*
        $filter_data = [
            'option_id'     => 1,
            'category_id'   => $category_id,
        ];

        $results = $this->model_extension_module_filter_a->getFilterA_S($filter_data);

        if (!empty($this->fia_GET) && !$results_EMPTY) {
            foreach ($this->fia_GET as $keyG => $nameG) {
                foreach ($this->fia[$keyG] as $name => $values) {
                    foreach ($values as $value_key => $value) {

                        foreach ($results as $result) {
                            if (!isset($this->fia['S'][$result['name']]['id'])) {
                                $this->fia['S'][$result['name']]['id'] = $result['id'];
                            }

                            if (!isset($this->fia['S'][$result['name']]['+'])) {
                                $this->fia['S'][$result['name']]['+'] = [];
                            }

                            if (!isset($this->fia['S'][$result['name']]['-'])) {
                                $this->fia['S'][$result['name']]['-'] = [];
                            }

                            if (!isset($this->fia['S'][$result['name']]['sort_order'])) {
                                $this->fia['S'][$result['name']]['sort_order'] = $result['sort_order'];
                            }

                            if ($result['product_id'] == $value_key) {
                                $this->fia['S'][$result['name']]['+'][$result['product_id']] = '';
                            } else {
                                $this->fia['S'][$result['name']]['-'][$result['product_id']] = '';
                            }
                        }

                    }
                }
            }

        } else {

            foreach ($results as $result) {
                if (!isset($this->fia['S'][$result['name']]['id'])) {
                    $this->fia['S'][$result['name']]['id'] = $result['id'];
                }

                if (!isset($this->fia['S'][$result['name']]['+'])) {
                    $this->fia['S'][$result['name']]['+'] = [];
                }

                if (!isset($this->fia['S'][$result['name']]['-'])) {
                    $this->fia['S'][$result['name']]['-'] = [];
                }

                $this->fia['S'][$result['name']]['+'][$result['product_id']] = '';
            }

        }


        */


        //print_r( $this->fia['S']);
        //die();

        $data['fia'] = $this->fia;







        /*
        $results_Genders = $this->model_extension_module_filter_a->getFilterA_Genders([ 'attribute_id'  => 59, 'category_id'   => $category_id ]);

        foreach ($results_Genders as $gender) {
            $this->fia_ALL[$gender['name']] = $this->model_extension_module_filter_a->getFilterA_AllData([ 'fiaG' => $gender['name'], 'category_id'   => $category_id]);
        }

        if (!isset($this->fia_GET['G'])) {
            foreach ($results_Genders as $gender) {
                $this->fia_GET['G'][] = $gender['name'];
            }
        }
        */



        // $key = array_search('100', array_column($userdb, 'uid'));
        /*

        foreach ($this->fia_GET['G'] as $keyG => $nameG) {
            foreach ($this->fia as $key => $values) {
                foreach ($values as $name => $value) {
                    echo PHP_EOL;
                    echo $name . PHP_EOL;

                    print_r($this->fia_ALL[$nameG]);
                    echo PHP_EOL;
                    echo PHP_EOL;

                    die();
                }
            }
        }




        print_r($this->fia);

        die();

        $fiaG_flip = array_flip($fiaG);
        $fiaC_flip = array_flip($fiaC);
        $fiaM_flip = array_flip($fiaM);
        $fiaP_flip = array_flip($fiaP);
        $fiaS_flip = array_flip($fiaS);


        // Genders
        $filter_data = [
            'attribute_id'  => 59,
            'category_id'   => $category_id,
        ];

        $data['fia']['G'] = $this->model_extension_module_filter_a->getFilterA_Genders($filter_data);

        // Genders Filter Data
        $filter_data = [
            'category_id'   => $category_id,
            'fiaC'          => $fiaC,
            'fiaM'          => $fiaM,
            'fiaP'          => $fiaP,
            'fiaS'          => $fiaS
        ];

        foreach ($data['fia']['G'] as $key => $value) {
            $filter_data['fiaG'] = $value['name'];

            $data['fia']['ALL'][$value['name']] = $this->model_extension_module_filter_a->getFilterA_AllData([ 'fiaG' => $value['name'], 'category_id'   => $category_id]);

            if (!$this->min_price_all) {
                $this->min_price_all = $data['fia']['ALL'][$value['name']]['min_price'];
            } else {
                $this->min_price_all = $this->getMinMaxPrice($this->min_price_all, $data['fia']['ALL'][$value['name']]['min_price'], 'min');
            }

            if (!$this->max_price) {
                $this->max_price_all = $data['fia']['ALL'][$value['name']]['max_price'];
            } else {
                $this->max_price_all = $this->getMinMaxPrice($this->max_price_all, $data['fia']['ALL'][$value['name']]['max_price'], 'max');
            }

            unset($data['fia']['ALL'][$value['name']]['min_price']);
            unset($data['fia']['ALL'][$value['name']]['max_price']);

            $results = $this->model_extension_module_filter_a->getFilterA_Data($filter_data);

            if (!$this->min_price) {
                $this->min_price = $results['min_price'];
            } else {
                $this->min_price = $this->getMinMaxPrice($this->min_price, $results['min_price'], 'min');
            }

            if (!$this->max_price) {
                $this->max_price = $results['max_price'];
            } else {
                $this->max_price = $this->getMinMaxPrice($this->max_price, $results['max_price'], 'max');
            }

            unset($results['min_price']);
            unset($results['max_price']);

            // $data['fia']['ALL'][$value['name']] = $results;
            $data['fia']['G'][$value['name']] = $results;

            unset($data['fia']['G'][$key]);
        }

        $data['min_price_all'] = floor($this->min_price_all / 100) * 100;
        $data['max_price_all'] = ceil($this->max_price_all / 100) * 100;

        $data['min_price'] = floor($this->min_price / 100) * 100;
        $data['max_price'] = ceil($this->max_price / 100) * 100;

        if (!empty($fiaP[0]) && !empty($fiaP[1]) && !$fiaG && !$fiaC && !$fiaM && !$fiaS) {
            $data['min_price'] = (float)$fiaP[0];
            $data['max_price'] = (float)$fiaP[1];
        }

        $data['min_price_nf'] = number_format($data['min_price'], 0, '', ' ');
        $data['max_price_nf'] = number_format($data['max_price'], 0, '', ' ');

        // Categories
        $filter_data = [
            'attribute_id'  => 60,
            'category_id'   => $category_id,
        ];

        $results = $this->model_extension_module_filter_a->getFilterA_Categories($filter_data);

        $filter_data = [
            'fiaG_flip'     => $fiaG_flip,
            'fiaC_flip'     => $fiaC_flip,
            'fiaM_flip'     => $fiaM_flip,
            'fiaP_flip'     => $fiaP_flip,
            'fiaS_flip'     => $fiaS_flip,
            'key'        => 'C',
            'fia'           => $data['fia'],
            'data'          => $results
        ];

        $data['fia'] = $this->formatFilterA_Data($filter_data);

        //print_r($data['fia']);
        //die();

        // Manufacturers
        $filter_data = [
            'category_id'   => $category_id,
        ];

        $results = $this->model_extension_module_filter_a->getFilterA_Manufacturers($filter_data);

        $filter_data = [
            'fiaG_flip'     => $fiaG_flip,
            'fiaC_flip'     => $fiaC_flip,
            'fiaM_flip'     => $fiaM_flip,
            'fiaP_flip'     => $fiaP_flip,
            'fiaS_flip'     => $fiaS_flip,
            'key'        => 'M',
            'fia'           => $data['fia'],
            'data'          => $results
        ];

        $data['fia'] = $this->formatFilterA_Data($filter_data);

        // Sizes
        $filter_data = [
            'option_id'     => 1,
            'category_id'   => $category_id,
        ];

        $results = $this->model_extension_module_filter_a->getFilterA_Sizes($filter_data);

        $filter_data = [
            'fiaG_flip'     => $fiaG_flip,
            'fiaC_flip'     => $fiaC_flip,
            'fiaM_flip'     => $fiaM_flip,
            'fiaP_flip'     => $fiaP_flip,
            'fiaS_flip'     => $fiaS_flip,
            'key'        => 'S',
            'fia'           => $data['fia'],
            'data'          => $results
        ];

        $data['fia'] = $this->formatFilterA_Data($filter_data);

        */










        //$data['fiaG'] = $fiaG;
        //$data['fiaC'] = $fiaC;
        //$data['fiaM'] = $fiaM;
        //$data['fiaP'] = $fiaP;
        //$data['fiaS'] = $fiaS;
// print_r($data);
        //unset($data['fia']['ALL']);

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

        $data['results'] = [];

        /*foreach ($this->request->get as $get) {

        }
        $data['results'][]*/

        return $data;
    }

    /*
    public function formatFilterA_DataNew($data) {
        print_r($data);
        die();
        foreach ($data['data'] as $result) {

        }
    }
    */

    public function formatFilterA_Data($data) {
        $allG = false;

        if (!empty($data['fiaG_flip']) && count($data['fiaG_flip']) == count($data['fia']['G'])) {
            $allG = true;
        }

        foreach ($data['data'] as $result) {
            foreach ($data['fia']['G'] as $nameG => $value) {



                $data['fia'][$data['key']][$result['name']]['id'] = $result['id'];

                if (isset($data['fia' . $data['key'] . '_flip'][$result['name']])) {
                    $data['fia'][$data['key']][$result['name']]['prefix'] = '=';
                }

                if (isset($value[$result['product_id']])) {
                    if (!isset($data['fia'][$data['key']][$result['name']][$result['product_id']])) {

                        $data['fia'][$data['key']][$result['name']][$result['product_id']] = 1;
                    }
                } else {
                    if (!empty($data['fiaC_flip']) || !empty($data['fiaS_flip'])) {
                        if ($data['key'] == 'C') {
                            //echo 'C====' . PHP_EOL;
                            //print_r($result);
                            // unset($data['fia'][$data['key']][$result['name']][$result['product_id']]);
                        }
                        if ($data['key'] == 'S') {
                            //echo 'S====' . PHP_EOL;
                            //print_r($result);
                        }
                        if ($data['key'] == 'S' && !empty($data['fia']['C'])) {
                            //print_r($data);
                            //die();
                            foreach ($data['fia']['C'] as $nameS => $value) {
                                //echo $nameS . PHP_EOL;
                                //print_r($value);
                                //die();
                            }
                        }
                    }

                    if (isset($data['fia'][$data['key']][$result['name']][$result['product_id']])) {
                        continue;
                    }

                    if ($data['key'] == 'C') {
                        if (!empty($data['fiaM_flip']) || !empty($data['fiaP_flip']) || !empty($data['fiaS_flip'])) {




                            $data['fia'][$data['key']][$result['name']][$result['product_id']] = 33;
                            //unset($data['fia'][$data['key']][$result['name']][$result['product_id']]);

                            continue;
                        }
                    }

                    if ($data['key'] == 'M') {
                        if (!empty($data['fiaC_flip']) || !empty($data['fiaS_flip'])) {
                            $data['fia'][$data['key']][$result['name']][$result['product_id']] = $result['id'];
                            unset($data['fia'][$data['key']][$result['name']][$result['product_id']]);

                            continue;
                        }
                    }

                    if ($data['key'] == 'S') {




                        if (!empty($data['fiaM_flip']) || !empty($data['fiaC_flip'])) {



                            if (isset($data['fia']['ALL'][$nameG][$result['product_id']])) {
                                $data['fia']['C'][$result['name']]['est'] = '+';
                            }


                            $data['fia'][$data['key']][$result['name']]['prefix'] = '+';
                            //$data['fia'][$data['key']][$result['name']][$result['product_id']] = 8;
                            unset($data['fia'][$data['key']][$result['name']][$result['product_id']]);

                            continue;
                        }

                    }





                    if (!empty($data['fiaG_flip']) && isset($data['fiaG_flip'][$nameG])) {
                        if (isset($data['fia']['ALL'][$nameG][$result['product_id']])) {
                            $data['fia'][$data['key']][$result['name']]['prefix'] = '+';
                            $data['fia'][$data['key']][$result['name']][$result['product_id']] = $result['id'];
                        } else {
                            $data['fia'][$data['key']][$result['name']][$result['product_id']] = $result['id'];

                            if (!$allG) {
                                unset($data['fia'][$data['key']][$result['name']][$result['product_id']]);
                            }

                            if (!empty($data['fiaC_flip']) || !empty($data['fiaS_flip'])) {
                                if ($data['key'] == 'M') {
                                    unset($data['fia'][$data['key']][$result['name']][$result['product_id']]);
                                }
                            }
                        }
                    } else {
                        if (isset($data['fia']['ALL'][$nameG][$result['product_id']])) {

                                if (empty($data['fiaG_flip'])) {
                                    $data['fia'][$data['key']][$result['name']]['prefix'] = '+';
                                    $data['fia'][$data['key']][$result['name']][$result['product_id']] = 5;
                                } else if (isset($data['fiaG_flip'][$nameG])) {
                                    $data['fia'][$data['key']][$result['name']]['prefix'] = '+';
                                    $data['fia'][$data['key']][$result['name']][$result['product_id']] = 6;
                                } else {
                                    $data['fia'][$data['key']][$result['name']]['prefix'] = '-';
                                    $data['fia'][$data['key']][$result['name']][$result['product_id']] = 7;
                                }



                            if (!empty($data['fiaC_flip']) || !empty($data['fiaS_flip'])) {
                                // print_r($result);

                                if ($data['key'] == 'C') {

                                    // unset($data['fia'][$data['key']][$result['name']][$result['product_id']]);
                                }
                            }
                        }

                    }
                }
            }
        }
        if ($data['key'] == 'S') {
            //print_r($data['fia']);
            //die();
        }

       // print_r($data['fia']);
       // die();

        return $data['fia'];
    }

    public function format_C($fia_GET_key) {
        $temp = $this->fia[$fia_GET_key];

        $this->fia[$fia_GET_key] = [];
        $this->fia[$fia_GET_key][$fia_GET_key] = [];
        $this->fia[$fia_GET_key]['S'] = [];

        if (!empty($this->fia_GET['S'])) {
            $filter_data = [
                'fia_GET' => $this->fia_GET,
                'option_id' => 1,
            ];

            $this->fia[$fia_GET_key]['S'] = $this->model_extension_module_filter_a->getFilterA_Sizes($filter_data);
        }

        foreach ($this->fia_ALL[$fia_GET_key] as $result) {
            if (!isset($this->fia[$fia_GET_key][$result['name']]['id'])) {
                $this->fia[$fia_GET_key][$result['name']]['id'] = $result['id'];
            }

            if (!isset($this->fia[$fia_GET_key][$result['name']]['='])) {
                $this->fia[$fia_GET_key][$result['name']]['='] = [];
            }

            if (!isset($this->fia[$fia_GET_key][$result['name']]['+'])) {
                $this->fia[$fia_GET_key][$result['name']]['+'] = [];
            }

            if (isset($temp[$result['name']][$result['product_id']])) {
                $this->fia[$fia_GET_key][$result['name']]['='][$result['product_id']] = 'C+';
            } else {
                //$this->fia['C']['-'][$result['product_id']] = [$result['name']];
                $this->fia[$fia_GET_key][$fia_GET_key][$result['product_id']] = [$result['name']];
            }
        }
    }

    public function format_S($fia_GET_key) {
        $temp = $this->fia[$fia_GET_key];

        $this->fia[$fia_GET_key] = [];
        $this->fia[$fia_GET_key][$fia_GET_key] = [];
        $this->fia[$fia_GET_key]['S'] = [];

        if (!empty($this->fia_GET['S'])) {
            $filter_data = [
                'fia_GET' => $this->fia_GET,
                'option_id' => 1,
            ];

            $this->fia[$fia_GET_key]['S'] = $this->model_extension_module_filter_a->getFilterA_Sizes($filter_data);
        }

        foreach ($this->fia_ALL[$fia_GET_key] as $result) {
            if (!isset($this->fia[$fia_GET_key][$result['name']]['id'])) {
                $this->fia[$fia_GET_key][$result['name']]['id'] = $result['id'];
            }

            if (!isset($this->fia[$fia_GET_key][$result['name']]['='])) {
                $this->fia[$fia_GET_key][$result['name']]['='] = [];
            }

            if (!isset($this->fia[$fia_GET_key][$result['name']]['+'])) {
                $this->fia[$fia_GET_key][$result['name']]['+'] = [];
            }

            if (isset($temp[$result['name']][$result['product_id']])) {
                $this->fia[$fia_GET_key][$result['name']]['='][$result['product_id']] = 'C+';
            } else {

                if (isset($this->fia['C'][$fia_GET_key][$result['name']])) {
                    if (isset($this->fia['C']['C'][$result['product_id']])) {
                        foreach ($this->fia['C']['C'][$result['product_id']] as $name) {
                            $this->fia['C'][$name]['+'][$result['product_id']] = 'NEW from SIZE';
                        }
                    }
                }

            }
        }











        /*

        $this->fia['S'] = [];

        if (!isset($this->fia['S']['='])) {
            $this->fia['S']['='] = [];
        }

        foreach ($this->fia_ALL['S'] as $result) {



            if (!isset($this->fia['S'][$result['name']]['id'])) {
                $this->fia['S'][$result['name']]['id'] = $result['id'];
            }

            if (!isset($this->fia['S'][$result['name']]['+'])) {
                $this->fia['S'][$result['name']]['+'] = [];
            }

            if (!isset($this->fia['S'][$result['name']]['-'])) {
                $this->fia['S'][$result['name']]['-'] = [];
            }

            if (isset($temp[$result['name']][$result['product_id']])) {
                $this->fia['S'][$result['name']]['+'][$result['product_id']] = '';
            } else {
                if (isset($this->fia['C']['S'][$result['name']])) {
                    if (isset($this->fia['C']['-'][$result['product_id']])) {
                        foreach ($this->fia['C']['-'][$result['product_id']] as $name) {
                            $this->fia['C'][$name]['+'][$result['product_id']] = 'NEW from SIZE';
                        }
                    }
                }
            }
        }
        */



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


}
