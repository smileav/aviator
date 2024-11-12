<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
require_once(dirname(__FILE__).'/lib/NovaPoshtaApi2.php');
require_once(dirname(__FILE__).'/config.php');


$langs=array('ua'=>'UA','ru'=>'RU');

$res=mysqli_query($db, "select vars_code,vars_name, vals,`default` from np_config");
while(list($vcode,$vname,$vals,$def)=@mysqli_fetch_row($res)){
	$$vcode=$vals;
	if($vcode=='STAT_ACCORD') 	$DEFAULT[$vcode]=unserialize($def);
	else $DEFAULT[$vcode]=$def;
}

if(!isset($DEFAULT['NP_STATE_TYPE']) or ($DEFAULT['NP_STATE_TYPE']!=1)) $NP_STATE_N=$NP_STATE;



if(isset($_POST['submit_login'])&&$_POST['submit_login']){
	$username=strip_tags($_POST['username']);




	if($SETUP_LOGIN!=$username) setcookie("mes",1,time()+(3600),$SITE_['path'],$SITE_['host']);
	else{

		$md_password=md5($_POST['password']);
		$chpsw=md5($SETUP_PASSWORD);
		if($md_password==$chpsw){

			setcookie("auth_login","$username",time()+(3600*24),$SITE_['path'],$SITE_['host']);
			setcookie("auth_key","$md_password",time()+(3600*24),$SITE_['path'],$SITE_['host']);
		}else{
			setcookie("mes",1);
		}	


	}

	header("Location: $SITE"."np_setup.php"); exit();

}









if(isset($_COOKIE['auth_login'])&&isset($_COOKIE['auth_key'])&&($SETUP_LOGIN==$_COOKIE['auth_login']) and (md5($SETUP_PASSWORD)==$_COOKIE['auth_key']) and $db){


	$res_config=mysqli_query($db, "select vars_code,vars_name, vals,`default` from np_config");


	 	while(list($vars_code,$vars_name, $vals,$default)=mysqli_fetch_row($res_config)){
	 	 	$NP_CONFIG[$vars_code]=$default;


		}


//	}


}



if(isset($_GET['clear']) and ($_GET['clear']==1)){

	$CHECK_MS=true;
	$page=0;
	$limit=100;
	while($CHECK_MS){
		$offset=$limit*$page;

		$link2="https://api.moysklad.ru/api/remap/1.2/entity/customentity/".$MS_WARE.'?limit='.$limit.'&ofsset='.$offset;

		$json=ms_query($link2);
		foreach ( $json['rows'] as  $k=>$v) {


			$linkx="{$v['meta']['href']}";
			ms_query_send($linkx,'',"DELETE");

		}

		if(!count($json['rows'])) { $CHECK_MS=false; } 
		$page++;
	}

	mysqli_query($db,"update np_ware set newname='1'");
	header("Location: $SITE"."np_setup.php"); exit();
	

}





if(isset($NP_CONFIG['ENTITY'])){
$link="https://api.moysklad.ru/api/remap/1.2/entity/".$NP_CONFIG['ENTITY']."/metadata";
$json=ms_query($link);
if(isset($json['states'])) foreach($json['states'] as $k=>$v){
 	$STATN[$v['name']]=$v['name'];
}}





$np_link="https://api.novaposhta.ua/v2.0/json/";
$sen_cre["apiKey"]=$NP_KEY;
$sen_cre['modelName']="Counterparty";
$sen_cre["calledMethod"]="getCounterparties";
$sen_cre["methodProperties"]=array(
	"CounterpartyProperty"=>"Sender",
	"Page"=> "1"
);
$res=np_query_send($np_link,$sen_cre);

foreach($res['data'] as $ka=>$va){
	$CONT[$va['Ref']]=$va['Description'];
	$CONT_CITY[$va['Ref']]=$va['City'];

}


