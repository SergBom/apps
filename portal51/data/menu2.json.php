<?php
include_once("../../php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('portal');
/*---------------------------------------------------------------------------*/

$method = $_SERVER['REQUEST_METHOD'];
$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));
//print_r(substr(@$_SERVER['PATH_INFO'], 1));
$params = parseRequest($method);

switch ($method) {
  case 'GET':  // SELECT
	_get_data($db,$params);
    break;
}


function _get_data($db,$params){	// Вывести список записей
//	global $_SB_cfg, $_dbLocal;

	$root = array();
	$item = array();
	
	$sql = "SELECT * FROM menu WHERE menu_id=0 ORDER BY id"; //WHERE Org_ID='.$Org_id.'
	if ( $result = $db->query( $sql ) ) {		//Сначала собираем корень
		$cnt=0;
		while ($row = $result->fetch_assoc()) {
			$cnt++;
		
			$root[ $row['id'] ] = array(
					"id"=>$row['id'],
					"title"=>$row['title'],
					"iconCls"=>$row['iconCls']
					//"menu_id"=>$row['menu_id'],
					//"text"=>$row['text'],
					//"className"=>$row['className']
					//"expanded"=>($row['expanded']==0)?"false":"true",
					//"leaf"=>($row['leaf']==0)?"false":"true"
			);
		}
	}
	
	$sql = "SELECT * FROM menu WHERE menu_id<>0 ORDER BY menu_id, id"; //WHERE Org_ID='.$Org_id.'
	if ( $result = $db->query( $sql ) ) {
		$cnt=0;
		while ($row = $result->fetch_assoc()) {
			$cnt++;
			//echo "$cnt --> {$row['menu_id']}<br>";
			
			$item[ $row['menu_id'] ][] = array(
					"id"=>$row['id'],
					"menu_id"=>$row['menu_id'],
					"text"=>$row['text'],
					//"title"=>$row['title'],
					"iconCls"=>$row['iconCls'],
					"className"=>$row['className']
					//"expanded"=>($row['expanded']==0)?"false":"true",
					//"leaf"=>($row['leaf']==0)?"false":"true"
			);
			
		}
	}
//		array_push( $root[ $row['menu_id'] ], $item );

		//print_r($root); echo "<br><br><br>";
			
		//$str = json_encode( array( "items" => $root ) )	;
			
		/*
		print_r($root[1]); echo "<br><br><br>";
		echo json_encode($root[1]);echo "<br><br><br>";
		
		print_r($item[3]); echo "<br><br><br>";
		//echo json_encode($item[3]);echo "<br><br><br>";
*/
		
		for( $r=1; $r<=count($root); $r++){
			@array_push( $root[$r], $item[$r] );
			$a[] = $root[$r];
		}
		
		//print_r($a);echo "<br><br><br>";
		
		
		//echo "<br><br><br>";
		$str = json_encode( array( "items" => $a )	);
//		$str = str_replace('"0":[','"items":[',$str);
		
		
		echo $str;//"<br><br><br><br>";

}

?>