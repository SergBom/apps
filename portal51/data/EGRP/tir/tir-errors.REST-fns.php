<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
$userid = trim ((!empty($_GET['userid'])) ? $_GET['userid'] : "" ); // 
/*---------------------------------------------------------------------------*/

	$conn = ConnectLocalTIR(); // Присоска к базе


$method = $_SERVER['REQUEST_METHOD'];
$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));

$params = parseRequest($request);

//echo $method;
//print_r( $_REQUEST );


switch ($method) {
  case 'PUT':  // UPDATE
    _put_data( $conn, $params );
    break;
//  case 'POST':  // CREATE - Приходим, чтобы добавить запись
//	_post_data($params);
//    break;
  case 'GET':  // SELECT
	_get_data( $conn, $_GET); //$params); // 
    break;
//  case 'DELETE': // DELETE
//	_delete_data($params);
}

@oci_free_statement($stid);
@oci_close($conn);



//**********************************************************//
function _put_data( $conn, $adata ){ // Обновить запись
//	print_r($adata);

	echo json_encode(array('success'=>'true'));
}
//**********************************************************//
function _get_data( $conn, $adata ){	// Вывести список записей
//	print_r($adata);

	$session = isset($adata['session'])  ? $adata['session']  :  0;
	
	if($session){

		$otdel = isset($adata['otdel']) ? $adata['otdel']  :  0;
		$stype = isset($adata['stype']) ? $adata['stype']  :  'R'; // R= ЕГРП или C= ГКН

		$Rotdel = ( $otdel ) ? "and DEPT_ID in (select ID FROM rp_depts where dept_id=$otdel)"  : "" ;
		$Rotdel = ( $otdel==12 ) ? "AND DEPT_ID='' OR DEPT_ID IS NULL " : $Rotdel;
		$Rotdel = ( $otdel==888 ) ? " " : $Rotdel;
		$SType =  ( $stype == 'C' ) ? "AND SYSTEM_TYPE='ГКН'" : "AND ( SYSTEM_TYPE IS NULL OR SYSTEM_TYPE='ЕГРП' )";
	
		// collect request parameters
		$start  = isset($adata['start'])  ? $adata['start']+1  :  1;
		$limit  = isset($adata['limit'])  ? $adata['limit']  : 25;
		$end	= $start + $limit;
		$filters = isset($adata['filter']) ? $adata['filter'] : null;
		$filters = str_replace('property','field',$filters );
	
		$sort = "";
		if(isset($adata['sort'])){
			$sort = json_decode($adata['sort']);
			$sortDirection = $sort[0]->direction;
			$sortProperty = ($sort[0]->property <> '') ? $sort[0]->property : "RW";// "DEPT_ID, CONCADNUM"; 
			$sortProperty = ($sort[0]->property == 'kladr_n-y') ? "KLADR_NEED {$sortDirection}, KLADR_YES " : $sort[0]->property;
			$sort = " ORDER BY $sortProperty $sortDirection";
		}

		//echo "Sort = {$adata['sort']}<br>";

		//print_r( $filters );
		//		echo "<p>$Filter</p>";
		$Filter = setFilter( $filters );
		

		$conn = ConnectLocalTIR(); // Присоска к базе
	
		$sql =	"SELECT o2.* FROM (
			SELECT ROWNUM RN, max(rownum) over () counts, o.* FROM (
				SELECT row_number() over (order by REC_GUID) RW, o.*
                FROM V\$FNS o
                WHERE SESSION#=$session
                    $Rotdel
				$sort
				) o
			WHERE 0=0 $Filter
			) o2
			WHERE o2.RN >= $start
			AND o2.RN < $end
		";	
			// echo "$sql";

		$stid = oci_parse($conn, $sql);
		oci_execute($stid);

		// Заполняем массив со Статусами ошибок
		$stidStatus = oci_parse($conn, "SELECT * FROM T\$STATUS_ERROR");
		oci_execute($stidStatus);
		$aStatus = array();
		while (($row = oci_fetch_array($stidStatus))){
			$aStatus[ $row['ID'] ] = array($row['PICTURE'],$row['ERROR_SOCR']);
		}
		
		

			while ($row = oci_fetch_array($stid)){

				$error_pic = $row['PICTURE'];
				if ( $row['PICTURE'] == '' ) { $error_pic = $aStatus[0][0];	}
				$error_txt = $row['ERROR_SOCR'];
				if ( $row['ERROR_SOCR'] == '' ) { $error_txt = $aStatus[0][1]; }


				$data[] = array(
					'rw'=>$row['RW'],
					'rn'=>$row['RN'],
					'rec_guid'=>$row['REC_GUID'],
					'doc_guid'=>$row['DOC_GUID'],
					'status_error'=>$row['STATUS_ERROR'],            //$error_pic, //."&nbsp;&nbsp;".$error_txt,
					'dept_id'=>$row['DEPT_ID'],
					'name'=>"{$row['NAME']}",		//"[".str_pad($row['DEPT_ID'],'0',STR_PAD_LEFT)."] 
//					'external_id'=>$row['EXTERNAL_ID'],
					'error_path'=>$row['ERROR_PATH'],
					'attribute_value'=>$row['ATTRIBUTE_VALUE'],
					'concadnum'=>$row['CONCADNUM'],
					'error_text'=>$row['ERROR_TEXT'],
//					'user_id'=>$row['USER_ID'],
					'user_name'=>$row['USER_NAME'],
					'user_date'=>$row['USER_DATE'],
					'user_comment'=>$row['USER_COMMENT']
					);
				$count = $row['COUNTS'];

			}

			echo json_encode(Array(
	//		    "org_id"=>$adata['id'],
				"success"=>"true",
				"total"=>$count,
				"data"=>$data
			));
		
//		}
		@oci_free_statement($stid);
		@oci_close($conn);
	} // session
}

?>
