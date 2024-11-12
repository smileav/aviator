<?php

date_default_timezone_set('Europe/Kiev');

Header("Content-Type: text/html;charset=UTF-8");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
$chd=date("H",time());

//if(($chd<9)||($chd>21)) exit();
ini_set('error_reporting', E_ALL&~E_NOTICE);
ini_set('display_startup_errors',1);

date_default_timezone_set('Europe/Kiev');




 $hand=fopen("cron.log","a+");
 fwrite($hand,date("d-m-Y H:i:s",time()).' - '.json_encode($_GET)." \n");
 fclose($hand);

$w_upd=$_GET['upd'];


require_once(dirname(__FILE__).'/lib/NovaPoshtaApi2.php');
require_once(dirname(__FILE__).'/config.php');
if(!checkLicence()) { echo("Лицензия не активна. Обратитесь в info@ciframe.com если есть вопросы"); exit();}





$np = new NovaPoshtaApi2($NP_KEY);
$res=$np->getOwnershipFormsList();

$FORM_S=array();
foreach($res['data'] as $k=>$v){
 	$FORM_S[$v['Description']]=$v['Ref'];
}

$res=mysqli_query($db, "select vars_code,vars_name, vals,`default` from np_config");
while(list($vcode,$vname,$vals,$def)=mysqli_fetch_row($res)){
	$$vcode=$vals;
	if($vcode=='STAT_ACCORD') 	$DEFAULT[$vcode]=unserialize($def);
	else $DEFAULT[$vcode]=$def;
}


$link="https://api.moysklad.ru/api/remap/1.2/entity/".$DEFAULT['ENTITY']."/metadata";
$json=ms_query($link);
if(isset($json['states'])) foreach($json['states'] as $k=>$v){
 	$STATN[$v['name']]=$v['meta'];
}




if((date('d-m-Y H', @filemtime ('gottn')) !=date('d-m-Y H', time())) or  ((date('d-m-Y H', time())>30) and date('d-m-Y H', time()<35))) @unlink('gottn');
//if(date('d-m-Y', @filemtime ('gottn')) !=date('d-m-Y', time())) @unlink('gottn');
if(file_exists('gottn') and ($_GET['upd']!='status')) { echo('Script in action from '.date('d-m-Y H:i:s', filemtime ('gottn'))); exit();}
$hand=fopen("gottn","w");
fwrite($hand,date("H:i:s",time()));
fclose($hand);


