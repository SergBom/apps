<?php
$isCLI = ( php_sapi_name() == 'cli' );
$_include_path = ($isCLI) ? "/var/www/portal/public_html" : $_SERVER['DOCUMENT_ROOT'];
include_once($_include_path."/php/init2.php");
header('Content-type: text/html; charset=utf-8');


	$user_id  = isset( $_SESSION['portal']['user_id'] )   ?  $_SESSION['portal']['user_id']   : '0';
	//$otdel_id = isset( $_SESSION['portal']['otdel_id'] )  ?  $_SESSION['portal']['otdel_id']  : '0';
	//$org_id   = isset( $_SESSION['portal']['domain_id'] ) ?  $_SESSION['portal']['domain_id'] : '0';
	$user_FIO = isset( $_SESSION['portal']['FIO'] ) ?  $_SESSION['portal']['FIO'] : '';



$a = (object)$_REQUEST;




$dbo = ConnectPDO('ETables');

//print_r($_POST);

$id    = $a->id;
$title = $a->title;
$info  = isset($a->info) ? $a->info : "";
$refer  = isset($a->refer) ? 1 : 0;


$set = "title='$title', info='$info', refer=$refer, user_id=$user_id, user_fio='$user_FIO'";


if( $id == 0 ){

	// Создаем запись с временной таблицей
	$name  = 'tb' . rand(10000000,99999999);
	$dbo->query("INSERT INTO A_Tables SET name='$name'");
	
	// Переименовываем таблицу под номер записи
	$id = $dbo->lastInsertId();
	$name  = 'tb' . str_pad($id,8,'0',STR_PAD_LEFT);
	$dbo->query("UPDATE A_Tables SET name='$name', $set WHERE id=$id");
	
	// Создаем запись о структуре таблицы - обязательное поле "id"
	$dbo->query("INSERT INTO S_Tables SET
			a_id=$id,
			field_name='id',
			field_title='id',
			field_type='INT',
			field_len='11',
			field_PK=1,
			field_order=-10,
			user_id=$user_id,
			user_fio='$user_FIO'
			");
			
/*	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`a_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
	`field_name` VARCHAR(50) NOT NULL DEFAULT '0',
	`field_title` VARCHAR(50) NOT NULL DEFAULT '',
	`field_type` VARCHAR(10) NOT NULL DEFAULT '0',
	`field_len` SMALLINT(5) UNSIGNED NULL DEFAULT NULL,
	`field_PK` BIT(1) NOT NULL DEFAULT b'0',
	`field_INDEX` BIT(1) NOT NULL DEFAULT b'0',
	`field_order` SMALLINT(5) NOT NULL DEFAULT '0',
	`xtype` VARCHAR(50) NOT NULL DEFAULT 'string',
	`editor` VARCHAR(50) NULL DEFAULT NULL,
	`allowBlank` BIT(1) NULL DEFAULT b'0',
	`format` VARCHAR(50) NULL DEFAULT NULL,
	`width` SMALLINT(6) NULL DEFAULT NULL,
	`filter_type` VARCHAR(20) NOT NULL DEFAULT 'string',
	`del` BIT(1) NOT NULL DEFAULT b'0',
	`date_update` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`user_id` INT(11) NOT NULL DEFAULT '0',
	`user_fio` VARCHAR(100) NOT NULL DEFAULT '',			
			*/
			
			
	
	$sql = "CREATE TABLE `$name` (
		`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		PRIMARY KEY (`id`),
		UNIQUE INDEX `id_UNIQUE` (`id`)
		)
		COLLATE='utf8_general_ci'
		COMMENT='$title|$info'
		ENGINE=InnoDB"
		;
			
	$dbo->query($sql);

} else {
	
	$dbo->query("UPDATE A_Tables SET $set WHERE id=$id");
	
}
	
	
	
	
$c = array('success'=>true,'msg'=>'Ok');
	echo json_encode($c);


?>