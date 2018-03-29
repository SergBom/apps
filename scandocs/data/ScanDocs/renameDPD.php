<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
	$user_id  = isset( $_SESSION['portal']['user_id'] )   ?  $_SESSION['portal']['user_id']   : '0';
	$user_FIO = isset( $_SESSION['portal']['FIO'] )       ?  $_SESSION['portal']['FIO']       : '';

$name = trim( $_POST['name'] );
$cdate = trim( $_POST['cdate'] );
$cyear = trim( $_POST['cyear'] );
$otdel = trim( $_POST['n1'] );
/*---------------------------------------------------------------------------*/


//if ( $path ) {


    $db = ConnectPDO('Scan_docs');

/*---------------------------------------------------------------------------*/

	$data = array();	
	$cp = $_SERVER['DOCUMENT_ROOT']. "/portal51/data/ScanDocs/";
	$cp = "/mnt/archivedocs/";
	
	/*---------------------------------------------------------------------------*/

	$a = explode('/',$_SERVER["DOCUMENT_ROOT"]);
	//print_r($a);
	
/*	if( $a[1] == 'var'){
		$rp = "/mnt/archivedocs/scan_docs/";
	} else {
		$rp = 'file://///ARCHIVESHARE/Archive/scan_docs/';
	}
*/

	
	$rename = true;
/*	if($path){
	
		$full_name_new = $cp . $path. '/' . $cyear . '/' . $name;
	
		$name_old = $db->query("SELECT name FROM docs_l1 WHERE id={$_POST['id']}")->fetchColumn();
	
		$full_name_old = $cp . $path. '/' . $cyear . '/' . $name_old;
	
	
	
		$msg = $full_name_new . "<br>". $full_name_old;
	
	
		
		$cmd = 'mv "'.$full_name_old.'" "'.$full_name_new.'"'; 
		exec($cmd, $output, $return_val); 

		if ($return_val == 0) { 
//		if( rename( "'".$full_name_old."'", "'".$full_name_new."'" ) ){
			$rename = true;
		} else {
			$msg = "Ошибка переименования каталога!";
		}
	} else {
			$rename = true;
	}
	*/

	if( $rename ){
		
		$n3 = $db->query("SELECT n3 FROM `t\$district_data` WHERE `n1`='$otdel'")->fetchColumn();
		
		
		$sql = "UPDATE docs_l1 SET
			name='$name',
			cdate='$cdate',
			cyear='{$_POST['cyear']}',
			`n1`='$otdel',
			`n3`='$n3'
			,user_id='$user_id'
			,user_fio='$user_FIO'
			WHERE id={$_POST['id']}";
		$db->query($sql);

		$msg = "Переименовано успешно!";
	}
	
	echo json_encode(array(
			'success'=>true,
			'msg'=>$msg));
	
	
	
?>