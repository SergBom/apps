<?php
$isCLI = ( php_sapi_name() == 'cli' );
if($isCLI){
    include_once("/var/www/portal/public_html/php/init2.php");
}else{
    include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
}
header('Content-type: text/html; charset=utf-8');


$db = ConnectPDO('security');

/*---------------------------------------------------------------------------*/

$sql = "SELECT * FROM `cert2`";

$data = $db->query( $sql )->fetchAll(); //PDO::FETCH_COLUMN);

echo json_encode(array('success'=>'true','data'=>$data));




?>