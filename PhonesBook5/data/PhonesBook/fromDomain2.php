<?php
$isCLI = ( php_sapi_name() == 'cli' );
$_include_path = ($isCLI) ? "/var/www/portal/public_html" : $_SERVER['DOCUMENT_ROOT'];
include_once($_include_path."/php/init2.php");
include_once($_include_path."/php/ldap/ldap-func2.php");
//header('Content-type: text/html; charset=utf-8');
echo "<!DOCTYPE html><html><head><meta charset='utf-8'></head><body>";


//$now = time();



/*---------------------------------------------------------------------------*/
    $dbo = ConnectPDO('portal');
/*---------------------------------------------------------------------------*/

//$domain = $adata->domain; //"MURMANSK.NET";
$domain = "MURMANSK.NET";
//$domain = "локально";
//$domain = "KP51.LOCAL";

$domain_id	= $dbo->query( "select id FROM `prt#domains`	where name = '$domain'" )->fetchColumn();

/*---------------------------------------------------------------------------*/
	$adata = $_GET; //(object)$_GET;


	
	$ad_conn  = ad_connect($domain);
	$ldapBind = ldap_bind( $ad_conn['ds'], $ad_conn['ad_admin'], $ad_conn['ad_pass']); 


	if ( $ldapBind ){ // Авторизация прошла успешно
	
////////////////////////////////////////////////////////////////////////	
////////////////////////////////////////////////////////////////////////	
//////////  Обновляем группы	
////////////////////////////////////////////////////////////////////////		
	
		//******************************************************//
			
		$ldapBaseGroup =  $ad_conn['user_dn'] .",". $ad_conn['base'];
		
		//	Рекурсивно обходим все подразделения пользователей
		getOtdels(0,$ldapBaseGroup);
	
		echo "<hr>";
	
	
////////////////////////////////////////////////////////////////////////	
////////////////////////////////////////////////////////////////////////	
//////////  Обновляем Пользователей	
////////////////////////////////////////////////////////////////////////		
	
	
			//----- Поисковый контейнер пользователя
			//$ldapBase[0] = "OU=Murmansk,".$ad_conn['base'];
			//$ldapBase[1] = "OU=User_U51,".$ad_conn['base'];
			$ldapBase[0] =  $ad_conn['user_dn'] .",". $ad_conn['base'];

		// ---------- Проверяем удаленных пользователей в домене
		$sql = "SELECT id, username,dateOff,dateOn,ad_state,off FROM `prt#users` WHERE domain_id='$domain_id'";
		if ( $result = $dbo->query($sql) ){
			while ($row = $result->fetch()) {

				$c=0;
				foreach( $ldapBase as $v ){
					$sr  = ldap_search($ad_conn['ds'], $v, "(&(samaccountname={$row['username']})(objectclass=user))", $ad_params_user);
					$ent = ldap_get_entries($ad_conn['ds'],$sr);
					$c += $ent['count'];
				}

				echo "{$row['username']}({$row['id']}) => $c";
		
				if( !$sr ){ // Удаляем, если пользователя в домене не существует
					echo " ---> <font color='red'>Удаляем</font>";
					echo " | >> prt#users_ad_group";
						//$dbo->query("DELETE FROM `prt#users_ad_group`	WHERE user_id={$row['id']}");
					echo " - Delete";
					echo " | >> prt#users_group";
						//$dbo->query("DELETE FROM `prt#users_group` 		WHERE user_id={$row['id']}");
					echo " - Delete";
					echo " | >> prt#users";
						//$dbo->query("DELETE FROM `prt#users` 			WHERE id={$row['id']}");
					echo " - Delete";
				}else{
					echo " ---> <font color='blue'>Оставляем</font>";
					/////////////////////////////////////////////////////////
					/// now - сегодня
					/// ----------------
					/// Условие обязательное: dateOff < dateOn - задается и выполняется на уровне интерфейса
					/// ----------------
					/// dateOn > now 
					///     |- dateOff is NULL OR dateOff <= now *** Выключить
					/// ---
					/// dateOff <= now 
					///     |- dateOn is NULL OR dateOn > now *** Выключить
					/// ---
					/// В остальных случаях Включить
					/////////////////////////////////////////////////////////
					
					//echo "NOW ='$now' strtotme='".strtotime("now")."'   DIFF='".dateDiff($row['dateOff'])."' dateOff='{$row['dateOff']}'";
					
					$dOFF = dateDiff($row['dateOff']);
					$dON  = dateDiff($row['dateOn']);
					
					echo " OFF='".$row['dateOff']."' ON='".$row['dateOn']."' dOFF='$dOFF' dON='$dON'";
					
						
					if($row['dateOff']){
						if( $dOFF <= 0 ){
							if($row['dateOn']){
								if( $dON > 0 ){
									echo " ---> <font color='red'>Выключить1a</font>";
								} else {
									echo " ---> <font color='green'>Включить1a</font>";
								}
							} else {
								echo " ---> <font color='red'>Выключить1b</font>";
							}
						} else {
							echo "???";
							// dateOff еще не наступила
							echo " ---> <font color='green'>Включить1к</font>";
						}
					} else {
						if($row['dateOn']){
							if( $dON > 0 ){
								echo " ---> <font color='red'>Выключить1c</font>";
							} else {
								echo " ---> <font color='green'>Включить1c</font>";
							}
						} else {
							echo " ---> <font color='green'>Включить1d</font>";
						}
					}

/*					if( $dON > 0 ){
						if ( $row['dateOff'] == "" OR $dOFF <= 0 ) {
							echo " ---> <font color='green'>Выключить1</font>";
						}
						
					}// else 
*/
					
					
				}
				echo "<br>";
			}
		} 	

		echo "<hr>";


		// ----------- Забираем к себе пользователей из домена
		foreach( $ldapBase as $v ){

			echo "++++++++ ldapBase:  $v +++++++<br><br>";
		
			$sr = ldap_search($ad_conn['ds'], $v, "(&(samaccountname=*)(objectclass=user))", $ad_params_user);
			$ent= ldap_get_entries($ad_conn['ds'],$sr);
	
			for( $i=0; $i < $ent['count']; $i++ ){
			
				//print_r( $ent[$i] ); 
			
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

				if($uac - $enable == 0){
					echo "<font color='green'>";	
				}else{
					echo "<font color='red'>";
				}
				
				$fio = explode(" ",$cn);
				//print_r($fio);
				if( count($fio)==3 and strlen(@$fio[1])>2 ){ //and substr($fio[1],1,1)=='.' ){
					$Fm = $fio[0];
					$Im = $fio[1];
					$Ot = $fio[2];
				}



				echo "** LOGIN=$sa **</font><br>";
				echo "** dn=$dn **<br>";
				//echo "UAC = $uac<br> dis = $disable <br> ena = $enable<br>";

				$otdel_dn = user_dn_container($dn);
				$otdel_id = $dbo->query("SELECT id FROM `prt#otdels` WHERE dn='$otdel_dn'")->fetchColumn();
				$otdel_id = ( $otdel_id > 0 ) ? "otdel_id='$otdel_id'," : "";
			
				$user_id=0;
				$sql = "SELECT id,userFm,userIm,userOt FROM `prt#users` WHERE username='$sa' AND domain_id='$domain_id'";
				if( ($record = $dbo->query($sql)->fetch() ) != false ){
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
					$dbo->query($sql);
					$user_id=$dbo->lastInsertId();
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
					$dbo->query($sql);
				}
				echo "$sql<br>";
			
			
/*				if( isset($ent[$i]['memberof'])){
					$dbo->query("DELETE FROM `prt#users_ad_group` WHERE user_id=$user_id");
					for( $k=0; $k < $ent[$i]['memberof']['count']; $k++ ){
						$sql = "INSERT `prt#users_ad_group` SET user_id=$user_id, group_dn='{$ent[$i]['memberof'][$k]}'";
						$dbo->query($sql);
						echo "$sql<br>";
					}
				}*/
			
				echo "================<br>";
			} // Пользователи
		} // Домены
	}// Если подкл. к LDAP
