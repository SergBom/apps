<?php
include_once("tir-connect-tir.php");
include_once("tir-connect-loc.php");
include_once("tir-query.php");
include_once("function.php");
//include_once("xml-header.php");
header('Content-type: text/html; charset=utf-8');

$gknError = array('Не указана кадастровая и нормативная стоимость ЗУ');

$TIR_Session = trim ((!empty($_GET['PSESSION'])) ? $_GET['PSESSION'] : "" );
$TIR_Session = 48465134851;
if ($TIR_Session <> ""){

	//***************************************************************
	//*************** Набор данных из T$FNS#SESSION-TIR
	$sql = str_replace(':Session_Root',$TIR_Session,$sqlTIR['sessionNUM']);
	//echo $sql."<br><br>";
	$stidTIRimport = sql_exec($connTIR, $sql);

	//while (($row = oci_fetch_array($stidTIR))){
	$rowSession = oci_fetch_array($stidTIRimport);

	//******************************************************
	//** Проверяем: есть ли данная сессия в локальной базе?
	$sqlIs = 'SELECT count(*) as Count FROM T$SESSION WHERE SESSION_ROOT='.$TIR_Session;
	//echo $sqlIs."<br><br>";
	$stidLOCimport = sql_exec($connLOC, $sqlIs);
	$r = oci_fetch_array($stidLOCimport);
	//echo "Сессия в базе SESSION: ".$r['COUNT']."<br><br>";

	if ($r['COUNT'] == 0) {
	

		//***************************************************************************
		// Таблица T$SESSION
		$sqlIs = "SELECT VYGRUZKA# FROM T\$SESSION WHERE
						DATE_BEGIN=to_date('".$rowSession['DATE_BEGIN']."','dd.mm.yy')
					AND DATE_END=to_date('".$rowSession['DATE_END']."','dd.mm.yy')";
		//echo $sqlIs."<br><br>";
		$stidLOCimport = sql_exec($connLOC, $sqlIs);
		$rV = oci_fetch_array($stidLOCimport);
		if ( $rV['VYGRUZKA#'] > 0 ){
			$rc = $rV['VYGRUZKA#'];
			$rs = "";
		} else {
			$stidLOCimport = sql_exec($connLOC, 'SELECT MAX(VYGRUZKA#) as Count FROM T$SESSION');
			$r = oci_fetch_array($stidLOCimport);
			$rc = $r['COUNT']+1;
			$rs = "p";
		}

		$sqlLOCimport = 'INSERT INTO T$SESSION
		(SESSION_ROOT,	SESSION_TYPE, DATE_BEGIN, DATE_END, 
		SESSION_CNT,DATE_FIRST,DATE_LAST,DOC_CNT, FNS_CNT, FLK_ERR, FNS_ERR, VYGRUZKA#, STATUS
		) VALUES ('.
		$rowSession['SESSION_ROOT'].','.
		"'".$rowSession['SESSION_TYPE']."',".
		"to_date('".$rowSession['DATE_BEGIN']."','dd.mm.yy'),".
		"to_date('".$rowSession['DATE_END']."','dd.mm.yy'),".
		$rowSession['SESSION_CNT'].','.
		"to_date('".$rowSession['DATE_FIRST']."','dd.mm.yy'),".
		"to_date('".$rowSession['DATE_LAST']."','dd.mm.yy'),".
		$rowSession['DOC_CNT'].','.
		$rowSession['FNS_CNT'].','.
		$rowSession['FLK_ERR'].','.
		$rowSession['FNS_ERR'].','.
		$rc.",'$rs')";
		//echo $sqlLOC."<br><br>";
		
		$stidLOCimport = sql_exec($connLOC, $sqlLOCimport);
		echo "T\$SESSION import - ok<br>";
		// Таблица T$SESSION
		//***************************************************************************
		
		//***************************************************************************
		// Таблица T$DOCUMENT
		$sql = "SELECT count(*) as COUNT FROM T\$DOCUMENT WHERE SESSION#=$TIR_Session";
		$stidLOCimport = sql_exec($connLOC, $sql);
		$r = oci_fetch_array($stidLOCimport);
		if ( $r['COUNT'] == 0) {
        
			$sql = "INSERT INTO T\$DOCUMENT (SESSION#, DOC_GUID)
					SELECT TR.SESSION#, TR.DOC_GUID
					FROM T\$FNS#DOCUMENT@TIR51 TR WHERE TR.SESSION# = $TIR_Session";
			sql_exec($connLOC, $sql);
			echo "T\$DOCUMENT import - ok<br>";
        }
		// Таблица T$DOCUMENT
		//***************************************************************************
   
		//***************************************************************************
		// Таблица T$DOCUMENT_FLK_LOG
   		$sql = "SELECT count(*) as COUNT FROM T\$FNS#DOCUMENT_FLK_LOG WHERE SESSION#=$TIR_Session";
		$stidLOCimport = sql_exec($connLOC, $sql);
		$r = oci_fetch_array($stidLOCimport);
		if ( $r['COUNT'] == 0) {

			$sql = "INSERT INTO T\$FNS#DOCUMENT_FLK_LOG (
				DOC_GUID,
				STATUS_ERROR,
				EVENT,
				DESCRIPTION,
				ATTRIBUTE_NAME,
				ATTRIBUTE_VALUE,
				TAG_NAME,
				DATE_CREATE,
				PATH,
				TAG_NUM,
				SESSION#,
				REC_GUID,
				SYSTEM_TYPE,
				ESSENCE_TYPE,
				EXTERNAL_ID
				)
				SELECT
				TR.DOC_GUID,
				0,
				TR.EVENT,
				TR.DESCRIPTION,
				TR.ATTRIBUTE_NAME,
				TR.ATTRIBUTE_VALUE,
				TR.TAG_NAME,
				TR.DATE_CREATE,
				TR.PATH,
				TR.TAG_NUM,
				TR.SESSION#,
				TR.REC_GUID,
				TR.SYSTEM_TYPE,
				TR.ESSENCE_TYPE,
				TR.EXTERNAL_ID
				FROM T\$FNS#DOCUMENT_FLK_LOG@TIR51 TR WHERE TR.SESSION# = $TIR_Session";
			sql_exec($connLOC, $sql);				
			
			foreach ( $gknError as $val ) {
				$sql = "UPDATE T\$FNS#DOCUMENT_FLK_LOG SET SYSTEM_TYPE='C' WHERE upper(PATH) like upper('Документ/СведЗу%') AND SESSION# = $TIR_Session";
			    sql_exec($connLOC, $sql);
			}
			
			echo "T\$FNS#DOCUMENT_FLK_LOG import - ok<br>";
			
		}
		// Таблица T$DOCUMENT_FLK_LOG
		//***************************************************************************

		//***************************************************************************
		// Таблица T$DOCUMENT_RES_LOG
		$sql = "SELECT count(*) as COUNT FROM T\$FNS#DOCUMENT_RES_LOG WHERE SESSION#=$TIR_Session";
   		$stidLOCimport = sql_exec($connLOC, $sql);
		$r = oci_fetch_array($stidLOCimport);
		if ( $r['COUNT'] == 0) {

			$sql = "INSERT INTO T\$FNS#DOCUMENT_RES_LOG (
				SESSION#,
				DOC_GUID,
				ERROR_TEXT,
				ERROR_PATH,
				FILE_GUID,
				ATTRIBUTE_VALUE)
				SELECT
				TR.SESSION#,
				TR.DOC_GUID,
				TR.ERROR_TEXT,
				TR.ERROR_PATH,
				TR.FILE_GUID,
				TR.ATTRIBUTE_VALUE
				FROM T\$FNS#DOCUMENT_RES_LOG@TIR51 TR WHERE TR.SESSION# = $TIR_Session";
			sql_exec($connLOC, $sql);				
			echo "T\$FNS#DOCUMENT_FLK_RES import - ok<br>";
		}
		// Таблица T$DOCUMENT_RES_LOG
		//***************************************************************************
  
		//***************************************************************************
		// Таблица T$FNS#CADASTRAL
		$sql = "SELECT count(*) as COUNT FROM T\$FNS#CADASTRAL WHERE SESSION#= $TIR_Session";
   		$stidLOCimport = sql_exec($connLOC, $sql);
		$r = oci_fetch_array($stidLOCimport);
		if ( $r['COUNT'] == 0) {

			$sql = "INSERT INTO T\$FNS#CADASTRAL (
				SESSION#,
				DOC_GUID,
				DOC_TYPE,
				EXTERNAL_ID,
				DOC_STATUS,
				NORMALIZED_NUMBER,
				CADASTRALNUMBER,
				CONDITIONALNUMBER,
				CAD_RAION
				)
			SELECT
				TR.SESSION#,
				TR.DOC_GUID,
				TR.DOC_TYPE,
				TR.EXTERNAL_ID,
				TR.DOC_STATUS,
				TR.NORMALIZED_NUMBER,
				TR.CADASTRALNUMBER,
				TR.CONDITIONALNUMBER,
				CASE TR.NORMALIZED_NUMBER WHEN NULL THEN ''
				ELSE SUBSTR(TR.NORMALIZED_NUMBER,1,5)
				END AS CAD_RAION    
			FROM  V\$FNS#CADASTRAL@TIR51 TR WHERE TR.SESSION# = $TIR_Session";
			sql_exec($connLOC, $sql);
			echo "T\$FNS#CADASTRAL import - ok<br>";
		}
		// Таблица T$FNS#CADASTRAL
		//***************************************************************************
		
		echo "<b>Переброска документов завершена</b><br>";

		$sql = "update T\$Fns#document_flk_log t1 SET
					t1.RF = '1',
