<?php
class ControllerProductDetermineSize extends Controller {
    public function index() {
        $data = [];

        if ($this->request->server['REQUEST_METHOD'] == 'GET') {
            $this->load->language('extension/module/callback');
        }

        return $this->load->view('product/determine_size', $data);
    }

    public function get() {
        $this->response->setOutput($this->index());
    }
}
