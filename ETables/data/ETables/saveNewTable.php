<?php
$isCLI = ( php_sapi_name() == 'cli' );
$_include_path = ($isCLI) ? "/var/www/portal/public_html" : $_SERVER['DOCUMENT_ROOT'];
include_once($_include_path."/php/init2.php");
header('Content-type: text/html; charset=utf-8');

$dbo = ConnectPDO('ETables');

//print_r($_POST);

$id    = $_POST['id'];
$title = $_POST['title'];
$info  = isset($_POST['info']) ? $_POST['info'] : "";

if( $id == 0 ){

	$name  = 'tb' . rand(10000000,99999999);
	$dbo->query("INSERT INTO A_Tables SET name='$name', title='$title', info='$info'");
	$id = $dbo->lastInsertId();
	$name  = 'tb' . str_pad($id,8,'0',STR_PAD_LEFT);
	$dbo->query("UPDATE A_Tables SET name='$name' WHERE id=$id");
	
	$dbo->query("INSERT INTO S_Tables SET
			a_id=$id,
			field_name='id',
			field_title='id',
			field_type='INT',
			field_len='11',
			field_PK=1,
			field_order=-10
			");
	
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
	
	$dbo->query("UPDATE A_Tables SET title='$title', info='$info' WHERE id=$id");
	
}
	
	
	
	
$c = array('success'=>true,'msg'=>'Ok');
	echo json_encode($c);


?>