--					t1.STATUS_ERROR=2,
					t1.USER_NAME = 'ADMIN',
					T1.USER_COMMENT = 'РФ',
					T1.USER_DATE = SYSDATE
				where 
					t1.SESSION# = $TIR_Session and
					( t1.STATUS_ERROR=0 OR t1.STATUS_ERROR is null ) and
					to_number(regexp_substr( t1.external_id, '([0123456789])+')) in (
                                    select t2.id from V\$REF_RF t2)";
		sql_exec($connLOC, $sql);
		echo "<b>Обновили записи по \"РФ(1)\"</b><br>";
		
		$sql = "update T\$Fns#document_flk_log t1 SET
					t1.RF = '2',
--					t1.STATUS_ERROR=2,
					t1.USER_NAME = 'ADMIN',
					T1.USER_COMMENT = 'РФ Мурманская область',
					T1.USER_DATE = SYSDATE
				where 
					t1.SESSION# = $TIR_Session and
					( t1.STATUS_ERROR=0 OR t1.STATUS_ERROR is null ) and
					to_number(regexp_substr( t1.external_id, '([0123456789])+')) in (
									SELECT ee.id FROM ent_entities@ssd ee
										WHERE   ee.r_type = 'О'
												AND EE.PROP_TYPE = 2)";
		sql_exec($connLOC, $sql);
		echo "<b>Обновили записи по \"РФ(2)\"</b><br>";
		
		$sql = "update T\$Fns#document_flk_log t1 SET
					t1.RF = '3',
