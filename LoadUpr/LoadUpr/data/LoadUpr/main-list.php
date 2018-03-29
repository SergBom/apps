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

	$sql = "SELECT VarsData FROM `Settings` WHERE VarsName='WorksDay' AND years={$a->years} AND months={$a->months}";
	$WorksDay = $db->query( $sql )->fetchColumn();

	//echo $WorksDay."<hr>";


	$sql = "SELECT name, var_data FROM `vTimes` WHERE years={$a->years} AND months={$a->months}";
	$Times = $db->query( $sql )->fetchAll(PDO::FETCH_COLUMN|PDO::FETCH_GROUP);     //PDO::FETCH_FUNC, "fr");     // PDO::FETCH_COLUMN); //|PDO::FETCH_GROUP);
	


	$sql = "SELECT id,name_full FROM `Otdels` ORDER BY `ord`";
	if ( $result = $db->query( $sql ) ) {
	
		while ($row = $result->fetch()) {
			
			$sql2 = "SELECT name, var_data FROM `vDatas2` WHERE otdel_id={$row['id']} AND years={$a->years} AND months={$a->months}";
			//echo $sql2."<br>";
			$result2 = $db->query( $sql2 )->fetchAll(); //PDO::FETCH_COLUMN); //|PDO::FETCH_GROUP);     //PDO::FETCH_FUNC, "fr");     // PDO::FETCH_COLUMN); //|PDO::FETCH_GROUP);
			if($result2){
				//var_dump($result2);			
			
			
			
				$WorksHourInDay		= 0;   	// Параметр 24
				$SotrudnikHour 		= 0;	// Параметр 25
				$SotrudnikDay 		= $result2[0]['var_data'] / $WorksDay / 8;	// Параметр 26
				$LoadSotrudnikDay 	= 0;	// Параметр 27
			
				$new_row = array();
			
				for( $i=0; $i<count($result2); $i++ ){
					//echo $result2[$i] ."<br>";
					$new_row = $new_row + array( $result2[$i]['name']=>$result2[$i]['var_data'] );
					$t = $Times[$result2[$i]['name']][0] /60;
					$SotrudnikHour		+= $result2[$i]['var_data'] * $t;
				
					//echo $result2[$i]['name'] ."=". $t  . "<br>";
				}
			
				$WorksHourInDay		= $SotrudnikHour / $WorksDay;
				if( $SotrudnikDay == 0 ){ $SotrudnikDay = 0.000000000001; }
				$LoadSotrudnikDay 	= $WorksHourInDay / $SotrudnikDay;
			
				$new_row2 = array(
					'WorksHourInDay'	=> $WorksHourInDay,
					'SotrudnikHour'		=> $SotrudnikHour,
					'SotrudnikDay'		=> $SotrudnikDay,
					'LoadSotrudnikDay'	=> $LoadSotrudnikDay
				);
			
//			print_r($result2);
			
			
				array_push( $data, $row + $new_row + $new_row2 );
			
			}//break;
			
		}
	
	}
	

	echo json_encode(array('success'=>'true','data'=>$data));


?>