<?php
class ControllerExtensionFeedUnixmlHotline extends Controller {
  public function index() {

    $feed = 'hotline';
    $controller = str_replace($feed, 'startup', $this->request->get['route']);
    $startup = $this->load->controller($controller, array('feed'=>$feed));
    $xml = false;

    //XML_body
      //headerXML
      $xml .= '<?xml version="1.0" encoding="UTF-8"?>';
      $xml .= '<price>';
      $xml .= '<date>' . date('Y-m-d H:i', time()) . '</date>';
      $xml .= '<firmName>' . $startup['name'] . '</firmName>';

      if($startup['categories_xml']) {
        $xml .= '<categories>';
        foreach($startup['categories_xml'] as $category) {
          $xml .= '<category>';
          $xml .= '<id>' . $category['category_id'] . '</id>';
          if($category['parent_id']){
            $xml .= '<parentId>' . $category['parent_id'] . '</parentId>';
          }
          $xml .= '<name>' . $category['name'] . '</name>';
          $xml .= '</category>';
        }
        $xml .= '</categories>';
      }

      $xml .= '<items>';

      $xml = $this->unixml->exportToXml($startup, $xml, "start");
      //headerXML

      //generateXML
        for($startup['iteration'] = 0; 1; $startup['iteration']++){

          $controller_data = $this->load->controller($controller, $startup);
          $startup['stat'] = $controller_data['data']['stat'];

          if($controller_data['products']){

            foreach($controller_data['products'] as $product_id => $product){
              $xml .= '<item>';
              $xml .= '<id>' . $product_id . '</id>';
              if(isset($product['group_id'])){
                $xml .= '<group_id>' . $product['group_id'] . '</group_id>';
              }
              $xml .= '<categoryId>' . $product['category_id'] .  '</categoryId>';
              $xml .= '<code>' . $product['model'] . '</code>';
              $xml .= '<vendor>' . $product['manufacturer'] .  '</vendor>';
              $xml .= '<name>' . $product['name'] .  '</name>';
              $xml .= '<url>' . $product['url'] .  '</url>';
              $xml .= '<image>' . $product['image'] .  '</image>';
              if($product['images']){
                foreach($product['images'] as $image){
                  $xml .= '<image>' . $image .  '</image>';
                }
              }
              $stock = $product['stock']?'В наявності':'Немає';
              if(isset($product['custom_stock']) && $product['custom_stock']){
                $stock = $product['custom_stock'];
              }
              $xml .= '<stock>' . $stock .  '</stock>';
              if($product['special']){
                $xml .= '<priceRUAH>' . $product['special'] .  '</priceRUAH>';
                $xml .= '<oldprice>' . $product['price'] .  '</oldprice>';
              }else{
                $xml .= '<priceRUAH>' . $product['price'] .  '</priceRUAH>';
              }
              $xml .= '<description><![CDATA[' . $product['description'] .  ']]></description>';
              foreach($product['attributes_full'] as $attribute){
                if(isset($attribute['decode']) && $attribute['decode']){
                  $attribute['text'] = html_entity_decode($attribute['text'], ENT_QUOTES, 'UTF-8');
                }
                $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
              }
              foreach($product['attributes'] as $attribute){
                $xml .= '<param name="' . $attribute['name'] . '">' . $attribute['text'] .  '</param>';
              }
              $xml .= '</item>';
            }
          } else {
            break;
          }

          $xml = $this->unixml->exportToXml($controller_data['data'], $xml);
        }
      //generateXML

      //footerXML
      $xml .= '</items>';
      $xml .= '</price>';

      $this->unixml->exportToXml($controller_data['data'], $xml, "finish");
      //footerXML
    //XML_body

  }
}
