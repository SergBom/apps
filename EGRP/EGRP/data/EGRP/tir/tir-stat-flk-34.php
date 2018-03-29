<?php
/**************************************************************
*	Статистика По текущему периоду + Сумма всех предыдущих
*
*/
include_once("init.php");
	
include_once(_TIR_WORK_DIR."/tir-query.php");
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

/*-------------------------- Входные переменные -----------------------------*/
$TIR_Session = trim ((!empty($_GET['PSESSION'])) ? $_GET['PSESSION'] : "" );
$TIR_System = trim ((!empty($_GET['ST'])) ? $_GET['ST'] : "R" ); // По умолчанию - ЕГРП (C- ГКН)
$V = trim ((!empty($_GET['V'])) ? $_GET['V'] : "" ); // Номер выгрузки
$S = trim ((!empty($_GET['S'])) ? $_GET['S'] : "" ); // Номер выгрузки 2
$Period = trim ((!empty($_GET['Period'])) ? $_GET['Period'] : "" ); // Период выгрузки
/*---------------------------------------------------------------------------*/


	$cLocalTIR = ConnectLocalTIR();

/*-------
$stid = oci_sql_exec($cLocalTIR, 'SELECT STATUS FROM T$SESSION WHERE SESSION_ROOT=$TIR_Session');
$row = oci_fetch_array($stid);
$S = $row['STATUS'];
if ($S=='p'){
---------------*/

/*------------------------ Включаем файл расчетов статистики ----------------*/
	include(_TIR_WORK_DIR."/tir-stat-new33.php");
/*---------------------------------------------------------------------------*/


$sql = 'SELECT FLK_ERR FROM T$SESSION WHERE SESSION_ROOT='.$TIR_Session; //str_replace(':SESSION',$TIR_Session,$sqlLOC['stat33']);
$stid = oci_sql_exec($cLocalTIR, $sql);
$row = oci_fetch_array($stid);
$err_flk = $row['FLK_ERR']; // Общее количество ошибок по данной выгрузке

//$sql = str_replace(':SESSION',$TIR_Session,$sqlLOC['stat33']);

$sql = "SELECT * FROM T\$OTDELS WHERE SESSION#=$TIR_Session AND ID<>12 ORDER BY ID";
//echo $sql;
$stid = oci_sql_exec($cLocalTIR, $sql);

include_once(_SCRIPT_WORK_DIR."/xml-header.php");

?>
<rows>
<?php
$CalcErN = 0;
$CalcEr0 = 0;
$CalcEr1 = 0;
$CalcEr2 = 0;
$CalcEr3 = 0;
$CalcTotal = 0;
$CalcPispr = 0;
$CalcPobr = 0;
while (($row = oci_fetch_array($stid))){
/*	$CalcErN += $row['ERN'];
	$CalcEr0 += $row['ER0'];
	$CalcEr1 += $row['ER1'];
	$CalcEr2 += $row['ER2'];
	$CalcEr3 += $row['ER3'];
	$CalcTotal += $row['TOTAL'];
	$CalcPispr += $row['PISPR'];
	$CalcPobr  += $row['POBR'];*/
?>
<row id="<?php echo $row['ID']?>">
    <cell><?php echo $row['ID']?></cell>
    <cell><?php echo $row['NAME']?></cell>
    <cell><?php echo $row['N_ERR']?></cell>
    <cell><?php echo $row['N_ISPR']?></cell>
    <cell><?php echo $row['N_PRCNT']?></cell>
    <cell><?php echo $row['TOTAL']?></cell>
    <cell><?php echo $row['ER1']?></cell>
    <cell><?php echo $row['ER2']?></cell>
    <cell><?php echo $row['ER3']?></cell>
    <cell><?php echo $row['PISPR']?></cell>
    <cell><?php echo $row['POBR']?></cell>
</row>
<?php } ?>
</rows>
<?php
/*
<row id="TotalNums">
	<cell></cell>
	<cell><![CDATA[ <b>Всего:</b> ]]></cell>
	<cell></cell>
	<cell></cell>
	<cell></cell>
	<cell><![CDATA[ <b><?php echo $CalcTotal.'/'.$err_flk; ?></b> ]]></cell>
	<cell><![CDATA[ <b><?php echo $CalcEr1; ?></b> ]]></cell>
	<cell><![CDATA[ <b><?php echo $CalcEr2; ?></b> ]]></cell>
	<cell><![CDATA[ <b><?php echo $CalcEr3; ?></b> ]]></cell>
	<cell></cell>
	<cell></cell>
</row>	

</rows>
<?php
// <![CDATA[ <b> echo $CalcPispr; </b> ]]>
//<![CDATA[ <b> echo $CalcPobr; </b> ]]>
//	<cell><![CDATA[ <b><?php echo $CalcErN; </b> ]]></cell>
//	<cell><![CDATA[ <b><?php echo $CalcEr0; </b> ]]></cell>
//    <cell><?php echo $row['ER0']</cell>
//    <cell><?php echo $row['ER1']</cell>
*/


@oci_free_statement($stid);
//@oci_close($connLOC);
DisconnectOCI( array($cLocalTIR) );
?>