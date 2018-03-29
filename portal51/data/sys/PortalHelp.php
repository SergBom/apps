<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/

    $db = ConnectMyDB('portal');

/*---------------------------------------------------------------------------*/
	//print_r( $_POST );

	$data = array();
	
	$sql = "SELECT * FROM `portal`.`v#help` WHERE alias='{$_POST['alias']}'"; // ORDER BY name";
	if ( $result = $db->query( $sql ) ) {
		
		if($db->numRows($result)>0){
			$row = $result->fetch_assoc();
			array_push($data, $row);

			echo json_encode(array('success'=>true,'data'=>[
				"textHelp"=>$row["textHelp"],
				"header"=>$row["header"]
				]));
			
		} else {
			echo json_encode(array('success'=>true,'data'=>["textHelp"=>"Помощь в данном разделе отсутствует.",
				"header"=>""
				]));
			
		}
		
	}

?>