--					t1.STATUS_ERROR=2,
					t1.USER_NAME = 'ADMIN',
					T1.USER_COMMENT = 'РФ Муниципальные образования',
					T1.USER_DATE = SYSDATE
				where 
					t1.SESSION# = $TIR_Session and
					( t1.STATUS_ERROR=0 OR t1.STATUS_ERROR is null ) and
					to_number(regexp_substr( t1.external_id, '([0123456789])+')) in (
									SELECT ee.id FROM ent_entities@ssd ee
										WHERE   ee.r_type = 'О'
												AND EE.PROP_TYPE = 3)";
		sql_exec($connLOC, $sql);
		echo "<b>Обновили записи по \"РФ(3)\"</b><br>";
		
		$sql = "update T\$Fns#document_flk_log t1 SET
					t1.RF = '4'
				where 
					t1.SESSION# = $TIR_Session and
					( t1.STATUS_ERROR=0 OR t1.STATUS_ERROR is null ) and
					to_number(regexp_substr( t1.external_id, '([0123456789])+')) in (
									SELECT ee.id FROM ent_entities@ssd ee
										WHERE   ee.r_type = 'О'
												AND EE.PROP_TYPE = 4)";
		sql_exec($connLOC, $sql);
		echo "<b>Обновили записи по \"РФ(4)\"</b><br>";

		$sql = "update T\$Fns#document_flk_log t1 SET
					t1.RF = '99'
				where 
					t1.SESSION# = $TIR_Session and
					( t1.STATUS_ERROR=0 OR t1.STATUS_ERROR is null ) and
					to_number(regexp_substr( t1.external_id, '([0123456789])+')) in (
									SELECT ee.id FROM ent_entities@ssd ee
										WHERE   ee.r_type = 'О'
												AND EE.PROP_TYPE = 99)";
		sql_exec($connLOC, $sql);
		echo "<b>Обновили записи по \"РФ(99)\"</b><br>";
		
		
		//***************************************************************************
		// Корректировка кадастровых районов
		$sqlLOCimport = 'SELECT
				SESSION#,
				DOC_GUID,
				NORMALIZED_NUMBER,
				CADASTRALNUMBER,
				CONDITIONALNUMBER    
				FROM  T$FNS#CADASTRAL
				WHERE SESSION# = '.$TIR_Session;
			
		$stidLOCimport = sql_exec($connLOC, $sqlLOCimport);
		while ($r = oci_fetch_array($stidLOCimport)) {
			$CAD_RAION = '';
			$NorNum = ($r['NORMALIZED_NUMBER']) ? $r['NORMALIZED_NUMBER'] : '';
			$CadNum = ($r['CADASTRALNUMBER']) ? $r['CADASTRALNUMBER'] : '';
			$ConNum = ($r['CONDITIONALNUMBER']) ? $r['CONDITIONALNUMBER'] : '';


			if ( $NorNum <> ''){
				$CAD_RAION = substr($NorNum,0,5);
			} else if ( $CadNum <> '') {
				$CAD_RAION = substr($CadNum,0,5);
			} else if ( substr($ConNum,2,1) == ":") {
				$CAD_RAION = substr($ConNum,0,5);
			} else if ( substr($ConNum,0,6) == "51-51-") {
				$sqlTemp = "SELECT CAD_RAION FROM T\$CAD_RAION WHERE CONDITIONALNUMBER='".substr($ConNum,0,17)."'";
				$stidTempimport = sql_exec($connLOC, $sqlTemp);
				$rTemp = oci_fetch_array($stidTempimport);
				if ( $rTemp['CAD_RAION'] ) {
					$CAD_RAION = $rTemp['CAD_RAION'];
				} else {
				//****************************************
				// Заполнение
				//$sql = "INSERT INTO T\$CAD_RAION (CONDITIONALNUMBER, CAD_RAION) VALUES ('".$ConNum."','".$CAD_RAION."')";
				//sql_exec($connLOC, $sql);
				
				// номера 01/ххх/2009 и далее - должны быть в 51:20 - Мурманск
				//   * from T$CAD_RAION where substr(conditionalnumber,1,2) = '01' AND substr(conditionalnumber,8,4) > 2008;
				//	substr($ConNum,0,6) == "51-51-"
					if ( preg_match("|^51-51-01/\d\d\d/(20\d\d)|",$ConNum,$matches) ){
						if( $matches[1] >= 2009 ) {
							$CAD_RAION = '51:20';
						}
					}
				}
			}

//			echo "'".$r['NORMALIZED_NUMBER']."'  -  '".$r['CADASTRALNUMBER']."'  -  '".$r['CONDITIONALNUMBER']."'  --- $CAD_RAION<br>";

			$sqlLOCimportI = "UPDATE T\$FNS#CADASTRAL SET
				CAD_RAION = '".$CAD_RAION."' ".
				" WHERE SESSION#=".$TIR_Session.
				" AND DOC_GUID='".$r['DOC_GUID']."'";

//			echo $sqlLOCimportI ."<br><br><br>";
				
			sql_exec($connLOC, $sqlLOCimportI);
		}
		echo "<b>Корректировка кадастровых районов завершена</b><br>";
	} else {
		echo "Сессия уже в базе";
	}
} else {
	echo "<b> Параметр 'PSESSION' не передан</b>";
}
@oci_free_statement($stidTIRimport);
@oci_close($connTIR);
@oci_free_statement($stidLOCimport);
@oci_close($connLOC);
?>