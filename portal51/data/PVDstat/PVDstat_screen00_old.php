<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

//$_GET = array_change_key_case($_GET);

$DateBegin = trim ((!empty($_GET['db'])) ? $_GET['db'] : "09.01.".date('Y') ); // 
$DateEnd = trim ((!empty($_GET['de'])) ? $_GET['de'] : date('d.m.Y') ); // 
$Otdel = trim ((!empty($_GET['otd'])) ? $_GET['otd'] : 0 ); // 
/*---------------------------------------------------------------------------*/
$DateB = "09.01.".date('Y');
//$r = array(); // Массив со статистикой

	$linkPVD = ConnectOciDB('SB_PVD');

//******************************************************
//	
//
	/*$st = oci_sql_exec($linkPVD, "SELECT * FROM T\$PROC WHERE TEXT is not NULL ORDER BY IDORDER");
	$case = "";
	while ($row = oci_fetch_assoc($st)){
		$case .= " WHEN ID_PROC = '{$row['ID_PROC']}' THEN '{$row['TEXT']}'\n";
		$a[] = "'{$row['ID_PROC']}'";
		$proc[$row['ID_PROC']] = $row['TEXT'];
	}
	$where = " AND ID_PROC in (". implode(",",$a). ")";
	*/
//echo "$where<br>"; 						upper(U_LOGIN) like upper('murmansk\%')
//											upper(U_JOB) like upper('%Специалист%')

//**********************************//
$likeOUR =	" AND ( not REGEXP_LIKE(upper(U_JOB),upper('Техник|Инженер|Заместитель') )
					OR ( U_JOB is NULL and upper(U_LOGIN) not like upper('reestr%') )
				)
				AND U_LOGIN not like 'admin'
				";
					
$likeKP =	" AND upper(U_JOB) not like upper('специалист')
				AND upper(U_LOGIN) not like upper('murmansk\%')
				AND upper(U_LOGIN) not like upper('reestr%')
				AND U_LOGIN not like 'admin'
					";
$likeMFC = 	" AND upper(U_LOGIN) not like upper('reestr%')
				AND U_LOGIN not like 'admin'
";

