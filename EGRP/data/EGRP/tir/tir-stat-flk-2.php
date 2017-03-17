<?php
//include_once($_SERVER["DOCUMENT_ROOT"]."/php/tir/tir-connect-tir.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/php/tir/tir-connect-loc.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/php/tir/tir-query.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/php/function.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/php/xml-header.php");
//header('Content-type: text/html; charset=utf-8');

/*
$Vygruzka = (!empty($_GET['Vygruzka'])) ? $_GET['Vygruzka']."_" : "" ; //  4p
preg_match_all('/(?P<digit>\d*)(?P<name>\w)/', $Vygruzka, $aV);
//echo $Vygruzka." = ".$aV[0][0]." - ".$aV[1][0]."  -  ".$aV[2][0]."<br>";
$VygruzkaN = $aV[1][0]; //Номер выгрузки
$VygruzkaS = ($aV[2][0]=="_") ? "": $aV[2][0]; //Статус выгрузки
//echo $VygruzkaN."  -  ".$VygruzkaS."<br>";
$stid = oci_parse($connLOC, "SELECT SESSION_ROOT FROM T\$SESSION");
oci_execute($stid);
*/

$TIR_Session = trim ((!empty($_GET['PSESSION'])) ? $_GET['PSESSION'] : "" );
$TIR_System = trim ((!empty($_GET['ST'])) ? $_GET['ST'] : "R" ); // По умолчанию - ЕГРП (C- ГКН)
$V = trim ((!empty($_GET['V'])) ? $_GET['V'] : "" ); // Номер выгрузки
$S = trim ((!empty($_GET['S'])) ? $_GET['S'] : "" ); // Номер выгрузки 2
$Period = trim ((!empty($_GET['Period'])) ? $_GET['Period'] : "" ); // Период выгрузки

$sql = str_replace(':SESSION',$TIR_Session,$sqlLOC['stat2']);
$stid = sql_exec($connLOC, $sql);

?>
<rows>
<?php
$CalcPrcl = 0;
$CalcCrds = 0;
$CalcFlat = 0;
$CalcTotal = 0;
while (($row = oci_fetch_array($stid))){
	$CalcPrcl  += $row['CNT_PRCL'];
	$CalcCrds  += $row['CNT_CRDS'];
	$CalcFlat  += $row['CNT_FLAT'];
	$CalcTotal += $row['TOTAL'];
?>
<row id="<?php echo $row['ROWNUM']?>">
    <cell><?php echo $row['ROWNUM']?></cell>
    <cell><![CDATA[ <?php echo $row['DESCRIPTION']?> ]]></cell>
    <cell><![CDATA[ <?php echo $row['PATH']?> ]]></cell>
    <cell><?php echo $row['CNT_PRCL']?></cell>
    <cell><?php echo $row['CNT_CRDS']?></cell>
    <cell><?php echo $row['CNT_FLAT']?></cell>
    <cell><?php echo $row['TOTAL']?></cell>
</row>
<?php } ?>
<row id="TotalNums">
	<cell></cell>
	<cell></cell>
	<cell><![CDATA[ <b>Всего:</b> ]]></cell>
	<cell><![CDATA[ <b><?php echo $CalcPrcl; ?></b> ]]></cell>
	<cell><![CDATA[ <b><?php echo $CalcCrds; ?></b> ]]></cell>
	<cell><![CDATA[ <b><?php echo $CalcFlat; ?></b> ]]></cell>
	<cell><![CDATA[ <b><?php echo $CalcTotal; ?></b> ]]></cell>
</row>	
</rows>
<?php
@oci_free_statement($stid);
@oci_close($connLOC);
?>