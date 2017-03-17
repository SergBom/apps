<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
$rec_guid = trim ((!empty($_POST['rec_guid'])) ? $_POST['rec_guid'] : "" ); // 
/*---------------------------------------------------------------------------*/

					$connLOC = ConnectLocalTIR(); // Присоска к базе
					$connTIR = ConnectTIR(); // Присоска к базе

//echo $rec_guid;
$sql = "SELECT DOC_GUID,EXTERNAL_ID,PATH FROM V\$FLK WHERE REC_GUID='$rec_guid'";
$stidLOC = oci_parse($connLOC, $sql);
oci_execute($stidLOC);
$row1 = oci_fetch_array($stidLOC);

if ($row1['EXTERNAL_ID']) {

	$sql1 = "select 
		replace(replace(replace(to_char(SQL$),':external_id','''".$row1['EXTERNAL_ID']."''')
	                ,':doc_guid','''".$row1['DOC_GUID']."''')
			,':normalized_number','45') as F1
		FROM T\$FNS#SQL
		WHERE SQL_TYPE=3 
			AND PATH= Upper('".$row1['PATH']."')";
	//echo $sql1;
		
	$stidTIR = oci_parse($connTIR, $sql1);
	oci_execute($stidTIR);
	$row1 = oci_fetch_array($stidTIR);


	$stidTIR = oci_parse($connTIR, $row1['F1']);
	oci_execute($stidTIR);

	$nrows = oci_fetch_all($stidTIR, $res);


// Форматирование результатов
	echo "<table style='border: 1px solid black; width:100%;'>\n";
	foreach ($res as $k=>$v) {
		echo "<tr style='border: 1px solid #a5a5a5;'>\n";
			echo "    <td style='padding:2px;border-right: 1px solid #a5a5a5;'>$k</td><td>".$v[0] ."</td>\n";
		echo "</tr>\n";
	}
	echo "</table>\n";
}

//oci_close($connLOC);
//oci_close($connTIR);
?>
