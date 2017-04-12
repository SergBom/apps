<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/ldap/ldap-func.php");
header('Content-type: text/html; charset=utf-8');

/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('portal');
/*---------------------------------------------------------------------------*/

//$domain = $adata->domain; //"MURMANSK.NET";
$domain = "MURMANSK.NET";
//$domain = "локально";
//$domain = "KP51.LOCAL";

$domain_id	= $db->getOne( "select id FROM `prt#domains`	where name = '$domain'" );

/*---------------------------------------------------------------------------*/
	$adata = $_GET; //(object)$_GET;


	
	$ad_conn  = ad_connect($domain);
	$ldapBind = ldap_bind( $ad_conn['ds'], $ad_conn['ad_admin'], $ad_conn['ad_pass']); 


	if ( $ldapBind ){ // Авторизация прошла успешно
	
////////////////////////////////////////////////////////////////////////	
////////////////////////////////////////////////////////////////////////	
//////////  Обновляем Пользователей	
////////////////////////////////////////////////////////////////////////		
	
			//----- Поисковый контейнер пользователя
			//$ldapBase[0] = "OU=Murmansk,".$ad_conn['base'];
			//$ldapBase[1] = "OU=User_U51,".$ad_conn['base'];
	//		$ldapBase =  $ad_conn['user_dn'] .",". $ad_conn['base'];

	$dn = 'CN=Тютина Оксана Н.,OU=_Otdel_AHO,OU=U51_USERS,DC=murmansk,DC=net';
	$newRdn = 'CN=Тютина Оксана Н.';

	$newParent = 'OU=_Otdel_AHO_Org,OU=_Otdel_AHO,OU=U51_USERS,DC=murmansk,DC=net';
	ldap_rename($ad_conn['ds'], $dn, $newRdn, $newParent, true);
			
	}
?>