// Получаем отгрузки за прошедший час
$CHECK_MS=true;
$page=0;
while($CHECK_MS){
	$limit=100;
	$offset=$limit*$page;
	$link='https://api.moysklad.ru/api/remap/1.2/entity/'.$DEFAULT['ENTITY'].'?expand=organization,positions,state,agent,customerOrder&limit=100&offset='.$offset.'&filter='.urlencode('updated>').date('YmdHis',strtotime('- 20 minutes',time())) ;
	$json=ms_query($link);

	if($json['meta']['size']>500) { 	@unlink('gottn'); exit();}
	
	if(isset($json['rows'])) foreach ( $json['rows'] as  $k=>$v) {


		$lsum=0;
		if(isset($v['payments'])) foreach($v['payments'] as $kp=>$vp){

		 	if($vp['meta']['type']=='paymentin') {
		 		$lsum=$vp['linkedSum'];
			}
		}
		if(1==1){
//		if(in_array($v['name'],$ORDS) or (count($v['demands'])>0)){

		$city_np=$ware_np=$cust_addr=$chphone=$cherr=$chttn='';
		$WITH_DEMAND=$CREATE_TTN=$back=false;
		if(isset($v['demands']) and count($v['demands'])>0) $WITH_DEMAND=true;
		
		$oncard=false;
		foreach($v['attributes'] as $ka=>$va){
			 	if($va['id']==$PHONE_RECEP) $chphone=$va['value'];
		 		if($va['id']==$ERR_MS) $cherr=$va['value'];
		 		if($va['id']==$TTN_MS) $chttn=$va['value'];
		 		if($va['id']=='fd483df7-c064-11ea-0a80-03e9000ddc9b') {

					$sender_code=$va['value'];
				}

//		 		if(($va['name']=='Способ доставки')&&($va['value']['name']=='Нова Пошта')) $CREATE_TTN=true;
		 		if(($va['name']=='Способ оплаты')&&($va['value']['name']=='На карту ПриватБанка')) $oncard=true;
		 		if($va['name']=='Город НП') { $city_np=$va['value']['name']; }
			
		 		if($va['name']=='Номер отделения НП') { $ware_np=$va['value']; }

		}


		$descr=null;


//		$vesnow=$weight;
		$CREATE_ADDR=false;
                $nazad=$ttn=$obem=$ves=$mest=NULL;
		$whld=null;
		$SEND_FROM_WARE=null;
		$client_type=$adddescr='';

		$bcksum=0;
		$clearsum=0;
		$sum=$v['sum']/100;

		if(isset($v['deliveryPlannedMoment'])) $date=explode(" ",$v['deliveryPlannedMoment']);


		else $date[0]=date('Y',time())."-".date('m',time())."-".date('d',time());
		$date_d=explode("-",$date[0]);


		$FIO=$name=$fname=$lname=$mname='';

		$id_ref=$city=$ttn='';
		//$CREATE_TTN=false;
		$whopay=$whopay_bck='Recipient';
		$id_ref_send=$city_send=$formsobst='';
		if(isset($DEFAULT['PAYMENT_TYPE']) or !$DEFAULT['PAYMENT_TYPE']) $payment_type='Cash';
		else $payment_type=$DEFAULT['PAYMENT_TYPE'];

		$PAYMENT_CONTROL_VAL=0;
		$addr_addr='';
		$client_type="PrivatePerson";
		$sender_name='';
		$ocenka=null;
		$alt_city=$alt_ware=null;
		$np_pay='';
		if(isset($v['attributes'])) foreach($v['attributes'] as $ka=>$va){


			if($va['id']=='1324f9f2-3941-11ef-0a80-174d0014019e'){
				$np_pay=$va['value']['name'];
			}

		 	if($va['id']==$SKLAD_MS)  { 
				$tmp=$va['value']['meta']['href'];
				$jsonx=ms_query($tmp);

				$res=mysqli_query($db,"select id_ref,city from np_ware where id_ref='{$jsonx['code']}' or id_ref='{$jsonx['description']}'");
			//	$tmp=explode("/",$va['value']['meta']['href']);
			//	$res=mysql_query("select id_ref,city from np_ware where ms='{$tmp[9]}'");
        			list($id_ref,$city)=mysqli_fetch_row($res);


//var_dump($city);
			}


		 	if($va['id']==$SEND_WARE_NEW)  { 
				$tmp=$va['value']['meta']['href'];
				$jsonx=ms_query($tmp);
				$res=mysqli_query($db,"select id_ref,city from np_ware where id_ref='{$jsonx['code']}' or id_ref='{$jsonx['description']}'");
	   			list($id_ref_send,$city_send)=mysqli_fetch_row($res);
			}
//		 	if($va['id']==$MEST_MS)  { 
//		 	 	$mest=$va['value'];
//			}
		 	if($va['id']==$CREATE_TTN_MS)  { 
		 	 	if($va['value']) $CREATE_TTN=true;

			}


		 	if($va['id']=="c595fad2-6acf-11eb-0a80-0867000dfa9b"){//$VES_MS)  { 

		 	 	$ves=str_replace(",",".",$va['value']);
			}

		 	if($va['id']==$WARE_RECIPIENT)  { 

		 	 	$alt_ware=$va['value'];

			}

		 	if($va['id']=='9228bed7-6add-11eb-0a80-06da001229ad')  { 


				$jsonx=ms_query($va['value']['meta']['href']);
				$NP_KEY=$jsonx['code'];


$np_link="https://api.novaposhta.ua/v2.0/json/";
$sen_cre["apiKey"]=$NP_KEY;
$sen_cre['modelName']="Counterparty";
$sen_cre["calledMethod"]="getCounterparties";
$sen_cre["methodProperties"]=array(
	"CounterpartyProperty"=>"Sender",
	"Page"=> "1"
);
$res=np_query_send($np_link,$sen_cre);
$SENDER=$res['data'][0]['Ref'];
/*
var_dump($res);
foreach($res['data'] as $ka=>$va){
	$CONT[$va['Ref']]=$va['Description'];
	$CONT_CITY[$va['Ref']]=$va['City'];

}*/


$np_link="https://api.novaposhta.ua/v2.0/json/";
$sen_cre["apiKey"]=$NP_KEY;
$sen_cre['modelName']="Counterparty";
$sen_cre["calledMethod"]="getCounterpartyContactPersons";
$sen_cre["methodProperties"]=array(
			"Ref"=>"$SENDER",
			"Page"=> "1"
);
$res2=np_query_send($np_link,$sen_cre);
$SENDER_C=$res2['data'][0]['Ref'];

$PHONE_SENDER=$res2['data'][0]['Phones'];
//		 	 	var_dump($jsonx) 	;			

			}


		 	if($va['id']==$CITY_RECIPIENT)  { 
				$tmp=$va['value']['meta']['href'];
				$jsonx=ms_query($tmp);
				$res=mysqli_query($db,"select id_ref from np_cities where id_ref='{$jsonx['code']}'");
        			list($alt_city)=mysqli_fetch_row($res);


			}




		 	if($va['id']==$PAYMENT_CONTROL)  { 
		 	 	if($va['value']) $PAYMENT_CONTROL_VAL=1;
			}
//		 	if($va['id']==$MEST_MS)  { 
//		 	 	$mest=$va['value'];
//			}


		 	if($va['id']==$TTN_MS)  { 
		 	 	$ttn=$va['value'];
			}
		 	if($va['id']==$OCENKA)  { 
		 	 	$ocenka=$va['value'];
			}

		 	if($va['id']==$DESCR_MS)  { $adddescr=$va['value'];}
		 	if($va['id']==$FIO_RECEP)  { 
		 	 	$FIO=$va['value'];
			}

//		 	if($va['id']==$PHONE_RECEP)  { 	$phone=str_replace(array("+","("," ",")","-"),array("","","","",""),$va['value']);}
		 	if($va['id']==$BACK_SUM)  { $bcksum=$va['value']; }
		 	if($va['id']==$OCENKA)  { $clearsum=$va['value']; }
		 	if($va['id']==$WD_MS)  { $whld=$va['value'];}

		 	if($va['id']==$ADDR_CITY)  { $addr_city=$va['value'];}
		 	if($va['id']==$ADDR_ADDR)  { $addr_addr=$va['value'];}
//		 	if($va['id']==$NAZAD_MS)  { 
//		 	 	if($va['value']) $nazad=false;
//				else $nazad=true;
//			}
		 	if($va['id']==$WHOPAY_MS)  { 
		 	 	if($va['value']) $whopay='Sender';
				else $whopay='Recipient';
			}


		 	if($va['id']==$WHOPAY_BCK_MS)  { 
		 	 	if($va['value']) $whopay_bck='Sender';
				//else $whopay_bck='Recipient';

			}


		 	if($va['id']==$CLIENT_TYPE)  {  if($va['value']) {
				$client_type="Organization"; 
			}else $client_type="PrivatePerson"; }

		}


		if(!isset($whld)) $whld=$DEFAULT['WD_MS'];

		$sizes_arr=explode(",",$whld);	
		$SIZES=null;
		foreach($sizes_arr as $kas=>$vas){
			$whld_expl=explode("*",trim($vas));
			$SIZES[]=array('wd'=>$whld_expl[0], 'ht'=>$whld_expl[1], 'lt'=>$whld_expl[2]);
		}
		$sum=$v['sum']/100;
		if(!$id_ref_send) {
		 	$id_ref_send=$ADDR_SENDER[$SEND_FROM_WARE];
			$city_send=$CITY_SENDER[$SEND_FROM_WARE];
		}


//		if(!$ves) $ves=$DEFAULT['VES_MS'];

//		$ves=$weight;
		if(!$SENDER) $SENDER=$DEFAULT['SENDER'];
		if(!$SENDER_C) $SENDER_C=$DEFAULT['SENDER_C']; //$CONT_C_ARR[$sender_name];

		if(!$DESCR_MS or !$adddescr) $adddescr=$DEFAULT['DESCR_MS'];
		$adddescr=str_replace("{ORDER_NUM}", $v['name'],$adddescr);


		if(!$SEND_WARE_NEW) $SEND_WARE_NEW=$DEFAULT['SEND_WARE_NEW'];
		if(!$PHONE_SENDER) $PHONE_SENDER=$DEFAULT['PHONE_SENDER'];
		if(!$city_send) $city_send=$DEFAULT['CITY_SENDER'];
		if(!$id_ref_send) $id_ref_send=$DEFAULT['SEND_WARE_NEW'];
		//$FIO=$fname.' '.$lname;




/*
		if(isset($v['agent']['attributes'])) foreach($v['agent']['attributes'] as $ka=>$va){
			 		if(!$FIO) if($va['name']=='Прізвище') $fname=$va['value'];
			 		if(!$FIO) if($va['name']=="Iм'я") $lname=$va['value'];
			 		if(!$FIO) if($va['name']=="По батькові") $mname=$va['value'];
					if(!$chphone) if($va['name']=="Телефон") $chphone=$va['value']; 
					if($va['name']=="Відділення НП") {
						if(!$id_ref){
							$tmp=$va['value']['meta']['href'];
							$jsonx=ms_query($tmp);
							$res=mysqli_query($db,"select id_ref,city from np_ware where id_ref='{$jsonx['code']}'");
	        					list($id_ref,$city)=mysqli_fetch_row($res);
						}

					}
		}
*/
		if(!$chphone) if($v['agent']['phone']) $chphone=$v['agent']['phone']; 
		if(!$FIO) if($v['agent']['name']) $FIO=$v['agent']['name']; 



		if(substr($chphone,0,2)=="38") $chphone="+".$chphone;
		if(substr($chphone,0,3)!="+38") $chphone="+38".$chphone;
		if(substr($chphone,0,2)!="+3") $chphone="+3".$chphone;
		$chphone=str_replace("(","",$chphone);
		$chphone=str_replace("-","",$chphone);
		$chphone=str_replace(")","",$chphone);
		$chphone=str_replace(" ","",$chphone);


		if($FIO){

			$name=explode(" ",$FIO);
			$fname=$name[1];
			if(isset($name[0])) $lname=$name[0]; //str_replace($name[0]." ","",$json_agent['name']);
			if(isset($name[2])) $mname=$name[2];

		}
/*
		if($cust_addr){
			$cust_addr_=explode(",",$cust_addr);
			$cust_addr=trim(str_replace($cust_addr_[0].",","",$cust_addr));
			$res=mysqli_query($db,"select id_ref,city from np_ware where title='$cust_addr'");
			list($id_ref,$city)=mysqli_fetch_row($res);

		}
*/


/*
		$agenttmp=explode(',',$v['agent']['actualAddress']);
		$city_np=$agenttmp[0];
		$ware_np=str_replace($agenttmp[0].', ','', $v['agent']['actualAddress']);
		if($city_np and  $ware_np){
			$res=mysqli_query($db,"select id_ref from np_cities where title_ru='$city_np'");
			list($city)=mysqli_fetch_row($res);
//echo("select id_ref from np_cities where title='$city_np' - $city!!!");
			$res=mysqli_query($db,"select id_ref from np_ware where city='$city' and title_ru like '%$ware_np%'");
			list($id_ref)=mysqli_fetch_row($res);



		}
*/

		if($ves) $vesnow=$ves;

		if(!$CREATE_TTN_MS) if($v['state']['name']==$DEF['CREATE_TTN_MS'])   $CREATE_TTN=true;

		if(!$ttn and $CREATE_TTN ){
	

	
			$volume=$weight=0;
			$shvd='';
			$optseat=null;
			foreach($v['positions']['rows'] as $kr=>$vr){



				$chgoodvolume=$chgoodweight=0;
				$pathName='';



				$link3=$vr['assortment']['meta']['href'];
				$json3=ms_query($link3);
				if($json3['product']['meta']['href']){
						$link3=$json3['product']['meta']['href'];
						$json3=ms_query($link3);
					$chgoodname=$json3['name'];
					$chgoodcode=$json3['code'];

				}
//				mysqli_query($db,"update np_ms_goods set  weight='{$json3['weight']}' where id='$chgoodid'");
				$descr.="$chgoodname - $chgoodcode\n";




			}
	








			$who_c=$who=null;

			$sen_cre=null;
			$np_link="https://api.novaposhta.ua/v2.0/json/";
			$sen_cre["apiKey"]=$NP_KEY;
			$sen_cre['modelName']="Counterparty";
			$sen_cre["calledMethod"]="save";


			if($client_type=="Organization"){
				$ownersh=$formsobst;//'7f0f351d-2519-11df-be9a-000c291af1b3';
				if(strpos(" ".$FIO, ",")) $FIO_ARR=explode(",",$FIO);
			
				$name=explode(" ",trim($FIO_ARR[1]));
				$fname=$name[1];
				if(isset($name[0])) $lname=$name[0]; //str_replace($name[0]." ","",$json_agent['name']);
				if(isset($name[2])) $mname=$name[2];


				$sen_cre["methodProperties"]=array(
			     "CityRef"=> "$city",
			     "FirstName"=> $FIO_ARR[0],
			        "MiddleName"=> "",
			        "LastName"=> "",
			        "Phone"=> "",
			        "Email"=> "$email",
				"OwnershipForm"=>$ownersh,
			        "CounterpartyType"=> "$client_type",
			        "CounterpartyProperty"=> "Recipient",
/*
				"ContactPerson"=> array(array(
				     "FirstName"=> "$fname",
				        "MiddleName"=> "",
				        "LastName"=> "$lname",
				        "Phone"=> "$phone",
			        	"Email"=> "$email"))
*/

				);
	
				$res=np_query_send($np_link,$sen_cre);
				$err='';
				foreach($res['errors'] as $kx=>$vx){
					$err.="Err: $vx\n";
				}

			
				if($res['success']) {
				   	$who=$res['data'][0]['Ref'];
				}
				$sen_cre=null;
				$np_link="https://api.novaposhta.ua/v2.0/json/";
				$sen_cre["apiKey"]=$NP_KEY;
				$sen_cre['modelName']="ContactPerson";
				$sen_cre["calledMethod"]="save";
				$sen_cre["methodProperties"]=array(
				     "CounterpartyRef"=>$who,
				     "FirstName"=> "$fname",
				        "MiddleName"=> "",
				        "LastName"=> "$lname",
				        "Phone"=> "$chphone",
				);

				$res=np_query_send($np_link,$sen_cre);

				if($res['success']) {
				   	$who_c=$res['data'][0]['Ref'];
				}


		}else{


			$sen_cre["methodProperties"]=array(
			     "CityRef"=> "$city",
			     "CounterpartyRef"=>$who,
			     "FirstName"=> "$fname",
			        "MiddleName"=> "$mname",
			        "LastName"=> "$lname",
			        "Phone"=> "$chphone",
			        "Email"=> "$email",
			        "CounterpartyType"=> "PrivatePerson",
			        "CounterpartyProperty"=> "Recipient",

			);

	
			$res=np_query_send($np_link,$sen_cre);
			$err='';
			foreach($res['errors'] as $kx=>$vx){
					$err.="Err: $vx\n";
			}

			$who=$who_c=null;
//var_dump($res);
			if($res['success']) {
			   	$who_c=$res['data'][0]['ContactPerson']['data'][0]['Ref'];
			   	$who=$res['data'][0]['Ref'];

			}
		}



			


		$backward=null;

//		if(!$bcksum) $bcksum=$sum;
		if($bcksum) {
		 	$backward=array(array("PayerType"=> "$whopay_bck",
	                "CargoType"=> "Money",
//                "Services"=> array(
  //                  "Attorney"=> true,
    //                "WaybillNewPostWithStamp"=> true,
      //              "UserActions"=>"UserCallSender",
		   	"RedeliveryString"=>"$bcksum"
//                )
			));

			$ocenka=$bcksum;	


			if($oncard and $lsum>=$v['sum']) {
				$ms_data_=array();
				$ms_data_['attributes'][]=array('id'=>$BACK_SUM,
	     				"meta"=>array('href'=> "https://api.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/attributes/".$BACK_SUM, 'type'=>'attributemetadata',"mediaType"=>"application/json"),
					'value'=>'');
				$resm=ms_query_send($v['meta']['href'],$ms_data,"PUT");

			 	
			}

		}

		
//		$resz=mysqli_query($db,"select title_ru, area from np_cities where id_ref='$city'");
//		list($city_name,$area)=mysqli_fetch_row($resz);

//		$resz=mysqli_query($db,"select title_ru, area,region from np_cities where id_ref='$city'");
//		list($city_name,$area,$region)=mysqli_fetch_row($resz);

		$resz=mysqli_query($db,"select  area,region from np_cities where id_ref='$addr_city_code'");
		list($area,$region)=mysqli_fetch_row($resz);
		$resz=mysqli_query($db,"select title_ua from np_areas where id_ref='$area'");
		list($areaname)=mysqli_fetch_row($resz);




		if($addr_addr)$CREATE_ADDR=true;
		else $CREATE_ADDR=false;


		if($CREATE_ADDR){
			$service_type='';
			$addr_arr=explode(",",$addr_addr);

			$addr_dostavka=array(
			"NewAddress"=>1,
			"Recipient"=> "$who",
			"ContactRecipient"=> "$who_c",

			"RecipientArea"=>$areaname,
			"RecipientAreaRegions"=>$regionname,
			"RecipientCityName"=>trim($addr_arr[0]),
			"RecipientAddressName"=>$addr_arr[1],
			"RecipientHouse"=>trim($addr_arr[2]),
			"RecipientFlat"=>trim($addr_arr[3]),

			"RecipientName"=>trim($fname).' '.trim($mname).' '.trim($lname),
			"RecipientType"=>"$client_type",
			"RecipientsPhone"=>$chphone,
			"ServiceType"=> "WarehouseDoors",
			);


		}else{

//			if($who_c) 
			$service_type='';
			$addr_dostavka=array(
			"CityRecipient"=>"$city",
			"Recipient"=> "$who",
			"RecipientAddress"=> "$id_ref",
			"ContactRecipient"=> "$who_c",
			"ServiceType"=> "WarehouseWarehouse",
			"RecipientType"=>"$client_type"
			);

		}




		if($mest>1){

			$optseat=null;
			$ves_arr=explode(",",$ves);
			for($ks=0;$ks<=($mest-1);$ks++){

				$obves=$SIZES[$ks]['wd']*$SIZES[$ks]['lt']*$SIZES[$ks]['ht']/4000;
	
				if($ves_arr[$ks]) $vesnow=$ves_arr[$ks];
				else $vesnow=$ves_arr[0];
				if((isset($SIZES[$ks]['wd'])and  isset($SIZES[$ks]['lt']) and isset($SIZES[$ks]['ht'])) and !$vesnow){
				 	$optseat[]=array(
					"volumetricVolume"=> "$obves",
					"volumetricWidth"=> $SIZES[$ks]['wd'],
					"volumetricLength"=> $SIZES[$ks]['lt'],
					"volumetricHeight"=> $SIZES[$ks]['ht'],
					);
				}elseif((!isset($SIZES[$ks]['wd']) or  !isset($SIZES[$ks]['lt']) or !isset($SIZES[$ks]['ht'])) and $vesnow){
				 	$optseat[]=array(
					"volumetricVolume"=> $vesnow/250,
					"weight"=> $vesnow);

				}else{
				 	$optseat[]=array(
					"volumetricVolume"=> "$obves",
					"volumetricWidth"=> $SIZES[$ks]['wd'],
					"volumetricLength"=> $SIZES[$ks]['lt'],
					"volumetricHeight"=> $SIZES[$ks]['ht'],
					"weight"=> $vesnow);
				}

			}


		}else{
			$optseat=null;
			$ks=0;
			$obves=$SIZES[0]['wd']*$SIZES[0]['lt']*$SIZES[0]['ht']/4000;
			if((isset($SIZES[$ks]['wd'])and  isset($SIZES[$ks]['lt']) and isset($SIZES[$ks]['ht'])) and !$vesnow and !$ves){
				 	$optseat[]=array(
					"volumetricVolume"=> "$obves",
					"volumetricWidth"=> $SIZES[0]['wd'],
					"volumetricLength"=> $SIZES[0]['lt'],
					"volumetricHeight"=> $SIZES[0]['ht'],
					);
			}elseif((isset($SIZES[$ks]['wd'])and  isset($SIZES[$ks]['lt']) and isset($SIZES[$ks]['ht'])) and $ves){
				 	$optseat[]=array(
					"volumetricVolume"=> "$obves",
					"volumetricWidth"=> $SIZES[0]['wd'],
					"volumetricLength"=> $SIZES[0]['lt'],
					"volumetricHeight"=> $SIZES[0]['ht'],
					"weight"=> $ves
					);

			}elseif((!isset($SIZES[$ks]['wd']) or  !isset($SIZES[$ks]['lt']) or !isset($SIZES[$ks]['ht'])) and $vesnow){
				 	$optseat[]=array(
					"volumetricVolume"=> $vesnow/250,
					"weight"=> $vesnow);

			}else{
				 	$optseat[]=array(
					"volumetricVolume"=> $obves,
					"volumetricWidth"=> $SIZES[0]['wd'],
					"volumetricLength"=> $SIZES[0]['lt'],
					"volumetricHeight"=> $SIZES[0]['ht'],
					"weight"=> $vesnow);
			}


		}



			$sen_cre=null;		
			$np_link="https://api.novaposhta.ua/v2.0/json/";
			$sen_cre["apiKey"]=$NP_KEY;
			$sen_cre['modelName']="InternetDocument";
			$sen_cre["calledMethod"]="save";

			if($ocenka) $cost=$ocenka; 
			elseif($DEFAULT['OCENKA'])$cost=$DEFAULT['OCENKA']; 
			else $cost=$sum;

			$sen_cre["methodProperties"]=array(
				"PayerType"=>"$whopay",
				"Weight"=> "$ves",
				"OptionsSeat"=>$optseat,

				"SeatsAmount"=> "$mest",
				"Cost"=> $cost,
				"PaymentMethod"=> "$payment_type",
				"DateTime"=> $date_d[2].".".$date_d[1].".".$date_d[0],
				"CargoType"=> "Cargo",
				"SendersPhone"=>"$PHONE_SENDER",
				"CitySender"=> $city_send,
				"Sender"=> "$SENDER",
				"Description"=> $adddescr,
				"AdditionalInformation"=>$adddescr,//$adddescr,
				"SenderAddress"=>$id_ref_send,
				"ContactSender"=> "$SENDER_C",
				"RecipientsPhone"=> "$chphone",

			);

			if(isset($backward)) {

				
//				if(($DEFAULT['PAYMENT_CONTROL'] =='1') or ($PAYMENT_CONTROL_VAL==1)){	
				if($np_pay=='Контроль оплати'){
					$sen_cre["methodProperties"]['AfterpaymentOnGoodsCost']=$bcksum;
					unset($backward);
				}
				$sen_cre["methodProperties"]['BackwardDeliveryData']=$backward;
			}


			$sen_cre["methodProperties"]=array_merge($sen_cre["methodProperties"],$addr_dostavka);
			$res=np_query_send($np_link,$sen_cre);

			if($_GET['debug']){
				var_dump($sen_cre);
				var_dump($res);
			}


			foreach($res['errors'] as $kx=>$vx){
				$err.="Err: $vx\n";
			}
			foreach($res['warnings'] as $kx=>$vx){
//				$err.="Warn: $vx\n";
			}

			$ms_data=null;

			if($res['success']) {


			   	$ttn=$res['data'][0]['IntDocNumber'];

				$ms_data['attributes'][]=array('id'=>$TTN_MS,
	     				"meta"=>array('href'=> "https://api.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/attributes/".$TTN_MS, 'type'=>'attributemetadata',"mediaType"=>"application/json"),
					'value'=>$ttn);
				$ms_data['attributes'][]=array('id'=>$COST_MS,
	     				"meta"=>array('href'=> "https://api.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/attributes/".$COST_MS, 'type'=>'attributemetadata',"mediaType"=>"application/json"),
					'value'=>(string)$res['data'][0]['CostOnSite']);
				$data = 'https:\/\/my.novaposhta.ua/orders/printMarkings\/orders[]\/'.$res['data'][0]['Ref']
				.'/type/pdf/apiKey/'.$NP_KEY;
				$ms_data['attributes'][]=array('id'=>$ERR_MS,
	     				"meta"=>array('href'=> "https://api.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/attributes/".$ERR_MS, 'type'=>'attributemetadata',"mediaType"=>"application/json"),
					'value'=>$err."\n".$aut);
        			$ms_data['attributes'][]=array('id'=>$PDF_MS,
	     				"meta"=>array('href'=> "https://api.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/attributes/".$PDF_MS, 'type'=>'attributemetadata',"mediaType"=>"application/json"),
					'value'=>$data);
				$tmp_date=explode(".",$res['data'][0]['EstimatedDeliveryDate']);

				$ms_data['attributes'][]=array('id'=>$DATA_MS,
	     				"meta"=>array('href'=> "https://api.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/attributes/".$DATA_MS, 'type'=>'attributemetadata',"mediaType"=>"application/json"),
					'value'=>$tmp_date[2].'-'.$tmp_date[1].'-'.$tmp_date[0].' 12:00:00');

				$resm=ms_query_send($v['meta']['href'],$ms_data,"PUT");


				if(!isset($resm['errors'])){


					$SMS_VALS=null;
					$nomer_tel=$chphone;
				      	$text = $DEFAULT['TRIGGER_TTN'];
					$SMS_VALS['TTN']=$ttn;

//					$res=mysqli_query($db,"select id from ciframe_sms_log where ms_id='{$v['id']}' and text='$text'");
//					list($checkid)=mysqli_fetch_row($res);


//					if(!$checkid){					

					if($TRIGGER_TTN=='on'){
						//if($DEFAULT['SMS_SERVICE']=='turbosms')	
						send_sms($chphone,$text,$SMS_VALS);
					}
//						mysqli_query($db,"insert into ciframe_sms_log set  ms_id='{$v['id']}' , text='$text'");
//					}

				}

				$np_id=$res['data'][0]['Ref'];
				mysqli_query($db,"insert into np_ms set np='$np_id',ms='{$v['id']}' ");


			}else{

 $hand=fopen("cron.log","a+");
 fwrite($hand,date("d-m-Y H:i:s",time()).' - '.$v['name']." \n".json_encode($sen_cre)."\n".json_encode($res)."\n\n");
 fclose($hand);


				$ms_data['attributes'][]=array('id'=>$ERR_MS,
	     				"meta"=>array('href'=> "https://api.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/attributes/".$ERR_MS, 'type'=>'attributemetadata',"mediaType"=>"application/json"),
					'value'=>$err);
				$resm=ms_query_send($v['meta']['href'],$ms_data,"PUT");


			}

}
		}

	}
	if(!@count($json['rows'])) { $CHECK_MS=false; } 
	$page++;
}//while




