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

	
	$sql = "select * from 
	(select count(*) count_dpd   from `v_docs3` p2 where 0=0  $begin  $end  $otdel $cyear ) t1,
	(select count(*) count_opis  from `v_docs3` p2 where p2.opis=1 $begin  $end  $otdel $cyear ) t2,
	(select count(*) count_retro from `v_docs3` p2 where p2.retro=2 $begin  $end  $otdel $cyear ) t3";
	
	
	
	
	$data = $db->query(	$sql )->fetch() ; //PDO::FETCH_COLUMN);
	//print_r($data);
	
echo "{
	success: true,
	data: {
		count_dpd: "  .$data['count_dpd'].",
		count_opis: " .$data['count_opis'].",
		count_retro: ".$data['count_retro']."
	}
}";
		
}
?>