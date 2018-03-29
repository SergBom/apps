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

$sql = str_replace(':SESSION',$TIR_Session,$sqlLOC['stat3']);
$stid = sql_exec($connLOC, $sql);

?>
<rows>
<?php
$CalcErN = 0;
$CalcEr0 = 0;
$CalcEr1 = 0;
$CalcEr2 = 0;
$CalcEr3 = 0;
$CalcTotal = 0;
while (($row = oci_fetch_array($stid))){
	$CalcErN += $row['ERN'];
	$CalcEr0 += $row['ER0'];
	$CalcEr1 += $row['ER1'];
	$CalcEr2 += $row['ER2'];
	$CalcEr3 += $row['ER3'];
	$CalcTotal += $row['TOTAL'];
?>
<row id="<?php echo $row['ROWNUM']?>">
    <cell><?php echo $row['ROWNUM']?></cell>
    <cell><![CDATA[ <?php echo $row['DESCRIPTION']?> ]]></cell>
    <cell><?php echo $row['ERN']?></cell>
    <cell><?php echo $row['ER0']?></cell>
    <cell><?php echo $row['ER1']?></cell>
    <cell><?php echo $row['ER2']?></cell>
    <cell><?php echo $row['ER3']?></cell>
    <cell><?php echo $row['TOTAL']?></cell>
</row>
<?php } ?>
<row id="TotalNums">
	<cell></cell>
	<cell><![CDATA[ <b>Всего:</b> ]]></cell>
	<cell><![CDATA[ <b><?php echo $CalcErN; ?></b> ]]></cell>
	<cell><![CDATA[ <b><?php echo $CalcEr0; ?></b> ]]></cell>
	<cell><![CDATA[ <b><?php echo $CalcEr1; ?></b> ]]></cell>
	<cell><![CDATA[ <b><?php echo $CalcEr2; ?></b> ]]></cell>
	<cell><![CDATA[ <b><?php echo $CalcEr3; ?></b> ]]></cell>
	<cell><![CDATA[ <b><?php echo $CalcTotal; ?></b> ]]></cell>
</row>	
</rows>
<?php
@oci_free_statement($stid);
@oci_close($connLOC);
?>