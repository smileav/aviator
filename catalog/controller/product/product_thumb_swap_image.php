<?php
class ControllerProductProductThumbSwapImage extends Controller {
    public function index() {
        $json['image'] = '';

        if ($this->request->server['REQUEST_METHOD'] == 'GET' && !empty($this->request->get['id']) && !empty($this->request->get['w']) && !empty($this->request->get['h'])) {
            $this->load->model('tool/image');

            $image      = $this->getImage($this->request->get['id']);

            $width      = $this->request->get['w'];
            $height     = $this->request->get['h'];

            if (!empty($image['image'])) {
                $json['image'] = $this->model_tool_image->resize($image['image'], $width, $height, 'auto');;
            } else {
                $json['image'] = '';
            }

            if (isset($this->session->data['webp'])) {
                $re = '/(cache)(.*)(\.jpg|\.png|\.PNG|.jpeg)/U';
                $subst = '$1webp$2.webp';
                $json['image'] = preg_replace($re, $subst, $json['image']);
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getImage($product_id) {
        $query = $this->db->query("SELECT `image` FROM `" . DB_PREFIX . "product_image` WHERE `product_id` = '" . (int)$product_id . "' ORDER BY sort_order ASC LIMIT 1");

        return $query->row;
    }
}
