<?php
/*******************************************************************
	Инициализируемся глобально ;)
*/	
error_reporting(E_ALL);
define('_SB_CFG', TRUE);	// Чтобы без этого файлика ничего не работало ;)

define('_SCRIPT_WORK_DIR', "");

date_default_timezone_set('Europe/Moscow');


$_SB_cfg = array(); // Глобальный конфигурационный массив

include_once( _SCRIPT_WORK_DIR . "config-db.php");	// Инициализируем переменные для работы с базой MySQL
include_once( _SCRIPT_WORK_DIR . "classes/safemysql.class.php");
$_dbLocal = new SafeMysql(array(
					'host' => $_SB_cfg['LocalConnectServer'],
					'user' => $_SB_cfg['LocalConnectLogin'],
					'pass' => $_SB_cfg['LocalConnectPassword'],
					'db' => $_SB_cfg['LocalConnectDatabase']
					));




include_once( _SCRIPT_WORK_DIR . "_function.php");		// Наш наборчик функций
include_once( _SCRIPT_WORK_DIR . "_function-2.php.inc");		// Наш наборчик функций

$portal_auth = array();

if ( is_session_started() === FALSE ) session_start();

//print_r($portal_auth);
?>