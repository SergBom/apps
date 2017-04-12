<?php
include_once("init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
//$Org_id = trim ((!empty($_GET['org'])) ? $_GET['org'] : "1" ); // 
/*---------------------------------------------------------------------------*/

	$mysqli = ConnectLOCAL( $_SB_cfg['PhonesBookDB'] );

/*---------------------------------------------------------------------------*/

$method = $_SERVER['REQUEST_METHOD'];
$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));
//print_r(substr(@$_SERVER['PATH_INFO'], 1));

$params = parseRequest($method);

switch ($method) {
  case 'PUT': 
    _put_data( $params );
    break;
  case 'POST':  // CREATE - Приходим, чтобы добавить запись
	_post_data($params);
    break;
  case 'GET':  // SELECT
	_get_data($params);
    break;
  case 'DELETE': // DELETE
	_delete_data($params);
}

function _delete_data( $adata ){ // Обновить запись

	$result = mys_query( "DELETE FROM Organisation WHERE id = {$adata['id']}" );
	if($result){
		$c = array('success'=>'true','data'=>$adata);
	} else {
		$c = array('success'=>'false'); //,'data'=>$item);
	}
	echo json_encode($c);

}


function _put_data( $adata ){ // Обновить запись
//print_r( $adata);
	$sql = "UPDATE Organisation SET
					code = {$adata['code']},
					Name = ".ent2utf( $adata['name'])."
					WHERE id = {$adata['id']}";
	$result = mys_query( $sql );
	if($result){
		$c = array('success'=>'true','data'=>
										array(	'code'=>$adata['code'],
												'name'=>ent2utf( $adata['name'])
											)
				);
	} else {
		$c = array('success'=>'false'); //,'data'=>$item);
	}
	echo json_encode($c);
}

function _post_data( $adata ){ // Создать запись
	$result = mys_query( "INSERT INTO Organisation (code, Name) VALUES ({$adata['code']},".ent2utf( $adata['name']).")" );
	if($result){
		$adata['id'] = mysql_insert_id();
		$c = array('success'=>'true','data'=>$adata);
	} else {
		$c = array('success'=>'false'); //,'data'=>$item);
	}
	echo json_encode($c);
}
	
function _get_data(){	// Вывести список записей
	
	$sql = "SELECT * FROM Organisation ORDER BY Code"; //WHERE Org_ID='.$Org_id.'
	if ( $result = mys_query( $sql ) ) {
		$rows = array();
		$cnt = 0;

		while ($row = mysql_fetch_assoc($result)) {

			$data[] = array(
				'id'=>$row['id'],
				'code'=>$row['Code'],
				'name'=>$row['Name']);
 
			$cnt++;

		}
		$c = array('success'=>'true','data'=>$data);
		echo json_encode($c);
	}
}
?>