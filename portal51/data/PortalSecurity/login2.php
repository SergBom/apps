<?php
include("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
include("{$_SERVER['DOCUMENT_ROOT']}/php/ldap/ldap-func.php");
require("PassHash.php");
if ( is_session_started() === FALSE ) session_start();
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $db = ConnectPDO('portal');
/*---------------------------------------------------------------------------*/


// username and password sent from form 
$userName = stripslashes($_REQUEST['login']);
$pass = stripslashes($_REQUEST['password']); 
$domain = $_REQUEST['domain']; //<>'локально' ) ? $_POST['domain'] : ''; 
	
//echo "domain='$domain'<br>";


$result = array();

if ($domain == 'локально'){ //====== Пользователь локальный

	$record = $db->query("select * from `prt#v#users` WHERE userName='$userName' and domain='$domain'")->fetch();

	if ( $record ) { // Если Логин найден


			if (PassHash::check_password($record['password'],$pass)){

				$_SESSION['portal']['authenticated'] = "yes";
				$_SESSION['portal']['username'] = $userName;
				$_SESSION['portal']['FIO'] = $record['userFm']." ".$record['userIm']." ".$record['userOt'];
				$_SESSION['portal']['user_id'] = $record['id'];
				$_SESSION['portal']['org_id'] = $record['org_id'];
				$_SESSION['portal']['org_name'] = $record['org_name'];
				$_SESSION['portal']['main_group'] = $record['main_group'];
				$_SESSION['portal']['registerDate'] = $record['registerDate'];
				$_SESSION['portal']['lastvisitDate'] = $record['registerDate'];
				$_SESSION['portal']['userImageSrc'] = $record['registerDate'];
				$_SESSION['portal']['domain_id'] = 0; //$domain;
				$_SESSION['portal']['domain_name'] = 'локально'; //$domain;

//print_r($_SESSION);
				
				$result['success'] = true;
				$result['msg'] = 'Вы авторизованы!';

//				set_cookies($_SESSION['portal']['username'],$_SESSION['portal']['domain_name']);
				
				//Запоминаем Логин и Пароль в Куках на 30 дней
				set_cookies(array(
					'Login'=>array($_SESSION['portal']['username'],time()+60*60*24*30),
					'Domain'=>array($_SESSION['portal']['domain_name'],time()+60*60*24*30)
				));

			} else{
				$result['success'] = false;
				$result['msg'] = 'Не верный пароль.';
			}

	} else { 
		
		$result['success'] = false;
		$result['msg'] = 'Не верный логин.';
	}
	

} else { //====== Пользователь доменный

//	echo "<p>=1= ".time()."<br>";
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	////////////////////////////////////// Соединяемся с ЛДАП
	$ad_conn = ad_connect($domain);

	if ($ad_conn['ds']) {
					
		
					
		$ldapBindUser = @ldap_bind( $ad_conn['ds'], $userName . $ad_conn['suffix'], $pass); 
			
		if ( $ldapBindUser ){ // Авторизация прошла успешно
	
				
			//----- Поисковый контейнер пользователя
			//$ldapBase =  "OU=User_U51,".$ad_conn['base'];
			$ldapBase =  $ad_conn['user_dn'] .",". $ad_conn['base'];
			$sr = @ldap_search($ad_conn['ds'], $ldapBase, "(&(samaccountname={$userName})(objectclass=user))", $ad_params_user);
			$ent= @ldap_get_entries($ad_conn['ds'],$sr);
			
			//echo "'".$ent['count']."'<br>";
			
			if( $ent['count'] > 0) { //**** Пользователь найден в LDAP
			
				//print_r($ent);
			
				//$dn = mb_convert_encoding( $ent[0]["dn"], 'UTF-8', 'Windows-1251');
				$dn = $ent[0]["dn"];
				$cn = $ent[0]["cn"][0];
				$Fm = @$ent[0]["sn"][0];
				$Im = @$ent[0]["givenname"][0];
				$Ot = @$ent[0]["initials"][0];
				$tel1  = @$ent[0]['telephonenumber'][0];
				$tel2  = @$ent[0]['mobile'][0];
				$telIP = @$ent[0]['ipphone'][0];
				$email = @$ent[0]['mail'][0];
			
				$uac = $ent[0]['useraccountcontrol'][0];
				$disable = ($uac |  2);
				$enable  = ($uac & ~2);
			
				$otdel_dn = user_dn_container($dn);
				$otdel_id = $db->query("SELECT id FROM `prt#otdels` WHERE dn='$otdel_dn'")->fetchColumn();
				$otdel_sql = ( $otdel_id > 0 ) ? "otdel_id='$otdel_id'," : "";

				// ==== Обновляем запись в локальной базе, если пользователь есть в АД
				if ($record = $db->query("select * from `prt#v#users_full` WHERE username='$userName' and org_id='{$ad_conn['domain_id']}'")->fetchColumn()) {
					//$record = $resDb->fetch_assoc();
				
					//if($resDb->num_rows==1){ // Если запись в БД существует, то обновляем ее
//echo "<Нашел запись><br>";		
						$Fm = ($record['userFm']) ? $record['userFm'] : $Fm;
						$Im = ($record['userIm']) ? $record['userIm'] : $Im;
						$Ot = ($record['userOt']) ? $record['userOt'] : $Ot;
						//$otdel_id = ($record['otdel_id']>0) ? $record['userOt'] : $Ot;

						//echo "UserFm={$record['userFm']}<br>UserIm={$record['userIm']}<br>UserOt={$record['userOt']}<br>Fm=$Fm<br>Im=$Im<br>Ot=$Ot<br>";
					
						$user_id= $record['id'];
						
						$sql = "UPDATE `prt#users` SET 
							username='$userName',
							dn='$dn',
							userFm='$Fm',
							userIm='$Im',
							userOt='$Ot',
							$otdel_sql
							say='1'
							WHERE username='$userName' and domain_id='{$ad_conn['domain_id']}'";
						$db->query($sql);
		//				echo "$sql<br>";
	
						// Добавляем обязательную группу
						if($db->query("SELECT count(*) FROM `prt#users_group` WHERE user_id={$user_id} AND group_id=2")->fetchColumn() == 0 ){
							$db->query("INSERT `prt#users_group` SET user_id={$user_id}, group_id=2");
						}
						// Добавляем основную группу
						if($db->query("SELECT count(*) FROM `prt#users_group` WHERE user_id={$user_id} AND group_id=3")->fetchColumn() == 0 ){
							$db->query("INSERT `prt#users_group` SET user_id={$user_id}, group_id=3");
						}
						// Добавляем группу Редакторы главной страницы
						if($db->query("SELECT count(*) FROM `prt#users_group` WHERE user_id={$user_id} AND group_id=4")->fetchColumn() == 0 ){
							$db->query("INSERT `prt#users_group` SET user_id={$user_id}, group_id=4");
						}

				} else { // Если записи в локальной базе нет, то добавляем ее
						// main_group=2 - Включаем юзера в группу "Пользователи"
						$sql = "INSERT `prt#users` SET
							username='$userName',
							domain_id='{$ad_conn['domain_id']}',
							dn='$dn',
							userFm='$Fm',
							userIm='$Im',
							userOt='$Ot',
							tel1='$tel1',
							tel2='$tel2',
							telIP='$telIP',
							email='$email',
							$otdel_sql
							say='1',
							ad_state='$uac',
							off='".($uac - $enable)."',
							main_group=2
							";
						$db->query($sql);

	//					echo "$sql<br>";
					
						$user_id=$db->lastInsertId();
						
						// Добавляем обязательную группу
						if($db->query("SELECT count(*) FROM `prt#users_group` WHERE user_id={$user_id} AND group_id=2")->fetchColumn() == 0 ){
							$db->query("INSERT `prt#users_group` SET user_id={$user_id}, group_id=2");
						}
						// Добавляем основную группу
						if($db->query("SELECT count(*) FROM `prt#users_group` WHERE user_id={$user_id} AND group_id=3")->fetchColumn() == 0 ){
							$db->query("INSERT `prt#users_group` SET user_id={$user_id}, group_id=3");
						}
						// Добавляем группу Редакторы главной страницы
						if($db->query("SELECT count(*) FROM `prt#users_group` WHERE user_id={$user_id} AND group_id=4")->fetchColumn() == 0 ){
							$db->query("INSERT `prt#users_group` SET user_id={$user_id}, group_id=4");
						}
				}
				

//echo "<Session><br>";
				$_SESSION['authenticated'] = "yes";
				$_SESSION['username'] = $userName;
				$_SESSION['FIO'] = $Fm." ".$Im." ".$Ot;
				$_SESSION['user_id'] = $user_id; //$record['id'];
				$_SESSION['main_group'] = $record['main_group'];
				$_SESSION['otdel_id'] = $otdel_id; //$record['id'];
				$_SESSION['registerDate'] = $record['registerDate'];
				$_SESSION['lastvisitDate'] = $record['registerDate'];
				$_SESSION['userImageSrc'] = $record['registerDate'];
				$_SESSION['domain_id'] = $ad_conn['domain_id'];
				$_SESSION['domain_name'] = $ad_conn['domain']; 
	
				$_SESSION['portal']['authenticated'] = "yes";
				$_SESSION['portal']['username'] = $userName;
				$_SESSION['portal']['FIO'] = $Fm." ".$Im." ".$Ot;
				$_SESSION['portal']['user_id'] = $user_id; //$record['id'];
				$_SESSION['portal']['org_id'] = $record['org_id'];
				$_SESSION['portal']['org_name'] = $record['org_name'];
				$_SESSION['portal']['main_group'] = $record['main_group'];
				$_SESSION['portal']['otdel_id'] = $otdel_id; //$record['id'];
				$_SESSION['portal']['registerDate'] = $record['registerDate'];
				$_SESSION['portal']['lastvisitDate'] = $record['registerDate'];
				$_SESSION['portal']['userImageSrc'] = $record['registerDate'];
				$_SESSION['portal']['domain_id'] = $ad_conn['domain_id'];
				$_SESSION['portal']['domain_name'] = $ad_conn['domain'];
			
	//print_r($_SESSION);
			
				set_cookies(array(
					'Login'=>array($_SESSION['portal']['username'],time()+60*60*24*30),
					'Domain'=>array($_SESSION['portal']['domain_name'],time()+60*60*24*30)
				));

				$result['success'] = true;
				$result['msg'] = 'Вы авторизованы!';
				
			} else { //**** Пользователь НЕ найден в LDAP
				$result['success'] = false;
				$result['msg'] = 'Пользователь с такими данными не найдет в Каталоге<br>'.$ad_conn['user_dn'].','.$ad_conn['base'];
			}
			
		} else {  // Авторизация не прошла
			$result['success'] = false;
			$result['msg'] = 'Не верный логин или пароль.';
		}
	}

	//$resultDb->close();
}


echo json_encode($result);
/////////////////////////////////////////////////////////////////////////////////////////
function domain_split($d){
	$a = explode(':',$d);
	/*if($ad[0]=='1'){ //Наименование организации
	} else { // Имя домена
	}*/
	return $a[1];
}

function set_cookies($a){
	foreach($a as $k=>$v){
		setcookie("Portal{$k}",$v[0],$v[1]);
	}
	//setcookie("PortalLogin",$Login,  time()+60*60*24*30);
	//setcookie("PortalDomain",$Domain,  time()+60*60*24*30);
}

?>
