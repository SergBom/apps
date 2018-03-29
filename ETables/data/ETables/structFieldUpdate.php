<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');

$db = ConnectPDO('ETables');
//var_dump( $_POST);


/**********
	Приходят данные:

	{
		"id":15,
		"a_id":"2",
		"field_name":"fld54307",
		"field_title":"\u041f\u043e\u043b\u04353\u0444",
		"field_type":"VARCHAR",
		"field_len":23,
		"field_PK":"0",
		"field_INDEX":"0",
		"field_order":0,
		"xtype":"gridcolumn",
		"editor":null,
		"allowBlank":null
	}

*/


$j = json_decode($_POST['data']);



//var_dump($j);
// ALTER TABLE `tb00000001`	ADD COLUMN `fff` DATE NULL				 		AFTER `fld36257`;
// ALTER TABLE `tb00000001`	ADD COLUMN `fff` TIME NULL DEFAULT NULL 		AFTER `fff`;
// ALTER TABLE `tb00000001`	ADD COLUMN `fff` VARCHAR(50) NULL DEFAULT NULL 	AFTER `fff`;
// ALTER TABLE `tb00000001`	ADD COLUMN `fff` INT NULL DEFAULT NULL 			AFTER `fff`;
// ALTER TABLE `tb00000001`	ADD COLUMN `fff` FLOAT NULL DEFAULT NULL 		AFTER `fff`;

$a_type = array(
// [0]Тип поля, [1]Длина поля, [2]Тип колонки(grid), [3]Редактор(grid), [4]Фильтр
	'VARCHAR'=>array("VARCHAR({$j->field_len}) NULL DEFAULT NULL",$j->field_len,"gridcolumn","textfield","string"),
	'DATE'=>array('DATE NULL',0,"datecolumn","datefield","date"),
	'TIME'=>array('TIME NULL DEFAULT NULL',0,"datecolumn","datefield","string"),
	'INT'=>array('INT NULL DEFAULT NULL',0,"numbercolumn","numberfield","number"),
	'FLOAT'=>array('FLOAT NULL DEFAULT NULL',0,"numbercolumn","numberfield","string"),
	'BOOLEAN'=>array('BIT NULL DEFAULT NULL',0,"booleancolumn","booleanfield","boolean")
	
);

$field_len = $a_type[$j->field_type][1];


//editor
//allowBlank
$a_editor = array(
	''
);




if ($j->id == 0 ){
	
		$field_name = 'fld' . rand(10000,99999); // Генерим случайное имя для поля
		$j->field_name=$field_name; // меняем в массиве для возврата в ExtJS
	
		// Втавляем новое поле в таблицу со структурами Таблиц
		$db->query("INSERT INTO S_Tables SET
			a_id={$j->a_id},
			field_name='{$field_name}',
			field_title='{$j->field_title}',
			field_type='{$j->field_type}',
			field_len='{$a_type[$j->field_type][1]}',
			xtype='{$a_type[$j->field_type][2]}',
			field_order={$j->field_order},
			`editor`='{$j->editor}',
			`allowBlank`='{$j->allowBlank}',
			`filter_type`='{$a_type[$j->field_type][4]}'
			");
			
		$id = $db->lastInsertId();
		$j->id = $id;


		$table_name = 'tb' . str_pad($j->a_id,8,'0',STR_PAD_LEFT); // Генерим имя Таблицы в соответствии с ID 
		
		$sql = "ALTER TABLE `$table_name` ADD COLUMN `$field_name` {$a_type[$j->field_type][0]} COMMENT '{$j->field_title}' ";
		$sql = str_replace('%N%', $j->field_len, $sql);
		
		$db->query( $sql );
	
} else {

		$db->query("UPDATE S_Tables SET
			field_title='{$j->field_title}',
			field_type='{$j->field_type}',
			field_len='{$a_type[$j->field_type][1]}',
			xtype='{$a_type[$j->field_type][2]}',
			field_order='{$j->field_order}',
			`editor`='{$j->editor}',
			`allowBlank`='{$j->allowBlank}',
			`filter_type`='{$a_type[$j->field_type][4]}'
			WHERE id={$j->id}
			");

		$table_name = 'tb' . str_pad($j->a_id,8,'0',STR_PAD_LEFT);

		$sql = "ALTER TABLE `$table_name` CHANGE COLUMN `{$j->field_name}` `{$j->field_name}` {$a_type[$j->field_type][0]} COMMENT '{$j->field_title}' ";
		$sql = str_replace('%N%', $j->field_len, $sql);
		$db->query( $sql );
	
	
}



echo json_encode(array('success'=>'true',
'data'=>$j
));
?>
