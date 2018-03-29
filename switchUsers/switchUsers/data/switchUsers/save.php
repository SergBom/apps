<?php
$isCLI = ( php_sapi_name() == 'cli' );
$_include_path = ($isCLI) ? "/var/www/portal/public_html" : $_SERVER['DOCUMENT_ROOT'];
include_once($_include_path."/php/init2.php");
include_once($_include_path."/php/ldap/ldap-func2.php");
header('Content-type: text/html; charset=utf-8');

//print_r($_POST);

/************************************************************************/

$off = ( isset($_POST['off']) ) ? true : false;
$say = ( isset($_POST['say']) ) ? 1 : 0;
$dOff = ( $_POST['dateOff'] ) ? "'".$_POST['dateOff']."'" : "NULL";
$dOn  = ( $_POST['dateOn'] )  ? "'".$_POST['dateOn']."'"  : "NULL";

$user_data = $_POST;

/************************************************************************/


	$dbo = ConnectPDO('Portal');


		///////////////////////////////////////////////////////////////
		// Берем исходные данные пользователя
		$user_data_old	= $dbo->query( "select * FROM v_pbook where id='u-{$_POST['id']}'" )->fetch();
		
		

$sql = "UPDATE `prt#users` SET 
	userFm = '{$_POST['userFm']}',
	userIm = '{$_POST['userIm']}',
	userOt = '{$_POST['userOt']}',
	otdel_id = '{$_POST['otdel_id']}',
	dolzhnost_id = '{$_POST['dolzhnost_id']}',
	refer = '{$_POST['refer']}',
	say = '$say',
	dateOff = $dOff,
	dateOn = $dOn
	WHERE id='{$_POST['id']}'
";

	$msg = "$sql<br>";

	$dbo->query($sql);

	user_update2ad($_POST['id'],$user_data_old);

	

echo json_encode( array("success"=>"true","msg"=>$msg));


///////////////////////////////
//
// SELECT username, account_status, lock_date, EXPIRY_DATE, CREATED FROM dba_users WHERE account_status = 'OPEN'
//
// account_status: 
// OPEN
// EXPIRED
// LOCKED
// LOCKED(TIMED)
// EXPIRED & LOCKED(TIMED)
// EXPIRED & LOCKED
?>