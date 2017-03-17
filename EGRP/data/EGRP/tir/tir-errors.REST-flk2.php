<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
$userid = trim ((!empty($_GET['userid'])) ? $_GET['userid'] : "" ); // 
/*---------------------------------------------------------------------------*/


$method = $_SERVER['REQUEST_METHOD'];
$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));

$params = parseRequest($request);

//  print_r( $_REQUEST );


switch ($method) {
  case 'PUT':  // UPDATE
    _put_data(  $params );
    break;
//  case 'POST':  // CREATE - Приходим, чтобы добавить запись
//	_post_data($params);
//    break;
  case 'GET':  // SELECT
	_get_data(  $_GET); //$params); // 
    break;
//  case 'DELETE': // DELETE
//	_delete_data($params);
}

@oci_free_statement($stid);
@oci_close($conn);



//**********************************************************//
function _put_data( $adata ){ // Обновить запись
	//print_r($adata);

	echo json_encode(array('success'=>'true'));
}
//**********************************************************//
function _get_data(  $adata ){	// Вывести список записей
	//print_r($adata);

	$conn = ConnectLocalTIR(); // Присоска к базе

	$session = isset($adata['session'])  ? $adata['session']  :  0;
	
	if($session){

		$otdel = isset($adata['otdel']) ? $adata['otdel']  :  0;
		$stype = isset($adata['stype']) ? $adata['stype']  :  'R'; // R= ЕГРП или C= ГКН

		if( isset($adata['otdel']) ){
			if( $adata['otdel'] > 0 ){
				if ( $adata['otdel'] == 12 ){
					$Rotdel = "AND DEPT_ID='' OR DEPT_ID IS NULL ";
				} else {
					$Rotdel = "and DEPT_ID in (select ID FROM rp_depts where dept_id={$adata['otdel']})";
				}
			}else{ // Значит все отделы
				$Rotdel = "";
			}
		}
		
		$SType =  ( $stype == 'C' ) ? "AND SYSTEM_TYPE='ГКН'" : "AND ( SYSTEM_TYPE IS NULL OR SYSTEM_TYPE='ЕГРП' )";
	
		// collect request parameters
		$start  = isset($adata['start'])  ? $adata['start']+1  :  1;
		$limit  = isset($adata['limit'])  ? $adata['limit']  : 25;
		$end	= $start + $limit;
		$sort   = isset($adata['sort'])   ? json_decode($adata['sort'])   : null;
		$filters = isset($adata['filter']) ? $adata['filter'] : null;
		$filters = str_replace('property','field',$filters );
	
		if( isset($sort) ){
			$sortDirection = $sort[0]->direction;
			$sortProperty = ($sort[0]->property <> '') ? $sort[0]->property : "RW";// "DEPT_ID, CONCADNUM"; 
			$sortProperty = ($sort[0]->property == 'kladr_ny') ? "KLADR_NEED {$sortDirection}, KLADR_YES " : $sortProperty;
		} else {
			$sortDirection = "";
			$sortProperty = "RW";
		}
//		if (isset($sort[0]->property)){
//			echo "sort = ".$sort[0]->property;
//			print_r($sort);
//		}

		//print_r( $filters );
		//		echo "<p>$Filter</p>";
		$Filter = setFilter( $filters );
		

		$conn = ConnectLocalTIR(); // Присоска к базе
	
		$sql =	"SELECT o2.* FROM (
			SELECT ROWNUM RN, max(rownum) over () COUNTS, o.* FROM (
				SELECT row_number() over (order by REC_GUID) RW,
                    REC_GUID,
					DOC_GUID,
					STATUS_ERROR,
					DEPT_ID,
                    EXTERNAL_ID,
                    ERROR_TEXT2 AS PICTURE,
                    ERROR_SOCR,
					TAG_NAME,
					KLADR_NEED||'/'||KLADR_YES AS KLADR_NY,
                    KLADR_NEED,KLADR_YES,
                    '[dept_id='||DEPT_ID||'] '||NAME as NAME,
					PATH,
                    ESSENCE_TYPE,
                    CONCADNUM,
                    DESCRIPTION,
					ATTRIBUTE_NAME,
					ATTRIBUTE_VALUE,
					USER_NAME,
					USER_DATE,
					USER_COMMENT,
					RF
                FROM V\$FLK o
                WHERE SESSION#=$session
					$SType
                    $Rotdel
				ORDER BY $sortProperty $sortDirection
				) o
			WHERE 0=0 $Filter
			) o2
			WHERE o2.RN >= $start
			AND o2.RN < $end
		";	
			//echo "$sql";

		$stid = oci_parse($conn, $sql);
		oci_execute($stid);


		$co = oci_fetch_all($stid, $data, null, null, OCI_FETCHSTATEMENT_BY_ROW);
		
		//print_r($data);
		if($co){
			$count = $data[0]['COUNTS'];
		}  else {
			$count = 0;
		}
		

			echo json_encode(Array(
	//		    "org_id"=>$adata['id'],
				"success"=>"true",
				"total"=>$count,
				"data"=>$data
			));
		
	} // session
}

?>
