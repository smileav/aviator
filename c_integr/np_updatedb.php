<?php

if(isset($_GET['upd'])) $w_upd=$_GET['upd'];
if(isset($argv[1])) $w_upd=$argv[1];

ini_set('display_errors',1);
ini_set('display_startup_errors',1);




require_once(dirname(__FILE__).'/lib/NovaPoshtaApi2.php');
require_once(dirname(__FILE__).'/config.php');
//require_once(dirname(__FILE__).'/functions_np.php');


$res=mysqli_query($db, "select vars_code,vars_name, vals,`default` from np_config");
while(list($vcode,$vname,$vals,$def)=mysqli_fetch_row($res)){

	$$vcode=$vals;
	$DEFAULT[$vcode]=$def;
}


$np = new NovaPoshtaApi2($NP_KEY);








if($w_upd=='download'){

        $cities_txt = file_get_contents('https://i.uastocks.com/exe/globalnp/areas.json');
	$cities=json_decode($cities_txt,JSON_UNESCAPED_UNICODE);

	foreach($cities as $k=>$v){


		$res=mysqli_query($db,"select  id,title_ru, title_ua from np_areas where id_ref='{$v['id_ref']}'");
		list($chid,$otitle_ru,$otitle_ua)=mysqli_fetch_row($res);
		$err=mysqli_error($db);
		if($err) var_dump($err);

		if(!$chid){
			mysqli_query($db,"insert into np_areas set id_ref='{$v['id_ref']}', title_ru='".addslashes($v['title_ru'])."',title_ua='".addslashes($v['title_ua'])."',  area='', upd='{$v['upd']}'");


		}

	}


        $cities_txt = file_get_contents('https://i.uastocks.com/exe/globalnp/cities.json');
	$cities=json_decode($cities_txt,JSON_UNESCAPED_UNICODE);
	foreach($cities as $k=>$v){
		$res=mysqli_query($db,"select  id,title_ru, title_ua,upd from np_cities where id_ref='{$v['id_ref']}'");
		list($chid,$otitle_ru,$otitle_ua,$oldupd)=mysqli_fetch_row($res);
		$err=mysqli_error($db);
		if($err) var_dump($err);

		if(!$chid){
			mysqli_query($db,"insert into np_cities set id_ref='{$v['id_ref']}', title_ru='".addslashes($v['title_ru'])."',title_ua='".addslashes($v['title_ua'])."', ms='', area='{$v['area']}', upd='{$v['upd']}',region='".addslashes($v['region'])."'");
			$err=mysqli_error($db);
			if($err) var_dump($err);


		}else{
		 	if($oldupd<$v['upd']){
				mysqli_query($db,"update np_cities set id_ref='{$v['id_ref']}', title_ru='".addslashes($v['title_ru'])."',title_ua='".addslashes($v['title_ua'])."', area='{$v['area']}', upd='{$v['upd']}',region='".addslashes($v['region'])."' where id='$chid'");
			}
		}

	}

	
        $txt = file_get_contents('https://i.uastocks.com/exe/globalnp/ware.json');
	$json=json_decode($txt,JSON_UNESCAPED_UNICODE);
	foreach($json as $k=>$v){
		$res=mysqli_query($db,"select  id,title_ru, title_ua,upd from np_ware where id_ref='{$v['id_ref']}'");
		list($chid,$otitle_ru,$otitle_ua,$oldupd)=mysqli_fetch_row($res);
		$err=mysqli_error($db);
		if($err) var_dump($err);

		if(!$chid){
			mysqli_query($db,"insert into np_ware set id_ref='{$v['id_ref']}', title_ru='".addslashes($v['title_ru'])."',title_ua='".addslashes($v['title_ua'])."', ms='', number='{$v['number']}', city='{$v['city']}', upd='{$v['upd']}',service_type=''");
			$err=mysqli_error($db);
			if($err) var_dump($err);


		}else{
		 	if($oldupd<$v['upd']){
				mysqli_query($db,"update np_ware set id_ref='{$v['id_ref']}', title_ru='".addslashes($v['title_ru'])."',title_ua='".addslashes($v['title_ua'])."', number='{$v['number']}', city='{$v['city']}', upd='{$v['upd']}',service_type='',newname='1' where id='$chid'");
			}

		}

	}


}



