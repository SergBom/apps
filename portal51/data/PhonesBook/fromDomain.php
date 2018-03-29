<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/ldap/ldap-func.php");
header('Content-type: text/html; charset=utf-8');

/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('portal');
/*---------------------------------------------------------------------------*/

//$domain = $adata->domain; //"MURMANSK.NET";
$domain = "MURMANSK.NET";
//$domain = "локально";
//$domain = "KP51.LOCAL";

$domain_id	= $db->getOne( "select id FROM `prt#domains`	where name = '$domain'" );

/*---------------------------------------------------------------------------*/
	$adata = $_GET; //(object)$_GET;


	
	$ad_conn  = ad_connect($domain);
	$ldapBind = ldap_bind( $ad_conn['ds'], $ad_conn['ad_admin'], $ad_conn['ad_pass']); 


	if ( $ldapBind ){ // Авторизация прошла успешно
	
////////////////////////////////////////////////////////////////////////	
////////////////////////////////////////////////////////////////////////	
//////////  Обновляем группы	
////////////////////////////////////////////////////////////////////////		
	
			$ldapBaseGroup =  $ad_conn['group_dn'] .",". $ad_conn['base'];
			
			$sr = ldap_search($ad_conn['ds'], $ldapBaseGroup, "(&(samaccountname={$ad_conn['prefixOtdel']}*)(objectclass=group))", $ad_params_group);
			if($sr){
				$ent= ldap_get_entries($ad_conn['ds'],$sr);

				for ($i = 0; $i < $ent["count"]; $i++) {
				
					echo "CN=".$ent[$i]['cn'][0]; echo "<br>";
					echo "Description=".@$ent[$i]['description'][0]; echo "<br>";
		
					$sql = "SELECT count(*) FROM `prt#otdels` WHERE cn = '{$ent[$i]["cn"][0]}' AND org_id='{$ad_conn['domain_id']}'";
					//echo "$sql<br>";
					$C = $db->getOne($sql);
					//echo "C='$C'<br>";
					if ( $C==0 ) { // Если нет в базе, то добавляем
									
						$sql = "INSERT INTO `prt#otdels` SET
								org_id = {$ad_conn['domain_id']},
								name = '".@$ent[$i]["description"][0]."',
								cn = '{$ent[$i]["cn"][0]}',
								dn = '{$ent[$i]["dn"]}',
								info = '".@$ent[$i]["info"][0]."'
								";
				
					} else { // Если есть, то обновляем 

						$sql = "UPDATE  `prt#otdels` SET
								name = '".@$ent[$i]["description"][0]."',
								cn = '{$ent[$i]["cn"][0]}',
								dn = '{$ent[$i]["dn"]}',
								info = '".@$ent[$i]["info"][0]."'
							WHERE org_id = {$ad_conn['domain_id']} AND cn = '{$ent[$i]["cn"][0]}'
								";

					}

					echo "$sql<br>";
					$result = $db->query($sql);

					echo "<br>=====<br>";
					
				}
			}
			//******************************************************//
			
			$ldapBaseGroup =  $ad_conn['user_dn'] .",". $ad_conn['base'];
			
			getOtdels(0,$ldapBaseGroup);
			
			
			
	
	
	
////////////////////////////////////////////////////////////////////////	
////////////////////////////////////////////////////////////////////////	
//////////  Обновляем Пользователей	
////////////////////////////////////////////////////////////////////////		
	
	
			//----- Поисковый контейнер пользователя
			//$ldapBase[0] = "OU=Murmansk,".$ad_conn['base'];
			//$ldapBase[1] = "OU=User_U51,".$ad_conn['base'];
			$ldapBase[0] =  $ad_conn['user_dn'] .",". $ad_conn['base'];

		// ---------- Проверяем удаленных пользователей в домене
/*		$sql = "SELECT id, username FROM `prt#users` WHERE domain_id='$domain_id'";
		if ( $result = $db->query($sql) ){
			while ($row = $result->fetch_assoc()) {

				$c=0;
				foreach( $ldapBase as $v ){
					$sr  = ldap_search($ad_conn['ds'], $v, "(&(samaccountname={$row['username']})(objectclass=user))", $ad_params_user);
					$ent = ldap_get_entries($ad_conn['ds'],$sr);
					$c += $ent['count'];
				}

				echo "{$row['username']} => $c<br>";
		
				if($c==0){ // Удаляем, если пользователя в домене не существует
				//	$db->query("DELETE FROM `prt#users_ad_group`	WHERE user_id={$row['id']}");
				//	$db->query("DELETE FROM `prt#users_group` 		WHERE user_id={$row['id']}");
				//	$db->query("DELETE FROM `prt#users` 			WHERE id={$row['id']}");
				}
			}
		} 	*/

		// ----------- Забираем к себе пользователей из домена
		foreach( $ldapBase as $v ){

			echo "++++++++  $v  +++++++<br><br>";
		
			$sr = ldap_search($ad_conn['ds'], $v, "(&(samaccountname=*)(objectclass=user))", $ad_params_user);
			$ent= ldap_get_entries($ad_conn['ds'],$sr);
	
			for( $i=0; $i < $ent['count']; $i++ ){
			
				print_r( $ent[$i] ); 
			
				$sa = $ent[$i]["samaccountname"][0];
				$dn = $ent[$i]["dn"];
				$cn = $ent[$i]["cn"][0];
				$Fm = @$ent[$i]["sn"][0];
				$Im = @$ent[$i]["givenname"][0];
				$Ot = @$ent[$i]["initials"][0];
				$tel1  = @$ent[$i]['telephonenumber'][0];
				$tel2  = @$ent[$i]['mobile'][0];
				$telIP = @$ent[$i]['ipphone'][0];
				$email = @$ent[$i]['mail'][0];

				$uac = $ent[$i]['useraccountcontrol'][0];
				$disable = ($uac |  2);
				$enable  = ($uac & ~2);

				echo "<br>** $sa **<br>";
				//echo "UAC = $uac<br> dis = $disable <br> ena = $enable<br>";

				$otdel_dn = user_dn_container($dn);
				
				$otdel_id = "";
				if( isset($ent[$i]['memberof'])){
					$ad_user_group = ad_group_user('GP-', $ent[$i]['memberof']);
					$otdel_id = $db->getOne("SELECT id FROM `prt#otdels` WHERE cn='$ad_user_group'");
					$otdel_id = ( $otdel_id > 0 ) ? "otdel_id='$otdel_id'," : "";
					
					$primaryGroupId = @$ent[$i]['primarygroupid'][0];
					echo "primaryGroupId=>'$primaryGroupId'<br>";

				}
				echo "Otdel => memberof='$ad_user_group' $otdel_id<br>";
			
			
				$user_id=0;
				$sql = "SELECT id,userFm,userIm,userOt FROM `prt#users` WHERE username='$sa' AND domain_id='$domain_id'";
				$resDb = $db->query($sql);
				$record = $resDb->fetch_assoc();
				if($resDb->num_rows==1){
					// Корректируем ФИО
					$Fm = ($record['userFm']) ? $record['userFm'] : $Fm;
					$Im = ($record['userIm']) ? $record['userIm'] : $Im;
					$Ot = ($record['userOt']) ? $record['userOt'] : $Ot;
					$user_id = $record['id'];
				}
					
				
				
				if($user_id==0){ // Если нет такого пользователя, то добавляем
					$sql = "INSERT `prt#users` SET
						username='$sa',
						domain_id=$domain_id,
						org_id=$domain_id,
						dn='$dn',
						userFm='$Fm',
						userIm='$Im',
						userOt='$Ot',
						tel1='$tel1',
						tel2='$tel2',
						telIP='$telIP',
						email='$email',
						$otdel_id
						ad_state='$uac',
						off='".($uac - $enable)."',
						say='0',
						main_group=2
						";
					$db->query($sql);
					$user_id=$db->insertId();
				} else { // если есть такой пользователь, то обновляем данные из АД
					$sql = "UPDATE `prt#users` SET 
						dn='$dn',
						org_id=$domain_id,
						userFm='$Fm',
						userIm='$Im',
						userOt='$Ot',
						tel1='$tel1',
						tel2='$tel2',
						telIP='$telIP',
						$otdel_id						
						ad_state='$uac',
						off='".($uac - $enable)."',
						email='$email'
						WHERE id=$user_id";
					$db->query($sql);
				}
			
			
				if( isset($ent[$i]['memberof'])){
					$db->query("DELETE FROM `prt#users_ad_group` WHERE user_id=$user_id");
					for( $k=0; $k < $ent[$i]['memberof']['count']; $k++ ){
						$sql = "INSERT `prt#users_ad_group` SET user_id=$user_id, group_dn='{$ent[$i]['memberof'][$k]}'";
						$db->query($sql);
						echo "$sql<br>";
					}
				}
			
			echo "<br>===<br>";
			
			}
	
		}
	}

	
