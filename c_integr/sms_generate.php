<?php


include("config.php");
header('Content-type: text/html; charset=utf-8');

if(!checkLicence_sms()) { echo("Лицензия не активна. Обратитесь в info@ciframe.com если есть вопросы"); exit();}

$res=mysqli_query($db, "select vars_code,vars_name, vals,`default` from np_config");
while(list($vcode,$vname,$vals,$def)=mysqli_fetch_row($res)){
	$$vcode=$vals;
	if($vcode=='STAT_ACCORD') 	$DEFAULT[$vcode]=unserialize($def);
	else $DEFAULT[$vcode]=$def;
}



$link=$DEFAULT['TRIGGER_STATUS'];
$json=ms_query($link);

$json=ms_query($json['entityMeta']['href']);
foreach($json['rows'] as $k=>$v){
	$SMS_STATS[$v['name']]=$v['description'];
}
/*
$link='https://api.moysklad.ru/api/remap/1.2/entity/customentity/'.$DEFAULT['TRIGGER_STATUS'];
$json=ms_query($link);
foreach($json['rows'] as $k=>$v){
	$SMS_TEMPL[$v['name']]=$v['description'];
}
*/


$time=time();
$time_ins=date('Y-m-d H:i:s',$time);

$link='https://api.moysklad.ru/api/remap/1.2/entity/'.$DEFAULT['ENTITY_SMS'].'/metadata/';
$json=ms_query($link);
$MS_ST=null;
foreach($json['states'] as $k=>$v){
	$MS_STATS[$v['meta']['href']]=$v['name'];
}









//
$ttn_upd_int=date("Y-m-d H:i:s",time()-3600*24*4);
$ttn_upd_old=date("Y-m-d H:i:s",time()-3600*24*5);
$rff=mysqli_query($db,"select ms from np_ms where (upd<'$ttn_upd_int' and upd>'$ttn_upd_old') and state like '%Прибув у відділення%' and (done!='1' or isnull(done))");
while(list($ms)=mysqli_fetch_row($rff)){
	break;
	if($ms){
		$link_ord='https://api.moysklad.ru/api/remap/1.2/entity/customerorder/'.$ms."?expand=agent,organization";
		$v=ms_query($link_ord);
		echo($v['name']."<BR>");
		if($v['name']){

			$nomer_tel=$phone='';
			foreach($v['attributes'] as $ka=>$va){
			
				if($va['name']=='Телефон получателя') $phone=$va['value'];
				if($va['id']==$DATA_MS) {
					$deldate_=strtotime($va['value']);
					$deldate=date('d.m.Y H:i',$deldate_);
				}	
			
				if($va['id']==$BACK_SUM) {
					$money_transfer=$va['value'];
				}	
	
				if($va['id']==$COST_MS) {
					$delprice=$va['value'];
				}	
	
				if($va['name']=='Статус доставки') {
					$np_stat=$va['value'];
				}	
		
				if($va['id']==$TTN_MS) {
					$TTN=$va['value'];
				}	
			 	if($va['id']==$FIO_RECEP)  { 
			 	 	$FIO=$va['value'];
				}
			

				if($va['id']==$DEFAULT['TRIGGER_TEMPLATE']) {
					$linka=$va['value']['meta']['href'];
					$rex=ms_query($linka);
					$text_template=$rex['description'];
					$SMS_SIGNATURE=$rex['code'];

					$meta_template=$va['meta'];
					$id_template=$va['id'];
				}
			}
			$fio=$v['agent']['name'];
			$nomer_tel=$v['agent']['phone'];
			if($v['organization']['name']=='Aviator') {
				$DEFAULT['SMS_SIGNATUR']='AVIATOR';
				$SMS_SIGNATURE='AVIATOR';
				$type=3;
				$text="Ваше замовлення прибуло на вказане відділення Нової Пошти:
{TTN}
www.AVIATOR.shop";
		
			}

			if($v['organization']['name']=='GLAZUR') {
				$DEFAULT['SMS_SIGNATUR']='GLAZUR';
				$SMS_SIGNATURE='GLAZUR';

				$type=3;
				$text="Ваше замовлення прибуло на вказане відділення Нової Пошти:
{TTN}
www.glazur.in.ua";


			}
			$phone=$nomer_tel;

			$res=mysqli_query($db,"select id from sms_log where phone='$phone' and (type='$type' and ms_id='{$v['id']}' and sms='".addslashes($text)."')");
			list($checkid)=mysqli_fetch_row($res);
			if(!$checkid and (($type>0) or @$id_template)){




					if($text and $phone){

echo("SEND");
						$SMS_VALS=null;
						$SMS_VALS['TTN']=$TTN;
						$SMS_VALS['ORDER_NUMBER']=$SMS_VALS['ORDER_NUM']=$SMS_VALS['NUMBER']=str_replace("PROM","",$v['name']);
						$SMS_VALS['SUM']=$v['sum'];
						$SMS_VALS['FIO']=$FIO;
						$SMS_VALS['ORDER_DATE']=$order_date;
						$SMS_VALS['DELIVERY_DATE']=$deldate;
						$SMS_VALS['DELIVERY_PRICE']=$delprice;
						$SMS_VALS['MONEY_TRANSFER']=$money_transfer;
						$SMS_VALS['OKPO']=$v['organization']['okpo'];



                                      		$result_sms=send_sms($phone,$text,$SMS_VALS);
						if($_GET['debug']==1){
							var_dump($SMS_VALS);
							var_dump($text);
							var_dump($result_sms);
						}
						mysqli_query($db,"insert into sms_log set phone='$phone', type='$type',sms='".addslashes($text)."',upd='".time()."',ms_id='{$v['id']}'");
					}
				


			}


		}
	}

}