if(isset($DEFAULT['SENDER'])){
	$np_link="https://api.novaposhta.ua/v2.0/json/";
	$sen_cre["apiKey"]=$NP_KEY;
	$sen_cre['modelName']="Counterparty";
	$sen_cre["calledMethod"]="getCounterpartyContactPersons";
	$sen_cre["methodProperties"]=array(
			"Ref"=>"{$DEFAULT['SENDER']}",
			"Page"=> "1"
	);


	$res2=np_query_send($np_link,$sen_cre);
	foreach($res2['data'] as $ka=>$va){
		$DEF_PHONE=$va['Phones'];
		$CONT_C[$va['Ref']]=$va['Description'];

	}

	////////
	$np_link="https://api.novaposhta.ua/v2.0/json/";
	$sen_cre["apiKey"]=$NP_KEY;
	$sen_cre['modelName']="Counterparty";
	$sen_cre["calledMethod"]="getCounterpartyAddresses";
	$sen_cre["methodProperties"]=array(
			"Ref"=>"{$DEFAULT['SENDER']}",
			"Page"=> "1"
	);

}
$res=np_query_send($np_link,$sen_cre);
//var_dump($res);



if(isset($_POST['submit_field'])){
	$link="https://api.moysklad.ru/api/remap/1.2/entity/".$NP_CONFIG['ENTITY']."/metadata/attributes";

	foreach($_POST['field_name'] as $k=>$v){

		$f_data=$ms_data=null;
		if(($k=='MEST_MS')||($k=='PHONE_RECEP')||($k=='FIO_RECEP')||($k=='TTN_MS')||($k=='ADDR_ADDR')||($k=='VES_MS')||($k=='WD_MS')||($k=='MS_STATE')||($k=='COST_MS')){
			$f_data['name']=$v;
		        $f_data["type"]= "string";
          		$f_data["required"]=false;
			if($k=='ADDR_ADDR') $f_data["description"]="Формат адреса: Город, Улица, Дом, Квартира";
			if($k=='VES_MS') $f_data["description"]="Вес. Для нескольких мест, указывается через запятую для каждого места";
			if($k=='WD_MS') $f_data["description"]="Формат ввода: Ш*В*Д. Для нескольких мест, указывается через запятую для каждого места";
			if(($k=='COST_MS')||($k=='MS_STATE')) $f_data["description"]="Поле возвращаемое НП (не требуется заполнять)";


		}
		if(($k=='CREATE_TTN_MS')||($k=='WHOPAY_BCK_MS')||($k=='WHOPAY_MS')||($k=='PAYMENT_CONTROL')){
			$f_data['name']=$v;
		        $f_data["type"]= "boolean";
          		$f_data["required"]=false;

		}
		if(($k=='BACK_SUM')||($k=='OCENKA')){
			$f_data['name']=$v;
		        $f_data["type"]= "double";
          		$f_data["required"]=false;

		}
		if($k=='OCENKA'){
			$f_data['name']=$v;
		        $f_data["type"]= "double";
			$f_data["description"]="Если поле не заполнено, то берется сумма заказа";
          		$f_data["required"]=false;

		}

		if(($k=='PDF_MS')){
			$f_data['name']=$v;
		        $f_data["type"]= "link";
          		$f_data["required"]=false;

		}
		if(($k=='ERR_MS')){
			$f_data['name']=$v;
		        $f_data["type"]= "text";
          		$f_data["required"]=false;

		}
		if(($k=='DATA_MS')){
			$f_data['name']=$v;
		        $f_data["type"]= "time";
          		$f_data["required"]=false;

		}



		if($k=='SKLAD_MS'){

			$link_ent="https://api.moysklad.ru/api/remap/1.2/entity/customentity";
			$ent_data=null;
			$ent_data['name']=$v;
			$res_ent=ms_query_send($link_ent,  $ent_data,'POST');

			if($res_ent['id']) 	{
				mysqli_query($db,"update np_config set `vals`='{$res_ent['id']}' where vars_code='MS_WARE'");		
				$f_data['name']=$v;
			        $f_data["type"]= "customentity";
			        $f_data["customEntityMeta"]= array('href'=>'https://api.moysklad.ru/api/remap/1.2/context/companysettings/metadata/customEntities/'.$res_ent['id'],'type'=>'customentitymetadata','mediaType'=> "application/json");
	          		$f_data["required"]=false;
//				mysqli_query($db,"update np_config set `vals`='{$res_ent['id']}' where vars_code='MS_WARE'");		

			}else{
				echo("<font color=red>{$res_ent['errors'][0]['error']}</font>"); exit();								
			}



		}


		if($k=='SENDER'){

			$link_ent="https://api.moysklad.ru/api/remap/1.2/entity/customentity";
			$ent_data=null;
			$ent_data['name']=$v;
			$res_ent=ms_query_send($link_ent,  $ent_data,'POST');

			if($res_ent['id']) 	{
				mysqli_query($db,"update np_config set `vals`='{$res_ent['id']}' where vars_code='MS_SENDER'");		
				$f_data['name']=$v;
			        $f_data["type"]= "customentity";
			        $f_data["customEntityMeta"]= array('href'=>'https://api.moysklad.ru/api/remap/1.2/context/companysettings/metadata/customEntities/'.$res_ent['id'],'type'=>'customentitymetadata','mediaType'=> "application/json");
	          		$f_data["required"]=false;

			}else{
				echo("<font color=red>{$res_ent['errors'][0]['error']}</font>"); exit();								
			}



		}

		if($k=='SEND_WARE_NEW'){

			$link_ent="https://api.moysklad.ru/api/remap/1.2/entity/customentity";
			$ent_data=null;
			$ent_data['name']=$v;
			$res_ent=ms_query_send($link_ent,  $ent_data,'POST');

			if($res_ent['id']) 	{
				mysqli_query($db,"update np_config set `vals`='{$res_ent['id']}' where vars_code='MS_WARE_SENDER'");		
				$f_data['name']=$v;
			        $f_data["type"]= "customentity";
			        $f_data["customEntityMeta"]= array('href'=>'https://api.moysklad.ru/api/remap/1.2/context/companysettings/metadata/customEntities/'.$res_ent['id'],'type'=>'customentitymetadata','mediaType'=> "application/json");
	          		$f_data["required"]=false;

			}else{
				echo("<font color=red>{$res_ent['errors'][0]['error']}</font>"); exit();								
			}



		}



		if($k=='CITY_RECIPIENT'){

			$link_ent="https://api.moysklad.ru/api/remap/1.2/entity/customentity";
			$ent_data=null;
			$ent_data['name']=$v;
			$res_ent=ms_query_send($link_ent,  $ent_data,'POST');

			if($res_ent['id']) 	{
				mysqli_query($db,"update np_config set `vals`='{$res_ent['id']}' where vars_code='MS_CITIES'");		
				$f_data['name']=$v;
			        $f_data["type"]= "customentity";
			        $f_data["customEntityMeta"]= array('href'=>'https://api.moysklad.ru/api/remap/1.2/context/companysettings/metadata/customEntities/'.$res_ent['id'],'type'=>'customentitymetadata','mediaType'=> "application/json");
	          		$f_data["required"]=false;
//				mysqli_query($db,"update np_config set `vals`='{$res_ent['id']}' where vars_code='MS_WARE'");		

			}else{
				echo("<font color=red>{$res_ent['errors'][0]['error']}</font>"); exit();								
			}



		}

		$ms_data[]=$f_data;

		$res=ms_query_send($link,  $ms_data,'POST');
		mysqli_query($db,"update np_config set `vals`='{$res['id']}' where vars_code='$k'");		
	}
	header("Location: np_setup.php"); exit();

}

