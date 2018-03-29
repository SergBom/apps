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

    $db = ConnectPDO('CadastErrors');

/*---------------------------------------------------------------------------*/


	//echo $WorksDay."<hr>";
	//$data = $stEng->fetchAll();

/*
	$sql = "SELECT name, var_data FROM `vTimes` WHERE years={$a->years} AND months={$a->months}";
	$Times = $db->query( $sql )->fetchAll(PDO::FETCH_COLUMN|PDO::FETCH_GROUP);     //PDO::FETCH_FUNC, "fr");     // PDO::FETCH_COLUMN); //|PDO::FETCH_GROUP);
*/	

	$sqlEng = "SELECT id,fio,AttNumber FROM `vEngineers`";
	//$stEng = $db->query( $sql ); //->fetchColumn();

	if ( $stEng = $db->query( $sqlEng ) ) {
	
	
		while ($rowEng = $stEng->fetch()) {
			
			
			$sql2 = "select t2.FieldName, sum(t1.var_data) as var_data  from `Errors` t1
				left join `ParamsErrors` t2 on t1.var_id=t2.id
	where t2.calc=0 AND t1.dateIn >= '{$a->db}' AND t1.dateIn <= '{$a->de}' AND t1.eng_id = {$rowEng['id']}
	group by t2.FieldName";
			
			
			//echo $sql2."<br>";
			$rtemp2 = array();
			$summ[1] = 0;
			$summ[2] = 0;
			$r2 = $db->query( $sql2 )->fetchAll(PDO::FETCH_FUNC, "fr"); //PDO::FETCH_COLUMN); //|PDO::FETCH_GROUP);     //PDO::FETCH_FUNC, "fr");     // PDO::FETCH_COLUMN); //|PDO::FETCH_GROUP);

			$rc = count($r2);
			//echo "COUNT = $rc<br>";

			if ( $rc > 0 ){
				
				$rt =
				 array(
					'id'=>$rowEng['id'],
					'fio'=>$rowEng['fio'],
					'AttNumber'=>$rowEng['AttNumber']
				);

			
				$rt += $rtemp2;
				
				$rt['f.1.common'] = $summ[1];
				$rt['f.2.common'] = $summ[2];
			
				//var_dump($rt);

				//echo "<hr>";
				array_push( $data, $rt);
			}

		}
	
	}
	

	echo json_encode(array('success'=>'true','data'=>$data));


	
function fr($name, $data) {
	global $rtemp2, $summ;
	$rtemp2 += array($name => $data );
	
	switch (substr($name,2,1)) {
		case 1:
			$summ[1] += $data; 
			break;
		case 2:
			$summ[2] += $data; 
			break;
	}
    return '';
}
?>