<?php
// curl_setopt($ch, CURLOPT_USERPWD, 'julia@glazursales1' . ':' . 'katya2023');
// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
$ch = curl_init();

// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
$ch = curl_init();

// curl_setopt($ch, CURLOPT_URL, 'https://api.moysklad.ru/api/remap/1.2/entity/product');
// curl_setopt($ch, CURLOPT_URL, 'https://api.moysklad.ru/api/remap/1.2/entity/product?filter=code=5781');
//curl_setopt($ch, CURLOPT_URL, 'https://api.moysklad.ru/api/remap/1.2/report/stock/bystore?filter=product=https://api.moysklad.ru/api/remap/1.2/entity/product/caa86d6b-4e89-11ee-0a80-119b0015b0be');
//curl_setopt($ch, CURLOPT_URL, 'https://api.moysklad.ru/api/remap/1.2/entity/variant?filter=productid=caa86d6b-4e89-11ee-0a80-119b0015b0be');

// 3a9e7f9a-16c3-11ec-0a80-0654001ac701
$_CURLOPT_URL = 'https://api.moysklad.ru/api/remap/1.2/report/stock/bystore?';
$_CURLOPT_URL .= 'filter=';
//$_CURLOPT_URL .= 'store=https://api.moysklad.ru/api/remap/1.2/entity/store/3a9e7f9a-16c3-11ec-0a80-0654001ac701;';
//$_CURLOPT_URL .= 'product=https://api.moysklad.ru/api/remap/1.2/entity/product/caa86d6b-4e89-11ee-0a80-119b0015b0be;';
$_CURLOPT_URL .= 'variant=https://api.moysklad.ru/api/remap/1.2/entity/variant/d51fa87a-4e89-11ee-0a80-0375001624cd;';
//$_CURLOPT_URL .= 'productid=https://api.moysklad.ru/api/remap/1.2/entity/product/caa86d6b-4e89-11ee-0a80-119b0015b0be;';





// curl_setopt($ch, CURLOPT_URL, 'https://api.moysklad.ru/api/remap/1.2/entity/variant?filter=productid=caa86d6b-4e89-11ee-0a80-119b0015b0be');
curl_setopt($ch, CURLOPT_URL, $_CURLOPT_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

// curl_setopt($ch, CURLOPT_USERPWD, 'login' . ':' . 'password');
curl_setopt($ch, CURLOPT_USERPWD, 'julia@glazursales1' . ':' . 'katya2023');

$headers = array();
$headers[] = 'Accept-Encoding: gzip';
$headers[] = 'Lognex-Pretty-Print-Json: true';

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);

if ($errors = isJson($result)) {
    print_r($errors);
} else {
    print_r(json_decode(gzdecode($result), true));
}

curl_close($ch);


function isJson($string) {
    if(!empty($string) && !is_array($string)) {
        if($string == '{}') {
            return [];
        }

        $res = json_decode($string, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            return $res;
        }
    }

    return false;
}
