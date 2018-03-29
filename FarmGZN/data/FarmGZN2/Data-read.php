<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');
if ( is_session_started() === FALSE ) session_start();

/*-------------------------- Входные переменные -----------------------------*/
//	$user_id = isset( $_SESSION['portal']['user_id'] ) ?  $_SESSION['portal']['user_id']  : '';
//	$org_id   = isset( $_SESSION['portal']['domain_id'] ) ? $_SESSION['portal']['domain_id'] : 0;
//	@$org_id  = isset( $portal_auth['domain_id'] ) ? ($portal_auth['domain_id'])? $portal_auth['domain_id']:1  : 1;
/*---------------------------------------------------------------------------*/
//print_r($_SESSION);
/*---------------------------------------------------------------------------*/

	$data = array();
	$a = (object)$_REQUEST;

	$db = ConnectPDO('CadastErrors');
	
	
	
	// Если данных по данному КадИнженеру за этот период нет, то надо создать
	$N = $db->query( "select count(*) from `Errors` t1 where dateIn='{$a->dateIn}' AND eng_id={$a->eng_id}" )->fetchColumn();
	
	if ( $N == 0 ){
		
		$res = $db->query( "select id from `ParamsErrors` " ); //->fetchAll(PDO::FETCH_COLUMN);
		while( $res_d = $res->fetch() ){
			
			$db->query( "INSERT INTO `Errors` SET 
				eng_id='{$a->eng_id}',
				dateIn='{$a->dateIn}',
				var_id='{$res_d['id']}'
				" );
			
		}
		
	}
	
	
	// Потом всё показываем
	
	$sql = "SELECT * FROM `vData` WHERE eng_id='{$a->eng_id}' AND dateIn='{$a->dateIn}'";
	//echo $sql;
	
	$data = $db->query( $sql )->fetchAll();
	
	
	echo json_encode(array('success'=>'true','data'=>$data));
		
?>