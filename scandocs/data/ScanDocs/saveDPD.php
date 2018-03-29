<?php
$isCLI = ( php_sapi_name() == 'cli' );
if($isCLI){
	include_once("/var/www/portal/public_html/php/init2.php");
	$cr = "\n";
}else{
	include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
	$cr = "<br>";
}
header('Content-type: text/html; charset=utf-8');


/*-------------------------- Входные переменные -----------------------------*/
	$user_id  = isset( $_SESSION['portal']['user_id'] )   ?  $_SESSION['portal']['user_id']   : '0';
	$user_FIO = isset( $_SESSION['portal']['FIO'] )       ?  $_SESSION['portal']['FIO']       : '';
	
	$a = (object)$_REQUEST;
/*---------------------------------------------------------------------------*/

    $pdo = ConnectPDO('Scan_docs');


	// Разбираем по строкам
	$dpd_list = explode("\n",$a->dpd_list);

//************************************************************/
//**** Шаблоны форматов ДПД ****
$matches[] = "/^51 \d\d \d\d\d\d\d\d\d \d+$/"; // Правильные номера
// Условные кадастровые старые номера
$matches[] = "/^51 \d\d \d\d \d\d \d\d\d \d\d\d \d\d \d\d-\d+$/";
$matches[] = "/^51 \d\d \d\d \d\d \d\d \d\d \d{2,3}-\d\d\/\d\d\d\d-\d+$/";
$matches[] = "/^51 \d\d \d\d \d\d-\d\/\d\d\d\d-\d+$/";
$matches[] = "/^51 \d\d \d\d \d\d \d\d \d\d \d{2,3}-\d+$/";
$matches[] = "/^51 \d\d \d\d \d\d \d\d \d\d \d{2,3} \d\d-.+$/";
$matches[] = "/^51 \d\d \d\d \d\d \d\d \d\d$/";
$matches[] = "/^51 \d\d \d\d \d\d \d\d \d\d \d\d \d+$/";
$matches[] = "/^51 \d\d \d\d\d\d\d\d \d\d\d\d \d+$/";

$matches[] = "/^51 \d\d \d\d\ d\d\ d\d \d\d \d\d\d\d .+$/";
$matches[] = "/^51 \d\d \d\d\ d\d\ d\d \d\d\d\d \d\d\d\d .+$/";


// Условные старые номера
$matches[] = "/^51-\d\d-\d\d\/\d\d\d\/\d\d\d\d-\d+$/";
$matches[] = "/^51-\d\d\/\d\d-\d\d\/\d\d\d\d-\d+$/";
$matches[] = "/^51 \d\d \d\d \d\d \d\d \d\d-[\d+]\/\d\d\d\d-\d+$/";
$matches[] = "/^51 \d\d \d\d \d\d\d \d\d\d \d\d\d\d \d\d-\d+$/";
$matches[] = "/^51 \d\d \d\d \d\d \d\d\d \d\d\d\ \d+\/.+/";

//************************************************************/

$count   	= 0;
$count_bad	= 0;
$err_dpd 	= "";

foreach( $dpd_list as $stroka ){
	
	$stroka = str_replace(':',' ',trim($stroka));
	$stroka = str_replace('  ',' ',$stroka);
	if( $stroka ){ // Если не пустая строка

//		echo "stroka='$stroka'$cr";
	
		// Форматы принимаемых строк:
		// Д:П Д = № тома = Кол-во листов
		
		$a_stroka = explode('=',$stroka);
	
	//var_dump($a_stroka);
	
		$dpd   = trim($a_stroka[0]);
		$tom   = isset($a_stroka[1]) ? "Том_" . (int)$a_stroka[1] . "скан" : false;
		$lists = isset($a_stroka[2]) ? (int)$a_stroka[2] : 0;
	
		//if($tom){ echo "Tom = $tom<br>"; }
		//if($lists){ echo "Lists = $lists<br>"; }
	
		$pm = true;
		//foreach( $matches as $fm ){
			//$pm = ( preg_match($fm, $dpd) OR $pm );
		//}
	
	
		if( $pm ){
			$c = $pdo->query("SELECT count(*) c, id FROM docs_l1 WHERE `name`='$dpd'")->fetch();
			if( $c['c'] ){
				$count_bad++;
				$err_dpd .= $dpd . " - Дубликат";
				$id = $c['id'];
			} else {
				
				// Определяем отдел
				
				$n3 = $pdo->query("SELECT n3 FROM `t\$district_data` WHERE `n1`='{$a->otdel}'")->fetchColumn();
				
				
				// Добавляем в базу
				$sql = "INSERT INTO docs_l1 SET 
					`n1`='{$a->otdel}',
					`n3`='$n3',
					cyear='{$a->cyear}',
					`name`='$dpd',
					`cdate`='{$a->dpd_date}',
					`opis`=1,
					`retro`=2
					,user_id='$user_id'
					,user_fio='$user_FIO'
					";
					//ON DUPLICATE KEY UPDATE `name`='$dpd'";
		
					//echo $sql.$cr;
					$pdo->query( $sql );
					
					$count++;
					
				$id = $pdo->lastInsertId();
			}
			
			// Внесение информации о Томах и Листах
			if($tom){
				
				$n = $pdo->query("SELECT count(*) n, id  FROM `docs_l2` WHERE `id_l1`=$id AND name='$tom'")->fetch();
				
				if($n['n']==0){
				
					$sql = "INSERT INTO `docs_l2` SET 
					`id_l1`=$id,
					`cyear`='{$a->cyear}',
					`name`='$tom',
					`cdate`='{$a->dpd_date}',
					`cnt_lists`=$lists
					,user_id='$user_id'
					,user_fio='$user_FIO'";
					
				} else {
					
					$sql = "UPDATE `docs_l2` SET 
					`id_l1`=$id,
					`cyear`='{$a->cyear}',
					`name`='$tom',
					`cdate`='{$a->dpd_date}',
					`cnt_lists`=$lists
					,user_id='$user_id'
					,user_fio='$user_FIO'
					WHERE id={$n['id']}";
					
				}
				$pdo->query( $sql );
				
				$err_dpd .= " - Внесено: $tom (Листов: $lists)";
	
			}
			
			$err_dpd .= $cr;
			
		} else {
			$count_bad++;
			$err_dpd .= $dpd . " - Не верный формат" . $cr;
		}
	} 
	
}

	$msg = "Внесено дел = $count.<br>С ошибками дел = $count_bad.";
	if( $count_bad ){
		$msg .= "<p>Следующие записи с ошибками:<br>".$err_dpd."</p>";
	}


	$c = array('success'=>true,'msg'=>$msg);
	echo json_encode($c);
	
?>