<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
	$a = (object)$_REQUEST;
/*---------------------------------------------------------------------------*/

    $db = ConnectPDO('Scan_docs');
	
/*---------------------------------------------------------------------------*/

	$p = json_decode($a->frm,true);

	$data = array();	

	$begin = ( $p['dateBegin'] ) ? " AND p2.cdate >= str_to_date('".str_replace('T00:00:00','',@$p['dateBegin'])."', '%Y-%m-%d') " : "";
	$end   = ( $p['dateEnd'] )   ? " AND p2.cdate <= str_to_date('".str_replace('T00:00:00','',@$p['dateEnd'])."', '%Y-%m-%d') " : "";
	$otdel = ( $p['Otdel'] )     ? " AND p2.n1 = ". $p['Otdel'] ." " : "";
	$cyear = ( $p['cyear']<>'' ) ? " AND p2.cyear = {$p['cyear']}" : "";	
	

	$filter = ( @$a->filter )  ? " AND name like '%".trim(@$a->filter)."%' " : "";
	
	
	$opis = ""; 
	if( $a->opis<8 and $a->opis<>'' ){
		$opis = " AND opis=".$a->opis ;
	}
	
	$retro = ( $a->retro<>'' and $a->retro<8 ) ? " AND retro=".$a->retro : "" ;
	
	
	$sql = "select SQL_CALC_FOUND_ROWS * from `v_docs` p2 where 0=0 $begin  $end  $otdel $cyear $opis $retro $filter";
	//$sql .= " ORDER BY name";
	$sql .= " LIMIT {$a->start},{$a->limit}";
	
	//echo $sql;

	$data = $db->query( $sql )->fetchAll(); //PDO::FETCH_COLUMN);
	$total_rows=$db->query("select FOUND_ROWS()")->fetchAll(PDO::FETCH_COLUMN);
	
	
	$c = array('success'=>0,'total'=>$total_rows,'data'=>$data);
	echo json_encode($c);
	

?>