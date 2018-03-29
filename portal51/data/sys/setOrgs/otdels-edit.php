<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/ldap/ldap-func.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
//$Org_id = trim ((!empty($_GET['org'])) ? $_GET['org'] : "1" ); // 

	/*$_SESSION['portal']['user_id'] = $_SESSION['user_id'];
	$_SESSION['portal']['username'] = $_SESSION['username'];
	$user_id = isset( $_SESSION['portal']['user_id'] ) ?  $_SESSION['portal']['user_id']  : '';
	$org_id  = isset( $portal_auth['domain_id'] ) ? ($portal_auth['domain_id'])? $portal_auth['domain_id']:1  : 1;
	*/

	$data = array();
	$adata = (object)$_POST; //json_decode($info);
/*---------------------------------------------------------------------------*/

    $db = ConnectMyDB('portal');

/*---------------------------------------------------------------------------*/
//$domain = $adata->domain; //"MURMANSK.NET";
//$domain = "локально";
//$domain = "KP51.LOCAL";
	
	print_r($adata);

	$par_id = ( $adata->par_id == '') ? 0 : $adata->par_id;
	$code = ( $adata->code == 0 ) ? $adata->id : $adata->code;


if ( $adata->id <> 0 ) {

	
	$sql = "UPDATE `prt#otdels` SET
		code='{$code}',
		name='{$adata->name}',
		sname='{$adata->sname}',
		info='{$adata->info}',
		par_id='{$par_id}'

		WHERE id={$adata->id}";
	echo "$sql<br>";
	$db->query($sql);
	
}


///////////// Модифицируем АД ///////////////
	$ad_conn  = ad_connect($_SESSION['portal']['domain_name']);
	$ldapBind = ldap_bind( $ad_conn['ds'], $ad_conn['ad_admin'], $ad_conn['ad_pass']); 

			/*
			"samaccountname",
			"dn","cn","name","description","info",
			"objectclass",
			"useraccountcontrol"	
			*/
	
$entry = array();
//$entry['cn']=$r_new['FIO'];
$entry['info']=$adata->info;
$entry['name']=$adata->name;
//$entry['info']=$adata->info;
/*if($r_new['tel1']){$entry['telephoneNumber']="{$r_new['tel1']}";}
if($r_new['telIP']){$entry['ipPhone']="{$r_new['telIP']}";}
if($r_new['email']){$entry['mail']="{$r_new['email']}";}
if($r_new['tel2']){$entry['mobile']="{$r_new['tel2']}";}*/
ldap_modify($ad_conn['ds'], $r_new['user_dn'], $entry);


/*

	$sql = "SELECT * FROM `prt#domains` WHERE name='$domain'";

	if ($resultDb = $db->query($sql)) {
			
		$recDomain = $resultDb->fetch_assoc();
		////////////////////////////////////// Соединяемся с ЛДАП
		$ldapBase =  $recDomain['ad_group_dn'].','.$recDomain['ad_base_dn'];

		// Соединение с ЛДАП только от пользователя Domain Schema. Иначе не читает ветку пользователя
		$ds = ldap_connect( $recDomain['ad_ip'], $recDomain['ad_port'] ) or die('Cannot Connect to LDAP server');
		ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
		$ldapBindUser = @ldap_bind( $ds, $recDomain['ad_admin'] . $recDomain['ad_suffix'], $recDomain['ad_password']); 
		
		if ( $ldapBindUser ){ // Авторизация прошла успешно
		
			//echo "ID_DOMAIN=".$recDomain['id']."<br>";		

			$ad_params = array(
			"samaccountname",
			"dn","cn","name","description","info",
			"objectclass",
			"useraccountcontrol"
			);
		
			$sr = @ldap_search($ds, $ldapBase, "(&(samaccountname=*)(objectclass=group))", $ad_params);
			if($sr){
				$ent= ldap_get_entries($ds,$sr);

				for ($i = 0; $i < $ent["count"]; $i++) {
				
					$entData[] = "'".$ent[$i]["samaccountname"][0]."'";
		
					$sql = "SELECT count(*) C FROM `prt#otdels` WHERE cn = '{$ent[$i]["cn"][0]}' AND org_id='{$recDomain['id']}'";
					//echo "$sql<br>";
					if ( $db->getOne($sql)==0 ) {

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
					//echo "$sql<br><br>";
//							sb_debug_info( "$sql" );
					$result = $db->query($sql);

								
//					$data[] = $ss;
					//echo $ss;
				
				} else { //Если есть, то обновляем путь dn

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
				}
				
				}
			}
		}
		
		

		$sql = "CALL `proc#otdels`('{$recDomain['id']}')";
		if ( $result = $db->query( $sql ) ) {
		
			while ($row = $result->fetch_assoc()) {
				array_push($data, $row);
			}

			echo json_encode(array('success'=>'true','data'=>$data));
		}
	
	}
	
	*/
?>