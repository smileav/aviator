<?
$CRM_ID='';
$LICENSES['NP']='W5XhFXPfFuUwVrl611ufXZVxRjzLgvzuYg6iZHCM5UljHGHD9LgD1900WeS0DU5THx475NDBAY+raiTHJ4x9piSS8IAVuKR+p9NqFSyOPmgPEiItHX8duC993O+IXQ5lrjt64pG2TH+9ypuHyJYspX1NCwNQtomxyg3yLnuXlNQ=';//M1ovAtKmCyiYeYqPgohqQNk3gFgzAedDl4YH0PsMm9AQQqT/l3niItkDKwP3KkxBqClFQ3qgv7YsGr/4Ct821ryjEsEZdWP6YWXNaXDGzGVyEBbbYrCAGv6akNRLZ94NPwCVt3pmwSJdgb9fIX+bH04lgf5aKQSj4aIR7yJ6pmQ=';
$LICENSES['SMS']='X9YdLOsZCMHMUdQTZpTrQuINJlXg+BkR7P+0W3/7//f85FFj74WCZPtEhbfnLwtfZiz9VNAjYcYwxv4CtpgXld+WWc89ynyBUVThMcIxaySi5sZiM60R+oIVMIPsNUbskeECjVPgb0GktQR6O2iW09VdSw0csfVFvvNeZRRbGYs=';//DCrRscnUaYtRH35G1gfXqkN9MIcrurtRYhR+BIvKZRfcP7k1tXWnzCU5pI/Yy4rPpRT0H3Sd6VHfp1VQoMZXY2wrReUuXyJH6DYm/FH4mksNIg2trTE/n1RLfnVUwjDTNgXVM76IBa8fWou+8BQ9+QpK2wiQwEpNlmrGb2s++BM=';
$LICENSES['FIN']='';
$LICENSES['RZ']='';
//MOYSKLAD
//#OLD  $MS_AUTH='admin@glazursales1:27040900';
$MS_AUTH='julia@glazursales1:katya2023';

//NOVA POSHTA SETTINGS
$NP_KEY="";

//ADMIN AUTH
$SETUP_LOGIN='admin';
$SETUP_PASSWORD='admin';

//DB AUTH
$db=mysqli_connect("aviato02.mysql.tools","aviato02_ciframe","e(E8N6^n4g","aviato02_ciframe");
mysqli_query($db,"set names utf8");
if(!$db) { echo("Database Error"); exit(); }

$lang='ru';

//SMS TURBOSMS/SMSFLY SETTINGS
$SMS_AUTH['login']='BlackinWhite';
$SMS_AUTH['password']='7460079';

//SMS SENDPULSE
$SMS_AUTH['grant_type']='';
$SMS_AUTH['client_id']='';
$SMS_AUTH['client_secret']='';

//ROZETKA UATH
$RZ_DATA['username']='';
$RZ_DATA['password']='';

//PROM/TIU/DEAL
$PROM_TIU['HOST']='my.prom.ua';  // e.g.: my.prom.ua, my.tiu.ru, my.satu.kz, my.deal.by, my.prom.md
$PROM_TIU['TOKEN']='';

include_once("inc.php");
if(file_exists(__DIR__.'/np_functions.php')) { include_once(__DIR__."/np_functions.php"); $MENU[]="<a href='np_setup.php'>Настройки Новая почта</a>"; }
if(file_exists(__DIR__.'/sms_functions.php')) { include_once(__DIR__."/sms_functions.php"); $MENU[]="<a href='sms_setup.php'>Настройки SMS</a>"; }
if(file_exists(__DIR__.'/pb_functions.php')) { include_once(__DIR__."/pb_functions.php"); $MENU[]="<a href='pb_setup.php'>Настройки Приватбанк</a>"; }
if(file_exists(__DIR__.'/fin_functions.php')) { include_once(__DIR__."/fin_functions.php"); $MENU[]="<a href='fin_setup.php'>Настройки Приватбанк</a>"; }
//if(file_exists(__DIR__.'/rz_functions.php')) { include_once(__DIR__."/rz_functions.php");  $MENU[]="<a href='rz_setup.php'>Настройки Розетка</a>"; }
//if(file_exists(__DIR__.'/prom_functions.php')) { include_once(__DIR__."/prom_functions.php");  }