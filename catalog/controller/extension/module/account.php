<?php
class ControllerExtensionModuleAccount extends Controller {
	public function index() {
        if ($this->customer->isLogged()) {
            $this->load->language('extension/module/account');
            $this->load->model('account/customer');
            $this->load->model('account/order');
            $this->load->model('account/wishlist');
            $this->load->model('account/return');


            if ($this->customer->isLogged()) {
                $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
            }

            if (isset($this->request->get['route']) && $this->request->get['route']) {
                $data['route'] = $this->request->get['route'];
            } else {
                $data['route'] = '';
            }

            if ($customer_info) {
                $data['customer_name'] = $customer_info['firstname'] . ' ' . $customer_info['lastname'];
                $data['contact'] = implode('<br>', [$this->customer->formatTelephone($customer_info['telephone']), $customer_info['email']]);
                $data['order_total'] = $this->model_account_order->getTotalOrders();
                $data['order_total_wishlist'] = $this->model_account_wishlist->getTotalWishlist();
                $data['return_total'] = $this->model_account_return->getTotalReturns();
            }

            $data['customer_name'] = trim($data['customer_name']);

            $data['logged'] = $this->customer->isLogged();
            $data['register'] = $this->url->link('account/register', '', true);
            $data['login'] = $this->url->link('account/login', '', true);
            $data['logout'] = $this->url->link('account/logout', '', true);
            $data['forgotten'] = $this->url->link('account/forgotten', '', true);
            $data['account'] = $this->url->link('account/account', '', true);
            $data['edit'] = $this->url->link('account/edit', '', true);
            $data['password'] = $this->url->link('account/password', '', true);
            $data['address'] = $this->url->link('account/address', '', true);
            $data['wishlist'] = $this->url->link('account/wishlist');
            $data['order'] = $this->url->link('account/order', '', true);
            $data['download'] = $this->url->link('account/download', '', true);
            $data['reward'] = $this->url->link('account/reward', '', true);
            $data['return'] = $this->url->link('account/return', '', true);
            $data['transaction'] = $this->url->link('account/transaction', '', true);
            $data['newsletter'] = $this->url->link('account/newsletter', '', true);
            $data['recurring'] = $this->url->link('account/recurring', '', true);

            return $this->load->view('extension/module/account', $data);
        }
	}
}