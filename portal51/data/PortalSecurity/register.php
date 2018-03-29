<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
require("PassHash.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('portal');
/*---------------------------------------------------------------------------*/


// username and password sent from form 
$username = stripslashes($_POST['username']);
$pass = stripslashes($_POST['password']); 
$pass2 = stripslashes($_POST['password2']); 
$userFm = stripslashes($_POST['userFm']); 
$userIm = stripslashes($_POST['userIm']); 
$userOt = stripslashes($_POST['userOt']); 
//$domain = trim($_POST['domain']); 


$result = array();


if($pass <> $pass2){
	$result['success'] = false;
	$result['msg'] = 'Введенные пароли не совпадают.';
} else {
	//$userName = $db->real_escape_string($userName);
	//$pass = $db->real_escape_string($pass);

	$sql = "SELECT count(*) cnt FROM `prt#users` WHERE upper(username)=upper('$username') and domain_id=''";
	//echo $sql;
	if ($resultDb = $db->query($sql)) {
		
		$record = $resultDb->fetch_assoc();
		$count = $record['cnt'];
		
		if($count==1){
			$result['success'] = false;
			$result['msg'] = 'Пользователь с таким логином уже существует.';
		} else {
			//////////////////////////////////////////
			/// Регистрируем пользователя

			$result['success'] = false;
			$result['msg'] = "Ошибка регистрации:<br>";
			
			$pass_hash = PassHash::hash($pass);
			$sql = "INSERT INTO `prt#users` SET
				username='$username',
				password='$pass_hash',
				userFm='$userFm',
				userIm='$userIm',
				userOt='$userOt',
				say='1',
				main_group=3
				";
			if ( $db->query($sql) ){
							
				$user_id = $db->insertId();
				// main_group=3 - Включаем юзера в группу "Гости"
				// Добавляем обязательную группу
				$sql = "INSERT `prt#users_group` SET
						user_id={$user_id},
						group_id=2
				";
				if ( $db->query($sql)) {
					$result['success'] = true;
					$result['msg'] = "Регистрация прошла успешно!";
				}
			}
		}
	}
}
/* close connection */
//$db->close();

//JSON encoding
echo json_encode($result);
?>
