<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
//$Org_id = trim ((!empty($_GET['org'])) ? $_GET['org'] : "1" ); // 

	/*
	$user_id = isset( $_SESSION['portal']['user_id'] ) ?  $_SESSION['portal']['user_id']  : '';
	$org_id  = isset( $_SESSION['portal']['domain_id'] ) ? $_SESSION['portal']['domain_id'] : 0;
	*/

	$data = array();
	$adata = (object)$_GET; //json_decode($info);
/*---------------------------------------------------------------------------*/

    $db = ConnectMyDB('portal');

/*---------------------------------------------------------------------------*/
//$domain = $adata->domain; //"MURMANSK.NET";
//$domain = "MURMANSK.NET";
//$domain = "локально";
//$domain = "KP51.LOCAL";

/*	$sql = "SELECT * FROM `prt#domains` WHERE name='$domain'";

	if ($resultDb = $db->query($sql)) {
			
		$recDomain = $resultDb->fetch_assoc();
		////////////////////////////////////// Соединяемся с ЛДАП
		$ldapBase =  $recDomain['ad_group_dn'].','.$recDomain['ad_base_dn'];
		
		//echo $ldapBase."<br>";

		// Соединение с ЛДАП только от пользователя Domain Schema. Иначе не читает ветку пользователя
		$ds = ldap_connect( $recDomain['ad_ip'], $recDomain['ad_port'] ) or die('Cannot Connect to LDAP server');
		ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
		$ldapBindUser = @ldap_bind( $ds, $recDomain['ad_admin'] . $recDomain['ad_suffix'], $recDomain['ad_password']); 
		
		if ( $ldapBindUser ){ // Авторизация прошла успешно
		
		
			$sr = @ldap_search($ds, $ldapBase, "(&(samaccountname={$recDomain['ad_prefixOtdel']}*)(objectclass=group))", $ad_params_group);
			if($sr){
				$ent= ldap_get_entries($ds,$sr);

				for ($i = 0; $i < $ent["count"]; $i++) {
				
					echo $ent[$i]['cn'][0]; echo "<br>";
					echo @$ent[$i]['description'][0]; echo "<br>";
		
					$sql = "SELECT count(*) FROM `prt#otdels` WHERE cn = '{$ent[$i]["cn"][0]}' AND org_id='{$recDomain['id']}'";
					echo "$sql<br>";
					$C = $db->getOne($sql);
					//echo "C='$C'<br>";
					if ( $C==0 ) { // Если нет в базе, то добавляем
									
						$sql = "INSERT INTO `prt#otdels` SET
								org_id = {$recDomain['id']},
								name = '".@$ent[$i]["description"][0]."',
								cn = '{$ent[$i]["cn"][0]}',
								dn = '{$ent[$i]["dn"]}',
								info = '".@$ent[$i]["info"][0]."'
								";
				
					} else { // Если есть, то обновляем 

						$sql = "UPDATE  `prt#otdels` SET
								org_id = {$recDomain['id']},
								name = '".@$ent[$i]["description"][0]."',
								cn = '{$ent[$i]["cn"][0]}',
								dn = '{$ent[$i]["dn"]}',
								info = '".@$ent[$i]["info"][0]."'
							WHERE org_id = {$recDomain['id']} AND cn = '{$ent[$i]["cn"][0]}'
								";

					}

						echo "$sql<br>";
					//$result = $db->query($sql);

					echo "<br>=====<br>";
					
				}
			}
		}
	*/	
		

		$sql = "CALL `proc#otdels`('{$adata->domain_id}')";
		if ( $result = $db->query( $sql ) ) {
		
			while ($row = $result->fetch_assoc()) {
				array_push($data, $row);
			}

			echo json_encode(array('success'=>'true','data'=>$data));
		}
	
	//}
	

?>