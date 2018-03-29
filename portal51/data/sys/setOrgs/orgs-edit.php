<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
//$Org_id = trim ((!empty($_GET['org'])) ? $_GET['org'] : "1" ); // 

	$_SESSION['portal']['user_id'] = $_SESSION['user_id'];
	$_SESSION['portal']['username'] = $_SESSION['username'];
	$user_id = isset( $_SESSION['portal']['user_id'] ) ?  $_SESSION['portal']['user_id']  : '';
	$org_id  = isset( $portal_auth['domain_id'] ) ? ($portal_auth['domain_id'])? $portal_auth['domain_id']:1  : 1;

	$data = array();
	$adata = (object)$_POST; //json_decode($info);
	
/*---------------------------------------------------------------------------*/

    $db = ConnectMyDB('portal');

/*---------------------------------------------------------------------------*/

// Заполняем шаблоны запросов
	$sql_orgs = " `prt#orgs` SET name='{$adata->name}',	name_full='{$adata->name_full}'";
	$sql_domain = " `prt#domains` SET 
				name='{$adata->domain}',
				ad_ip='{$adata->ad_ip}',
				ad_port='{$adata->ad_port}',
				ad_password='{$adata->ad_password}',
				ad_base_dn='{$adata->ad_base_dn}',
				ad_user_dn='{$adata->ad_user_dn}',
				ad_group_dn='{$adata->ad_group_dn}',
				ad_suffix='{$adata->ad_suffix}',
				ad_prefixOtdel='{$adata->ad_prefixOtdel}'";


/*---------------------------------------------------------------------------*/





if($adata->id == 0){ // Добавляем новую запись

	$s = $db->getOne("SELECT count(*)  FROM `prt#v#orgs_full` WHERE name='{$adata->name}'");
	if ( $s > 0 ) { // Значит организация с таким наименованием уже существует
		// Возвращаем ошибку
	} else { // Организации с таким наименованием не существует
		//$s = $db->getOne("SELECT count(*)  FROM `prt#v#orgs_full` WHERE name='{$adata->domain}'");
		//if( $s > 0 ) { // Значит домен такой уже существует
		
		// создаем новую запись
		
		$db->query("INSERT INTO $sql_orgs");
		$ID= $db->insertId();
		
		if( strlen(trim($adata->domain)) <> 0 ){
			$db->query("INSERT INTO $sql_domain, org_id='{$ID}'");
		}
	}

} else { // Редактируем запись

		$db->query("UPDATE $sql_orgs WHERE id='{$adata->id}'");

		if( $adata->domain ){ // Если введен домен, то Исправляем его в базе
			$s = $db->getOne("SELECT count(*)  FROM `prt#domains` WHERE org_id='{$adata->id}'");
			echo "s='$s'<br>";
			if( $s == 0 ){ // Домен для этой организации в базе не существует - значит Добавляем
				$sql="INSERT INTO $sql_domain, org_id='{$adata->id}'";
				echo "$sql<br>";
				$db->query($sql);
			
			} else {// Иначе Обновляем
				$sql = "UPDATE $sql_domain WHERE org_id='{$adata->id}'";
				echo "$sql<br>";
				$db->query($sql);
			}
				
		} else { // Иначе удаляем его, если он там есть
			$db->query("DELETE FROM `prt#domains` WHERE org_id='{$adata->id}'");
		}
			
}

//print_r($params);	
	/*$sql = "SELECT * FROM `prt#v#orgs_full`";
	if ( $result = $db->query( $sql ) ) {

		while ($row = $result->fetch_assoc()) {
			array_push($data, $row);
		}

		echo json_encode(array('success'=>'true')); //,'data'=>$data));
	}*/
	
	echo json_encode(array('success'=>'true'));

?>