//	$stLink = oci_sql_exec($linkPVD, "SELECT * FROM T\$TLINK");
	
	//echo "Общее за период с $DateBegin по $DateEnd<br>";

		$sql = "with OP_DOP as (";
	
		// Выборка за период с начала года
		$sql .= "select 1 as \"N\", 'ROSREESTR' as \"ORG\", count(cs.id) as \"COUNT_DEL\", ID_PROC as \"ID_PROC\", 'FULL' as \"TYPE\"
				from T\$PVD_14 cs
				where 
					CS.STARTDATE between TO_DATE('{$DateB}','DD.MM.YYYY') AND TO_DATE('{$DateEnd}','DD.MM.YYYY')+1
					AND ID_PROC in ('EGRP_GRP','EGRP_PS','OKS_GU','OKS_PS','OKU_GKU','OKU_PS')
					{$likeOUR}
				group by ID_PROC
				
				union
				
				select 2 as \"N\", 'ROSREESTR' as \"ORG\",	count(cs.id) as \"COUNT_DEL\", 'ALL1' as \"ID_PROC\", 'FULL' as \"TYPE\"
				from T\$PVD_14 cs
				where
					CS.STARTDATE between TO_DATE('{$DateB}','DD.MM.YYYY') AND TO_DATE('{$DateEnd}','DD.MM.YYYY')+1
					AND ID_PROC in ('EGRP_GRP','EGRP_PS','OKS_GU','OKS_PS','OKU_GKU','OKU_PS')
					{$likeOUR}
				
				union
				
				select 3 as \"N\", 'ROSREESTR' as \"ORG\",	count(cs.id) as \"COUNT_DEL\", 'ALL' as \"ID_PROC\", 'FULL' as \"TYPE\"
				from T\$PVD_14 cs
				where
					CS.STARTDATE between TO_DATE('{$DateB}','DD.MM.YYYY') AND TO_DATE('{$DateEnd}','DD.MM.YYYY')+1
					{$likeOUR}
		";
		$sql .= "		union      ";

		// Выборка за выбранный период
		$sql .= "select 4 as \"N\", 'ROSREESTR' as \"ORG\", count(cs.id) as \"COUNT_DEL\", ID_PROC as \"ID_PROC\", 'SMALL' as \"TYPE\"
				from T\$PVD_14 cs
				where 
					CS.STARTDATE between TO_DATE('{$DateBegin}','DD.MM.YYYY') AND TO_DATE('{$DateEnd}','DD.MM.YYYY')+1
					AND ID_PROC in ('EGRP_GRP','EGRP_PS','OKS_GU','OKS_PS','OKU_GKU','OKU_PS')
					{$likeOUR}
				group by ID_PROC
				
				union
				
				select 5 as \"N\", 'ROSREESTR' as \"ORG\",	count(cs.id) as \"COUNT_DEL\", 'ALL1' as \"ID_PROC\", 'SMALL' as \"TYPE\"
				from T\$PVD_14 cs
				where
					CS.STARTDATE between TO_DATE('{$DateBegin}','DD.MM.YYYY') AND TO_DATE('{$DateEnd}','DD.MM.YYYY')+1
					AND ID_PROC in ('EGRP_GRP','EGRP_PS','OKS_GU','OKS_PS','OKU_GKU','OKU_PS')
					{$likeOUR}
				
				union
				
				select 6 as \"N\", 'ROSREESTR' as \"ORG\",	count(cs.id) as \"COUNT_DEL\", 'ALL' as \"ID_PROC\", 'SMALL' as \"TYPE\"
				from T\$PVD_14 cs
				where
					CS.STARTDATE between TO_DATE('{$DateBegin}','DD.MM.YYYY') AND TO_DATE('{$DateEnd}','DD.MM.YYYY')+1
					{$likeOUR}
		";
		//$sql .= "		union ";

		/*		$sql .= "
				select 5 as \"N\", 'ROSREESTR' as \"ORG\",	count(cs.id) as \"COUNT_DEL\", 'ALL1' as \"ID_PROC\", 'SMALL' as \"TYPE\"
				from T\$PVD_14 cs
				where
					CS.STARTDATE between TO_DATE('{$DateBegin}','DD.MM.YYYY') AND TO_DATE('{$DateEnd}','DD.MM.YYYY')+1
					AND ID_PROC in ('EGRP_GRP','EGRP_PS','OKS_GU','OKS_PS','OKU_GKU','OKU_PS')
					{$likeOUR}
				
				union
				
				select 6 as \"N\", 'ROSREESTR' as \"ORG\",	count(cs.id) as \"COUNT_DEL\", 'ALL' as \"ID_PROC\", 'SMALL' as \"TYPE\"
				from T\$PVD_14 cs
				where
					CS.STARTDATE between TO_DATE('{$DateBegin}','DD.MM.YYYY') AND TO_DATE('{$DateEnd}','DD.MM.YYYY')+1
					{$likeOUR}
		";
		*/
		
		$sql .= ") select N, ORG, COUNT_DEL, PP.ID_PROC, TYPE, P.TEXT
					from OP_DOP PP
					left join  T\$PROC p on P.ID_PROC=pp.ID_PROC
					order by N asc, P.IDORDER asc";
		
		//
//	CASE {$case}
//	{$where}
//echo "$sql<br>";
		
		$st2 = oci_sql_exec($linkPVD, $sql);
		while ($row = oci_fetch_assoc($st2)) {
			//echo "{$row['TYPE']}, {$row['ID_PROC']}, {$row['TEXT']} <br>";
			if($row['TYPE']=='FULL'){
				if($row['ID_PROC']=='ALL1'){
					$FULL_ALL1 = $row['COUNT_DEL'];
					$data1[] = array(
						'ID_PROC'=>$row['ID_PROC'],
						'TEXT'=>'Количество принятых заявлений с использованием ПК ПВД (Росреестр,Всего)',
						'CALC'=>$row['COUNT_DEL'],
						'DB'=>$DateB,
						'DE'=>$DateEnd
					);
				} else
				if($row['ID_PROC']=='ALL'){
					$FULL_ALL = $row['COUNT_DEL'];
					$data1[] = array(
						'ID_PROC'=>$row['ID_PROC'],
						'TEXT'=>'Количество операций с использованием ПК ПВД (Росреестр,Всего)',
						'CALC'=>$row['COUNT_DEL'],
						'DB'=>$DateB,
						'DE'=>$DateEnd
					);
				} else {
					$data1[] = array(
						'ID_PROC'=>$row['ID_PROC'],
						'TEXT'=>$row['TEXT'],
						'CALC'=>$row['COUNT_DEL'],
						'DB'=>$DateB,
						'DE'=>$DateEnd
					);
				}
			} else 
			if($row['TYPE']=='SMALL'){
				if($row['ID_PROC']=='ALL1'){
					$SMALL_ALL1 = $row['COUNT_DEL'];
					$data2[] = array(
						'ID_PROC'=>$row['ID_PROC'],
						'TEXT'=>'Количество принятых заявлений с использованием ПК ПВД (Росреестр,За период)',
						'CALC'=>$row['COUNT_DEL'],
						'DB'=>$DateBegin,
						'DE'=>$DateEnd
					);
				} else
				if($row['ID_PROC']=='ALL'){
					$SMALL_ALL = $row['COUNT_DEL'];
					$data2[] = array(
						'ID_PROC'=>$row['ID_PROC'],
						'TEXT'=>'Количество операций с использованием ПК ПВД (Росреестр,За период)',
						'CALC'=>$row['COUNT_DEL'],
						'DB'=>$DateBegin,
						'DE'=>$DateEnd
					);
				} else {
					$data2[] = array(
						'ID_PROC'=>$row['ID_PROC'],
						'TEXT'=>$row['TEXT'],
						'CALC'=>$row['COUNT_DEL'],
						'DB'=>$DateBegin,
						'DE'=>$DateEnd
					);
				}
			}
					//echo "{$row['COUNT_DEL']} - {$row['ID_PROC']} - ".@$row['TEXT']." - ".$row['TYPE']."<br>";
		}

	$dataS[] = array(
						'ID_PROC'=>'',
						'TEXT'=>'**********************************************',
						'CALC'=>'',
						'DB'=>'',
						'DE'=>''
					);
		