echo "</body>";

	
function getOtdels($par_id,$ldapBase)
{
	global $dbo,$ad_conn,$ad_params_orgUnit;
			
			$sr = @ldap_list($ad_conn['ds'], $ldapBase, "(&(name=_otdel*)(objectclass=organizationalUnit))", $ad_params_orgUnit);
			//echo "sr=$sr<br>";
			if($sr){
				$ent= ldap_get_entries($ad_conn['ds'],$sr);
				//echo "ent['count']={$ent["count"]}<br>";

				for ($i = 0; $i < $ent["count"]; $i++) {

					//echo "**** par_id=$par_id **********<br>";
					echo "<font color='blue'>**** Подразделение = '{$ent[$i]['name'][0]}' ****</font><br>";
					echo "description = '".@$ent[$i]['description'][0]."'<br>";
					echo "dn = '{$ent[$i]['dn']}'<br>";

					$ID = $dbo->query("SELECT id FROM `prt#otdels` WHERE cn='{$ent[$i]["ou"][0]}' AND org_id='{$ad_conn['domain_id']}'")->fetchColumn();
					$CountRow = $dbo->query( "SELECT FOUND_ROWS() CountRows" )->fetchColumn();
					//echo "ID-C='$ID'<br>";
					if ( $CountRow == 0 ) {
						$sql = "INSERT INTO `prt#otdels` SET
								par_id = $par_id,
								org_id = {$ad_conn['domain_id']},
								name = '".@$ent[$i]["description"][0]."',
								cn = '{$ent[$i]["ou"][0]}',
								dn = '{$ent[$i]["dn"]}'
								";
						//echo "$sql<br><br>";
						echo "Добавили<br>";
						$result = $dbo->query($sql);
						$ID = $dbo->lastInsertId();
					} else {
						$sql = "UPDATE `prt#otdels` SET
								par_id = $par_id,
								org_id = {$ad_conn['domain_id']},
								name = '".@$ent[$i]["description"][0]."',
								dn = '{$ent[$i]["dn"]}'
								WHERE  cn='{$ent[$i]["ou"][0]}' AND org_id='{$ad_conn['domain_id']}'";
						//echo "$sql<br><br>";
						echo "Обновили<br>";
						//  cn = '{$ent[$i]["ou"][0]}',
						$result = $dbo->query($sql);
					}
						
					//echo "ID=$ID=<br>";
					$sr2 = @ldap_list($ad_conn['ds'], "OU=". $ent[$i]['name'][0] .",". $ldapBase, "(&(name=_otdel*)(objectclass=organizationalUnit))", $ad_params_orgUnit);
					if($sr2){
						$ent2= ldap_get_entries($ad_conn['ds'],$sr2);
						if($ent2["count"]>0){
							getOtdels($ID,"OU=". $ent[$i]['name'][0] .",". $ldapBase);
						}
					}
					
					echo "================<br>";

				}
			}
}
	

