﻿<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/

    $db = ConnectMyDB('portal');

/*---------------------------------------------------------------------------*/


	$data = array();
	$sql = "SELECT * FROM `portal`.`prt#v#otdel` ORDER BY name";
	if ( $result = $db->query( $sql ) ) {
		
		while ($row = $result->fetch_assoc()) {
			array_push($data, $row);
		}

		echo json_encode(array('success'=>true,'data'=>$data));
	}

?>