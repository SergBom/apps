<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
//header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
	$a = (object)$_REQUEST;
/*---------------------------------------------------------------------------*/

    $db = ConnectPDO('Scan_docs');

/*---------------------------------------------------------------------------*/

$p = json_decode($a->f,true);


//var_dump($p);

	
	$begin = ( @$p['dateBegin'] ) ? " AND p2.cdate >= str_to_date('".str_replace('T00:00:00','',@$p['dateBegin'])."', '%Y-%m-%d') " : "";
	$end   = ( @$p['dateEnd'] )   ? " AND p2.cdate <= str_to_date('".str_replace('T00:00:00','',@$p['dateEnd'])."', '%Y-%m-%d') " : "";
	$cyear = ( $p['cyear']<>'' ) ? " AND p2.cyear = {$p['cyear']}" : "";	
	$otdel = ( @$p['Otdel'] )     ? " AND p2.n1 = ". $p['Otdel'] ." " : "";

	
	$sql = "select * from 
	(select count(*) count_dpd   from `v_docs` p2 where 0=0  $begin  $end  $otdel $cyear ) t1,
	(select count(*) count_opis  from `v_docs` p2 where p2.opis=1 $begin  $end  $otdel $cyear ) t2,
	(select count(*) count_retro from `v_docs` p2 where p2.retro=2 $begin  $end  $otdel $cyear ) t3";
	
	
	
	
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
		

?>