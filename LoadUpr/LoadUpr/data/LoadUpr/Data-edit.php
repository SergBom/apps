<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');
if ( is_session_started() === FALSE ) session_start();

/*-------------------------- Входные переменные -----------------------------*/
	//@$_SESSION['portal']['user_id'] = $_SESSION['user_id'];
	//@$_SESSION['portal']['username'] = $_SESSION['username'];
	//$user_id  = isset( $_SESSION['portal']['user_id'] )   ?  $_SESSION['portal']['user_id']   : '';
	//$otdel_id = isset( $_SESSION['portal']['otdel_id'] )  ?  $_SESSION['portal']['otdel_id']  : '0';
	//$org_id   = isset( $_SESSION['portal']['domain_id'] ) ?  $_SESSION['portal']['domain_id'] : '0';
	//$user_FIO = isset( $_SESSION['portal']['FIO'] ) ?  $_SESSION['portal']['FIO'] : '';

/*---------------------------------------------------------------------------*/
	$db = ConnectPDO('LoadUpr');
/*---------------------------------------------------------------------------*/
	$data = array();
	$a = (object)$_REQUEST;
	
	//print_r($a);
	//print_r($_SESSION['portal']);
	
//	$dateOtchet = substr($adata->dateOtchet,0,10);
	//echo "User_id='$user_id'<br>";
	
$sql = "UPDATE `Datas` SET 
			var_data={$a->var_data}
			WHERE id={$a->id}
			";
		$db->query($sql);
		

	
//	array_push($data, (array)$a	));
	echo json_encode(array('success'=>'true','data'=>$sql));
		
?>