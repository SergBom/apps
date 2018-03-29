<?php   

include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');
/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $link = ConnectMyDB('portal');
/*---------------------------------------------------------------------------*/
$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
  case 'GET':  // SELECT
	_get_data($link,$_GET);
    break;
  case 'POST':  // SELECT
	_post_data($link,$_POST);
    break;
}



function _get_data($link,$adata){	

	$data=array();
	$strW1 = "";
	
	if( isset($adata['groupMainId']) ){
		$strW1 = " AND main_group={$adata['groupMainId']}";
	}

	$strSQL = "SELECT id, concat(userFm,' ',userIm,' ',userOt)as name FROM `portal`.`prt#users` WHERE id>1 $strW1 AND deleted=0 ORDER BY name";
	$res_user = $link->query($strSQL);

	//$strSQL = "SELECT id, app_nik as name FROM `portal`.`prt#groups` ORDER  by id";
	$strSQL = "SELECT * FROM `portal`.`prt#groups_app` WHERE id>1 ORDER  by id";
	$res_group = $link->query($strSQL);

	$all_group=array();
	while($rows = mysqli_fetch_array($res_group, MYSQL_ASSOC)){
		array_push($all_group, $rows);
	}
	
	$count= count($all_group);
	//$strSQL = "SELECT user_id, group_id FROM `portal`.`prt#user_group`";
	//$res_prava = $link->query($strSQL);
	while($user = mysqli_fetch_array($res_user, MYSQL_ASSOC)) {
		for ($i = 0; $i<$count; $i++) {
			$strSQL = "SELECT group_id FROM `portal`.`prt#users_group` where user_id={$user['id']} and group_id={$all_group[$i]['id']}";
			$res_prava = $link->query($strSQL);
			list($pravo) = mysqli_fetch_array($res_prava);
			$user[$all_group[$i]['name']]= (isset($pravo))? 1: 0;
			$user[$all_group[$i]['id']]= (isset($pravo))? 1: 0;
		}
  
		array_push($data, $user);
	}
	
	$c = array('success'=>0,'data'=>$data);
		echo json_encode($c);
 

}

function _post_data($link,$adata){	

	//print_r( $_POST);
	//$params = $_POST['data'];
	//print_r($_POST['data'];
	//echo gettype($params).'  ';
	$v1=json_decode($adata['data'],true);
	//echo gettype($v1);
	//print_r($v1 );

	$v2=array_keys($v1);
	//print_r($v2 );

	$user_id=$v1['id'];
	$group_id=$v2[0];
	$val=$v1[$v2[0]];
	//echo "id=$id<br>gr=$group<br>val=$val<br>";
	//$strSQL = "SELECT id, name, true as group1,true as group2 FROM test";
	//$result = $link->query($strSQL);
	//switch ($group){
	//	case "admin":
	//    echo "admin<br>";
	//    break;
	//	case "users":
	//    echo "users<br>";
	//    break;
	//	case "guest":
	//    echo "guest<br>";
	//    break;
	//	default:
	//	break;

	//}
	if($val=='1'){//создаем новый
		//$strSQL = "CALL `proc#user_group_add`('$id', '$group')";
		$strSQL = "INSERT INTO `portal`.`prt#users_group` SET user_id=$user_id, group_id=$group_id ";
		//echo $strSQL;
	}else{//удаляем существующий
//		$strSQL = "CALL `proc#user_group_delete`('$id', '$group')";
		$strSQL = "DELETE FROM `portal`.`prt#users_group` WHERE user_id=$user_id AND group_id=$group_id ";
		//echo $strSQL;
	}

	$result = $link->query($strSQL);
	
	$c = array('success'=>mysql_errno()==0);
	echo json_encode($c);

}

?>


