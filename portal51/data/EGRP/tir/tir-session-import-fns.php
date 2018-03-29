<?php
include_once("tir-connect-tir.php");
include_once("tir-connect-loc.php");
include_once("tir-query.php");
include_once("function.php");
//include_once("xml-header.php");
header('Content-type: text/html; charset=utf-8');

$TIR_Session = trim ((!empty($_GET['PSESSION'])) ? $_GET['PSESSION'] : "" );

if ($TIR_Session <> ""){

	//***************************************************************
	//*************** Набор данных из T$FNS#SESSION-TIR
	$sql = str_replace(':Session_Root',$TIR_Session,$sqlTIR['sessionNUM']);
	//echo $sql."<br><br>";
	$stidTIRimport = sql_exec($connTIR, $sql);

	//while (($row = oci_fetch_array($stidTIR))){
	$rowSession = oci_fetch_array($stidTIRimport);

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
				
//echo $sql;				
			sql_exec($connLOC, $sql);
			
			$sql = "SELECT count(*) as COUNT FROM T\$FNS#DOCUMENT_RES_LOG WHERE SESSION#=$TIR_Session";
			$stidLOCimport = sql_exec($connLOC, $sql);
			$r = oci_fetch_array($stidLOCimport);
			$sql = "UPDATE T\$SESSION SET FNS_ERR=".$r['COUNT']." WHERE SESSION_ROOT=$TIR_Session";
			sql_exec($connLOC, $sql);
			
			echo "<b>Переброска документов ФНС завершена</b><br>";
		} else {
			echo "Сессия уже в базе";
		}
		// Таблица T$DOCUMENT_RES_LOG
		//***************************************************************************
} else {
	?><b> Параметр 'PSESSION' не передан</b><?php
}
oci_free_statement($stidTIRimport);
oci_close($connTIR);
oci_free_statement($stidLOCimport);
oci_close($connLOC);
?>