if($w_upd=='cities'){



	$areas = array();
	$p=0;
	$tmp = $np->getAreas($p);
	$areas = array_merge($areas,$tmp['data']);


	mysqli_query($db,"truncate np_areas");

	foreach ($areas as $city) {
		mysqli_query($db,"insert into np_areas set id_ref='{$city['Ref']}', title_ru='".addslashes($city['DescriptionRu'])."',title_ua='".addslashes($city['Description'])."'");
		//var_dumP(mysqli_error($db));
	}




	$cities = array();
	$p=0;
	$tmp = $np->getCities($p);
	$cities = array_merge($cities,$tmp['data']);
//	mysqli_query($db,"truncate np_cities");

	foreach ($cities as $city) {

		$res=mysqli_query($db,"select  id,title_ru, title_ua from np_cities where id_ref='{$city['Ref']}'");
		list($chid,$otitle_ru,$otitle_ua)=mysqli_fetch_row($res);
		if(!$chid){
			mysqli_query($db,"insert into np_cities set id_ref='{$city['Ref']}', title_ru='".addslashes($city['DescriptionRu'])."',title_ua='".addslashes($city['Description'])."', ms='',
			area='{$city['Area']}'");

		}else{
			if(($city['DescriptionRu']!=$otitle_ru) or ($otitle_ua!=$city['Description'])) {
				mysqli_query($db,"update np_cities set  title_ru='".addslashes($city['DescriptionRu'])."',title_ua='".addslashes($city['Description'])."',
				area='{$city['Area']}',ms='' where id='$chid'");
				mysqli_query($db,"update np_ware set newname='1' where city='{$city['Ref']}'");			
			}

		}
	
	}

	echo '[База городов загружена] <a href="np_updatedb.php?upd=ware">Загрузить отделения &raquo;</a>'.
"<script>setTimeout(window.location='np_updatedb.php?upd=ware',3000);</script>";exit;

}


if($w_upd=='region'){


	$cities = array();
	$p=0;
	$tmp = $np->getCities($p);
	$cities = array_merge($cities,$tmp['data']);

//	mysqli_query($db,"truncate np_cities");

	foreach ($cities as $city) {
		$RGNS=null;

		$rx=mysqli_query($db,"select id from  np_cities where id_ref='{$city['Ref']}' and ( region='' or ISNULL(region))");
		list($checkid)=mysqli_fetch_Row($rx);

		if($checkid){
			$np_link="https://api.novaposhta.ua/v2.0/json/";
			$sen_cre["apiKey"]=$NP_KEY;
			$sen_cre['modelName']="AddressGeneral";
			$sen_cre["calledMethod"]="getSettlements";
			$sen_cre["methodProperties"]=array(
				"FindByString"=>$city['DescriptionRu'],
				"Warehouse"=>1
			);
		

			$res=np_query_send($np_link,$sen_cre);

			foreach($res['data'] as $kd=>$vd){
				$RGNS[$vd["Region"]]=$vd["RegionsDescriptionRu"];
			}       	

			mysqli_query($db,"update np_cities set region='".addslashes(json_encode($RGNS))."' where id_ref='{$city['Ref']}'");
		}
		


	}





	echo '[База регионов обновлена]';exit;
}



if($w_upd=='ware'){
	$ff=@fopen(dirname(__FILE__)."/updpage","r");
	$page=@fread($ff,1000);
	@fclose($ff);
	$limit=50;
	$res=mysqli_query($db,"select count(id) from np_cities ");
	list($num)=mysqli_fetch_row($res);

	$num_page=ceil($num/$limit);

	$off=$page*$limit;
	$next=$page+1;
	if($page==$num_page) { $page=0; $next=1; }
	$count=0;
	$proc=$next*100/$num_page;

	$res=mysqli_query($db,"select id_ref,title_$lang from np_cities order by id limit $off,$limit");
	while(list($id_ref,$ctitle)=mysqli_fetch_row($res)){
		$count++;
		$warehouses = array();
		$p = 0;
		do {
			$tmp = $np->getWarehouses($id_ref,$p);
			if(empty($tmp['data']) ) {
				break;
			}
			foreach ($tmp['data'] as $w) {
				$wh_exist[] = $w['Ref'];
				$warehouses[] = $w;
			}
			$p++; 
			if($p == $limit) { break;}
		} while(true);
		foreach ($warehouses as $wh) {
			try {	

				$chid=NULL;
				$resxf=mysqli_query($db,"select id,title_ua from np_ware where  id_ref='".$wh['Ref']."'");
				list($chid,$chtitle_ua)=mysqli_fetch_row($resxf);
				$nnupd='';
				if($chtitle_ua!=$wh['Description']) {
					$nnupd=",newname='1'";
				}
				if($chid) {
					mysqli_query($db,"update np_ware set id_ref='{$wh['Ref']}',title_ru='".addslashes($wh['DescriptionRu'])."',
						title_ua='".addslashes($wh['Description'])."',city='$id_ref',number='{$wh['Number']}',service_type='' $nnupd where id_ref='{$wh['Ref']}'");			
				}else{
					mysqli_query($db,"insert into np_ware set id_ref='{$wh['Ref']}',title_ru='".addslashes($wh['DescriptionRu'])."',service_type='',ms='',
						title_ua='".addslashes($wh['Description'])."',city='$id_ref',number='{$wh['Number']}'");			

				}

			} catch (Exception $e) {
				echo $e->getMessage,"\n";//,json_encode($dicElem);
				continue;
			}

		} // end foreach warehouses

	} // end foreach cities
    	if($count<$limit) { $next=0;   echo("[База отделений загружена на 100".'%'.'] <br>
		<a href="np_setup.php">Можете продолжать настройку интеграции</a>');  
//		<b>Импорт отделений Шаг 2: <a href="np_updatedb.php?upd=ms_ware">Загрузить отделения в Мойсклад</b></a>');  
	}
    	else     echo("<b>Импорт отделений Шаг 1:</b> [База отделений загружена на $proc".'%'.'] <br>Ожидайте пожалуйста окончания загрузки отделений в базу данных, дальнейшие инструкции последуют :)'. //Обновите страницу или нажмите на <a href="np_updatedb.php?upd=ware">ссылку для продложения импорта</a>
	"<script>setTimeout(window.location='np_updatedb.php?page=$next&upd=".$_GET['upd']."',3000);</script>"); 


    	$hand=fopen(dirname(__FILE__)."/updpage","w");
    	fwrite($hand,$next);
    	fclose($hand);
}


if($w_upd=='ms_ware'){

	$BIG['Харьков']='Харьков';
	$BIG['Киев']='Киев';
	$BIG['Львов']='Львов';
	$BIG['Винница']='Винница';
	$BIG['Луцк']='Луцк';
	$BIG['Днепр ']='Днепр';
	$BIG['Донецк']='Донецк';
	$BIG['Житомир']='Житомир';
	$BIG['Ужгород']='Ужгород';
	$BIG['Запорожье']='Запорожье';
	$BIG['Ивано-Франковск']='Ивано-Франковск';
	$BIG['Кропивницкий']='Кропивницкий';
	$BIG['Николаев']='Николаев';
	$BIG['Одесса']='Одесса';
	$BIG['Полтава']='Полтава';
	$BIG['Ровно']='Ровно';
	$BIG['Сумы']='Сумы';
	$BIG['Тернополь']='Тернополь';
	$BIG['Херсон']='Херсон';
	$BIG['Хмельницкий']='Хмельницкий';
	$BIG['Черкассы']='Черкассы';
	$BIG['Чернигов']='Чернигов';
	$BIG['Черновцы']='Черновцы';



	$lang=$DEFAULT['LANG'];

	$resxf=mysqli_query($db,"select count(id) from np_ware order by id");
	list($num)=mysqli_fetch_row($resxf);


	$resxf=mysqli_query($db,"select count(id) from np_ware where (ms!='' and !isnull(ms)) and newname!='1' order by id");
	list($count)=mysqli_fetch_row($resxf);


	$proc=number_format($count*100/$num, 2, ',', ' ');

	$limit=100;

	$resxf=mysqli_query($db,"select number,id,id_ref,title_$lang,city from np_ware where ms='' or isnull(ms) or newname='1' order by id limit 0,$limit");
	while(list($number,$chid,$code,$title,$city_id)=mysqli_fetch_row($resxf)){
		$ms_data=null;
		$resxf2=mysqli_query($db,"select title_$lang from np_cities where id_ref='$city_id'");
		list($city)=mysqli_fetch_row($resxf2);
//		if($lang=='ua')	if($BIG[$city]) $city=$city.' місто';
//		else if($BIG[$city]) $city=$city.' город';
		$ms_data['name']=$city.", ".$title;
		$ms_data['code']=$city.' '.$number;
		$ms_data['description']=$code;

		if($MS_WARE){
			$link2="https://api.moysklad.ru/api/remap/1.2/entity/customentity/".$MS_WARE."?filter=code=$code";
			$json2=ms_query($link2);
			if(!isset($json2['rows'][0]['id'])){


				$link2="https://api.moysklad.ru/api/remap/1.2/entity/customentity/".$MS_WARE."?filter=description=$code";
				$json2=ms_query($link2);
				if(!isset($json2['rows'][0]['id'])){
					$link2="https://api.moysklad.ru/api/remap/1.2/entity/customentity/".$MS_WARE."?filter=name=".urlencode($city.", ".$title);
					$json2=ms_query($link2);
				}
			}
	
			if(isset($json2['rows'][0]['id']) and $json2['rows'][0]['id']){
				if($title){
					$link="https://api.moysklad.ru/api/remap/1.2/entity/customentity/$MS_WARE/{$json2['rows'][0]['id']}";
					$json=ms_query_send($link,$ms_data,'PUT');

					mysqli_query($db,"update np_ware set ms='{$json2['rows'][0]['id']}',newname='0' where id='$chid'");			
				}

			}else{
				if(isset($json2['rows'])) {
					if($title){
						$link="https://api.moysklad.ru/api/remap/1.2/entity/customentity/$MS_WARE";
						$json=ms_query_send($link,$ms_data,'POST');
				
						if(!isset($json['errors'])) mysqli_query($db,"update np_ware set ms='{$json['id']}',newname='0' where id='$chid'");
					}
				}
			}

		}

	}

    	if(($num-$count)<$limit) { $next=0;   echo("[База отделений загружена в мойсклад 100".'%'.']');  }
    	else     echo("<b>Импорт отделений в МойСклад Шаг 2:</b> [База отделений загружена на $proc".'%'.']'.
	"<script>setTimeout(window.location='np_updatedb.php?upd=".$_GET['upd']."',3000);</script>"); 

	echo("Загрузка завершена. <a href='np_setup.php'>Можете продолжать настройку</a>");



}




if($w_upd=='ms_cities'){
//	$MS_CITIES='3f74ea44-2536-11e9-9ff4-34e800038b34';
	$lang=$DEFAULT['LANG'];

	if($MS_CITIES){
	$resxf=mysqli_query($db,"select id,id_ref,title_$lang from np_cities where ms='' or isnull(ms) order by id");
	while(list($chid,$code,$title)=mysqli_fetch_row($resxf)){


		$ms_data['name']=$title;
		$ms_data['code']=$code;


		$link2="https://api.moysklad.ru/api/remap/1.2/entity/customentity/$MS_CITIES?filter=code=$code";
		$json2=ms_query($link2);
		if(isset($json2['errors'][0]['error']))exit();

		if(isset($json2['rows'][0]['id'])){
			if($title){
				$link="https://api.moysklad.ru/api/remap/1.2/entity/customentity/$MS_CITIES/{$json2['rows'][0]['id']}";
				$json=ms_query_send($link,$ms_data,'PUT');
				mysqli_query($db,"update np_cities set ms='{$json2['rows'][0]['id']}' where id='$chid'");			
			}

		}else{
			if(isset($json2['rows'])) {
				if($title){
		
					$link="https://api.moysklad.ru/api/remap/1.2/entity/customentity/$MS_CITIES";
					$json=ms_query_send($link,$ms_data,'POST');

					if(!isset($json['errors'])) mysqli_query($db,"update np_cities set ms='{$json['id']}' where id='$chid'");
				}
			}
		}
		


	}}


}



