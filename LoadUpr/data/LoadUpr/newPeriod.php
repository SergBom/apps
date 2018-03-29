<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
//$Org_id = trim ((!empty($_GET['org'])) ? $_GET['org'] : "1" ); // 

	//$_SESSION['portal']['user_id'] = $_SESSION['user_id'];
	//$_SESSION['portal']['username'] = $_SESSION['username'];
	//$user_id = isset( $_SESSION['portal']['user_id'] ) ?  $_SESSION['portal']['user_id']  : '';

	$data = array();
	$a = (object)$_REQUEST; //json_decode($info);
	
/*---------------------------------------------------------------------------*/

    $db = ConnectPDO('LoadUpr');

/*---------------------------------------------------------------------------*/

	$sql = "insert into `Settings` (VarsName,VarsNameFull,VarsData, years, months) VALUES ('WorksDay','Рабочих дней','{$a->worksday}', {$a->years}, {$a->months})";
	//echo $sql . "\n";
	
	$db->query( $sql );


	$sql = "insert into `Times` (var_id,years,months) select id, {$a->years}, {$a->months} from `Variants` where times=1";
	$db->query( $sql );
	
	$otdels = $db->query( "select id from `Otdels`" )->fetchAll(PDO::FETCH_COLUMN, 0);
	
	foreach( $otdels as $k=>$v ){
		
		$sql = "insert into `Datas` (otdel_id,var_id,years,months) select {$v}, id, {$a->years}, {$a->months} from `Variants` where times=1";
		$db->query( $sql );
	}
	echo json_encode(array('success'=>'true', 'message'=>'Данные сформированы'));

?>