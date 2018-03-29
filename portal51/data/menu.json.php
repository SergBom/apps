<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
//header('Content-type: text/html; charset=utf-8');
//if ( is_session_started() === FALSE ) session_start();

/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $db = ConnectMyPDO('portal');
/*---------------------------------------------------------------------------*/

//session_start();

if ( $_SESSION['portal']['authenticated'] == "yes" ){
	
//$userName =	$_SESSION['username'];
//$domain = $_SESSION['domain_id'];

	$a_null = array();
	
	$sql = "call `proc#menu_acl`('{$_SESSION['portal']['user_id']}');";
	if ( $result = $db->query( $sql ) ) {
		while ($row = $result->fetch()) {

			if( $row['menu_id'] == 0 ) //Значит корень
			{ 
				$root[$row['id']] = array(
					"id"=>$row['id'],
					//"menu_id"=>$row['menu_id'],
					//"text"=>$row['text'],
					"title"=>$row['title'],
					"iconCls"=>$row['iconCls'],
					//"className"=>$row['className']
					//"expanded"=>($row['expanded']==0)?"false":"true",
					//"leaf"=>($row['leaf']==0)?"false":"true"
				);
			} else {
				$item[$row['menu_id']][] = array(
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
		//print_r($root[1]); echo "<br><br><br>";
		//echo json_encode($root[1]);echo "<br><br><br>";
		
		//print_r($item[1]); echo "<br><br><br>";
		//echo json_encode($item[1]);echo "<br><br><br>";

	//	for( $r=1; $r<=count($root); $r++){
		foreach( $root as $key => $value ){
			//echo "$key =>   "; print_r($value); echo "<br>";
			
			if ( isset( $item[ $value['id'] ] ) ) {
				//echo "___$key =>   "; print_r( $item[ $value['id'] ]); echo "<br>";
				array_push( $root[ $key ], $item[ $value['id'] ] );
			} else {
				//echo "___$key =>   "; print_r( $a_null ); echo "<br>";
				array_push( $root[ $key ], $a_null );
			}
			//array_push( $root[$r], $item[$r] );
			$a[] = $root[$key];
		}
		
		$str = json_encode( array( "items" => $a )	);
		$str = str_replace('"0":[','"items":[',$str);
		
		echo $str;//"<br><br><br><br>";

	}
};	
?>