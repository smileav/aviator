<?php
class ControllerExtensionModuleFilterA extends Controller {

    private $fia;
    private $fia_ALL;
    private $fia_GET;
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
                    echo 'G';
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
                    break;
                case 'P':
                    $this->fia['P'] = [];
                    $this->fia_GET['P'] = explode('-', $this->request->get['P']);
                    break;
                case 'S':
                    $this->fia['S'] = [];
                    $this->fia_GET['S'] = explode(',', $this->request->get['S']);
                    $this->fia_GET_flip['S'] = array_flip($this->fia_GET['S']);
                    break;
            }
        }

        if (!isset($this->fia['G'])) {
            $this->fia['G'] = [];
        } else {
            $this->fia_GET['G'] = ['Мужской', 'Женский'];
            //print_r($this->fia_GET);
            $filter_data = [
                'category_id'   => $category_id,
                'fia_GET'       => [
                    'G' => ['Мужской', 'Женский']
                ]
            ];

            $filtered = $this->model_extension_module_filter_a->getFilterA_Data($filter_data);

            //print_r($filtered);

            ///die();
        }

        if (!isset($this->fia['C'])) {
            $this->fia['C'] = [];
        }

        if (!isset($this->fia['M'])) {
            $this->fia['M'] = [];
        }

        if (!isset($this->fia['P'])) {
            $this->fia['P'] = [];
        }

        if (!isset($this->fia['S'])) {
            $this->fia['S'] = [];
        }

        $this->fia_ALL = $this->model_extension_module_filter_a->getFilterA_DataByCategory($category_id);

       // print_r(count($this->fia_ALL));
       // print_r($this->fia_ALL );
        //die();

        foreach ($this->fia as $key => $value) {
            if ($key == 'P') {
                $this->fia[$key] = $value;

                continue;
            }

            $filter_data = [
                'category_id'   => $category_id,
            ];

            $results = $this->model_extension_module_filter_a->{'getFilterA_' . $key}($filter_data);

            foreach ($results as $result) {
                $this->fia[$key][$result['name']]['id'] = $result['id'];

                if (!isset($this->fia[$key][$result['name']]['total'])) {
                    $this->fia[$key][$result['name']]['total'] = 1;
                } else {
                    $this->fia[$key][$result['name']]['total']++;
                }

                if (isset($this->fia_GET_flip[$key][$result['id']]) || isset($this->fia_GET_flip[$key][$result['name']])) {
                    $this->fia[$key][$result['name']]['prefix'] = '=';
                } else {
                    $this->fia[$key][$result['name']]['prefix'] = '+';
                }

                $this->fia[$key][$result['name']][$result['product_id']] = '';
            }
        }


        // Genders
        $filter_data = [
            'attribute_id'  => 59,
            'category_id'   => $category_id,
        ];

        $fiaG = [];

        if (!empty($this->fia_GET['G'])) {
            $fiaGenders = $this->model_extension_module_filter_a->getFilterA_Genders($filter_data);

            foreach ($fiaGenders as $fiaGender) {
                $fiaG[] = $fiaGender['name'];
            }
        }

        $filter_data = [
            'category_id'   => $category_id,
            'fiaG'          => $fiaG,
            'fia_GET'       => $this->fia_GET
        ];

        $filtered = $this->model_extension_module_filter_a->getFilterA_Data($filter_data);

        //print_r($filtered);
        //die();
        // print_r($this->fia);



        foreach ($this->fia as $key => $value) {
            foreach ($value as $name => $result) {

                $total_f = 0;

                foreach ($result as $key_result => $val) {
                    if ($key_result == 'id' || $key_result == 'total' || $key_result == 'prefix') {
                        continue;
                    }


                    if ($name == 'Рюкзаки') {
                        //print_r($result);
                        //die();
                    }
                    if (!empty($this->fia_GET['G'])) {
                        // continue;
                       // print_r($this->fia_GET['G']);

                       // print_r($result);
                        // die();
                    }



                    if (isset($filtered[$key_result])) {
                        $total_f++;
                        $this->fia[$key][$name][$key_result] = 1;
                    } else {



                                $this->fia[$key][$name][$key_result] = 0;




                            // unset($this->fia[$key][$name][$key_result]);


                    }


                }

                $this->fia[$key][$name]['total_f'] = $total_f;
            }
        }









        //print_r($this->fia);
        //die();

        /*
        $data['fiaG'] = $fiaG;
        $data['fiaC'] = $fiaC;
        $data['fiaM'] = $fiaM;
        $data['fiaP'] = $fiaP;
        $data['fiaS'] = $fiaS;
        */
        $data['fia'] = $this->fia;

        //print_r($data['fia']);
        $data['fia_GET'] = $this->fia_GET;

        unset($data['fia']['ALL']);

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
    public function formatFilterA_DataNew($data) {
        print_r($data);
        die();
        foreach ($data['data'] as $result) {

        }
    }

    public function formatFilterA_Data($data) {
        $allG = false;

        if (!empty($data['fiaG_flip']) && count($data['fiaG_flip']) == count($data['fia']['G'])) {
            $allG = true;
        }

        foreach ($data['data'] as $result) {
            foreach ($data['fia']['G'] as $nameG => $value) {


                if (isset($value[$result['product_id']])) {
                    if (!isset($data['fia'][$data['prefix']][$result['name']][$result['product_id']])) {
                        $data['fia'][$data['prefix']][$result['name']][$result['product_id']] = $result['id'];
                    }
                } else {
                    if (!empty($data['fiaC_flip']) || !empty($data['fiaS_flip'])) {
                        if ($data['prefix'] == 'C') {
                            //echo 'C====' . PHP_EOL;
                            //print_r($result);
                            // unset($data['fia'][$data['prefix']][$result['name']][$result['product_id']]);
                        }
                        if ($data['prefix'] == 'S') {
                            //echo 'S====' . PHP_EOL;
                            //print_r($result);
                        }
                        if ($data['prefix'] == 'S' && !empty($data['fia']['C'])) {
                            //print_r($data);
                            //die();
                            foreach ($data['fia']['C'] as $nameS => $value) {
                                //echo $nameS . PHP_EOL;
                                //print_r($value);
                                //die();
                            }
                        }
                    }

                    if (isset($data['fia'][$data['prefix']][$result['name']][$result['product_id']])) {
                        continue;
                    }

                    if ($data['prefix'] == 'C') {
                        if (!empty($data['fiaM_flip']) || !empty($data['fiaP_flip']) || !empty($data['fiaS_flip'])) {

                            $data['fia'][$data['prefix']][$result['name']][$result['product_id']] = $result['id'];
                            unset($data['fia'][$data['prefix']][$result['name']][$result['product_id']]);

                            continue;
                        }
                    }

                    if ($data['prefix'] == 'M') {
                        if (!empty($data['fiaC_flip']) || !empty($data['fiaS_flip'])) {
                            $data['fia'][$data['prefix']][$result['name']][$result['product_id']] = $result['id'];
                            unset($data['fia'][$data['prefix']][$result['name']][$result['product_id']]);

                            continue;
                        }
                    }

                    if ($data['prefix'] == 'S') {
                        if (!empty($data['fiaM_flip']) || !empty($data['fiaC_flip'])) {
                            $data['fia'][$data['prefix']][$result['name']][$result['product_id']] = $result['id'];
                            unset($data['fia'][$data['prefix']][$result['name']][$result['product_id']]);

                            continue;
                        }
                    }





                    if (!empty($data['fiaG_flip']) && isset($data['fiaG_flip'][$nameG])) {
                        if (isset($data['fia']['ALL'][$nameG][$result['product_id']])) {
                            $data['fia'][$data['prefix']][$result['name']][$result['product_id']] = $result['id'];
                        } else {
                            $data['fia'][$data['prefix']][$result['name']][$result['product_id']] = $result['id'];

                            if (!$allG) {
                                unset($data['fia'][$data['prefix']][$result['name']][$result['product_id']]);
                            }

                            if (!empty($data['fiaC_flip']) || !empty($data['fiaS_flip'])) {
                                if ($data['prefix'] == 'M') {
                                    unset($data['fia'][$data['prefix']][$result['name']][$result['product_id']]);
                                }
                            }
                        }
                    } else {
                        if (isset($data['fia']['ALL'][$nameG][$result['product_id']])) {
                            $data['fia'][$data['prefix']][$result['name']][$result['product_id']] = $result['id'];

                            if (!empty($data['fiaC_flip']) || !empty($data['fiaS_flip'])) {
                                // print_r($result);

                                if ($data['prefix'] == 'C') {

                                    // unset($data['fia'][$data['prefix']][$result['name']][$result['product_id']]);
                                }
                            }
                        }

                    }
                }
            }
        }
        if ($data['prefix'] == 'C') {
            // print_r($data['fia'][$data['prefix']]);
        }
        return $data['fia'];
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
