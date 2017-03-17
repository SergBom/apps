<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
//header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
//$Org_id = trim ((!empty($_GET['org'])) ? $_GET['org'] : "1" ); // 
/*---------------------------------------------------------------------------*/

    $db = ConnectMyPDO('Scan_docs');

/*---------------------------------------------------------------------------*/

//$method = $_SERVER['REQUEST_METHOD'];
//$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));
//print_r(substr(@$_SERVER['PATH_INFO'], 1));

//$params = parseRequest($method);

_get_data($db,$_POST);


function _get_data($db,$params){	// Вывести список записей
$data = array();	
//print_r($params);
	
	$begin = ( @$params['dateBegin'] ) ? " AND p2.cdate >= str_to_date('".str_replace('T00:00:00','',@$params['dateBegin'])."', '%Y-%m-%d') " : "";
	$end   = ( @$params['dateEnd'] )   ? " AND p2.cdate <= str_to_date('".str_replace('T00:00:00','',@$params['dateEnd'])."', '%Y-%m-%d') " : "";
	$cyear = ( @is_numeric($params['cyear']) )     ? " AND p2.cyear = {$params['cyear']}" : "";
	$otdel = ( @$params['Otdel'] )     ? " AND p2.n1 = ".@$params['Otdel']." " : "";


	//$sql = "select count(*) from `v#docs2` p2 where p2.tp=1 $begin  $end  $otdel $cyear ";
	$cnt_dpd = $db->query("select count(*) from `v#docs2` p2 where p2.tp=1 $begin  $end  $otdel $cyear ")->fetchColumn();
	$opis_dpd = $db->query("select count(*) from `v#docs2` p2 where p2.tp=1 and p2.opis=1 $begin  $end  $otdel $cyear ")->fetchColumn();
	$retro_dpd = $db->query("select count(*) from `v#docs2` p2 where p2.tp=1 and p2.retro=2 $begin  $end  $otdel $cyear ")->fetchColumn();
	//echo "$sql<br>";
	
	//$sql = "select ";
	//$sql .= "(select count(*) from `v#docs2` where tp=2 $begin  $end  $otdel $cyear ) as count_dpd,";
	//$sql .= "(select IFNULL(round(sum(cnt_size)/(1024*1024*1024),1),0) from `v#docs2` where (tp=3 or tp=4) $begin  $end  $otdel $cyear) as count_size,";
	//$sql .= "(select count(*) from `v#docs2` where tp=3  $begin  $end  $otdel $cyear ) as count_files";

/*	$sql = "SELECT round(count(*)/2,1) cnt_files, IFNULL(round(sum(c.cnt_size)/(1024*1024*1024),2),0) cnt_size
					FROM docs2 AS c
					LEFT JOIN docs2 As p1 ON c.par_id = p1.id
					LEFT JOIN `v#docs2` As p2 ON p1.par_id = p2.id
					WHERE c.par_id IS NOT NULL AND p2.tp=1	$begin  $end  $otdel $cyear
				";
	//echo "$sql<br>";
	
	if ( $result = $db->query( $sql ) ) {

		$row = $result->fetch_assoc();
	*/	
			echo "{
				success: true,
				data: {
					count_dpd: ".$cnt_dpd.",
					count_opis: ".$opis_dpd.",
					count_retro: ".$retro_dpd."
				}
			}";
		
	//}  $row['cnt_size'].",".$row['cnt_files']."
}
?>