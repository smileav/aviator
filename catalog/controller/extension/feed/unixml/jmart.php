<?php
class ControllerExtensionFeedUnixmlJmart extends Controller {
  public function index() {

    $feed = 'kaspi';
    $controller = str_replace($feed, 'startup', $this->request->get['route']);
    $startup = $this->load->controller($controller, array('feed'=>$feed));
    $xml = false;

    //XML_body
      //headerXML
      $xml .= '<?xml version="1.0" encoding="utf-8"?>';
      $xml .= '<Jmart xmlns="Jmart" date="' . date('Y-m-d H:i', time()) . '">';
      $xml .= '<company>' . $this->config->get('config_name') . '</company>';
      $xml .= '<merchantid>' . $startup['name'] . '</merchantid>';
      $xml .= '<offers>';

      $xml = $this->unixml->exportToXml($startup, $xml, "start");
      //headerXML

      //generateXML
        for($startup['iteration'] = 0; 1; $startup['iteration']++){

          $controller_data = $this->load->controller($controller, $startup);
          $startup['stat'] = $controller_data['data']['stat'];

          if($controller_data['products']){

            foreach($controller_data['products'] as $product_id => $product){
              $xml .= '<offer sku="' . $product['model'] . '">'; // available="' . ($product['stock']?'true':'false') .'"
              $xml .= '<model>' . $product['name'] .  '</model>';
              if(isset($product['availabilities'])){
                $xml .= '<availabilities>';
                  foreach($product['availabilities'] as $store_id){
                    $xml .= '<availability available="yes" storeId="' . $store_id . '" />';
                  }
                $xml .= '</availabilities>';
              }
              if($product['special']){
                $xml .= '<price>' . $product['special'] . '</price>';
              }else{
                $xml .= '<price>' . $product['price'] . '</price>';
              }
              foreach($product['attributes_full'] as $attribute){
                if(isset($attribute['decode']) && $attribute['decode']){
                  $attribute['text'] = html_entity_decode($attribute['text'], ENT_QUOTES, 'UTF-8');
                }
                $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
              }
              $xml .= '</offer>';
            }
          } else {
            break;
          }

          $xml = $this->unixml->exportToXml($controller_data['data'], $xml);
        }
      //generateXML

      //footerXML
      $xml .= '</offers>';
      $xml .= '</Jmart>';

      $this->unixml->exportToXml($controller_data['data'], $xml, "finish");
      //footerXML
    //XML_body

  }
}
