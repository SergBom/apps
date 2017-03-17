<?php
defined('_SB_CFG') or die;

/*******************************************************************
	Конфигурационный файл настроек для работы с ТИР
*/	

$_db_name = 'PORTAL';
$_SB_cfg['connect'][$_db_name]['name'] = 'PORTAL';
$_SB_cfg['connect'][$_db_name]['dns'] = "mysql:host=10.51.119.244;dbname=portal;charset=utf8";
$_SB_cfg['connect'][$_db_name]['login'] = 'root';
$_SB_cfg['connect'][$_db_name]['password'] = '';
//$_SB_cfg['connect'][$_db_name]['charset'] = 'AL32UTF8';


$_db_name = 'DCVm';
$_SB_cfg['connect'][$_db_name]['name'] = 'DisputCadastralValue';
$_SB_cfg['connect'][$_db_name]['dns'] = "mysql:host=10.51.119.244;dbname=DisputCadastralValue;charset=utf8";
$_SB_cfg['connect'][$_db_name]['login'] = 'root';
$_SB_cfg['connect'][$_db_name]['password'] = '';

$_db_name = 'DCVp';
$_SB_cfg['connect'][$_db_name]['name'] = 'DisputCadastralValue';
$_SB_cfg['connect'][$_db_name]['dns'] = "pgsql:host=10.51.119.71;port=5432;dbname=DCV;charset=utf8";
$_SB_cfg['connect'][$_db_name]['login'] = 'postgres';
$_SB_cfg['connect'][$_db_name]['password'] = '';
// pgsql:host=localhost;port=5432;dbname=testdb;user=bruce;password=mypass

$_db_name = 'KLADR';
$_SB_cfg['connect'][$_db_name]['name'] = 'KLADR';
$_SB_cfg['connect'][$_db_name]['dns'] = "mysql:host=10.51.119.244;dbname=KLADR;charset=utf8";
$_SB_cfg['connect'][$_db_name]['login'] = 'root';
$_SB_cfg['connect'][$_db_name]['password'] = '';



$_db_name = 'TIR';
$_SB_cfg['connect'][$_db_name]['name'] = 'TIR';
$_SB_cfg['connect'][$_db_name]['dns'] = "10.51.119.230:1521/R51TIR1";
$_SB_cfg['connect'][$_db_name]['login'] = 'tir';
$_SB_cfg['connect'][$_db_name]['password'] = 'TEST';
$_SB_cfg['connect'][$_db_name]['charset'] = 'AL32UTF8';

$_db_name = 'TIR-MY';
$_SB_cfg['connect'][$_db_name]['name'] = 'TIR';
$_SB_cfg['connect'][$_db_name]['dns'] = "10.51.119.241:1521/tir.murmansk.net";
$_SB_cfg['connect'][$_db_name]['login'] = 'tir';
$_SB_cfg['connect'][$_db_name]['password'] = 'TEST';
$_SB_cfg['connect'][$_db_name]['charset'] = 'AL32UTF8';


/*******************************************************************
	Конфигурационный файл настроек для работы с Локальной базой MySQL
*/	
$_SB_cfg['LocalConnectLogin']		= "root";
$_SB_cfg['LocalConnectPassword']	= "javascript";
$_SB_cfg['LocalConnectServer']		= "10.51.119.244";
$_SB_cfg['LocalConnectDatabase']	= "portal";			// База данных JOOMLA
//$_SB_cfg['LocalConnectDatabase']	= "prt";			// База данных JOOMLA
//$_SB_cfg['LocalConnectPrefixDB']	= "d2_";			// Префикс таблиц в базе данных JOOMLA
/*********************************/


?>