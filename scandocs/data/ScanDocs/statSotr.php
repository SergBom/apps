<?php
$isCLI = ( php_sapi_name() == 'cli' );
if($isCLI){
	include_once("/var/www/portal/public_html/php/init2.php");
	$cr = "\n";
}else{
	include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
	$cr = "<br>";
}
header('Content-type: text/html; charset=utf-8');


/*-------------------------- Входные переменные -----------------------------*/
$year = $_GET['year'];
$type = $_GET['type'];
/*---------------------------------------------------------------------------*/

//$_year_old = $year - 1;


$Summ = array(
'name'=>'ИТОГО',
'm01'=>0,'m02'=>0,'m03'=>0,'m04'=>0,'m05'=>0,'m06'=>0,'m07'=>0,'m08'=>0,'m09'=>0,'m10'=>0,'m11'=>0,'m12'=>0,
'summa'=>0
);

$SummMax = array(
'name'=>'По нарастающему',
'm01'=>0,'m02'=>0,'m03'=>0,'m04'=>0,'m05'=>0,'m06'=>0,'m07'=>0,'m08'=>0,'m09'=>0,'m10'=>0,'m11'=>0,'m12'=>0,
'summa'=>0
);



    $db = ConnectPDO('Scan_docs');

	// Сумма всех дел за весь предыдущий период
	$SummOld = $db->query( "select count(*) from v_docs3 where cdate < str_to_date( '$year-01-01','%Y-%m-%d')" )->fetchColumn();
//echo $SummOld.$cr;
	
	
	if( $type == 1 ){
		$stm_otdels = $db->query( "select * from otdels ORDER BY id" ); //->fetchAll(PDO::FETCH_COLUMN);
	} else if( $type == 2 ){
		$stm_otdels = $db->query( "SELECT DISTINCT n1 as id, n2 as name FROM t\$district_data ORDER BY n1" ); 
	}




	
	$data = array();
	
	while( $row_otdels = $stm_otdels->fetch() ){

		if( $type == 1 ){
			$d = $db->query("CALL `statCommon`('{$year}',{$row_otdels['id']})")->fetchAll(); //PDO::FETCH_COLUMN);
		} else if( $type == 2 ){
			$d = $db->query("CALL `statCommon2`('{$year}',{$row_otdels['id']})")->fetchAll(); //PDO::FETCH_COLUMN);
		}
		
		// Суммируем по мясецам
		for($i=1; $i<=12; $i++ ){
			$o = str_pad($i, 2, "0", STR_PAD_LEFT);
			$Summ["m$o"] = $Summ["m$o"] + $d[0]["m$o"];
		}

		$Summ["summa"] = $Summ["summa"] + $d[0]["summa"];
		
		$d[0] = array('name'=>$row_otdels['name']) + $d[0] ;
		$d[0]["summa"] = 	"<b>" .	$d[0]["summa"] . "</b>";

		array_push($data,$d[0]);
		
	}

	$S = 0;
	foreach( $Summ as $k=>$v ){
		$S += $v;
		$S2 = $SummMax[$k] + $S + $SummOld;
		
		$Summ[$k]		= "<font color='red'><b>" . $v . "</b></font>";
		$SummMax[$k]	= "<font color='green'><b>" . $S2 . "</b></font>";
		
	}
	
	$SummMax["name"] = "<font color='green'><b>По нарастающему</b></font>";
	$SummMax["summa"] = "";

	array_push($data,$Summ);
	array_push($data,$SummMax);
	
	echo json_encode(array('success'=>'true','data'=>$data));
	
?>