if(isset($_POST['submit'])){

	$res=mysqli_query($db, "select vars_code,vars_name, vals,`default` from np_config where vars_code!='MS_CITIES' and vars_code!='MS_WARE' and (service_type='' or service_type='np')");
	while(list($vcode,$vname,$vals,$def)=mysqli_fetch_row($res)){
		if(!isset($_POST['sel'][$vcode])) $_POST['sel'][$vcode]=null;
		if(!isset($_POST['def'][$vcode])) $_POST['def'][$vcode]=null;
		if($vcode=='STAT_ACCORD') {

/*
		        $STATS=array();
var_dump($_POST['def'][$vcode]);
			foreach($_POST['def'][$vcode] as $kp=>$vp){
				if($vp['stat_np'] or $vp['stat_ms']) {
					$STATS[]=$vp;
				}
			}
*/
			$cards=serialize($_POST['def'][$vcode]);

			mysqli_query($db,"update np_config set vals='{$_POST['sel'][$vcode]}', `default`='$cards' where vars_code='$vcode'");
		}else 	{

			mysqli_query($db,"update np_config set vals='{$_POST['sel'][$vcode]}', `default`='{$_POST['def'][$vcode]}' where vars_code='$vcode'");
		}

	}

	header("Location: np_setup.php"); exit();
}

