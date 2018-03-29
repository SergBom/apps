<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/ldap/ldap-func.php");
header('Content-type: text/html; charset=utf-8');
//if ( is_session_started() === FALSE ) session_start();

/*-------------------------- Входные переменные -----------------------------*/
    $db = ConnectMyDB('portal');
/*---------------------------------------------------------------------------*/

$a = (object)$_POST;
print_r($_POST);

if( $a->status=='save' ) {

//print_r($_POST);

//	$_dolzhnost_id 	= $db->getOne( "select id   FROM `PhonesBook`.Dolzhnost  	where Name = '{$_POST['dolzhnost']}'" );
//	$_otdel_id 		= $db->getOne( "select id  	FROM `PhonesBook`.Otdels 	 	where Name = '{$_POST['otdel']}'" );
//	$_address_id 	= $db->getOne( "select id 	FROM `PhonesBook`.Address 		where Name = '{$_POST['address']}'" );
//	$org_id_1 = "";
//	$org_id_2 = "";

	////////////////////////////
	// Информация о пользователе до изменения
	$result = $db->query("select u.dn user_dn, concat(u.userFm,' ',u.userIm,' ',u.userOt) FIO, u.tel1, u.tel2, u.telIP, u.email,
		  o1.dn as otdel_dn from `prt#users` u left join `prt#otdels` o1 on o1.id=u.otdel_id where u.id={$_SESSION['portal']['user_id']}");
	$r_old = $result->fetch_assoc();

	////////////////////////////
	// Определяем входные параметры
	$say = (isset($a->say)) ?  "say = 1," : "";
	$org_id = (isset($a->org_id)) ?  "org_id = {$a->org_id}," : "";
	//$_otdel_id 		= $db->getOne( "select id  	FROM `PhonesBook`.Otdels 	 	where name = '{$a->otdel_id}'" );	
	//$otdel_id = (isset($a->otdel_id)) ?  "otdel_id = {$a->otdel_id}," : "";
	//$dolzhnost_id = (isset($a->dolzhnost_id)) ?  "dolzhnost_id = {$a->dolzhnost_id}," : "";
	
	

	if (isset($a->otdel_name)){
		$_otdel_id 	= $db->getOne( "select id  	FROM `prt#otdels`  	where name = '{$a->otdel_name}'" );	
		$otdel_id = "otdel_id = {$_otdel_id},";
	} else {
		$otdel_id = "";
	}
	
	if (isset($a->dolzhnost_name)){
		$_dolzhnost_id 	= $db->getOne( "select id   FROM `prt#dolzhnost` where name = '{$a->dolzhnost_name}'" );
		$dolzhnost_id = "dolzhnost_id = {$_dolzhnost_id},";
	} else {
		$dolzhnost_id = "";
	}
	
	
	
	
	$sql = "UPDATE `prt#users` SET
			userFm = '".trim($a->userFm)."',
			userIm = '".trim($a->userIm)."',
			userOt = '".trim($a->userOt)."',
			tel1 = '".trim($a->tel1)."',
			tel2 = '".trim($a->tel2)."',
			telIP = '".trim($a->telIP)."',
			email = '".trim($a->email)."',
			$say
			$dolzhnost_id
			$org_id 
			$otdel_id
			address_id = {$a->address_id}
	
			WHERE id='{$_SESSION['portal']['user_id']}'";
			
			//echo "$sql<br>";
	$result = $db->query($sql);
	
///////////// Модифицируем АД ///////////////
	//echo $_SESSION['portal']['domain_id']."<br>";
	//echo $_SESSION['portal']['domain_name']."<br>";
	$ad_conn  = ad_connect($_SESSION['portal']['domain_name']);
	
	if( $ad_conn['connect'] ){
	//if( false ){
		
		//echo $ad_conn['connect'];
		
		$ldapBind = ldap_bind( $ad_conn['ds'], $ad_conn['ad_admin'], $ad_conn['ad_pass']); 

		////////////////////////////
		// Информация о пользователе после изменения
		$result = $db->query("select u.dn user_dn, concat(u.userFm,' ',u.userIm,' ',u.userOt) FIO, u.tel1, u.tel2, u.telIP, u.email,
		  o1.dn as otdel_dn from `prt#users` u left join `prt#otdels` o1 on o1.id=u.otdel_id where u.id={$_SESSION['portal']['user_id']}");
		$r_new = $result->fetch_assoc();
	
	
		////////////////////////////
		// Модифицируем Информацию о пользователе в АД
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

		////////////////////////////
		// Изменяем группу пользователя
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
	}

	
		////////////////////////////	
	
	////////////////////////////
	// Выводим пользовательские данные
	data_out();
	
} else {  //////////////////// LOAD
	////////////////////////////
	// Выводим пользовательские данные
	data_out();

}

function data_out(){
	global $db;
	
	$result = $db->query("select * from `prt#v#users_full` WHERE id='{$_SESSION['portal']['user_id']}'");
	$row = $result->fetch_assoc();

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

?>