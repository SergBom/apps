<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/ldap/ldap-func.php");
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
$domain = "MURMANSK.NET";
//$domain = "локально";
//$domain = "KP51.LOCAL";

	$sql = "SELECT * FROM `prt#domains` WHERE name='$domain'";

	if ($resultDb = $db->query($sql)) {
			
		$recDomain = $resultDb->fetch_assoc();
		////////////////////////////////////// Соединяемся с ЛДАП
		//$ldapBase =  $recDomain['ad_group_dn'].','.$recDomain['ad_base_dn'];
		$ldapBase =  'OU=User_U51,'.$recDomain['ad_base_dn'];
		
		echo $ldapBase."<br>";

		// Соединение с ЛДАП только от пользователя Domain Schema. Иначе не читает ветку пользователя
		$ds = ldap_connect( $recDomain['ad_ip'], $recDomain['ad_port'] ) or die('Cannot Connect to LDAP server');
		ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
		$ldapBindUser = @ldap_bind( $ds, $recDomain['ad_admin'] . $recDomain['ad_suffix'], $recDomain['ad_password']); 
		
		if ( $ldapBindUser ){ // Авторизация прошла успешно
		
			echo "ID_DOMAIN=".$recDomain['id']."<br>";		

			getOtdels(0,$ldapBase);
		
		
		}
	}
				

function getOtdels($par_id,$ldapBase)
{
	global $db,$ds,$ad_params_orgUnit,$recDomain;
			
			$sr = @ldap_list($ds, $ldapBase, "(&(name=_otdel*)(objectclass=organizationalUnit))", $ad_params_orgUnit);
			//echo "sr=$sr<br>";
			if($sr){
				$ent= ldap_get_entries($ds,$sr);
				echo "{$ent["count"]}.<br>";

				for ($i = 0; $i < $ent["count"]; $i++) {

					echo "**** par_id=$par_id **********'<br>";
					echo "Подразделение = '{$ent[$i]['name'][0]}'<br>";
					echo "description = '".@$ent[$i]['description'][0]."'<br>";
					//echo "distinguishedName = '{$ent[$i]['distinguishedname'][0]}'<br>";
					echo "dn = '{$ent[$i]['dn']}'<br>";

					$sql = "SELECT id FROM `prt#otdels2` WHERE cn='{$ent[$i]["ou"][0]}' AND org_id='{$recDomain['id']}'";
					echo "$sql<br>";
					$C = $db->getOne($sql);
					echo "C='$C'<br>";
					if ( $C===0 ) {
						$sql = "INSERT INTO `prt#otdels2` SET
								par_id = $par_id,
								org_id = {$recDomain['id']},
								name = '".@$ent[$i]["description"][0]."',
								cn = '{$ent[$i]["ou"][0]}',
								dn = '{$ent[$i]["dn"]}'
								";
						//echo "$sql<br><br>";
						//sb_debug_info( "$sql" );
						$result = $db->query($sql);
						$ID = $db->insertId($result);
					} else {
						$sql = "INSERT INTO `prt#otdels2` SET
								par_id = $par_id,
								org_id = {$recDomain['id']},
								name = '".@$ent[$i]["description"][0]."',
								cn = '{$ent[$i]["ou"][0]}',
								dn = '{$ent[$i]["dn"]}'
								";
						//echo "$sql<br><br>";
						//sb_debug_info( "$sql" );
						//$result = $db->query($sql);
						//$ID = $db->insertId($result);
					}
						
					
					echo "ID=$ID=<br>";
					
					$sr2 = @ldap_list($ds, "OU=". $ent[$i]['name'][0] .",". $ldapBase, "(&(name=_otdel*)(objectclass=organizationalUnit))", $ad_params_orgUnit);
					if($sr2){
						$ent2= ldap_get_entries($ds,$sr2);
						if($ent2["count"]>0){
							getOtdels($ID,"OU=". $ent[$i]['name'][0] .",". $ldapBase);
						}
					}
					
					echo "**************<br>";

				}
			}

}



				//$entData[] = "'".$ent[$i]["samaccountname"][0]."'";
		
					//$sql = "SELECT count(*) FROM `prt#otdels` WHERE cn = '{$ent[$i]["cn"][0]}' AND org_id='{$recDomain['id']}'";
					//echo "$sql<br>";
					//$C = $db->getOne($sql);
					//echo "C='$C'<br>";
					//if ( $C===0 ) {

						/*$ss = "<p>*** Добавляем в базу:<br>\n
									dn---> {$ent[$i]["dn"]}<br>\n
									samaccountname---> {$ent[$i]["samaccountname"][0]}<br>\n
									cn--->{$ent[$i]["cn"][0]}<br>\n
									info--->".@$ent[$i]["info"][0]."<br>\n
									description--->".@$ent[$i]["description"][0]."<br>\n
									\n
									";
									*/
							/*		
						$sql = "INSERT INTO `prt#otdels` SET
								org_id = {$recDomain['id']},
								name = '".@$ent[$i]["description"][0]."',
								cn = '{$ent[$i]["cn"][0]}',
								dn = '{$ent[$i]["dn"]}',
								info = '".@$ent[$i]["info"][0]."'
								";
						echo "$sql<br><br>";
//							sb_debug_info( "$sql" );
						//$result = $db->query($sql);

								*/
//					$data[] = $ss;
					//echo $ss;
				
					//} else { //Если есть, то обновляем путь dn

						/*$sql = "UPDATE  `prt#otdels` SET
								org_id = {$recDomain['id']},
								name = '".@$ent[$i]["description"][0]."',
								cn = '{$ent[$i]["cn"][0]}',
								dn = '{$ent[$i]["dn"]}',
								info = '".@$ent[$i]["info"][0]."'
							WHERE org_id = {$recDomain['id']} AND cn = '{$ent[$i]["cn"][0]}'
								";
						echo "$sql<br><br>";
*/

						//$sql = "UPDATE Users SET dn='{$userData['dn']}' WHERE sAMAccountName='{$userData['sAMAccountName']}'";
						//$result = $db->query($sql);
					//}
				
		
		
/*
		$sql = "CALL `proc#otdels`('{$recDomain['id']}')";
		if ( $result = $db->query( $sql ) ) {
		
			while ($row = $result->fetch_assoc()) {
				array_push($data, $row);
			}

			echo json_encode(array('success'=>'true','data'=>$data));
		}
*/	
//	}
?>