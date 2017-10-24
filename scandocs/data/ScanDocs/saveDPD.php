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
/*---------------------------------------------------------------------------*/

    $pdo = ConnectPDO('Scan_docs');

$dpd_list = explode("\n",$_POST['dpd_list']);


$matches[] = "/^51 \d\d \d\d\d\d\d\d\d \d+$/"; // Правильные номера
// Условные кадастровые старые номера
$matches[] = "/^51 \d\d \d\d \d\d \d\d\d \d\d\d \d\d \d\d-\d+$/";
$matches[] = "/^51 \d\d \d\d \d\d \d\d \d\d \d{2,3}-\d\d\/\d\d\d\d-\d+$/";
$matches[] = "/^51 \d\d \d\d \d\d \d\d \d\d$/";
// Условные старые номера
$matches[] = "/^51-\d\d-\d\d\/\d\d\d\/\d\d\d\d-\d+$/";
$matches[] = "/^51-\d\d\/\d\d-\d\d\/\d\d\d\d-\d+$/";


$count   	= 0;
$count_bad	= 0;
$err_dpd 	= "";

foreach( $dpd_list as $dpd ){
	
	$dpd = str_replace(':',' ',trim($dpd));
	$dpd = str_replace('  ',' ',$dpd);
	if( $dpd ){

//		echo "DPD='$dpd'$cr";
	
		$pm = false;
		foreach( $matches as $fm ){
			//$t = preg_match($fm, $dpd);
			$pm = ( preg_match($fm, $dpd) OR $pm );
			//echo "Temp='$fm' => t='$t' => pm='$pm'$cr";
		}
		
		//echo "pm='$pm' = ";
	
	
		if( $pm ){
			$c = $pdo->query("SELECT count(*) FROM docs_l1 WHERE `name`='$dpd'")->fetchColumn();
			if( $c ){
				//$sql  = "UPDATE docs2 ";
				//$sql2 = "WHERE";
				$count_bad++;
				$err_dpd .= $dpd . " - Дубликат" . $cr;
			} else {
				
				// Определяем отдел
				
				
				
				
				// Добавляем в базу
				$sql = "INSERT INTO docs_l1 SET 
					`name`='$dpd',
					`cdate`='{$_POST['dpd_date']}',
					`opis`=1,
					`retro`=2";
					//ON DUPLICATE KEY UPDATE `name`='$dpd'";
		
					//echo $sql.$cr;
					$pdo->query( $sql );
					$count++;
			}
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