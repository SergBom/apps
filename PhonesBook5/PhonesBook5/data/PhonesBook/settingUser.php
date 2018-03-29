<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/ldap/ldap-func.php");
header('Content-type: text/html; charset=utf-8');
if ( is_session_started() === FALSE ) session_start();

/*-------------------------- Входные переменные -----------------------------*/
    $db = ConnectMyDB('portal');
/*---------------------------------------------------------------------------*/

$a = (object)$_POST;

print_r($a);

$say = isset( $a->say ) ? 1 : 0;
$say = ( $a->otdel_id ) ? $say : 0;
$id	= explode('-',$a->id);
$org_id = (isset($a->org_id)) ?  "org_id = {$a->org_id}," : "";
$_address   	= $db->getOne( "select name 	FROM `portal`.`kl#address` 		where id = '{$a->address_id}'" );

print_r($say);


if( $id[1] == '0' ) {  // *********** Новая запись - Только таблица PBook
	
	echo "*********** Новая запись<br>";
		$sql = "INSERT INTO `prt#pbook` SET
			userFm = '".trim($a->userFm)."',
			userIm = '".trim($a->userIm)."',
			userOt = '".trim($a->userOt)."',
			tel1 = '".trim($a->tel1)."',
			tel2 = '".trim($a->tel2)."',
			telIP = '".trim($a->telIP)."',
			email = '".trim($a->email)."',
			dolzhnost_id = '{$a->dolzhnost_id}',
			say=$say,
			org_id = {$a->org_id_h},
			otdel_id = '{$a->otdel_id}',
			address_id = '{$a->address_id}'";
	
	echo "$sql<br>";
		$result = $db->query($sql);
	
	
	
	
} else {			 // *********** Обновляем запись
	echo "*********** Обновляем запись<br>";

	if($id[0]=='u'){		// *** Таблица Users
	
		///////////////////////////////////////////////////////////////
		// Берем исходные данные пользователя
		$result = $db->query("SELECT * FROM `prt#v#users_full` WHERE id={$id[1]}");
		$r_old = $result->fetch_assoc();
//print_r($r_old);
		
	
		$sql = "UPDATE `prt#users` SET
			userFm = '".trim($a->userFm)."',
			userIm = '".trim($a->userIm)."',
			userOt = '".trim($a->userOt)."',
			tel1 = '".trim($a->tel1)."',
			tel2 = '".trim($a->tel2)."',
			telIP = '".trim($a->telIP)."',
			email = '".trim($a->email)."',
			refer = '".trim($a->refer)."',
			dolzhnost_id = '{$a->dolzhnost_id}',
			say=$say,
			$org_id 
			otdel_id = '{$a->otdel_id}',
			address_id = '{$a->address_id}'
	
			WHERE id='{$id[1]}'";
			echo "$sql<br>";
		$result = $db->query($sql);

		$result = $db->query("SELECT * FROM `prt#v#users_full` WHERE id={$id[1]}");
		$r_new = $result->fetch_assoc();

//print_r($r_new);
		
		///////////////////////////////////////////
		// Обновляем информацию в АД
		$domain	= $db->getOne( "select name FROM `prt#domains`	where id = '{$r_new['domain_id']}'" );
		$ad_conn = ad_connect($domain);
		if( $ad_conn['connect'] ){
		
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
			//$entry['info']		=	"Информация, заметки";
			$entry['st']		=	'Мурманская область'; // - Область
			if($_address)				{$entry['streetAddress']=	"$_address";}
			if($r_new['org_name'])		{$entry['company']		=	"{$r_new['org_name']}";}
			if($r_new['otdel_name'])	{$entry['department']	=	"{$r_new['otdel_name']}";}
			if($r_new['dolzhnost_name']){$entry['title']		=	"{$r_new['dolzhnost_name']}";}
			//$entry['homephone']="";
			//$entry['pager']="";
			//$entry['postalCode']="";
			//$entry['l']=''; // - город
			print_r($entry);
	
			$ldapBind = ldap_bind( $ad_conn['ds'], $ad_conn['ad_admin'], $ad_conn['ad_pass']); 
			ldap_modify($ad_conn['ds'], $r_old['dn'], $entry);
			
			//////////////////////////////////
			// Обновление отдела
			// 
			// Исходный DN пользователя
//			echo "<br>{$r_old['dn']}<br>";
			$newRdn = user_dn_cn($r_old['dn']);
//			echo "$newRdn<br>";  
			$newParent	= $db->getOne( "select dn FROM `prt#otdels`	where id = '{$a->otdel_id}'" );
//			echo "$newParent<br>";  
			//$newParent = 'OU=_Otdel_AHO_Org,OU=_Otdel_AHO,OU=U51_USERS,DC=murmansk,DC=net';
			ldap_rename($ad_conn['ds'], $r_old['dn'], $newRdn, $newParent, true);
			$newDN = $newRdn .",". $newParent;
			$db->query("UPDATE `prt#users` SET	dn='$newDN'	WHERE id='{$id[1]}'");
//			echo "$sql<br>";
			

			
			
			@ldap_unbind($ad_conn['ds']);
		}
	
	
	
	} else if($id[0]=='p'){	// *** Таблица PBook
	
	
		$sql = "UPDATE `prt#pbook` SET
			userFm = '".trim($a->userFm)."',
			userIm = '".trim($a->userIm)."',
			userOt = '".trim($a->userOt)."',
			tel1 = '".trim($a->tel1)."',
			tel2 = '".trim($a->tel2)."',
			telIP = '".trim($a->telIP)."',
			email = '".trim($a->email)."',
			refer = '".trim($a->refer)."',
			dolzhnost_id = '{$a->dolzhnost_id}',
			say=$say,
			org_id = {$a->org_id_h},
			otdel_id = '{$a->otdel_id}',
			address_id = '{$a->address_id}'
			WHERE id='{$id[1]}'";
	
	echo "$sql<br>";
		$result = $db->query($sql);
	
	
		
	}
	
}

?>