function getOtdels($par_id,$ldapBase)
{
	global $db,$ad_conn,$ad_params_orgUnit;
			
			$sr = @ldap_list($ad_conn['ds'], $ldapBase, "(&(name=_otdel*)(objectclass=organizationalUnit))", $ad_params_orgUnit);
			//echo "sr=$sr<br>";
			if($sr){
				$ent= ldap_get_entries($ad_conn['ds'],$sr);
				echo "ent['count']={$ent["count"]}<br>";

				for ($i = 0; $i < $ent["count"]; $i++) {

					echo "**** par_id=$par_id **********'<br>";
					echo "<font color='red'>Подразделение = '{$ent[$i]['name'][0]}'</font><br>";
					echo "description = '".@$ent[$i]['description'][0]."'<br>";
					//echo "distinguishedName = '{$ent[$i]['distinguishedname'][0]}'<br>";
					echo "dn = '{$ent[$i]['dn']}'<br>";

					$sql = "SELECT id FROM `prt#otdels2` WHERE cn='{$ent[$i]["ou"][0]}' AND org_id='{$ad_conn['domain_id']}'";
					echo "$sql<br>";
					$ID = $db->getOne($sql);
					echo "ID-C='$ID'<br>";
					if ( $ID===0 ) {
						$sql = "INSERT INTO `prt#otdels2` SET
								par_id = $par_id,
								org_id = {$ad_conn['domain_id']},
								name = '".@$ent[$i]["description"][0]."',
								cn = '{$ent[$i]["ou"][0]}',
								dn = '{$ent[$i]["dn"]}'
								";
						echo "$sql<br><br>";
						//sb_debug_info( "$sql" );
						$result = $db->query($sql);
						$ID = $db->insertId($result);
					} else {
						$sql = "UPDATE `prt#otdels2` SET
								par_id = $par_id,
								org_id = {$ad_conn['domain_id']},
								name = '".@$ent[$i]["description"][0]."',
								cn = '{$ent[$i]["ou"][0]}',
								dn = '{$ent[$i]["dn"]}'
								WHERE  cn='{$ent[$i]["ou"][0]}' AND org_id='{$ad_conn['domain_id']}'";
						echo "$sql<br><br>";
						//sb_debug_info( "$sql" );
						$result = $db->query($sql);
						//$ID = $db->insertId($result);
					}
						
					
					echo "ID=$ID=<br>";
					
					$sr2 = @ldap_list($ad_conn['ds'], "OU=". $ent[$i]['name'][0] .",". $ldapBase, "(&(name=_otdel*)(objectclass=organizationalUnit))", $ad_params_orgUnit);
					if($sr2){
						$ent2= ldap_get_entries($ad_conn['ds'],$sr2);
						if($ent2["count"]>0){
							getOtdels($ID,"OU=". $ent[$i]['name'][0] .",". $ldapBase);
						}
					}
					
					echo "**************<br>";

				}
			}

}
	
	
	
	
?>