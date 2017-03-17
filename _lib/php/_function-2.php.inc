<?php

/////////////////////////////////////////////////////////////////
// Проверяет наличие Юзера в локальной базе
// Возвращает:
// 0 - Авторизация не пройдена - Удаляет сессию
// 1 - Авторизацию в локальной базе прошел
// 2 - 
function checkLogin($username, $password){

	$link = ConnectLOCAL();
	$password = md5($password);
//	$query="SELECT count(*) cnt FROM users WHERE username='$username' and password='$password'";
	
	$result=mysql_query("SELECT id, password, dn FROM users WHERE username='$username'");
	$row = mysql_fetch_assoc($result);
	
	if(mysql_num_rows($result)>0){	//Запись в локальной базе существует
		if( empty($row['dn']) ){	//Значит пользователь локальный
			if($row['password']==$password){ // Авторризацию в локальной базе прошел
				$_SESSION['username'] = $username;

				$ret = 1;
			} else { //Авторизацию не прошел вообще
				session_unset();
				$ret = 0;
			}
		} else {	//Значит пользователь из АД
			//Ищем в АД
			echo "Значит пользователь из АД<br>";
			checkUserAD($username, $password);
		}
	} else { // Такого пользователя в локальной базе нет
		//Ищем в АД
			echo "Такого пользователя в локальной базе нет<br>";
			checkUserAD($username, $password);
	
	}
	mysql_close($link);
	return $ret;
}

/////////////////////////////////////////////////////////////////
// Проверяет наличие Юзера в АД
// Возвращает:
// 0 - Авторизация не пройдена - Удаляет сессию
// 1 - Авторизацию в локальной базе прошел
// 2 - 
function checkUserAD($username, $password)
{
	global $_SB_cfg;

		////////////////////////////////////// Соединяемся с ЛДАП
		$admin_login	= $_SB_cfg['LDAPusername'][1] . $_SB_cfg['account_suffix'][1];
		$admin_passwd	= $_SB_cfg['LDAPpassword'][1];
		$server = $_SB_cfg['domain_controllers'][1][0];
	
		$ldapBase =  $_SB_cfg['base_dn'][1];
//	echo "ldapBase = $ldapBase<br>";
		$ds = ldap_connect($server);
		if (!$ds) { echo 'Cannot Connect to LDAP server<br>'; return false;}
		$ldapBind = ldap_bind($ds,$admin_login,$admin_passwd);
		if (!$ldapBind) { echo 'Cannot Bind to LDAP server<br>'; return false;}
		ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);


		
		
		
}

///////////////////////////////////////////////////////////////////
// Возвращает Заголовок сайта
function getPortalName(){
	global $_SB_cfg, $_dbLocal;
	return $_dbLocal->getOne("SELECT value FROM config WHERE env='portal_title'");
}

function getPortalEnv($env){
	global $_SB_cfg, $_dbLocal;
	return $_dbLocal->getOne("SELECT value FROM config WHERE env='$env'");
}


?>