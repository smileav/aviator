<?php
class ControllerExtensionFeedUnixmlEkatalog extends Controller {
  public function index() {

    $feed = 'ekatalog';
    $controller = str_replace($feed, 'startup', $this->request->get['route']);
    $startup = $this->load->controller($controller, array('feed'=>$feed));
    $xml = false;

    //XML_body
      //headerXML
      $xml .= '<?xml version="1.0" encoding="utf-8"?>';
      $xml .= '<yml_catalog date="' . date('Y-m-d H:i', time()) . '">';
      $xml .= '<shop>';
      $xml .= '<name>' . $startup['name'] . '</name>';
      $xml .= '<url>' . HTTPS_SERVER . '</url>';
      $xml .= '<currencies>';
      $xml .= '<currency id="' . $startup['currency'] . '" rate="1"/>';
      $xml .= '</currencies>';

      if($startup['categories_xml']) {
        $xml .= '<categories>';
        foreach($startup['categories_xml'] as $category) {
          if($category['parent_id']){
            $xml .= '<category id="' . $category['category_id'] .'" ' . (($category['market_id'])?'rz_id="' . $category['market_id'] . '"':'') . ' parentId="' . $category['parent_id'] . '">' . $category['name'] .'</category>';
          } else{
            $xml .= '<category id="' . $category['category_id'] .'" ' . (($category['market_id'])?'rz_id="' . $category['market_id'] . '"':'') . '>' . $category['name'] .'</category>';
          }
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
              $xml .= '<item id="' . $product_id . '" available="' . ($product['stock']?'true':'false') .'">';
              $xml .= '<name>' . $product['name'] .  '</name>';
              $xml .= '<categoryId>' . $product['category_id'] .  '</categoryId>';
              $xml .= '<price>' . ($product['special']?$product['special']:$product['price']) .  '</price>';
              $xml .= '<url>' . $product['url'] .  '</url>';
              $xml .= '<image>' . $product['image'] .  '</image>';
              if($product['images']){
                foreach($product['images'] as $image){
                  $xml .= '<image>' . $image .  '</image>';
                }
              }
              foreach($product['attributes_full'] as $attribute){
                if(isset($attribute['decode']) && $attribute['decode']){
                  $attribute['text'] = html_entity_decode($attribute['text'], ENT_QUOTES, 'UTF-8');
                }
                $xml .= '<' . $attribute['name'] . '>' . $attribute['text'] .  '</' . $attribute['end'] . '>';
              }
              $xml .= '<vendor>' . $product['manufacturer'] .  '</vendor>';
              $xml .= '<description><![CDATA[' . $product['description'] .  ']]></description>';
              if($product['attributes']){
                foreach($product['attributes'] as $attribute){
                  $xml .= '<param name="' . $attribute['name'] . '">' . $attribute['text'] .  '</param>';
                }
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
      $xml .= '</shop>';
      $xml .= '</yml_catalog>';

      $this->unixml->exportToXml($controller_data['data'], $xml, "finish");
      //footerXML
    //XML_body

  }
}
