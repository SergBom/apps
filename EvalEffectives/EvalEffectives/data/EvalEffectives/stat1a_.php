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
	$formula = array();
	if ( $res_frm = $db->query( "SELECT * FROM `GZC#RecordCalc`" ) ) {
		while ($row_frm = $res_frm->fetch_assoc()) {
			$formula['id'][]		= $row_frm['id'];
			$formula['Punkt'][]		= $row_frm['Punkt'];
			$formula['C1'][]		= $row_frm['C1'];
			$formula['C2'][]		= $row_frm['C2'];
			$formula['formula'][]	= $row_frm['formula'];

	//////////////////////////////////////////////////////////////////////////
	// Идем по всем отделам 
	// Загоняем показатели в массив
    $db = ConnectMyDB('EvalEffective');
	$sql = "CALL `GZC#p#Otdels`()";				
	if ( $res1 = $db->query( $sql ) ) {
		$CN=0;
		$Effective_common = 0;
		while ($row1 = $res1->fetch_assoc()) {
			//$otdel[] = $row1['id'];
			// Выбираем данные по конкретному отделу
			$db = ConnectMyDB('EvalEffective');
			//$p[{$row1['id']]['Q_insp']
			$Q_insp = $db->getOne( "select n_gzi from `GZC#GroupsJobsCount` where otdel_id='{$row1['id']}'" );
			$sql = "CALL `GZC#p#RecordData`('$Date0', '$Date', '0', '{$row1['id']}')";
			if ( $res2 = $db->query( $sql ) ) {
				while ($row2 = $res2->fetch_assoc()) {
					//$p[{$row1['id']][$row2['R1']] = $row2['Value'];
					$row2['Value'] = ( substr($row2['R1'],0,1)=='S' ) ? $row2['Value']/1000 : $row2['Value'];
					$d[$row2['R1']] = $row2['Value'];
				}


				// Составляем формулу из значемй в базе
				$f = $row_frm['formula'];
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
				$sql = "select IFNULL(Effective,0) as Effective from `GZC#Effectives` where Punkt={$row_frm['Punkt']} and '$Proc' >= Percent1 and '$Proc' < Percent2 ";
				$Ball = $db->getOne($sql);
				$Ball = ($Ball) ? $Ball : '';


				$arrayS[$CN]['Punkt']=$row_frm['Punkt'];
				$arrayS[$CN]['C1']=$row_frm['C1'];
				$arrayS[$CN]['C2']=$row_frm['C2'];
				$arrayS[$CN][$row1['cn'].'Proc']=$Proc;
				$arrayS[$CN][$row1['cn'].'Ball']=$Ball;
				$CN++;

				$Effective_common = $Effective_common + $Ball;	
				
			}

		}
		$arrayS[$CN]['C2']=$row_frm['C2'];

	}


			
			
			
			
			
			
			
		}
	}

//	echo "countAll=".count($formula)."   count1=".count($formula['id'])."\n<br>";


	
/*

		for($i=0;$i<count($formula['id']);$i++){
			foreach($otdel as $v){
				
				
			}
		} 

					$arrayS[$CN]['Punkt']=$row2['Punkt'];
					$arrayS[$CN]['C1']=$row2['C1'];
					$arrayS[$CN]['C2']=$row2['C2'];
					
					
					$f = $row['formula'];
					foreach ($d as $k=>$v){
						$f = preg_replace('/'.$k.'(?!\w+)/i',$v,$f);
					}
					$f = preg_replace('/Q_rab(?!\w+)/i',$Q_rab,$f);
					$f = preg_replace('/Q_insp(?!\w+)/i',$Q_insp,$f);

					
					
					
					
					$arrayS[$CN][$row1['cn'].'Proc']=$row2['Value'];
					$arrayS[$CN][$row1['cn'].'Ball']=$row2['Value'];
					$CN++;
	*/
	
	


    $db = ConnectMyDB('EvalEffective');
	//////////////////////////////////////////////////////////////////////////
	// Два вичисляемых показателя
	$Q_insp = $db->getOne( "select sum(n_gzi) from `GZC#GroupsJobsCount` " );   //where otdel_id=15
	
	
	$arrayS = array();
	$data = array();
	$d = array();
	///////////////////////////////////////////////////////////////////////////
	// Выборка всех показателей за период
	//////////////GZC#p#RecordData(dadaBegin,dataEnd,UserId,OtdelId)
	$sql = "CALL `GZC#p#RecordData`('$Date0','$Date','0','0')";
	if ( $res = $db->query( $sql ) ) {
		while ($row = $res->fetch_assoc()) {
			// Показатель S - преобразуем из (руб) в (тыс.руб) 
			$row['Value'] = ( substr($row['R1'],0,1)=='S' ) ? $row['Value']/1000 : $row['Value'];
			$d[$row['R1']] = $row['Value'];
		}
	}
	//echo "<br>===<br>";
	$db = ConnectMyDB('EvalEffective');
	if ( $res2 = $db->query( "SELECT * FROM `GZC#RecordCalc`" ) ) {
	
		$Effective_common = 0;
	
		while ($row = $res2->fetch_assoc()) {
			
			// Составляем формулу из значемй в базе
			$f = $row['formula'];
			foreach ($d as $k=>$v){
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
				'summProc'=>$Otvet,
				'summBall'=>$Effective
			));
			
			$Effective_common = $Effective_common + $Effective;
		}

		///// Строка Итогов
		array_push($data, array(
			'Punkt'=>'<font color="red">*</font>',
			'C1'=>'<font color="red">Итого:</font>',
			'C2'=>'',
			'summProc'=>'',
			'summBall'=>"<font color='red'>$Effective_common</font>"
		));
		
	}
	
	// Информация по каждому отделу за период, которые вводили данные
	// Возвращаем полный список отделов с подотделами
    $db = ConnectMyDB('EvalEffective');
	$sql = "CALL `GZC#p#Otdels`()";				
	if ( $res1 = $db->query( $sql ) ) {

		while ($row1 = $res1->fetch_assoc()) {
	
			// Выбираем данные по конкретному отделу
			$db = ConnectMyDB('EvalEffective');
			$sql = "CALL `GZC#p#RecordData`('$DateBegin', '$DateEnd', '0', '{$row1['id']}')";
			if ( $res2 = $db->query( $sql ) ) {
				$CN=0;
				while ($row2 = $res2->fetch_assoc()) {
	
					$arrayS[$CN]['id']=$row2['id'];
					$arrayS[$CN]['R1']=$row2['R1'];
					$arrayS[$CN]['R2']=$row2['R2'];
					$arrayS[$CN][$row1['cn']]=$row2['Value'];
					$CN++;
				}
			}
		}
		
//		echo json_encode(array('success'=>'true','data'=>$arrayS));
	}
		echo json_encode(array('success'=>'true','data'=>$arrayS));
	
	
	
	//echo json_encode(array('success'=>'true','data'=>$data));
?>
