<?php
$isCLI = ( php_sapi_name() == 'cli' );
$_include_path = ($isCLI) ? "/var/www/portal/public_html" : $_SERVER['DOCUMENT_ROOT'];
include_once($_include_path."/php/init2.php");
include_once($_include_path."/php/ldap/ldap-func2.php");
header('Content-type: text/html; charset=utf-8');

//print_r($_POST);

/************************************************************************/

$user_id = $_POST['id'];

/************************************************************************/
///	1. Удаляем из Домена
/// 2. Удаляем из ЕГРП
/// 3. Удаляем из базы Портала
//////////


	$dbo		= ConnectPDO('Portal');

	$user = $dbo->query( "select * FROM `prt#users`	where id = '$user_id'" )->fetch();


/////////////////////////////////////////////
///	1. Удаляем из Домена
/////////////////////////////////////////////
		// Обновляем информацию в АД
		$domain	= $dbo->query( "select name FROM `prt#domains`	where id = '1'" )->fetchColumn();
		$ad_conn = ad_connect($domain);
		if( $ad_conn['connect'] ){
			
			$ldapBind = ldap_bind( $ad_conn['ds'], $ad_conn['ad_admin'], $ad_conn['ad_pass']); 
			@ldap_delete($ad_conn['ds'],$user['dn']);
$msg = "-- LDAP - deleted<br>";
		}


/////////////////////////////////////////////
/// 2. Удаляем из ЕГРП
/////////////////////////////////////////////

/*	$dbora	= ConnectGRP();
	$now = date('Y-m-d H:i:s');

	$sn = $user['userFm'].mb_substr($user['userIm'],0,1,'utf-8').".".mb_substr($user['userOt'],0,1,'utf-8').".";
	
	$stdi = oci_parse($dbora,"SELECT id,name,short_name,username FROM rp_emps WHERE E_DATE is NULL AND replace(short_name,' ')='$sn'");
	oci_execute($stdi);
	
	while(($row = oci_fetch_assoc($stdi)) != false ){
		//print_r( $row );
		$st = oci_parse($dbora,"DROP USER {$row['USERNAME']} CASCADE");
		@oci_execute($st);
$msg .= "---- EGRP User [{$row['USERNAME']}]- deleted<br>";
		
		$st = oci_parse($dbora,"UPDATE RP_EMPS SET E_DATE=TO_DATE('$now','YYYY-MM-DD HH24:MI:SS') WHERE ID={$row['ID']}");
		@oci_execute($st);
$msg .= "---- EGRP rp_emps ID:{$row['ID']} - modifyd<br>";
	}  
*/

		
/////////////////////////////////////////////
/// 3. Удаляем из базы Портала
/////////////////////////////////////////////
		
	$dbo->query("DELETE FROM `prt#users_ad_group`	WHERE user_id=$user_id");
$msg .= "------ Portal users_ad_group - deleted<br>";
	$dbo->query("UPDATE `prt#users_group`  SET deleted=1, e_date=NOW() WHERE user_id=$user_id");
$msg .= "------ Portal users_group - deleted<br>";
	$dbo->query("UPDATE `prt#users` SET deleted=1, e_date=NOW(), dateOff=NOW()	WHERE id=$user_id");
$msg .= "------ Portal users - deleted<br>";
		
		
		
	
	
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