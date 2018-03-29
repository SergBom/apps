<?php
/**************************************************************
 Статистика для Вариант1 (ЕГРП) ( почти как для налоговой, но с разделением по системам
*/
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
include_once("tir-query.php");
header('Content-type: text/html; charset=utf-8');

/****************************************************************************/
$TIR_Session = trim ((!empty($_GET['PSESSION'])) ? $_GET['PSESSION'] : "" );
$TIR_System = trim ((!empty($_GET['ST'])) ? $_GET['ST'] : "R" ); // По умолчанию - ЕГРП (C- ГКН)
/****************************************************************************/

$sql = str_replace(':SESSION',$TIR_Session,$sqlLOC['stat1N']);
$sql = str_replace(':ST',$TIR_System,$sql);


$conn = ConnectLocalTIR(); // Присоска к базе

	$stid = oci_parse($conn, $sql);
    oci_execute($stid);

?>
<rows>
<?php
$CalcNum = 0;
while (($row = oci_fetch_array($stid))){
	$CalcNum += $row['NUMS'];
?>
<row id="<?php echo $row['ROWNUM']?>">
    <cell><?php echo $row['ROWNUM']?></cell>
    <cell><![CDATA[ <?php echo $row['DESCRIPTION']?> ]]></cell>
    <cell>-</cell>
    <cell><?php echo $row['NUMS']?></cell>
</row>
<?php } ?>
<row id="TotalNums">
	<cell></cell>
	<cell><![CDATA[ <b>Всего:</b> ]]></cell>
	<cell></cell>
	<cell><![CDATA[ <b><?php echo $CalcNum; ?></b> ]]></cell>
</row>	
</rows>
<?php
@oci_free_statement($stid);
@oci_close($connLOC);
?>