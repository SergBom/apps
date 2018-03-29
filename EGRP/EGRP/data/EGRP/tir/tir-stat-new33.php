<?php
/**************************************************************
*	Статистика По текущему периоду + Сумма всех предыдущих
* 	Здесь вычисляем всю статистику за предыдущие периоды
*
*/

include_once("init.php");
	
include_once(_TIR_WORK_DIR."/tir-query.php");
//include_once(_SCRIPT_WORK_DIR."/xml-header.php");
//header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
$TIR_Session = trim ((!empty($_GET['PSESSION'])) ? $_GET['PSESSION'] : "" );
/*---------------------------------------------------------------------------*/

	$cLocalTIR_1 = ConnectLocalTIR(); // Присоска к локальному ТИРу


// Номер выгрузки:
/***+++++++++++++++++++++++++++
 Есть одна хрень: После переустановки СУБД Oracle ругается: "ora-01747: invalid user.table.column nls_lang"
 Лечиться так: в лягушке запускается запрос:
	create or replace trigger On_Logon after logon on database
	begin Execute immediate 'alter session set NLS_NUMERIC_CHARACTERS = ". "';end;
*/
$stid_1 = oci_sql_exec($cLocalTIR_1, "SELECT VYGRUZKA#,STATUS FROM T\$SESSION WHERE SESSION_ROOT=$TIR_Session");
$row = oci_fetch_array($stid_1);
$V = $row["VYGRUZKA#"];
//echo "V= $V<br>SELECT VYGRUZKA#,STATUS FROM T\$SESSION WHERE SESSION_ROOT=$TIR_Session";
if($row['STATUS']=='p'){
//	@oci_free_statement($stid);
//	@oci_close($connLOC);
//	exit;

	$TT = nV($V,$cLocalTIR_1); // Поиск предыдущей сессии

	//echo "Выгрузка №$V"."p<br>";
	// Ищем эту выгрузку в таблице T$OTDELS
	$stid1 = oci_sql_exec($cLocalTIR_1, "SELECT count(*) COUNT FROM T\$OTDELS WHERE VYGRUZKA#=$V AND STATUS='p'");
	$row = oci_fetch_array($stid1);

	if ($row['COUNT']==0) { // Если данной сессии нет, то создаем на основе предыдущей
		if ( $TT ) { // Если есть предыдущая сессия, то
		//	echo "Предыдущая выгрузка №$TT"."p<br>";

			// Создаем шаблон для текущей выгрузки
			$sql = "INSERT INTO T\$OTDELS (ID,NAME,VYGRUZKA#,STATUS,SESSION#)
						SELECT o.ID, o.NAME, $V, 'p', $TIR_Session
							FROM T\$OTDELS_TMP o WHERE o.ID<12";    //FROM T\$OTDELS o WHERE o.VYGRUZKA#=$TT"
			$stid1 = oci_sql_exec($cLocalTIR_1, $sql);
		}
	}

	if( $TT){
		// Берем предыдущую выгрузку
		// Считаем За Все периоды
		$stid1 = oci_sql_exec($cLocalTIR_1, "SELECT * FROM T\$OTDELS WHERE VYGRUZKA#=$TT AND STATUS='p'");
		while($row = oci_fetch_array($stid1)){
			$sql = "UPDATE T\$OTDELS SET
					N_ERR=".($row['N_ERR']+$row['TOTAL']).",
					N_ISPR=".($row['N_ISPR']+$row['ER1']). ",
					N_PRCNT=".@ROUND(($row['N_ISPR']+$row['ER1'])/($row['N_ERR']+$row['TOTAL'])*100,2) ."
				WHERE SESSION#=$TIR_Session AND NAME like '%".$row['NAME']."%'";
				//echo $sql."<br>";
			oci_sql_exec($cLocalTIR_1, $sql);
		}
	}

	// Обновляем статистику по текущей сессии
	$sql2 = str_replace(':SESSION',$TIR_Session,$sqlLOC['stat33a']);
	$stid2 = oci_sql_exec($cLocalTIR_1, $sql2);

	//$sql = 'select ID,NAME,N_ERR,N_ISPR  FROM T$OTDELS T1 WHERE T1.VYGRUZKA#=8 AND T1.STATUS=\'p\'';
	//$sql = "select *  FROM T\$OTDELS T1 WHERE T1.VYGRUZKA#=$V AND T1.STATUS='p'";
	//$stid = sql_exec($connLOC, $sql);

	while ($row2 = oci_fetch_array($stid2)){
		//$row1 = oci_fetch_array($stid1);
		//if($row2['NERN']!=NULL){
		// Заполняем таблицу текущими значениями статистики
			$sql = 'UPDATE T$OTDELS SET
				ERN='.$row2['ERN'].',
				ER0=0,
				ER1='.$row2['ER1'].',
				ER2='.$row2['ER2'].',
				ER3='.$row2['ER3'].',
				TOTAL='.$row2['TOTAL'].',
				PISPR='.$row2['PISPR'].',
				POBR='.$row2['POBR'].'
				WHERE SESSION#='.$TIR_Session." AND NAME='".$row2['NAME']."'";
			//echo $sql."<br>";
			oci_sql_exec($cLocalTIR_1, $sql);
			oci_commit($cLocalTIR_1);
		//}
	}

}


//@oci_free_statement($stid3);
@oci_free_statement($stid2);
@oci_free_statement($stid1);
//@oci_close($connLOC);
DisconnectOCI( array($cLocalTIR_1) );

function nV($Vygruzka,$connLOC){
	$Vt = $Vygruzka;
	while ($Vt!=0){
//		echo "$Vt<br>";
		$Vt = $Vt-1;
		$stid = oci_sql_exec($connLOC, "SELECT count(*) COUNT FROM T\$OTDELS WHERE VYGRUZKA#=$Vt AND STATUS='p'");
		$row = oci_fetch_array($stid);
		if ( $row['COUNT']!=0) { return $Vt;}
	}
	return false;
}
?>