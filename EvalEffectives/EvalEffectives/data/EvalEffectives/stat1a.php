<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

	$user_id  = isset( $_SESSION['portal']['user_id'] )   ?  $_SESSION['portal']['user_id']   : '';
	$otdel_id = isset( $_SESSION['portal']['otdel_id'] )  ?  $_SESSION['portal']['otdel_id']  : '0';
	$org_id   = isset( $_SESSION['portal']['domain_id'] ) ?  $_SESSION['portal']['domain_id'] : '0';
	
    $Date0 = ( !empty( $_GET['db'] ) ) ? substr($_GET['db'],0,10) : date('Y-01-01') ; // 
	$Date  = ( !empty( $_GET['de'] ) ) ? substr($_GET['de'],0,10) : date('Y-m-d') ; // 
/*---------------------------------------------------------------------------*/

	//print_r($_GET);

			$d=array();
			$o=array();

			// -> По каждому из отделов вычислим данную формулу
		    $db = ConnectMyDB('EvalEffective');
			//////////////////////////////////////////////////////////////////////////
			// Общий вычисляемый показатель - Количество рабочих часов в периоде
			$Q_rab  = $db->getOne( "SELECT `portal`.`func#jcal_wd`('$Date0', '$Date','h')");

			$o[0]['id']=0;
			$o[0]['cn']='summ';
			$o[0]['summ']=0;
			$db = ConnectMyDB('EvalEffective');
			$Q_insp = $db->getOne( "select sum(n_gzi) from `GZC#GroupsJobsCount`" );
			//echo "CALL `GZC#p#RecordData`('$Date0', '$Date', '0', '{$row_otd['id']}')<br>";
			if ( $res_dat = $db->query( "CALL `GZC#p#RecordData`('$Date0', '$Date', '0', '0')" ) ) {
				while ($row_dat = $res_dat->fetch_assoc()) {
					$row_dat['Value'] = ( substr($row_dat['R1'],0,1)=='S' ) ? $row_dat['Value']/1000 : $row_dat['Value'];
					// [№отдела][Параметр] = Значение
					$d[ 0 ][ $row_dat['R1'] ] = $row_dat['Value'];
				}
			}
			$d[ 0 ][ 'Q_insp' ] = $Q_insp;
			$d[ 0 ][ 'Q_rab' ]  = $Q_rab;
			
			
			$db = ConnectMyDB('EvalEffective');
			if ( $res_otd = $db->query( "CALL `GZC#p#Otdels`()" ) ) {
				while ($row_otd = $res_otd->fetch_assoc()) {	
					$o[$row_otd['id']]['id']=$row_otd['id'];
					$o[$row_otd['id']]['cn']=$row_otd['cn'];
					$o[$row_otd['id']]['summ']=0;

					$db = ConnectMyDB('EvalEffective');
					$Q_insp = $db->getOne( "select n_gzi from `GZC#GroupsJobsCount` where otdel_id='{$row_otd['id']}'" );
					//echo "CALL `GZC#p#RecordData`('$Date0', '$Date', '0', '{$row_otd['id']}')<br>";
					if ( $res_dat = $db->query( "CALL `GZC#p#RecordData`('$Date0', '$Date', '0', '{$row_otd['id']}')" ) ) {
						while ($row_dat = $res_dat->fetch_assoc()) {
							$row_dat['Value'] = ( substr($row_dat['R1'],0,1)=='S' ) ? $row_dat['Value']/1000 : $row_dat['Value'];
							// [№отдела][Параметр] = Значение
							$d[ $row_otd['id'] ][ $row_dat['R1'] ] = $row_dat['Value'];
						}
					}
					$d[ $row_otd['id'] ][ 'Q_insp' ] = $Q_insp;
					$d[ $row_otd['id'] ][ 'Q_rab' ]  = $Q_rab;

				}
			}
			
			//print_r($d); echo "<br><br>";


	$db = ConnectMyDB('EvalEffective');

	
	
	
	//////////////////////////////////////////////////////////////////////////
	// Выбираем формулы в массив
	$arrayS = array();
	$CN=0;
	
	$cnt_frm = $db->getOne( "SELECT count(*) FROM `GZC#RecordCalc`");
	if ( $res_frm = $db->query( "SELECT * FROM `GZC#RecordCalc`" ) ) {
		while ($row_frm = $res_frm->fetch_assoc()) {
			$arrayS[$CN]['id']		= $row_frm['id'];
			$arrayS[$CN]['Punkt']	= $row_frm['Punkt'];
			$arrayS[$CN]['C1']		= $row_frm['C1'];
			$arrayS[$CN]['C2']		= $row_frm['C2'];
			$arrayS[$CN]['formula']	= $row_frm['formula'];
			
			
			// -> По каждому из отделов вычислим данную формулу
			foreach ($d as $otdel_id=>$aV){
				
				//print_r($otdel_id); echo "***><br>";
				//print_r($aV); echo "<br><br>";
				
					$f = $row_frm['formula'];
					
					// Составляем формулу из значемй в базе	
					///echo "$f<br>";
					foreach ($aV as $k=>$v){
						
						$f = preg_replace('/'.$k.'(?!\w+)/i',$v,$f);
						//echo "$f<br>";
					}
					//echo "$f<br>";
			
					// Вычисляем полученную формулу
					@eval('$Proc='.$f.';');
					//$Proc = ($Proc===false) ? '<Деление на 0>' : round($Proc,1) ;
					if($Proc===false){
						$Proc = 'x/0';
						$Ball = 0;
					} else {
						$Proc = round($Proc,1);
						// Вычисление баллов
						$db = ConnectMyDB('EvalEffective');
						$Ball = $db->getOne("select IFNULL(Effective,0) as Effective from `GZC#Effectives` 
							where Punkt={$row_frm['Punkt']} and '$Proc' >= Percent1 and '$Proc' < Percent2 ");
						$Ball = ($Ball) ? $Ball : '';
					}
			
					$arrayS[$CN][$o[$otdel_id]['cn'].'Proc']=$Proc;
					$arrayS[$CN][$o[$otdel_id]['cn'].'Ball']=$Ball;
					$o[$otdel_id]['summ'] += $Ball;

					//echo "Proc='$Proc'   Ball='$Ball'   Summ='{$o[$otdel_id]['summ']}' ";
					//echo "<br>------------------<br>";
					
			}
			$CN++;
		}

	}


	$arrayS[$cnt_frm]['id']		= '';
	$arrayS[$cnt_frm]['Punkt']	= "<font color='red'><b>*</b></font>";
	$arrayS[$cnt_frm]['C1']		= '<font color="red"><b>Итого:</b></font>';
	$arrayS[$cnt_frm]['C2']		= '';
	foreach($o as $k => $v){
		$arrayS[$cnt_frm][$v['cn'].'Proc']='';
		$arrayS[$cnt_frm][$v['cn'].'Ball']='<font color="red"><b>'.$v['summ'].'</b></font>';
	}
			



	echo json_encode(array('success'=>'true','data'=>$arrayS));
	
	
	
	//echo json_encode(array('success'=>'true','data'=>$data));
?>
