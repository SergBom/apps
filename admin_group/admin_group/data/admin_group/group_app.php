<?php   
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');
/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $link = ConnectMyDB('portal');
/*---------------------------------------------------------------------------*/


$method = $_SERVER['REQUEST_METHOD'];
//$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));
//print_r(substr(@$_SERVER['PATH_INFO'], 1));
//$params = parseRequest($method);



switch ($method) {
  case 'GET':  // SELECT
	_get_data($link,$_GET);
    break;
  case 'POST':  // SELECT
	_post_data($link,$_POST);
    break;
  default:
	echo "method = '$method<br>'";
}

function _get_data($link,$adata){

	$data=array();
	
	$strSQL = "SELECT * FROM `prt#groups_app`";
	
	if( isset($adata['admin']) AND $adata['admin']==0){
		$strSQL .= " WHERE id>1";
	}
	
	$result = $link->query($strSQL);
	while ($row = $result->fetch_assoc()) {
		array_push($data, $row);
	}

	echo json_encode(array('success'=>mysql_errno()==0,'data'=>$data));
	
}
////////////////////////////////////////////////
function _post_data($link,$data){
	//$_POST['app_nik'] = ( $_POST['app_nik'] <> "" ) ? $_POST['app_nik']: "gr".mt_rand(10000, 99999);


	if($_POST['id']==0){ //Новая запись
	
		$strSQL = "INSERT INTO `prt#groups_app` SET name='{$_POST['name']}', reference='{$_POST['reference']}'
			";

	} else { // Обновление записи

		$strSQL = "UPDATE `prt#groups_app` SET name='{$_POST['name']}', reference='{$_POST['reference']}'
			WHERE id={$_POST['id']}";

	}

	$result = $link->query($strSQL);

	echo json_encode(array(
				'success' => mysql_errno()==0,
				'data' => $_POST
				));
}

				
?>


