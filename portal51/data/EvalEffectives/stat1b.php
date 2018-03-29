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
	// Общий вичисляемый показатель - Количество рабочих часов в периоде
	$Q_rab  = $db->getOne( "SELECT `portal`.`func#jcal_wd`('$Date0', '$Date','h')");

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
			
			$f = $row_frm['formula'];
			// -> По каждому из отделов вычислим данную формулу
		    $db = ConnectMyDB('EvalEffective');
			if ( $res_otd = $db->query( "CALL `GZC#p#Otdels`()" ) ) {
				while ($row_otd = $res_otd->fetch_assoc()) {	

					$db = ConnectMyDB('EvalEffective');
					//$p[{$row1['id']]['Q_insp']
					$Q_insp = $db->getOne( "select n_gzi from `GZC#GroupsJobsCount` where otdel_id='{$row_otd['id']}'" );
					if ( $res_dat = $db->query( "CALL `GZC#p#RecordData`('$Date0', '$Date', '0', '{$row_otd['id']}')" ) ) {
						while ($row_dat = $res_dat->fetch_assoc()) {
							//$p[{$row1['id']][$row2['R1']] = $row2['Value'];
							$row_dat['Value'] = ( substr($row_dat['R1'],0,1)=='S' ) ? $row_dat['Value']/1000 : $row_dat['Value'];
							$d[$row_dat['R1']] = $row_dat['Value'];
						}
					}
				
					// Составляем формулу из значемй в базе	
					foreach ($d as $k=>$v){
						$f = preg_replace('/'.$k.'(?!\w+)/i',$v,$f);
					}
					$f = preg_replace('/Q_rab(?!\w+)/i',$Q_rab,$f);
					$f = preg_replace('/Q_insp(?!\w+)/i',$Q_insp,$f);
					//echo '==> $'.$row['C1'].'='.$f."<br>";
			
					// Вычисляем полученную формулу
					@eval('$Proc='.$f.';');
					$Proc = ($Proc===false) ? '<Деление на 0>' : round($Proc,1) ;

					// Вычисление баллов
					$db = ConnectMyDB('EvalEffective');
					$Ball = $db->getOne("select IFNULL(Effective,0) as Effective from `GZC#Effectives` 
						where Punkt={$row_frm['Punkt']} and '$Proc' >= Percent1 and '$Proc' < Percent2 ");
					$Ball = ($Ball) ? $Ball : '';
			
					$arrayS[$CN][$row_otd['cn'].'Proc']=$Proc;
					$arrayS[$CN][$row_otd['cn'].'Ball']=$Ball;

					//$arrayS[$cnt_frm][$row_otd['cn'].'Ball'] += $Ball;

				}
			}
			$CN++;
		}



			$arrayS[$cnt_frm]['id']		= '';
			$arrayS[$cnt_frm]['Punkt']	= "<font color='red'>*</font>";
			$arrayS[$cnt_frm]['C1']		= '<font color="red">Итого:</font>';
			$arrayS[$cnt_frm]['C2']		= '';
			$arrayS[$cnt_frm][$row_otd['cn'].'Proc']='';
			//$arrayS[$cnt_frm][$row1['cn'].'Ball']=$Ball;

			
	}



	echo json_encode(array('success'=>'true','data'=>$arrayS));
	
	
	
	//echo json_encode(array('success'=>'true','data'=>$data));
?>
