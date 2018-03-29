<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
//$Org_id = trim ((!empty($_GET['org'])) ? $_GET['org'] : "1" ); // 

/*	$_SESSION['portal']['user_id'] = $_SESSION['user_id'];
	$_SESSION['portal']['username'] = $_SESSION['username'];
	$user_id = isset( $_SESSION['portal']['user_id'] ) ?  $_SESSION['portal']['user_id']  : '';
	$org_id  = isset( $portal_auth['domain_id'] ) ? ($portal_auth['domain_id'])? $portal_auth['domain_id']:1  : 1;
*/
	$data = array();
	$adata = (object)$_GET; //json_decode($info);
	
/*---------------------------------------------------------------------------*/

//    $db = ConnectMyDB('EvalEffective');

/*---------------------------------------------------------------------------*/
    $DateBegin = ( !empty( $_GET['db'] ) ) ? substr($_GET['db'],0,10) : date('Y-01-01') ; // 
	$DateEnd   = ( !empty( $_GET['de'] ) ) ? substr($_GET['de'],0,10) : date('Y-m-d') ; // 

//$Date0 = "2015-01-01";
//$Date  = "2016-04-01";

$otdels = array();
$array2 = array();


	// Общая информация по всем отделам за период
		$db = ConnectMyDB('EvalEffective');
		$sql = "CALL `GZC#p#RecordData`('$DateBegin', '$DateEnd', '0', '0')";
		//echo $sql."<br>";
		if ( $res2 = $db->query( $sql ) ) {
				$CN=0;
				while ($row2 = $res2->fetch_assoc()) {
	
					$arrayS[$CN]['id']=$row2['id'];
					$arrayS[$CN]['R1']=$row2['R1'];
					$arrayS[$CN]['R2']=$row2['R2'];
					$arrayS[$CN]['summ']=$row2['Value'];
					$CN++;
				}
		}



	// Информация по каждому отделу за период, которые вводили данные
	// Возвращаем полный список отделов с подотделами
    $db = ConnectMyDB('EvalEffective');
	if ( $res1 = $db->query( "CALL `GZC#p#Otdels`()" ) ) {

		while ($row1 = $res1->fetch_assoc()) {
	
			// Выбираем данные по конкретному отделу
			$db = ConnectMyDB('EvalEffective');
			$sql = "CALL `GZC#p#RecordData`('$DateBegin', '$DateEnd', '0', '{$row1['id']}')";
		//echo $sql."<br>";
			if ( $res2 = $db->query( $sql ) ) {
				$CN=0;
				while ($row2 = $res2->fetch_assoc()) {
	
					//$arrayS[$CN]['id']=$row2['id'];
					//$arrayS[$CN]['R1']=$row2['R1'];
					//$arrayS[$CN]['R2']=$row2['R2'];
					$arrayS[$CN][$row1['cn']]=$row2['Value'];
					$CN++;
				}
			}
		}
		
		echo json_encode(array('success'=>'true','data'=>$arrayS));
	}

?>