//	echo ">> ".round($FULL_ALL1/$FULL_ALL*100,2)." %<br>";
//	echo ">> ".round($SMALL_ALL1/$SMALL_ALL*100,2)." %<br>";


/*		// Выборка из АИС ЕГРП для сравнения
		$sql = "with OP_DOP as (";
		$sql .= "select 1 as N, count(*) cnt from app_applications@ssd
					where
					a_type in (	'С'  -- ЕГРП
								,'И'    -- ППД
					) and
					(R_DATE between TO_DATE('{$DateB}','DD.MM.RRRR')
								and TO_DATE('{$DateEnd}','DD.MM.YYYY')+1)
				
				union
				
				select 2 as N, count(*) cnt from app_applications@ssd
					where
					a_type in (	'С'  -- ЕГРП
								,'И'    -- ППД
					) and
					(R_DATE between TO_DATE('{$DateBegin}','DD.MM.RRRR')
								and TO_DATE('{$DateEnd}','DD.MM.YYYY')+1)
				";
		$sql .= ") select N, CNT
					from OP_DOP PP
					order by N asc";
				
				
//echo "\n\n*****************************\n\n<p>$sql</p>";				
		$st = oci_sql_exec($linkPVD, $sql);
		$row = oci_fetch_array($st);
		$EGRP_FULL = $row['CNT'];
		$row = oci_fetch_array($st);
		$EGRP_SMALL = $row['CNT'];
		//echo "EGRP: ".$EGRP_FULL."   ".$EGRP_SMALL."<BR>";

	$dataE1[] = array(
						'ID_PROC'=>'',
						'TEXT'=>'Количество принятых заявлений с использованием АИС ЕГРП (Всего)',
						'CALC'=>$EGRP_FULL,
						'DB'=>$DateB,
						'DE'=>$DateEnd
					);
	$dataE2[] = array(
						'ID_PROC'=>'',
						'TEXT'=>'Количество принятых заявлений с использованием АИС ЕГРП (За период)',
						'CALC'=>$EGRP_SMALL,
						'DB'=>$DateBegin,
						'DE'=>$DateEnd
					);
*/

	$P1 = round( $FULL_ALL1/$FULL_ALL*100,2);
	$P2 = round( $SMALL_ALL1/$SMALL_ALL*100,2);
	$dataP1[] = array(
						'ID_PROC'=>'',
						'TEXT'=>'% от общего количества принятых пакетов документов',
						'CALC'=>$P1,
						'DB'=>$DateB,
						'DE'=>$DateEnd
					);
	$dataP2[] = array(
						'ID_PROC'=>'',
						'TEXT'=>'% от общего количества принятых пакетов документов (За период)',
						'CALC'=>$P2,
						'DB'=>$DateBegin,
						'DE'=>$DateEnd
					);
	
	$data = array_merge(  $data1, $dataP1, $dataS,  $data2, $dataP2 );
	
//	print_r($data);

	
		// И пусть себе летят на экран
		echo json_encode(array(
				'success'=>'true',
				'data'=>$data
		));
?>
