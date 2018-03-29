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

//if( isset($a->list) ){

if ($a->mode == '0'){ $AND = " AND close=0";}
elseif ($a->mode == '1'){ $AND = " AND close=1";}
else { $AND = "";}


	if ($a->list == 'short'){
		$sql = "select * from Months where id not in (select months from Settings where years={$a->years})";
	} else if ($a->list == 'short2'){
		$sql = "select * from Months where id in (select months from Settings where years={$a->years} $AND)";
	} else {
		$sql = "select * from Months"; // where id not in (select distinct months from Times where years={$a->years})";
	}

	$data = $db->query( $sql )->fetchAll();	
	

		echo json_encode(array('success'=>'true','data'=>$data));
//}		
	

?>