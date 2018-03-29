<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

	@$_SESSION['portal']['user_id'] = $_SESSION['user_id'];
	@$_SESSION['portal']['username'] = $_SESSION['username'];
	@$user_id = isset( $_SESSION['portal']['user_id'] ) ?  $_SESSION['portal']['user_id']  : '';
	@$org_id  = isset( $portal_auth['domain_id'] ) ? ($portal_auth['domain_id'])? $portal_auth['domain_id']:1  : 1;

	
    $Date0 = ( !empty( $_GET['d0'] ) ) ? substr($_GET['d0'],0,10) : date('Y-01-01') ; // 
	$Date  = ( !empty( $_GET['db'] ) ) ? substr($_GET['db'],0,10) : date('Y-m-d') ; // 
/*---------------------------------------------------------------------------*/

    $db = ConnectMyDB('EvalEffective');

	//////////////////////////////////////////////////////////////////////////
	// Два вичисляемых показателя
	$Q_rab  = $db->getOne( "SELECT `portal`.`func#jcal_wd`('$Date0', '$Date','h')");
	$Q_insp = $db->getOne( "select sum(n_gzi) from `GZC#GroupsJobsCount` " );   //where otdel_id=15
	
	
	$data = array();
	$d = array();
	///////////////////////////////////////////////////////////////////////////
	// Выборка всех показателей за период
	//////////////GZC#p#RecordData(dadaBegin,dataEnd,UserId,OtdelId)
	$sql = "CALL `GZC#p#RecordData`('$Date0','$Date','0','0')";
	if ( $res = $db->query( $sql ) ) {
		
		while ($row = $res->fetch_assoc()) {
			$row['Value'] = ( substr($row['R1'],0,1)=='S' ) ? $row['Value']/1000 : $row['Value'];
			$d[$row['R1']] = $row['Value'];
			//print_r($row);
		}
		//print_r($data);
	}

	
	//echo "<br>===<br>";
	
	
	
	$db = ConnectMyDB('EvalEffective');
	if ( $res2 = $db->query( "SELECT * FROM `GZC#RecordCalc`" ) ) {
	
		$Effective_common = 0;
	
		while ($row = $res2->fetch_assoc()) {
			
			// Составляем формулу из значемй в базе
			//print_r($row);
			//$row['Punkt']
			//$row['C1'],$row['C2']
			$f = $row['formula'];
			//echo $f."<br>";
			foreach ($d as $k=>$v){
				//echo "k='$k',v='$v'<br>";
//				if( $k[0] == 'S' AND $v == 0 ){ $v = 0.00000000001; }
				$f = preg_replace('/'.$k.'(?!\w+)/i',$v,$f);
			}
			$f = preg_replace('/Q_rab(?!\w+)/i',$Q_rab,$f);
			$f = preg_replace('/Q_insp(?!\w+)/i',$Q_insp,$f);
			
			//echo '==> $'.$row['C1'].'='.$f."<br>";
			
			// Вычисляем полученную формулу
			@eval('$Otvet='.$f.';');
			$Otvet = ($Otvet===false) ? '<Деление на 0>' : round($Otvet,1) ;
			
			// Вычисление баллов
			$sql = "select IFNULL(Effective,0) as Effective from `GZC#Effectives` where Punkt={$row['Punkt']} and '$Otvet' >= Percent1 and '$Otvet' < Percent2 ";
			$Effective = $db->getOne($sql);
			$Effective = ($Effective) ? $Effective : '';
						
			
			array_push($data, array(
				'Punkt'=>$row["Punkt"],
				'C1'=>$row["C1"],
				'C2'=>$row["C2"],
				'Otvet'=>$Otvet,
				'Effective'=>$Effective
			));
			
			$Effective_common = $Effective_common + $Effective;
		}

			array_push($data, array(
				'Punkt'=>'<font color="red">*</font>',
				'C1'=>'<font color="red">Итого:</font>',
				'C2'=>'',
				'Otvet'=>'',
				'Effective'=>"<font color='red'>$Effective_common</font>"
			));
		
		//echo json_encode($data);
	}
	
	
	echo json_encode(array('success'=>'true','data'=>$data));
?>
