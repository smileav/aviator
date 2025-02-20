<?php
class ModelExtensionTotalPersonalDiscont extends Model {
	public function getTotal($total) {
        if ($this->customer->isLogged()) {
            $this->load->language('extension/total/personal_discont');
            $this->load->model('account/customer');

            $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

            $personal_discont_percent = 0;
            if($customer_info){
                $personal_discont_percent = $customer_info['discont'];
            }
            $total_personal_discont = 0;

            foreach ($this->cart->getProducts() as $product) {
                $price = $this->tax->calculate($product['_price'], $product['tax_class_id'], $this->config->get('config_tax'));
                $total_product = $price * $product['quantity'];

                if (!is_null($product['_special']) && (float)$product['_special'] > 0) {
                    $special = $this->tax->calculate($product['_special'], $product['tax_class_id'], $this->config->get('config_tax'));
                    $total_special = $special * $product['quantity'];
                    $discont_special = $total_product - $total_special;
                } else {
                    $special = false;
                    $total_special = false;
                    $discont_special = 0;
                }

                if($personal_discont_percent){
                   $personal_discont = $total_product * $personal_discont_percent / 100;
                    if($personal_discont < $discont_special){
                        $personal_discont = 0;
                    }else{
                        $personal_discont = $personal_discont - $discont_special;
                    }

                }else{
                    $personal_discont = 0;
                }

                $total_personal_discont += $personal_discont;

                $data['products'][] = array(
                    'cart_id'       => $product['cart_id'],
                    'product_id'    => $product['product_id'],
                    'total'     => $total_product,
                    'total_special'     => $total_special,
                    'discont_special'     => $discont_special,
                    'personal_discont'     => $personal_discont,
                );

            }

            $text_personal_discont = $this->language->get('text_personal_discont') . ' ' . $personal_discont_percent . '%';

            if($total_personal_discont){
                $total['totals'][] = array(
                    'code'       => 'personal_discont',
                    'title'      => $text_personal_discont,
                    'value'      => $total_personal_discont,
                    'sort_order' => $this->config->get('total_personal_discont_sort_order')
                );

                $total['total'] -= $total_personal_discont;
            }
	    }
    }
}