$CHECK_MS=true;
$limit=100;
$i=0;
while($CHECK_MS){

	$offset=$i*$limit;
	$updfrom=date("Y-m-d H:i:s",time()-(3600));	

	$link="https://api.moysklad.ru/api/remap/1.2/entity/".$DEFAULT['ENTITY_SMS']."?expand=project,state,agent,organization&limit=$limit&offset=$offset&filter=".urlencode('updated>').date('YmdHis',strtotime('- 20 minutes',time())) ;
	$json=ms_query($link);

	foreach($json['rows'] as $k=>$v){

		$moment=strtotime($v['moment']);
		$order_date=date('d.m.Y H:i',$moment);
		

		$SMS_SIGNATURE='';
		$phone='';		
//		$phone=$v['agent']['phone'];
		$status=$v['state']['name'];
		$np_stat=$money_transfer=$deldate=$text=$text_template=$TTN='';
		$delprice=$type=0;
                $status_np='';
		foreach($v['attributes'] as $ka=>$va){
			
			if($va['name']=='Телефон получателя') $phone=$va['value'];
			if($va['name']=='Статус доставки') $status_np=$va['value'];


			if($va['id']==$DATA_MS) {
				$deldate_=strtotime($va['value']);
				$deldate=date('d.m.Y H:i',$deldate_);
			}	

			if($va['id']==$BACK_SUM) {
				$money_transfer=$va['value'];
			}	

			if($va['id']==$COST_MS) {
				$delprice=$va['value'];
			}	

			if($va['name']=='Статус доставки') {
				$np_stat=$va['value'];
			}	

			if($va['id']==$TTN_MS) {
				$TTN=$va['value'];
			}	
		 	if($va['id']==$FIO_RECEP)  { 
		 	 	$FIO=$va['value'];
			}


			if($va['id']==$DEFAULT['TRIGGER_TEMPLATE']) {
					$linka=$va['value']['meta']['href'];
					$rex=ms_query($linka);
					$text_template=$rex['description'];
					$SMS_SIGNATURE=$rex['code'];

					$meta_template=$va['meta'];
					$id_template=$va['id'];

					

			}


		}

		if(strpos(" ".$status_np,"Прибув у відділення")){
			if($v['organization']['name']=='Aviator') {
				$DEFAULT['SMS_SIGNATUR']='AVIATOR';
				$SMS_SIGNATURE='AVIATOR';
				$type=4;
				$text="Ваше замовлення прибуло на вказане відділення Нової Пошти:
{TTN}
www.AVIATOR.shop";
		
			}

			if($v['organization']['name']=='GLAZUR') {
				$DEFAULT['SMS_SIGNATUR']='GLAZUR';
				$SMS_SIGNATURE='GLAZUR';
				$type=4;
				$text="Ваше замовлення прибуло на вказане відділення Нової Пошти:
{TTN}
www.glazur.in.ua";


			}
		}

		if(!$DEFAULT['SMS_SIGNATUR']) $DEFAULT['SMS_SIGNATUR']=$SMS_SIGNATURE;
		if($text_template) {
			$type=2;
			$text=$text_template;
		}elseif(@$SMS_STATS[$status]){
			$type=1;
			$text=$SMS_STATS[$status];

		}


		if(strpos(' '.$text,"{TTN}") and !$TTN) continue;
		


		$rff=mysqli_query($db,"select upd from np_ms where ms='{$v['id']}'");
		list($ttn_upd)=mysqli_fetch_row($rff);

		$ttn_upd_int=strtotime($ttn_upd);
		if(time()>($ttn_upd_int+3600*24*4)){
			if($v['organization']['name']=='Aviator') {
				$DEFAULT['SMS_SIGNATUR']='AVIATOR';
				$SMS_SIGNATURE='AVIATOR';
		
		
			}

			if($v['organization']['name']=='GLAZUR') {
				$DEFAULT['SMS_SIGNATUR']='GLAZUR';
				$SMS_SIGNATURE='GLAZUR';
			}
		}

		$res=mysqli_query($db,"select id from sms_log where phone='$phone' and (type='$type' and ms_id='{$v['id']}' and sms='".addslashes($text)."')");
		list($checkid)=mysqli_fetch_row($res);

		 $hand=fopen("db.log","a+");
		 fwrite($hand,date("d-m-Y H:i:s",time()).' ---- '.$v['name']." - ".$type." \n ".json_encode($db)."\n".$text."\n".$checkid."\n");
		 fclose($hand);
		if(!$checkid and (($type>0) or @$id_template)){
//		if((!$checkid and ($type>0)) or @$id_template){


					if($text){

echo("SEND");
						$SMS_VALS=null;
						$SMS_VALS['TTN']=$TTN;
						$SMS_VALS['ORDER_NUMBER']=$SMS_VALS['ORDER_NUM']=$SMS_VALS['NUMBER']=str_replace("PROM","",$v['name']);
						$SMS_VALS['SUM']=$v['sum'];
						$SMS_VALS['FIO']=$FIO;
						$SMS_VALS['ORDER_DATE']=$order_date;
						$SMS_VALS['DELIVERY_DATE']=$deldate;
						$SMS_VALS['DELIVERY_PRICE']=$delprice;
						$SMS_VALS['MONEY_TRANSFER']=$money_transfer;
						$SMS_VALS['OKPO']=$v['organization']['okpo'];



                                      		$result_sms=send_sms($phone,$text,$SMS_VALS);
						if($_GET['debug']==1){
							var_dump($SMS_VALS);
							var_dump($text);
							var_dump($result_sms);
						}

						$ms_data=null;
						$ms_data['attributes'][]=array('meta'=>$meta_template,'id'=>$id_template,'value'=>null);
						$resm=ms_query_send($v['meta']['href'],$ms_data,"PUT");

						mysqli_query($db,"insert into sms_log set phone='$phone', type='$type',sms='".addslashes($text)."',upd='".time()."',ms_id='{$v['id']}'");
						$err=mysqli_error($db);
						var_dump($err);
					}
				


		}
		

	}
	if(!count($json['rows'])) { $CHECK_MS=false; } 
	$i++;

	
}




