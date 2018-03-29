<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/ldap/ldap-func.php");
header('Content-type: text/html; charset=utf-8');
if ( is_session_started() === FALSE ) session_start();

/*-------------------------- Входные переменные -----------------------------*/
    $db = ConnectMyDB('portal');
/*---------------------------------------------------------------------------*/

//$_SESSION['portal']['user_id'] = $_SESSION['user_id'];
//$_SESSION['portal']['username'] = $_SESSION['username'];


$a = (object)$_POST;

print_r($a);

$say = isset( $a->say ) ? 1 : 0;
$id = explode('-',$a->id);
$org_id = (isset($a->org_id)) ?  "org_id = {$a->org_id}," : "";

$_dolzhnost 	= $db->getOne( "select name 	FROM `portal`.`prt#dolzhnost`  	where id = '{$_POST['dolzhnost_id']}'" );
$_otdel 		= $db->getOne( "select name  	FROM `portal`.`prt#otdels` 	 	where id = '{$_POST['otdel_id']}'" );
$_address   	= $db->getOne( "select name 	FROM `portal`.`kl#address` 		where id = '{$_POST['address_id']}'" );

print_r($id);


if( $id[1] == '0' ) {  // *********** Новая запись
	
	echo "*********** Новая запись<br>";
	
} else {			 // *********** Обновляем запись
	echo "*********** Обновляем запись<br>";

	if($id[0]=='u'){		// *** Таблица Users

		$result = $db->query("select u.dn user_dn, concat(u.userFm,' ',u.userIm,' ',u.userOt) FIO, u.userFm,u.userIm,u.userOt,u.tel1, u.tel2, u.telIP, u.email,
		  o1.dn as otdel_dn from `prt#users` u left join `prt#otdels` o1 on o1.id=u.otdel_id where u.id={$id[1]}");
		$r_old = $result->fetch_assoc();

	
		$sql = "UPDATE `prt#users` SET
			userFm = '".trim($a->userFm)."',
			userIm = '".trim($a->userIm)."',
			userOt = '".trim($a->userOt)."',
			tel1 = '".trim($a->tel1)."',
			tel2 = '".trim($a->tel2)."',
			telIP = '".trim($a->telIP)."',
			email = '".trim($a->email)."',
			dolzhnost_id = {$a->dolzhnost_id},
			$org_id 
			otdel_id = {$a->otdel_id},
			address_id = {$a->address_id}
	
			WHERE id='{$id[1]}'";
			echo "$sql<br>";
		//$result = $db->query($sql);

		$result = $db->query("select u.dn user_dn, concat(u.userFm,' ',u.userIm,' ',u.userOt) FIO, u.userFm,u.userIm,u.userOt,u.tel1, u.tel2, u.telIP, u.email,
		  o1.dn as otdel_dn from `prt#users` u left join `prt#otdels` o1 on o1.id=u.otdel_id where u.id={$id[1]}");
		$r_new = $result->fetch_assoc();
	
	$entry = array();
	//$entry['cn']=$r_new['FIO'];
	$entry['displayname']=$r_new['FIO'];
	if($r_new['userIm']) {$entry['givenname']="'{$r_new['userIm']}'";}
	if($r_new['tel1']) {$entry['telephoneNumber']="'{$r_new['tel1']}'";}
	if($r_new['telIP']){$entry['ipPhone']="{$r_new['telIP']}'";}
	if($r_new['email']){$entry['mail']="{$r_new['email']}";}
	if($r_new['tel2']) {$entry['mobile']="{$r_new['tel2']}";}


	//$entry['homephone']="";
	//$entry['facsimiletelephonenumber']="";
	//$entry['homephone']="";
	//company
	$entry['department']="'{$_otdel}'";
	$entry['title']="'{$_dolzhnost}'";
	//title
	print_r($entry);
	//echo $r_new['user_dn'];
	//ldap_modify($ad_conn['ds'], $r_new['user_dn'], $entry);
	
	
	
	
	} else if($id[0]=='p'){	// *** Таблица PBook
		
	}
	
}

/*
	$result = $db->query("select u.dn user_dn, concat(u.userFm,' ',u.userIm,' ',u.userOt) FIO, u.tel1, u.tel2, u.telIP, u.email,
		  o1.dn as otdel_dn from `prt#users` u left join `prt#otdels` o1 on o1.id=u.otdel_id where u.id={$_SESSION['portal']['user_id']}");
	$r_old = $result->fetch_assoc();
	
	$sql = "UPDATE `prt#users` SET
			userFm = '".trim($a->userFm)."',
			userIm = '".trim($a->userIm)."',
			userOt = '".trim($a->userOt)."',
			tel1 = '".trim($a->tel1)."',
			tel2 = '".trim($a->tel2)."',
			telIP = '".trim($a->telIP)."',
			email = '".trim($a->email)."',
			dolzhnost_id = {$a->dolzhnost_id},
			$org_id 
			otdel_id = {$a->otdel_id},
			address_id = {$a->address_id}
	
			WHERE id='{$_SESSION['portal']['user_id']}'";
	$result = $db->query($sql);
	
///////////// Модифицируем АД ///////////////
	$ad_conn  = ad_connect($_SESSION['portal']['domain_name']);
	$ldapBind = ldap_bind( $ad_conn['ds'], $ad_conn['ad_admin'], $ad_conn['ad_pass']); 

	$result = $db->query("select u.dn user_dn, concat(u.userFm,' ',u.userIm,' ',u.userOt) FIO, u.tel1, u.tel2, u.telIP, u.email,
		  o1.dn as otdel_dn from `prt#users` u left join `prt#otdels` o1 on o1.id=u.otdel_id where u.id={$_SESSION['portal']['user_id']}");
	$r_new = $result->fetch_assoc();
	
	
	
//cn
$entry = array();
//$entry['cn']=$r_new['FIO'];
$entry['displayname']=$r_new['FIO'];
if($r_new['tel1']){$entry['telephoneNumber']="{$r_new['tel1']}";}
if($r_new['telIP']){$entry['ipPhone']="{$r_new['telIP']}";}
if($r_new['email']){$entry['mail']="{$r_new['email']}";}
if($r_new['tel2']){$entry['mobile']="{$r_new['tel2']}";}


//$entry['homephone']="";
//$entry['facsimiletelephonenumber']="";
//$entry['homephone']="";
//company
//department
//title
//print_r($entry);
//echo $r_new['user_dn'];
ldap_modify($ad_conn['ds'], $r_new['user_dn'], $entry);


$entry = array();
$entry['member'] = $r_new['user_dn'];

if($r_old['otdel_dn']){
	//echo $r_old['otdel_dn']."<br>";
	@ldap_mod_del($ad_conn['ds'], $r_old['otdel_dn'], $entry);
}

if($r_new['otdel_dn']){
	//echo $r_old['otdel_dn']."<br>";
	@ldap_mod_add($ad_conn['ds'], $r_new['otdel_dn'], $entry);
}

//echo group_from_dn($r_old['otdel_dn'])."<br>";
//echo group_from_dn($r_new['otdel_dn'])."<br>";


	
////////////////////////////	
	
/*
	$sql = "UPDATE `PhonesBook`.`Users` SET
			Fm = '".trim($_POST['userFm'])."',
			Im = '".trim($_POST['userIm'])."',
			Ot = '".trim($_POST['userOt'])."',
			Tel1 = '".trim($_POST['Tel1'])."',
			Tel2 = '".trim($_POST['Tel2'])."',
			TelIP = '".trim($_POST['TelIP'])."',
			email = '".trim($_POST['email'])."',
			Dolzhnost_ID = '$_dolzhnost_id',
			Otdel_ID = '$_otdel_id',
			Adr_ID = '$_address_id'
			$org_id_2

			WHERE sAMAccountName='{$_SESSION['portal']['username']}'";
	//$result = $db->query($sql);
	*/
	
	/*

	$result = $db->query("select * from `prt#v#users_full` WHERE id='{$_SESSION['portal']['user_id']}'");
	$row = $result->fetch_assoc();
	
	//echo $db->numRows($result);
//	$row = $_POST;

	if( $row['userImageSrc']=='' ){
		// Определяем картинку по умолчанию - Мальчик или Девочка  = /resources/user_images/people
		if(	preg_match("/ич$/i", trim($row['userOt']) ) ) {
			$row['userImageSrc'] = "/resources/images/people/man_brown.png";
		} else if( preg_match("/на$/i", trim($row['userOt']) ) ) {
			$row['userImageSrc'] = "/resources/images/people/user_female2.png";
		} else {
			$row['userImageSrc'] = "/resources/images/people/royal_user.png";
		}
	}

	$c = array('success'=>'true',"data"=>$row); 
	echo json_encode($c, JSON_FORCE_OBJECT);
	
} else {

	$result = array();

	//$result = $db->query("select * from `prt#v#users2` WHERE id='{$_SESSION['portal']['user_id']}'");
	$result = $db->query("select * from `prt#v#users_full` WHERE id='{$_SESSION['portal']['user_id']}'");
	$row = $result->fetch_assoc();
	
//	echo "row['userImageSrc']='".$row['userImageSrc']."'     Ot=".$row['userOt'];

	if( $row['userImageSrc']=='' ){
		// Определяем картинку по умолчанию - Мальчик или Девочка  = /resources/user_images/people
		if(	preg_match("/ич$/i", trim($row['userOt']) ) ) {
			$row['userImageSrc'] = "/resources/images/people/man_brown.png";
		} else if( preg_match("/на$/i", trim($row['userOt']) ) ) {
			$row['userImageSrc'] = "/resources/images/people/user_female2.png";
		} else {
			$row['userImageSrc'] = "/resources/images/people/royal_user.png";
		}
	}

	$c = array('success'=>'true',"data"=>$row);
	echo json_encode($c, JSON_FORCE_OBJECT);
}
*/
?>