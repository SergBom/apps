<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
/*******************************************************************
	Выдает список выгрузок
*/	

//echo date("H:i:s")."<br>";

//$connLOC = oci_connect('tir', 'test', 'db.murmansk.net:1521/tir.murmansk.net','AL32UTF8');
//$connLOC = oci_pconnect('tir', 'test', '10.51.119.241:1521/tir.murmansk.net','AL32UTF8');
//if (!$connLOC) {$e = oci_error();trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);}
//echo date("H:i:s")."<br>";

//include_once($_SERVER["DOCUMENT_ROOT"]."/php/tir/tir-connect-loc.php");
//include_once($_SERVER["DOCUMENT_ROOT"]."/php/tir/tir-query.php");
//include_once($_SERVER["DOCUMENT_ROOT"]."/php/function.php");
//include_once($_SERVER["DOCUMENT_ROOT"]."/php/xml-header.php");
header('Content-type: text/html; charset=utf-8');

$Period = trim ( (!empty($_GET['Period'])) ? $_GET['Period'] : "" ); // Задаем Период по умолчанию (в списке будет выбранным сразу)
$notSID = trim ( (!empty($_GET['notSID'])) ? $_GET['notSID'] : "" ); // Период исключить из списка


$conn = ConnectLocalTIR(); // Присоска к базе


if ($notSID) {
	$sql  = "SELECT SESSION_ROOT, VYGRUZKA#||STATUS as VYGRUZKA, to_char(DATE_BEGIN,'YYYY.MM.DD')||' - '||to_char(DATE_END,'YYYY.MM.DD') as PERIOD
			FROM T\$SESSION
			WHERE SESSION_ROOT <> $notSID
			ORDER BY VYGRUZKA# desc,STATUS ";
} else {
	$sql  = "SELECT SESSION_ROOT as ID, VYGRUZKA#||STATUS||' * '||to_char(DATE_BEGIN,'YYYY.MM.DD')||' - '||to_char(DATE_END,'YYYY.MM.DD') as NAME
			FROM T\$SESSION
			ORDER BY VYGRUZKA# desc,STATUS ";
}
//echo $sql."<br><br><br>";



$stid = oci_parse($conn, $sql);
oci_execute($stid);

$co = oci_fetch_all($stid, $data, null, null, OCI_FETCHSTATEMENT_BY_ROW);

			echo json_encode(Array(
				"success"=>"true",
				"data"=>$data
			));

/*
echo "<complete>";
$s="";
while (($row = oci_fetch_array($stid))){
	$s .= "<option ".($Period==$row['PERIOD'] ? "selected='true'" : "")." value='{$row['SESSION_ROOT']}'>{$row['VYGRUZKA']} * {$row['PERIOD']}</option>";
}
echo "$s</complete>";*/
//@oci_free_statement($stidCR);
//@oci_close($connLOC);
//echo date("H:i:s")."<br>";
?>