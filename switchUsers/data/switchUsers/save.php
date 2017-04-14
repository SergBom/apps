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

/************************************************************************/

//echo "say=$say _POST['say']={$_POST['say']}";

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

	$dbo = ConnectPDO('Portal');

	$dbo->query($sql);
	
	user_update2ad($_POST['id']);

echo json_encode( array("success"=>"true","msg"=>$msg));


/*	
	$rP = $db->query("SELECT userFm,userIm,userOt FROM `prt#users` WHERE id='$id'")->fetch();

/*	$dbora	= ConnectGRP();
	
	$sn = $rP['userFm'].mb_substr($rP['userIm'],0,1,'utf-8').".".mb_substr($rP['userOt'],0,1,'utf-8').".";

	$stdi = oci_parse($dbora,"SELECT id,name,short_name,username FROM rp_emps WHERE E_DATE is NULL AND replace(short_name,' ')='$sn'");
	oci_execute($stdi);
	
	$row = oci_fetch_assoc($stdi);

			echo json_encode( array(
				"success"=>"true",
				"data"=>$row
			));
*/
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