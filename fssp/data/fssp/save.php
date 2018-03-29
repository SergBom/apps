<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/

    $db = ConnectMyDB('FSSP');

/*---------------------------------------------------------------------------*/


$doc_double = ($_POST['DoubleInput']) ? $_POST['DoubleInput'] : 0;
$doc_tech = ($_POST['TechInput']) ? $_POST['TechInput'] : 0;
$doc_otkaz = ($_POST['OtkazInput']) ? $_POST['OtkazInput'] : 0;
$doc_stop = ($_POST['StopInput']) ? $_POST['StopInput'] : 0;


$result = array();

$sql = "SELECT count(*) as cnt FROM fssp WHERE otdel={$_POST['OtdelInput']} AND date='". substr($_POST['DateInput'],0,10) ."'";

if ( $db->getOne($sql) == 0 ) { //Если такой записи не найдено, то добавляем новую
	$sql = "INSERT INTO fssp SET date=?s,doc_in=?i,doc_reg=?i,otdel=?i,doc_double=?i,doc_tech=?i,doc_otkaz=?i,doc_stop=?i";
	$db->query($sql,substr($_POST['DateInput'],0,10),$_POST['DocsInput'],$_POST['RegsInput'],$_POST['OtdelInput'],$doc_double,$doc_tech,$doc_otkaz,$doc_stop);

	$result['success'] = true;
	$result['msg'] = 'Данные успешно добавлены!';

} else {//Если такая запись существует, то обновляем ее
	$sql = "UPDATE fssp SET doc_in=?i,doc_reg=?i,doc_double=?i,doc_tech=?i,doc_otkaz=?i,doc_stop=?i WHERE date=?s AND otdel=?i";
	$db->query($sql,$_POST['DocsInput'],$_POST['RegsInput'],$doc_double,$doc_tech,$doc_otkaz,$doc_stop,substr($_POST['DateInput'],0,10),$_POST['OtdelInput']);
	
	$result['success'] = true;
	$result['msg'] = 'Данные обновлены!';
}	
echo json_encode($result);

?>