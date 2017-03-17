<?php
defined('_SB_CFG') or die;

/*******************************************************************
	Конфигурационный файл настроек для работы с ТИР
*/	

/**** Для подключения к базе ТИР Управления Росреестра ****/
$_SB_cfg['TIRconnectLogin']		= "tir";
$_SB_cfg['TIRconnectPassword']	= "TEST";
$_SB_cfg['TIRconnectServer']	= "10.51.119.230:1521/R51TIR1";
$_SB_cfg['TIRconnectCodePage']	= "AL32UTF8";

/*$_SB_cfg['SSDconnectLogin']		= "reg_rt";
$_SB_cfg['SSDconnectPassword']	= "reg";
$_SB_cfg['SSDconnectServer']	= "10.51.119.1:1521/P51SSD";
$_SB_cfg['SSDconnectCodePage']	= "AL32UTF8";
*/
$_SB_cfg['LocalTIRconnectLogin']	= "tir";
$_SB_cfg['LocalTIRconnectPassword']	= "test";
$_SB_cfg['LocalTIRconnectServer']	= "10.51.119.241:1521/tir.murmansk.net";
$_SB_cfg['LocalTIRconnectCodePage']	= "AL32UTF8";

$_SB_cfg['LocalPVDconnectLogin']	= "pvd";
$_SB_cfg['LocalPVDconnectPassword']	= "pvd";
$_SB_cfg['LocalPVDconnectServer']	= "10.51.119.241:1521/tir.murmansk.net";
$_SB_cfg['LocalPVDconnectCodePage']	= "AL32UTF8";

/****  ********************/
//$a_dept_id = array(0,1,2,3,4,5,6,7,8,9,10,11);
/****  ********************/
/*$dept_id = 0;	// SSD
$_SB_cfg['GRPconnectName'][$dept_id]		= "P51SSD";
$_SB_cfg['GRPconnectFullName'][$dept_id]	= "Сервер сбора данных";
$_SB_cfg['GRPconnectLogin'][$dept_id]		= "reg_rt";
$_SB_cfg['GRPconnectPassword'][$dept_id]	= "reg";
$_SB_cfg['GRPconnectServer'][$dept_id]		= "10.51.119.1:1521/P51SSD";
*/
/*$_SB_cfg['db']['EGRP'][0]['dept_id']		= 0;
$_SB_cfg['db']['EGRP'][0]['SID']			= "P51SSD";
$_SB_cfg['db']['EGRP'][0]['FullName']		= "Сервер сбора данных";
$_SB_cfg['db']['EGRP'][0]['login']			= "reg_rt";
$_SB_cfg['db']['EGRP'][0]['password']		= "reg";
$_SB_cfg['db']['EGRP'][0]['connect']		= "10.51.119.1:1521/".$_SB_cfg['db']['EGRP'][0]['SID'];




$dept_id = 1;	// MUR
$_SB_cfg['GRPconnectName'][$dept_id]		= "P51MUR";
$_SB_cfg['GRPconnectFullName'][$dept_id]	= "Сервер Мурманск";
$_SB_cfg['GRPconnectLogin'][$dept_id]		= "reg_rt";
$_SB_cfg['GRPconnectPassword'][$dept_id]	= "reg";
$_SB_cfg['GRPconnectServer'][$dept_id]		= "10.51.117.1:1521/P51MUR";

$dept_id = 2;	// KND
$_SB_cfg['GRPconnectName'][$dept_id]		= "P51KND";
$_SB_cfg['GRPconnectFullName'][$dept_id]	= "Сервер Кандалакша";
$_SB_cfg['GRPconnectLogin'][$dept_id]		= "reg_rt";
$_SB_cfg['GRPconnectPassword'][$dept_id]	= "reg";
$_SB_cfg['GRPconnectServer'][$dept_id]		= "10.51.19.1:1521/P51KND";

$dept_id = 3;	// APT
$_SB_cfg['GRPconnectName'][$dept_id]		= "P51APT";
$_SB_cfg['GRPconnectFullName'][$dept_id]	= "Сервер Апатиты";
$_SB_cfg['GRPconnectLogin'][$dept_id]		= "reg_rt";
$_SB_cfg['GRPconnectPassword'][$dept_id]	= "regrt";
$_SB_cfg['GRPconnectServer'][$dept_id]		= "10.51.14.1:1521/P51APT";

$dept_id = 4;	// KIR
$_SB_cfg['GRPconnectName'][$dept_id]		= "P51KIR";
$_SB_cfg['GRPconnectFullName'][$dept_id]	= "Сервер Кировск";
$_SB_cfg['GRPconnectLogin'][$dept_id]		= "reg_rt";
$_SB_cfg['GRPconnectPassword'][$dept_id]	= "reg";
$_SB_cfg['GRPconnectServer'][$dept_id]		= "10.51.22.1:1521/P51KIR";

$dept_id = 5;	// MON
$_SB_cfg['GRPconnectName'][$dept_id]		= "P51MON";
$_SB_cfg['GRPconnectFullName'][$dept_id]	= "Сервер Мончегорск";
$_SB_cfg['GRPconnectLogin'][$dept_id]		= "reg_rt";
$_SB_cfg['GRPconnectPassword'][$dept_id]	= "reg";
$_SB_cfg['GRPconnectServer'][$dept_id]		= "10.51.11.1:1521/P51MON";

$dept_id = 6;	// OLE
$_SB_cfg['GRPconnectName'][$dept_id]		= "P51OLE";
$_SB_cfg['GRPconnectFullName'][$dept_id]	= "Сервер Оленегорск";
$_SB_cfg['GRPconnectLogin'][$dept_id]		= "reg_rt";
$_SB_cfg['GRPconnectPassword'][$dept_id]	= "reg";
$_SB_cfg['GRPconnectServer'][$dept_id]		= "10.51.13.1:1521/P51OLE";

$dept_id = 7;	// ZAP
$_SB_cfg['GRPconnectName'][$dept_id]		= "P51ZAP";
$_SB_cfg['GRPconnectFullName'][$dept_id]	= "Сервер Заполярный";
$_SB_cfg['GRPconnectLogin'][$dept_id]		= "reg_rt";
$_SB_cfg['GRPconnectPassword'][$dept_id]	= "reg";
$_SB_cfg['GRPconnectServer'][$dept_id]		= "10.51.0.1:1521/P51ZAP";

$dept_id = 8;	// POL
$_SB_cfg['GRPconnectName'][$dept_id]		= "P51POL";
$_SB_cfg['GRPconnectFullName'][$dept_id]	= "Сервер Полярный";
$_SB_cfg['GRPconnectLogin'][$dept_id]		= "reg_rt";
$_SB_cfg['GRPconnectPassword'][$dept_id]	= "reg";
$_SB_cfg['GRPconnectServer'][$dept_id]		= "10.51.8.1:1521/P51POL";

$dept_id = 9;	// SEV
$_SB_cfg['GRPconnectName'][$dept_id]		= "P51SEV";
$_SB_cfg['GRPconnectFullName'][$dept_id]	= "Сервер Североморск";
$_SB_cfg['GRPconnectLogin'][$dept_id]		= "reg_rt";
$_SB_cfg['GRPconnectPassword'][$dept_id]	= "reg";
$_SB_cfg['GRPconnectServer'][$dept_id]		= "10.51.6.1:1521/P51SEV";

$dept_id = 10;	// KOL
$_SB_cfg['GRPconnectName'][$dept_id]		= "P51KOL";
$_SB_cfg['GRPconnectFullName'][$dept_id]	= "Сервер Кола";
$_SB_cfg['GRPconnectLogin'][$dept_id]		= "reg_rt";
$_SB_cfg['GRPconnectPassword'][$dept_id]	= "reg";
$_SB_cfg['GRPconnectServer'][$dept_id]		= "10.51.26.1:1521/P51KOL";

$dept_id = 11;	// KVD
$_SB_cfg['GRPconnectName'][$dept_id]		= "P51KVD";
$_SB_cfg['GRPconnectFullName'][$dept_id]	= "Сервер Ковдор";
$_SB_cfg['GRPconnectLogin'][$dept_id]		= "reg_rt";
$_SB_cfg['GRPconnectPassword'][$dept_id]	= "reg";
$_SB_cfg['GRPconnectServer'][$dept_id]		= "10.51.5.1:1521/P51KVD";
*/
/*******************************************************************
	Конфигурационный файл настроек для работы с Локальной базой MySQL
*/	
$_SB_cfg['LocalConnectLogin']		= "root";
$_SB_cfg['LocalConnectPassword']	= "javascript";
$_SB_cfg['LocalConnectServer']		= "10.51.119.244";
$_SB_cfg['LocalConnectDatabase']	= "portal";			// База данных JOOMLA
//$_SB_cfg['LocalConnectDatabase']	= "prt";			// База данных JOOMLA
//$_SB_cfg['LocalConnectPrefixDB']	= "d2_";			// Префикс таблиц в базе данных JOOMLA