@unlink('gottn');
/*

$dfrom=date("d.m.Y",time()-(3600*24*20));
$dto=date("d.m.Y",time()+(3600*24*20));
$np_link="https://api.novaposhta.ua/v2.0/json/";
$sen_cre=null;
$sen_cre["apiKey"]=$NP_KEY;
$sen_cre['modelName']="InternetDocument";
$sen_cre["calledMethod"]="getDocumentList";
$sen_cre["methodProperties"]=array(
		     "DateTimeFrom"=> "$dfrom",
		     "DateTimeTo"=> "$dto",
		     "GetFullList"=> "1",
);

$hour=date("H",time());
$res=np_query_send($np_link,$sen_cre);

foreach($res['data'] as $k=>$v){

	$res=mysqli_query($db,"select id,ms,state from np_ms where np='{$v['Ref']}'");
	list($id,$ms,$state)=mysqli_fetch_row($res);

	$ttn=$v['IntDocNumber'];//var_dump($v);exit();

	$link='https://api.moysklad.ru/api/remap/1.2/entity/'.$DEFAULT['ENTITY'].'?expand=state&filter=https://api.moysklad.ru/api/remap/1.2/entity/'.$DEFAULT['ENTITY'].'/metadata/attributes/'.$TTN_MS.'='.$ttn;
	$json=ms_query($link);
	if($ttn and $id and !$json['rows'][0]['id']){
	 	continue;
	}

	if($ttn and !$id){


		mysqli_query($db,"insert into np_ms set ms='{$json['rows'][0]['id']}',state='',np='{$v['Ref']}'");

	}

	//filter=https://api.moysklad.ru/api/remap/1.2/entity/<type>/metadata/attributes/<id> =4
	if(($state!=$v['StateName'])&&$id){
		$ms_data=null;
		$link='https://api.moysklad.ru/api/remap/1.2/entity/'.$DEFAULT['ENTITY'].'/'.$ms;

		$ms_data['attributes'][]=array('id'=>"$MS_STATE",'value'=>$v['StateName']);
		if(isset($DEFAULT['STAT_ACCORD'])) foreach($DEFAULT['STAT_ACCORD'] as $ks=>$vs){
		 	if($vs['stat_np']==$v['StateName']) $ms_data['state']['meta']=$STATN[$vs['stat_ms']];
		}

		$res=ms_query_send($link,$ms_data,"PUT");

		mysqli_query($db,"update np_ms set state='{$v['StateName']}' where id='$id'");

	}
	
	
}
*/



