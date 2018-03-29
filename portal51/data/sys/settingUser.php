<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');
if ( is_session_started() === FALSE ) session_start();

/*-------------------------- Входные переменные -----------------------------*/
    $db = ConnectMyDB('portal');
/*---------------------------------------------------------------------------*/



if( $_POST['status']=='save' ) {

//print_r($_POST);

	$_dolzhnost_id 	= $db->getOne( "select id   FROM `PhonesBook`.Dolzhnost  	where Name = '{$_POST['dolzhnost']}'" );
	$_otdel_id 		= $db->getOne( "select id  	FROM `PhonesBook`.Otdels 	 	where Name = '{$_POST['otdel']}'" );
	$_address_id 	= $db->getOne( "select id 	FROM `PhonesBook`.Address 		where Name = '{$_POST['address']}'" );
	$org_id_1 = "";
	$org_id_2 = "";

	
	if(isset($_POST['org_name'])){
		$_org	 		= $db->query( "select id,org_id	FROM `portal`.`prt#v#orgs` 		where name = '{$_POST['org_name']}'" );
		$r_org = $_org->fetch_assoc();
		$org_id_1 = ",org_id = '{$r_org['id']}'";
		$org_id_2 = ",org_id = '{$r_org['org_id']}'";
	}

	$sql = "UPDATE `prt#users` SET
			userFm = '".trim($_POST['userFm'])."',
			userIm = '".trim($_POST['userIm'])."',
			userOt = '".trim($_POST['userOt'])."',
			tel1 = '".trim($_POST['Tel1'])."',
			tel2 = '".trim($_POST['Tel2'])."',
			telIP = '".trim($_POST['TelIP'])."',
			email = '".trim($_POST['email'])."',
			dolzhnost_id = '$_dolzhnost_id',
			otdel_id = '$_otdel_id',
			address_id = '$_address_id'
			$org_id_1
	
			WHERE id='{$_SESSION['portal']['user_id']}'";
	$result = $db->query($sql);
	

	$sql = "UPDATE `PhonesBook`.`Users` SET
			Fm = '".trim($_POST['userFm'])."',
			Im = '".trim($_POST['userIm'])."',
			Ot = '".trim($_POST['userOt'])."',
			Tel1 = '".trim($_POST['Tel1'])."',
			Tel2 = '".trim($_POST['Tel2'])."',
			TelIP = '".trim($_POST['TelIP'])."',
			email = '".trim($_POST['email'])."',
			Dolzhnost_ID = '$_dolzhnost_id',
			Otdel_ID = '$_otdel_id',
			Adr_ID = '$_address_id'
			$org_id_2

			WHERE sAMAccountName='{$_SESSION['portal']['username']}'";
	$result = $db->query($sql);
	
	
	

	$result = $db->query("select * from `prt#v#users2` WHERE id='{$_SESSION['portal']['user_id']}'");
	$row = $result->fetch_assoc();
	
	//echo $db->numRows($result);
//	$row = $_POST;

	if( $row['userImageSrc']=='' ){
		// Определяем картинку по умолчанию - Мальчик или Девочка  = /resources/user_images/people
		if(	preg_match("/ич$/i", trim($row['userOt']) ) ) {
			$row['userImageSrc'] = "/resources/images/people/man_brown.png";
		} else if( preg_match("/на$/i", trim($row['userOt']) ) ) {
			$row['userImageSrc'] = "/resources/images/people/user_female2.png";
		} else {
			$row['userImageSrc'] = "/resources/images/people/royal_user.png";
		}
	}

	$c = array('success'=>'true',"data"=>$row); 
	echo json_encode($c, JSON_FORCE_OBJECT);
	
} else {

	$result = array();

	//$result = $db->query("select * from `prt#v#users2` WHERE id='{$_SESSION['portal']['user_id']}'");
	$result = $db->query("select * from `prt#v#users_full` WHERE id='{$_SESSION['portal']['user_id']}'");
	$row = $result->fetch_assoc();
	
//	echo "row['userImageSrc']='".$row['userImageSrc']."'     Ot=".$row['userOt'];

	if( $row['userImageSrc']=='' ){
		// Определяем картинку по умолчанию - Мальчик или Девочка  = /resources/user_images/people
		if(	preg_match("/ич$/i", trim($row['userOt']) ) ) {
			$row['userImageSrc'] = "/resources/images/people/man_brown.png";
		} else if( preg_match("/на$/i", trim($row['userOt']) ) ) {
			$row['userImageSrc'] = "/resources/images/people/user_female2.png";
		} else {
			$row['userImageSrc'] = "/resources/images/people/royal_user.png";
		}
	}

	$c = array('success'=>'true',"data"=>$row);
	echo json_encode($c, JSON_FORCE_OBJECT);
}
?>