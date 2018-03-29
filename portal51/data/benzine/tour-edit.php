<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');
if ( is_session_started() === FALSE ) session_start();

/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('portal_benzine');
/*---------------------------------------------------------------------------*/

	$data = array();
	$adata = (object)$_POST;
//	$info = $_POST['data'];
//	$adata = json_decode($info);
	//print_r($adata);
	
	//$adata->distance = ($adata->distance)
	
	$sql = "SELECT `setTour`({$adata->id},'{$adata->tour_from}','{$adata->tour_to}',{$adata->distance},{$adata->tour_id} )";
	
	//echo $sql;
	$id = $db->getOne($sql);

		echo json_encode(array(
			"success" => mysql_errno() == 0,
			"data" => array(
				"id" => $id,
				'tour_from'=>$adata->tour_from,
				'tour_to'=>$adata->tour_to,
				'tour_id'=>$adata->tour_id,
				'distance'=>$adata->distance
			)
		));
	
	
	/*if($adata->id==0){ // Добавляем
		@$db->query("INSERT INTO tours SET
			tour_from='{$adata->tour_from}',
			tour_to='{$adata->tour_to}',
			tour_id='{$adata->tour_id}',
			distance='{$adata->distance}'
			");

		echo json_encode(array(
			"success" => mysql_errno() == 0,
			"data" => array(
				"id" => $db->insertId(),
				'tour_from'=>$adata->tour_from,
				'tour_to'=>$adata->tour_to,
				'tour_id'=>$adata->tour_id,
				'distance'=>$adata->distance
			)
		));
		
	} else { // Исправляем
		@$db->query("UPDATE tours SET
			tour_from='{$adata->tour_from}',
			tour_to='{$adata->tour_to}',
			tour_id='{$adata->tour_id}',
			distance='{$adata->distance}'
			WHERE id={$adata->id}
			");

		echo json_encode(array(
			"success" => mysql_errno() == 0,
			"data" => array(
				"id" => $adata->id,
				'tour_from'=>$adata->tour_from,
				'tour_to'=>$adata->tour_to,
				'tour_id'=>$adata->tour_id,
				'distance'=>$adata->distance
			)
		));
	} */
?>