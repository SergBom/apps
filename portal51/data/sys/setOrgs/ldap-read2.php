<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
include("{$_SERVER['DOCUMENT_ROOT']}/php/ldap/ldap-func.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
//$Org_id = trim ((!empty($_GET['org'])) ? $_GET['org'] : "1" ); // 

	/*$_SESSION['portal']['user_id'] = $_SESSION['user_id'];
	$_SESSION['portal']['username'] = $_SESSION['username'];
	$user_id = isset( $_SESSION['portal']['user_id'] ) ?  $_SESSION['portal']['user_id']  : '';
	$org_id  = isset( $portal_auth['domain_id'] ) ? ($portal_auth['domain_id'])? $portal_auth['domain_id']:1  : 1;
	*/

	$data = array();
	$adata = (object)$_GET; //json_decode($info);
/*---------------------------------------------------------------------------*/

    $db = ConnectMyDB('portal');

/*---------------------------------------------------------------------------*/
$domain = "MURMANSK.NET";
//$domain = "локально";
//$domain = "KP51.LOCAL";

	//$sql = "SELECT * FROM `prt#domains` WHERE name='$domain'";

	//if ($resultDb = $db->query($sql)) {
			
//		$recDomain = $resultDb->fetch_assoc();
		////////////////////////////////////// Соединяемся с ЛДАП

		// Соединение с ЛДАП только от пользователя Domain Schema. Иначе не читает ветку пользователя
		//$ds = ldap_connect( $recDomain['ad_ip'], $recDomain['ad_port'] ) or die('Cannot Connect to LDAP server');
		//ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
		
		
		$ad_conn = ad_connect($domain);
		$ds = $ad_conn['ds'];

//		$ldapBindUser = ldap_bind( $ds, $recDomain['ad_admin'] . $recDomain['ad_suffix'], $recDomain['ad_password']); 
		$ldapBindUser = ldap_bind( $ds, 'sbmm'. $ad_conn['suffix'], 'privet'); 
//		$ldapBindUser = ldap_bind( $ds, $ad_conn['admin'], $ad_conn['pass']); 
		
		
		
		if ( $ldapBindUser ){ // Авторизация прошла успешно
		
			//echo "ID_DOMAIN=".$recDomain['id']."<br>";		

			
			
			// 1.Требуется найти все группы, в которые входит пользователь
			// 2.Определить DN контейнера, в котором находится пользователь - не обязательно
			//CN=Ольга А. Швейцер,OU=ZemleustroystvoMonitoringKartografii,OU=PZ-44,OU=Inet_44,OU=Murmansk,DC=murmansk,DC=net
			//$dn_user = "CN=Ольга А. Швейцер,OU=ZemleustroystvoMonitoringKartografii,OU=PZ-44,OU=Inet_44,OU=Murmansk,DC=murmansk,DC=net";
			//$gr_user = array();


//		$ldapBase =  $recDomain['ad_group_dn'].','.$recDomain['ad_base_dn'];
		
			//----- Поисковый контейнер пользователя
			//$ldapBase =  "OU=User_U51,".$ad_conn['base'];
			$ldapBase =  "OU=Murmansk,".$ad_conn['base'];
			$sr = ldap_search($ds, $ldapBase, "(&(samaccountname=sbmm)(objectclass=user))", $ad_params_user);
			$ent= ldap_get_entries($ds,$sr);
			
			if( $ent['count']==0) {
				$ldapBase =  "OU=User_U51,".$ad_conn['base'];
				$sr = ldap_search($ds, $ldapBase, "(&(samaccountname=sbmm)(objectclass=user))", $ad_params_user);
				$ent= ldap_get_entries($ds,$sr);
			}
				
				
			
				
				$mc = microtime(); //."<br>"; //"Y-m-d H:i:s:u"

				for ($i = 0; $i < $ent["count"]; $i++) {
				
					//$entData[] = "'".$ent[$i]["samaccountname"][0]."'";
		
					//$sql = "SELECT count(*) C FROM `prt#otdels` WHERE cn = '{$ent[$i]["cn"][0]}' AND org_id='{$recDomain['id']}'";
					//echo "$sql<br>";
					//if ( $db->getOne($sql)==0 ) {
						
					//echo "<p>**<b>-{$ent[$i]['cn'][0]}-</b>**:<br>";
					//foreach( $ad_params as $v ){
						//echo "_ $v='".$ent[$i][$v]."'";
						
					//}
					print_r($ent[$i]);
					echo "</p><b>--</b><br>";
					
					print_r( ldap_explode_dn( $ent[$i]['dn'], 1 ) );
					echo "</p><b>--</b><br>";
					
					
					//print_r($ent[$i]['memberof']);
					
					//echo "</p><b>--</b><br>";
					
						//foreach(  $ent[$i]['memberof'] as $k=>$v ){
							//echo "$k=$v<br>";
							print_r( ad_group_user( 'GP-', $ent[$i]['memberof'] ) );
							//if( $v == $dn_user ){
								//array_push();
//								echo $ent[$i]['cn'][0];
	//							break 2;
							//}
						//}
					
					echo "</p><b>--</b><br>";
/*					if( isset($ent[$i]['member']) ){
						foreach(  $ent[$i]['member'] as $k=>$v ){
							//echo "$k=$v<br>";
							if( $v == $dn_user ){
								//array_push();
								echo $ent[$i]['cn'][0];
								break 2;
							}
						}
						//echo "<br>***<br>";
					}
	*/				
					

					/*
									dn---> {$ent[$i]["dn"]}<br>\n
									samaccountname---> {$ent[$i]["samaccountname"][0]}<br>\n
									cn--->{$ent[$i]["cn"][0]}<br>\n
									info--->".@$ent[$i]["info"][0]."<br>\n
									description--->".@$ent[$i]["description"][0]."<br>\n
									\n
									";
									
									
					/*$sql = "INSERT INTO `prt#otdels` SET
								org_id = {$recDomain['id']},
								name = '".@$ent[$i]["description"][0]."',
								cn = '{$ent[$i]["cn"][0]}',
								dn = '{$ent[$i]["dn"]}',
								info = '".@$ent[$i]["info"][0]."'
								";*/
					//echo "$ss<br><br>";
//							sb_debug_info( "$sql" );
					//$result = $db->query($sql);

								
//					$data[] = $ss;
					//echo $ss;
				
				/*} else { //Если есть, то обновляем путь dn

					$sql = "UPDATE  `prt#otdels` SET
								org_id = {$recDomain['id']},
								name = '".@$ent[$i]["description"][0]."',
								cn = '{$ent[$i]["cn"][0]}',
								dn = '{$ent[$i]["dn"]}',
								info = '".@$ent[$i]["info"][0]."'
							WHERE org_id = {$recDomain['id']} AND cn = '{$ent[$i]["cn"][0]}'
								";
					//echo "$sql<br><br>";


					//$sql = "UPDATE Users SET dn='{$userData['dn']}' WHERE sAMAccountName='{$userData['sAMAccountName']}'";
					$result = $db->query($sql);
				}*/
				
					//echo "GR=".ad_user_group($ad_conn,$ent[$i]['dn'][0]);
				
				}
			//}
			
			
			
		}
		
		
		/*

		$sql = "CALL `proc#otdels`('{$recDomain['id']}')";
		if ( $result = $db->query( $sql ) ) {
		
			while ($row = $result->fetch_assoc()) {
				array_push($data, $row);
			}

			echo json_encode(array('success'=>'true','data'=>$data));
		}*/
	
	//}
	
	
	
	
?>