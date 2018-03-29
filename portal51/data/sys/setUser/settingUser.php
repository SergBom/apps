<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/ldap/ldap-func.php");
header('Content-type: text/html; charset=utf-8');
//if ( is_session_started() === FALSE ) session_start();

/*-------------------------- Входные переменные -----------------------------*/
    $db = ConnectMyDB('portal');
/*---------------------------------------------------------------------------*/

$a = (object)$_POST;

if( $a->status=='save' ) {

//print_r($_POST);

$_address   	= $db->getOne( "select name 	FROM `portal`.`kl#address` 		where id = '{$a->address_id}'" );


	////////////////////////////
	// Информация о пользователе до изменения
	$result = $db->query("SELECT * FROM `prt#v#users_full` WHERE id={$_SESSION['portal']['user_id']}");
	$r_old = $result->fetch_assoc();

	////////////////////////////
	// Определяем входные параметры
	$say 	= (isset($a->say)) ?  "say = 1," : "";
	$org_id = (isset($a->org_id)) ?  "org_id = {$a->org_id}," : "";
	$otdel_id 	= (isset($a->otdel_id)) ?  "otdel_id = {$a->otdel_id}," : "";
	$dolzhnost_id = (isset($a->dolzhnost_id)) ?  "dolzhnost_id = {$a->dolzhnost_id}," : "";
	
	
	
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
	$ad_conn  = ad_connect($_SESSION['portal']['domain_name']);
	
	if( $ad_conn['connect'] ){
	//if( false ){
		
		$ldapBind = ldap_bind( $ad_conn['ds'], $ad_conn['ad_admin'], $ad_conn['ad_pass']); 

		////////////////////////////
		// Информация о пользователе после изменения
  		$result = $db->query("SELECT * FROM `prt#v#users_full` WHERE id={$_SESSION['portal']['user_id']}");
		$r_new = $result->fetch_assoc();
	
		////////////////////////////
		// Модифицируем Информацию о пользователе в АД
			$entry = array();
			//$entry['cn']=$r_new['FIO'];
			$entry['displayname']						=	$r_new['FIO'];
			if($r_new['userFm']){$entry['sn']			=	"{$r_new['userFm']}";}
			if($r_new['userIm']){$entry['givenname']	=	"{$r_new['userIm']}";}
			if($r_new['userOt']){$entry['initials']		= 	mb_substr( "{$r_new['userOt']}",0,1,"UTF-8" ); }
			if($r_new['tel1'])  {$entry['telephoneNumber']	=	"{$r_new['tel1']}";}
			if($r_new['telIP']) {$entry['ipPhone']			=	"{$r_new['telIP']}";}
			if($r_new['email']) {$entry['mail']				=	"{$r_new['email']}";}
			if($r_new['tel2'])  {$entry['mobile']			=	"{$r_new['tel2']}";}
			$entry['facsimiletelephonenumber']				=	"888999000";
			$entry['info']		=	"Информация, заметки";
			$entry['st']		=	'Мурманская область'; // - Область
			if($_address)				{$entry['streetAddress']=	"$_address";}
			if($r_new['org_name'])		{$entry['company']		=	"{$r_new['org_name']}";}
			if($r_new['otdel_name'])	{$entry['department']	=	"{$r_new['otdel_name']}";}
			if($r_new['dolzhnost_name']){$entry['title']		=	"{$r_new['dolzhnost_name']}";}
			//$entry['homephone']="";
			//$entry['pager']="";
			//$entry['postalCode']="";
			//$entry['l']=''; // - город
			//print_r($entry);
	
			$ldapBind = ldap_bind( $ad_conn['ds'], $ad_conn['ad_admin'], $ad_conn['ad_pass']); 
			ldap_modify($ad_conn['ds'], $r_old['dn'], $entry);

		////////////////////////////
		// Изменяем группу пользователя
/*		$entry = array();
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
		*/
			//////////////////////////////////
			// Обновление отдела
			// 
			// Исходный DN пользователя
			//echo "<br>{$r_old['dn']}<br>";
			$newRdn = user_dn_cn($r_old['dn']);
			//echo "$newRdn<br>";  
			$newParent	= $db->getOne( "select dn FROM `prt#otdels`	where id = '{$a->otdel_id}'" );
			//echo "$newParent<br>";  
			ldap_rename($ad_conn['ds'], $r_old['dn'], $newRdn, $newParent, true);
			$newDN = $newRdn .",". $newParent;
			$db->query("UPDATE `prt#users` SET	dn='$newDN'	WHERE id='{{$_SESSION['portal']['user_id']}}'");
//			echo "$sql<br>";
		
		@ldap_unbind($ad_conn['ds']);

	}

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