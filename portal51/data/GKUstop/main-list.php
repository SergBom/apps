<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/

	$data = array();
	$a = (object)$_REQUEST; //json_decode($info);

/*---------------------------------------------------------------------------*/

$filter = "";


if( isset($a->filter) ){
	
	if( count($a->filter)> 0 ){
		$filters = json_decode($a->filter,true);
		
		$ff_v = array();

		foreach($filters as $f){
			//var_dump($f);
			
			
			if( @$f['isDateValue'] ){ // Если дата
				$fk = substr($f['_value'], 0, 10);
			} else {
				
				if( preg_match('/,/',$f['_value'] )){
					$fk = implode('","',$f['_value']); // Данные в виде массива
				}else{
					$fk = $f['_value'];
				}
				
			}
			//echo "FK=".$fk;
			
			switch ( $f['_operator'] ){
				case 'in':
					$ff_v[] = "{$f['_property']} in (\"$fk\")";
					break;
				case 'gt':
					$ff_v[] = "{$f['_property']} > \"$fk\"";
					break;
				case 'lt':
					$ff_v[] = "{$f['_property']} < \"$fk\"";
					break;
				case 'like':
					$ff_v[] = "{$f['_property']} like \"%$fk%\"";
					break;
			}
			
		}

		//print_r(count($ff_v));

		//echo  "<hr>";
		if( count($ff_v)>0 ){
			$filter = " AND ". implode(' AND ', $ff_v);
		} else {
			$filter = "";
		}
	}
}	

//echo $filter . "<hr>";


	
/*---------------------------------------------------------------------------*/

    $db = ConnectPDO('GKUstop');

/*---------------------------------------------------------------------------*/

//	$filter = ( @$a->filter ) ? " AND name like '%".trim(@$a->filter)."%' " : "";


	$sql  = "SELECT SQL_CALC_FOUND_ROWS * FROM `vData` WHERE 0=0 $filter ";
	//$sql .= " LIMIT {$a->start},{$a->limit}";

	
	//echo $sql;

	$data = $db->query( $sql )->fetchAll(); //PDO::FETCH_COLUMN);
	$total_rows=$db->query("select FOUND_ROWS()")->fetchAll(PDO::FETCH_COLUMN);
	
	

	
	

/*	if($data['error_type']==0){
		$data['error_type']='Не устранено';
	} else {
		$data['error_type']='Устранено';
	}
	*/
	

	echo json_encode( array('success'=>0,'total'=>$total_rows,'data'=>$data) );
	

?>