///////////////////
if($w_upd=='stats'){



$CHECK_MS=true;
$page=0;
while($CHECK_MS){
	$limit=100;
	$offset=$limit*$page;




	$link="https://api.moysklad.ru/api/remap/1.2/entity/".$DEFAULT['ENTITY']."?filter=https://api.moysklad.ru/api/remap/1.2/entity/".$DEFAULT['ENTITY']."/metadata/attributes/".$TTN_MS."!=;".urlencode('updated>').date('YmdHis',strtotime('- 20 days',time()))."&limit=$limit&offset=$offset";

	$json=ms_query($link);
	//var_dump($json['meta']['size']);
	foreach($json['rows'] as $k=>$v){



		$resxxx=mysqli_query($db,"select id,ms,state,done from np_ms where ms='".$v['id']."'");
		list($id,$ms,$state,$done)=mysqli_fetch_row($resxxx);
		if(!$done){


		 	foreach($v['attributes'] as $ka=>$va){
				if($va['id']==$TTN_MS) 	{
					$ttns[]=array('DocumentNumber'=>$va['value']);
					$TTNMS[$va['value']]=$v['id'];
				}
			}
		}
			
	}
	if(!@count($json['rows'])) { $CHECK_MS=false; } 
	$page++;

}



$RESULTS=array();
foreach($ttns as $kt=>$vt){

	$ttns_[]=$vt;
	$count++;

	if($count==99){
		$sen_cre=array();
		$sen_cre["apiKey"]='';
		$sen_cre['modelName']="TrackingDocument";
		$sen_cre["calledMethod"]="getStatusDocuments";
		$sen_cre["methodProperties"]=array(
			'Documents'=>$ttns_
		);
		$res=np_query_send($np_link,$sen_cre);

		$RESULTS=array_merge($res['data'],$RESULTS);

		$count=-1; $ttns_=null;
	}
	$page++;

}
$sen_cre=array();
$sen_cre["apiKey"]='';
$sen_cre['modelName']="TrackingDocument";
$sen_cre["calledMethod"]="getStatusDocuments";
$sen_cre["methodProperties"]=array(
	'Documents'=>$ttns_
);

$np_link="https://api.novaposhta.ua/v2.0/json/";
$res=np_query_send($np_link,$sen_cre);

$RESULTS=array_merge($res['data'],$RESULTS);

foreach($RESULTS as $k=>$res){



		$status=$res['Status'].' / '.$res['StatusCode'];

		$resxxx=mysqli_query($db,"select id,ms,state from np_ms where ms='".$TTNMS[$res['Number']]."'");
		list($id,$ms,$state)=mysqli_fetch_row($resxxx);



	
		if(!$id) {

			mysqli_query($db,"insert into np_ms set ms='".$TTNMS[$res['Number']]."'");
			$err=mysqli_error($db);


			$ms=$TTNMS[$res['Number']];
			$id=mysqli_insert_id($db);


		}
		
		
		if(($state!=$status)&&$id){
			$ms_data=null;
			$link='https://api.moysklad.ru/api/remap/1.2/entity/'.$DEFAULT['ENTITY'].'/'.$ms;
			$ms_data['attributes'][]=array('id'=>"$MS_STATE",
     				"meta"=>array('href'=> "https://api.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/attributes/".$MS_STATE, 'type'=>'attributemetadata',"mediaType"=>"application/json"),
				'value'=>$status);
			if(isset($DEFAULT['STAT_ACCORD'])) foreach($DEFAULT['STAT_ACCORD'] as $ks=>$vs){
			 	if($vs['stat_np']==$v['StateName']) $ms_data['state']['meta']=$STATN[$vs['stat_ms']];
			}

			$result=ms_query_send($link,$ms_data,"PUT");
			//var_dump($result);
			$upd=date("Y-m-d H:i:s",time());
			mysqli_query($db,"update np_ms set state='$status',upd='$upd' where id='$id'");


			if(strpos(' '.$status,'Відправлення отримано') and  !strpos(' '.$status,'Протягом доби')) {
				mysqli_query($db,"update np_ms set done='1' where id='$id'");
			}

// $hand=fopen("cron.log","a+");
// fwrite($hand,date("d-m-Y H:i:s",time()).' - '.json_encode($result)." \n");
// fclose($hand);


		}


}

 	echo("DONE");
}