?>


<!DOCTYPE html>
<html lang="ru">
	<head>
		<title>Настройка интеграции Новая почта + МойСклад</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
		<link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	</head>
	<body>



<?php

if(isset($_COOKIE['auth_login'])&&isset($_COOKIE['auth_key'])&&($SETUP_LOGIN==$_COOKIE['auth_login']) and (md5($SETUP_PASSWORD)==$_COOKIE['auth_key'])){


if(isset($NP_CONFIG['ENTITY']) and $NP_CONFIG['ENTITY']){
	$link='https://api.moysklad.ru/api/remap/1.2/entity/'.$NP_CONFIG['ENTITY'].'/metadata/attributes';
	$json=ms_query($link);

	if(isset($json['errors']) and $json['errors'][0]['code']==1056) echo("<font color=red>Проверьте данные Моего склада: ".$json['errors'][0]['error']."</font><BR>");
	if(isset($json['rows']))foreach($json['rows'] as $ka=>$va){

		$ATTR[$va['id']]=$va['name'];
	}
}
if(!isset($CONT)) echo("<font color=red>Проблемы с ключем API к новой почте</font><BR>");
if(!checkLicence()) { echo("<font color=red>Лицензия не активна</font>"); }

?>
<form method=post>
<center>
<br>
<?php $menu=implode(" / ",$MENU);
echo($menu); ?>

<br><br>

<table border=0 class="table" style="width:1200px;">
<?php



$num_cities=0;
$DISABLE=''; $NP_CITY=null;
$bo=true;
$res=mysqli_query($db, "select vars_code,vars_name, vals,`default` from np_config where vars_code!='MS_SENDER' and vars_code!='MS_WARE_SENDER' and vars_code!='MS_CITIES' and vars_code!='MS_WARE' and (service_type='' or service_type='np')");

while(list($vcode,$vname,$vals,$def)=mysqli_fetch_row($res)){
	
	

	  $SEL="<select $DISABLE name=sel[$vcode]><option value=''> - -";
	  if(isset($ATTR)) foreach($ATTR as $ka=>$va){
		if($ka==$vals) $sl="selected"; else $sl='';
	  	$SEL.="<option $sl value='$ka'>$va";
 	  }
	  $SEL.="</select>";
	  $help='';

	  if($vcode=='ENTITY') {
		$DEF="<select name=def[$vcode]><option value=''> - -";
		if($def=='demand')$sel='selected'; else $sel='';
		$DEF.="<option $sel value='demand'>Отгрузки</option>";
		if($def=='customerorder')$sel='selected';else $sel='';
		$DEF.="<option $sel value='customerorder'>Заказы</option>";
		$DEF.="</select>";
		if(!$def) $DISABLE='disabled';
	  }elseif($vcode=='SENDER') {

		if($vals) $sender_in_field=1;

		$DEF="<select $DISABLE name=def[$vcode]><option value=''> - -";
		if(isset($CONT)) foreach($CONT as $ka=>$va){
			if($def==$ka) {
				$SEL2='selected'; 

			}else $SEL2='';
			$DEF.="<option $SEL2 value=$ka>$va";
		}
		$DEF.="</select>";


	  }elseif($vcode=='SENDER_C') {
		$DEF="<select $DISABLE name=def[$vcode]><option value=''> - -";
		if(isset($CONT_C))foreach($CONT_C as $ka=>$va){
			if($def==$ka) $SEL3='selected'; else $SEL3='';
			$DEF.="<option $SEL3 value=$ka>$va";
		}
		$DEF.="</select>";



	  }elseif($vcode=='WHOPAY_BCK_MS') {


//		if(!$def) $def=$DEF_PHONE;
//		$SEL='';		
		$DEF="<input type=checkbox  name=def[$vcode] value='true'>";

	  }elseif($vcode=='WHOPAY_MS') {


//		if(!$def) $def=$DEF_PHONE;
//		$SEL='';		
		if($def=='true') $checked='checked'; else $checked='';
		$DEF="<input type=checkbox  $checked name=def[$vcode] value='true'>";

	  }elseif($vcode=='PHONE_SENDER') {


		if(!$def and isset($DEF_PHONE)) $def=$DEF_PHONE;
		$SEL='';		
		$DEF="<input type=text $DISABLE name=def[$vcode] value='$def'>";

	  }elseif($vcode=='CITY_SENDER') {
		$SEL=''; $CITY_SEL="<select name=def[$vcode] style='width:300px;'><option value=''> - -";
		$resc=mysqli_query($db,"select id_ref,title_".$lang." from np_cities order by title_$lang");
		$num_cities=mysqli_num_rows($resc);
		while(list($id_ref,$title)=mysqli_fetch_row($resc)){
			if($id_ref==$def) { $NP_CITY=$id_ref; $sel='selected'; } else $sel='';
			if($title) $CITY_SEL.="<option $sel value='$id_ref'>$title";
		}
		$CITY_SEL.="</select>";
		
		if(!$def and !$num_cities) $DEF="<a  href='np_updatedb.php?upd=cities'>Загрузить базу городов НП (долго, но напрямую из Новой Почты)</a> <br> <a  href='np_updatedb.php?upd=download'>Скачать постоянно обновляемую базу городов и отделений из CiFrame</a>";
		else $DEF=$CITY_SEL;

	  }elseif($vcode=='SEND_WARE_NEW') {

		
		
		$num=0;
		$CITY_SEL="<select name=def[$vcode] style='width:300px;'><option value=''> - -";
		$resc=mysqli_query($db,"select id_ref,title_".$lang." from np_ware where city='$NP_CITY' order by number");
		$num_wares=mysqli_num_rows($resc);
		while(list($id_ref,$title)=mysqli_fetch_row($resc)){
			if($id_ref==$def) $sel='selected'; else $sel='';
			if($title) $CITY_SEL.="<option $sel value='$id_ref'>$title";
		}
		$CITY_SEL.="</select>";

		if($num_wares ) $DEF=$CITY_SEL;
//		$DEF="<input type=text $DISABLE name=def[$vcode] value='$def'>";
		else $DEF="<a  href='np_updatedb.php?upd=ware'>Загрузить базу отделений НП</a>";
//		if($vals) $DEF='';
//		if($def or !$num_wares) {  $SEL='';  }

	  }elseif($vcode=='STAT_ACCORD') {
		$sel0=$sel1='';
		if(isset($DEFAULT['NP_STATE_TYPE']) and ($DEFAULT['NP_STATE_TYPE']==1)) $sel1='checked';
		else $sel0='checked';
		$DEF="<input $sel0 type=radio value='0' name=def[NP_STATE_TYPE] >Simple &nbsp; <input name=def[NP_STATE_TYPE] type=radio value='1' $sel1>Advanced";
		$DEF.='<div id="cards">';
		if($def) $cards=unserialize($def);
		else $cards=array();
		$numcard=0;

		if(isset($cards)) foreach($cards as $kc=>$vc){

			if($vc['stat_np']or $vc['stat_ms']  or !$numcard){

				$statnp='';
				foreach($NP_STATE_N as $ks=>$vs){
					if($vc['stat_np']==$ks) $sel='selected'; else $sel='';
					$statnp.="<option $sel value='$ks'>$vs ($ks)";
				}
				$DEF.="Статус НП: <select name=def[$vcode][$numcard][stat_np] ><option value=''>-- $statnp</select> &nbsp;<br>";

				$statms='';
				foreach($STATN as $ks=>$vs){
					if($vc['stat_ms']==$vs) $sel='selected'; else $sel='';
					$statms.="<option $sel value='$vs'>$vs";
				}
				$DEF.="Статус МС: <select name=def[$vcode][$numcard][stat_ms] ><option value=''>-- $statms</select> <br><br>";

				$numcard++;
			}
			
		}
		$DEF.="</div>";
		
		$statms='';
		if(isset($STATN)) foreach($STATN as $ks=>$vs){
			$statms.="<option value=\'$vs\'>$vs";

		}
		$statnp='';
		foreach($NP_STATE_N as $ks=>$vs){
			$statnp.="<option value=\'$ks\'>$vs  ($ks)";
		}

		$DEF.="<a href='javascript:void(0);' onclick=\"inn=document.getElementById('cards').innerHTML; numcard=document.getElementById('numcard').value; numcard=parseInt(numcard)+1; document.getElementById('numcard').value=numcard;".
			"inn=inn+'Статус НП: <select name=def[$vcode]['+numcard+'][stat_np] >$statnp</select> &nbsp;<br> ".
			"Статус МС: <select name=def[$vcode]['+numcard+'][stat_ms] >$statms</select> <br><br>';".
			" document.getElementById('cards').innerHTML=inn;\">Добавить смену статуса</a>";




	  }elseif($vcode=='CREATE_TTN_MS') {

		$ORGAN_SEL="<select name=def[$vcode]><option value=''> - -";
		if(isset($STATN)) foreach($STATN as $ko=>$vo){
			if($ko==$def) { $sel='selected'; } else $sel='';
			$ORGAN_SEL.="<option $sel value='".$ko."'>".$vo;
		}
		$ORGAN_SEL.="</select>";
		$DEF=$ORGAN_SEL;


	  }elseif($vcode=='PAYMENT_TYPE') {
		$SEL='';


		$DEF="<select name=def[$vcode]>";
		foreach($PAYMENTS as $ko=>$vo){
			if($ko==$def) { $sel='selected'; } else $sel='';
			$DEF.="<option $sel value='".$ko."'>".$vo;
		}
		$DEF.="</select>";

	  }elseif($vcode=='PAYMENT_CONTROL') {
		if($DEFAULT['PAYMENT_CONTROL']==1) $ch='checked';
		else $ch='';

		$DEF="<input type=checkbox name='def[PAYMENT_CONTROL]' $ch value=1>Контроль оплаты";

	  }elseif($vcode=='LANG') {
		$SEL=''; $ORGAN_SEL="<select name=def[$vcode]>";
		foreach($langs as $ko=>$vo){
			if($ko==$def) { $sel='selected'; } else $sel='';
			 $ORGAN_SEL.="<option $sel value='".$ko."'>".$vo;
		}
		$ORGAN_SEL.="</select>";
		
		$DEF=$ORGAN_SEL;

	  }elseif($vcode=='OCENKA') {
		if(isset($DEFAULT['NP_OCENKA_SUM']) and ($DEFAULT['NP_OCENKA_SUM']==1)) $ch='checked';
		else $ch='';
		$DEF="<input type=text $DISABLE name=def[$vcode] value='$def'> или <input type=checkbox name='def[NP_OCENKA_SUM]' $ch value=1>Оценочная по сумме заказа";

	  }else{

		if($vcode=='PHONE_SENDER') $help='';
		$DEF="<input type=text $DISABLE name=def[$vcode] value='$def'>";
	  }



	  if(($vcode=='STAT_ACCORD')||($vcode=='ENTITY')||($vcode=='SENDER_C')) $SEL='';
	  if(!$vals and !$def and ($vcode!='STAT_ACCORD') and  ($vcode!='DESCR_MS') ) 
		$add="<a href='javascript:void(0);' onClick=' document.getElementById(\"f_$vcode\").innerHTML=\"".'<input placeholder=\"Наименование поля\" type=text name=\"field_name['.$vcode.']\" value=\"'.$vname.'\"><input type=submit name=submit_field value=\"Создать\">'."\";'>Создать поле</a>"; 
	  else $add='';

	  if($vcode=='SKLAD_MS')  {
			$DEF='';
			$add.="<br><a href='np_setup.php?clear=1' onClick=\"if (confirm('Очистить базу складов?')) return true; else return false\">Очистить базу складов</a><br>";
			$add.="<a href='np_updatedb.php?upd=ms_ware' >Загрузить склады в Мойсклад</a>";
		}
	  

	  if($bo) { $bo=false; $col='bgcolor=#aedeff'; } else { $col=''; $bo=true; }



          if(($vcode!='NP_STATE_TYPE')and ($vcode!='NP_OCENKA_SUM') ) echo("<tr height=25 $col><td >$vname</td><td>$SEL</td><td>$DEF</td><td width=300 align=center><div id='f_$vcode'>$add </div></td></tr>");



	
//	  if($vcode=='SKLAD_MS') if(!$def and !$vals) break;//$DISABLE='disabled';
	  if($vcode=='CITY_SENDER') if(!$def and !$num_cities) break;//$DISABLE='disabled';
//	  if($vcode=='SEND_WARE_NEW') if(!$def and !$num_wares) break;//$DISABLE='disabled';





}





?>

</table>
<input type=hidden name=numcard value='<?=$numcard?>' id='numcard'>
<input type=submit name=submit value='Сохранить' class="btn btn-primary" >
</form>
<br><br>
<center>

<table width=1000 class="table">
<?php 
if(isset($_SERVER['HTTPS']) and ($_SERVER['HTTPS']=='on')) $site='https://';
else $site='http://';
$site.=$_SERVER['HTTP_HOST'];

//$tmp=explode("/",$_SERVER['HTTP_REFERER']);
//$url=str_replace(end($tmp),'',$_SERVER['HTTP_REFERER']);

?>

<tr><td colspan=3><b>Основные настройки для автоматической работы интеграции с Новой почтой</b></td></tr>
<tr><td>/usr/bin/wget -O - -q -t 1 "<?php echo $SITE;?>np_generate.php?upd=ttn" </td>
	<td>Запуск скрипта генерации TTN</td><td>Рекомендованная периодичность: каждые 2-10 минут</td></tr>
<tr><td>/usr/bin/wget -O - -q -t 1 "<?php echo $SITE;?>np_generate.php?upd=stats" </td>
	<td>Запуск скрипта генерации TTN</td><td>Рекомендованная периодичность: каждые 60 минут</td></tr>

<tr><td colspan=3><b>Обновление отделений CiFrame (быстро, но не напрямую)</b></td></tr>

<tr><td>/usr/bin/wget -O - -q -t 1 "<?php echo $SITE;?>np_updatedb.php?upd=download" </td>
	<td>Запуск скрипта обновления городов, отделений, регионов НП</td><td>Рекомендованная периодичность: раз в сутки</td></tr>

<tr><td colspan=3><b>Обновление отделений Новая Почта (долго, ресурсоемко но  напрямую)</b></td></tr>
<tr><td>/usr/bin/wget -O - -q -t 1 "<?php echo $SITE;?>np_updatedb.php?upd=cities" </td>
	<td>Запуск скрипта обновления городов НП</td><td>Рекомендованная периодичность: раз в сутки</td></tr>

<tr><td>/usr/bin/wget -O - -q -t 1 "<?php echo $SITE;?>np_updatedb.php?upd=ware" </td>
	<td>Запуск скрипта обновления отделений НП</td><td>Рекомендованная периодичность: каждый час</td></tr>

<tr><td>/usr/bin/wget -O - -q -t 1 "<?php echo $SITE;?>np_updatedb.php?upd=ms_ware" </td>
	<td>Запуск скрипта обновления отделений НП в Мойсклад</td><td>Рекомендованная периодичность: раз в сутки</td></tr>
</table>
</center>
<?php }else{?>
<center><br><br>
<form method=post action=np_setup.php>
Логин: <input type=text name=username>
Пароль: <input type=text name=password><br><br>
<input type=submit name=submit_login value='Зайти в настройки' class="btn btn-primary">
</form>
</center>

<?php }?>
</center>
</body></html>