$_SB_cfg['PortalLogin']		= "root";
$_SB_cfg['PortalPassword']	= "javascript";
$_SB_cfg['PortalServer']	= "10.51.119.244";
$_SB_cfg['PortalDatabase']	= "portal";			// База данных JOOMLA



$_SB_cfg['PhonesBookDB']		= "PhonesBook";
$_SB_cfg['EGRPDB']				= "EGRP";
$_SB_cfg['KladrDB']				= "Kladr";
$_SB_cfg['PortalDatabase']		= "Portal";

////// Настройка LDAP серверов //////
$_SB_cfg['domain'][1]			= "murmansk.net";
$_SB_cfg['domain_controllers'][1]	= array("10.51.115.1");
$_SB_cfg['LDAPusername'][1]		= "ldap";
$_SB_cfg['LDAPpassword'][1]		= "ldap";
$_SB_cfg['base_dn'][1]		= "DC=murmansk,DC=net";
$_SB_cfg['account_suffix'][1]	= "@murmansk.net";
$_SB_cfg['LDAPusers_three'][1]	= "Murmansk"; // Корень каталога, где находятся группы и пользователи OU=Murmansk

$_SB_cfg['domain'][2]			= "r51u.local";
$_SB_cfg['domain_controllers'][2]	= array("10.51.119.204");
$_SB_cfg['LDAPusername'][2]		= "ldap";
$_SB_cfg['LDAPpassword'][2]		= "ldapldap";
$_SB_cfg['base_dn'][2]		= "DC=r51u,DC=local";
$_SB_cfg['account_suffix'][2]	= "@r51.local";
$_SB_cfg['LDAPusers_three'][2]	= "Upr51";
/*********************************/


?>