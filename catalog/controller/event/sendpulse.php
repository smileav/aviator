<?php
class ControllerEventSendpulse extends Controller {

	// catalog/controller/common/cart/info/after
	//всі зміни по кошику відправляються в сендпульс
	public function cart($route, &$args,&$output) {

		if(!isset($this->session->data['customer'])||!$this->cart->hasProducts()) return;

		$customer=$this->session->data['customer'];

		$products=[];

		foreach($this->cart->getProducts() as $product) {

			if($product['image']) {
				$image = $this->model_tool_image->resize($product['image'], 336, 504, 'auto');
			}else{
				$image = $this->model_tool_image->resize('placeholder.png', 336, 504, 'auto');
			}

			$products[]=[
				"products_name"=> $product['name'],
				"products_id"=> $product['product_id'],
				"price"=> $product['price'],
				"img_url"=> $image,
				"count"=> $product['quantity'],
				"size"=> isset($product['option'][0])?$product['option'][0]['value']:'',
				"url"=> $this->url->link('product/product', 'product_id=' . $product['product_id'])
			];
		}

		$data=[
			"email"=> isset($customer['email']) ? $customer['email'] : '',
  			"phone"=> isset($customer['telephone']) ? preg_replace("/[^+0-9]/", '', $customer['telephone']) : '',
  			"user_id"=>isset($customer['telephone']) ? preg_replace("/[^0-9]/", '', $customer['telephone']) : '',
  			"event_date"=> date('Y-m-d'),
  			"currency"=> $this->session->data['currency'],
  			"cart_total"=> $this->cart->getTotal(),
  			"customer_name"=> isset($customer['firstname']) ? ($customer['firstname'].' '. $customer['lastname']) : '',
  			//"discount"=> 123,
  			"products"=> $products
		];

		$this->send('https://events.sendpulse.com/events/id/a53983d925744ef4bee52f65804e0a18/8835430',$data);

	}

	private function send($action,$data) {
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $action,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS =>json_encode($data),
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json'
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		$this->log->write($response);
	}
}
