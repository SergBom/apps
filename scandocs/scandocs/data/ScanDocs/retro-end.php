<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
//$Org_id = trim ((!empty($_GET['org'])) ? $_GET['org'] : "1" ); // 
$params = $_POST; 
/*---------------------------------------------------------------------------*/

    $db = ConnectMyDB('Scan_docs');

/*---------------------------------------------------------------------------*/

$start_dir = "/mnt/archivedocs/";
$dirScan = 'scans/';


$a = explode('/',$_SERVER["DOCUMENT_ROOT"]);
//print_r($a);
	
	if( $a[1] == 'var'){
		$rp = $start_dir.$dirScan;
/*	} else {
		//$rp = 'file://///ARCHIVESHARE/Archive/'.$dirScan.'/';
		$rp = '\\\\ARCHIVESHARE\\Archive\\'.$dirScan.'\\';
		$rt = '\\\\ARCHIVESHARE\\Archive\\'.$dirTemp.'\\'; */
	}

$DPD = str_replace(" ","_", 'DPD_'.substr($params['name'],0,12) );	
	

	// Каталог для обработки Дела
	$dir_dest = $rp . $DPD . '/' . $params['name'];
	
	echo $dir_dest.'<br>';
		
	
	exec('rm -rf "'.$dir_dest.'"');

	//echo $output;
	$db->query( "UPDATE docs_l1 SET retro=2 WHERE id={$_POST['id']}" );

	$c = array('success'=>0); //,'file'=>'data/ScanDocs/temp/'.$temp_URL);
	echo json_encode($c);
	
	
?>