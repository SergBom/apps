<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
$name = trim( $_POST['name'] );
$cdate = trim( $_POST['cdate'] );
$cyear = trim( $_POST['cyear'] );
$path = trim( $_POST['path'] );
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

	
	
	
	$full_name_new = $cp . $path. '/' . $cyear . '/' . $name;
	
	$name_old = $db->query("SELECT name FROM docs_l1 WHERE id={$_POST['id']}")->fetchColumn();
	
	$full_name_old = $cp . $path. '/' . $cyear . '/' . $name_old;
	
	
	
	$msg = $full_name_new . "<br>". $full_name_old;
	
	
	$rename = false;
	if($path){
		
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

	if( $rename ){
		$sql = "UPDATE docs_l1 SET name='$name', cdate='$cdate' WHERE id={$_POST['id']}";
		$db->query($sql);

		$msg = "Переименовано успешно!";
	}
	
	echo json_encode(array(
			'success'=>true,
			'msg'=>$msg));
	
	
	
?>