function dateDiff($d2){
	
	$dt1 = new DateTime( date("Y-m-d") );
	$dt2 = new DateTime($d2);
//	$dt1 = date_create('now');
//	$dt2 = date_create($d2);
	
	//if( $dt1 == $dt2  ) { return 0;}
	//if( $dt1 < $dt2  ) { return -1;}
	//if( $dt1 > $dt2  ) { return 1;}
	//echo "dt1='$dt1' dt2='$dt2'";
	$interval = date_diff($dt1,$dt2);
	return (int)$interval->format("%R%a");
	//return $dt2 - $dt1;
}


	
			//$ldapBaseGroup =  $ad_conn['group_dn'] .",". $ad_conn['base'];
			
/*			$sr = ldap_search($ad_conn['ds'], $ldapBaseGroup, "(&(samaccountname={$ad_conn['prefixOtdel']}*)(objectclass=group))", $ad_params_group);
			if($sr){
				$ent= ldap_get_entries($ad_conn['ds'],$sr);

				for ($i = 0; $i < $ent["count"]; $i++) {
				
					echo "CN=".$ent[$i]['cn'][0]; echo "<br>";
					echo "Description=".@$ent[$i]['description'][0]; echo "<br>";
		
					$sql = "SELECT count(*) FROM `prt#otdels` WHERE cn = '{$ent[$i]["cn"][0]}' AND org_id='{$ad_conn['domain_id']}'";
					//echo "$sql<br>";
					$C = $dbo->getOne($sql);
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
					$result = $dbo->query($sql);

					echo "<br>=====<br>";
					
				}
			}
			*/
	
	
?>