<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

$linkPVD = ConnectOciDB('PVDmy');

//	print_r($linkPVD);

//**********************************//
// �������� ��� ������ � �������� �� ������
// ������� ��� ������ � ���� ����

	$st = oci_sql_exec($linkPVD, "SELECT * FROM T\$TLINK");
	$port = 1521;

	while ($row = oci_fetch_assoc($st)) {


	}
	
@oci_close($linkPVD);	
?>
