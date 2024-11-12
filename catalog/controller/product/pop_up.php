<?php
class ControllerProductPopUp extends Controller {
    public function index() {
        $data['images'] = [];

        if ($this->request->server['REQUEST_METHOD'] == 'GET' && isset($this->request->get['product_id']) && isset($this->request->get['id'])) {
            $this->load->model('catalog/product');

            $product_id     = (int)$this->request->get['product_id'];
            $id             = (int)$this->request->get['id'];

            $start = 0;

            $image_width    = 737;
            $image_height   = 1105;

            $product_info = $this->model_catalog_product->getProduct($product_id);

            if ($product_info) {
                $this->load->model('tool/image');

                $data['images'][] = $this->model_tool_image->resize($product_info['image'], $image_width, $image_height);

                $results = $this->model_catalog_product->getProductImages($product_id);

                foreach ($results as $key => $result) {
                    $data['images'][] = $this->model_tool_image->resize($result['image'], $image_width, $image_height);

                    if ($result['product_image_id'] == $id) {
                        $start = $key + 1;
                    }
                }
            }

            $data['start'] = $start;
        }

        return $this->load->view('product/pop_up', $data);
    }

    public function get() {
        $this->response->setOutput($this->index());
    }
}
