<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/excel-header.php");

//////////////////////////////////////////////////////////////////////////////
// Принимаем параметры
//$DateB = "09.01.".date('Y');
//$DateBegin = trim ((!empty($_GET['db'])) ? $_GET['db'] : $DateB ); // По умолчанию - Начало года
//$DateEnd = trim ((!empty($_GET['de'])) ? $_GET['de'] : date('d.m.Y') ); // По умолчанию - Сегодня
//$Otdel = trim ((!empty($_GET['otd'])) ? $_GET['otd'] : 0 ); // 

	$data = array();
	$a = (object)$_REQUEST; //json_decode($info);

//var_dump($a);	
	
	$filter = "";

if( isset($a->filter) ){
	$filter = get_filter($a->filter);
}	

	//echo $filter;
	
$f = file_get_contents('GKU-out.html');

	echo $f;

	
/*---------------------------------------------------------------------------*/

    $db = ConnectPDO('GKUstop');

/*---------------------------------------------------------------------------*/

	$sql  = "SELECT SQL_CALC_FOUND_ROWS * FROM `vData` WHERE 0=0 $filter ";
	//echo $sql;

	$data = $db->query( $sql )->fetchAll(); //PDO::FETCH_COLUMN);
	
//var_dump($data);
	
	foreach( $data as $vv){

	echo " <tr>\n";
	echo "	<td>{$vv['zayav']}</td>\n";
    echo "	<td>{$vv['zayav_date']}</td>\n";
    echo "	<td>{$vv['ONvid_N']}</td>\n";
    echo "	<td>{$vv['zayav_type_N']}</td>\n";
    echo "	<td>{$vv['charc']}</td>\n";
    echo "	<td>{$vv['KU_treb']}</td>\n";
    echo "	<td>{$vv['CadEngineer_N']}</td>\n";
    echo "	<td>{$vv['RR_date_uchet']}</td>\n";
    echo "	<td>{$vv['RR_date_stop']}</td>\n";
    echo "	<td>{$vv['RR_date_none']}</td>\n";
    echo "	<td>{$vv['RR_FZ']}</td>\n";
    echo "	<td>{$vv['RR_refer']}</td>\n";
    echo " </tr>\n";
	
	}
	
	
	
//$html_end =
 echo "</table></body></html>";
	
	
	
	
	
	
	
	
	
	
function get_filter($_filter){
	
	if( count($_filter)> 0 ){
		$filters = json_decode($_filter,true);
		
		$ff_v = array();

		foreach($filters as $f){
			
			if( @$f['isDateValue'] ){ // Если дата
				$fk = substr($f['_value'], 0, 10);
			} else {
				if( preg_match('/,/',$f['_value'] )){
					$fk = implode('","',$f['_value']); // Данные в виде массива
				}else{
					$fk = $f['_value'];
				}
			}
			
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
			return " AND ". implode(' AND ', $ff_v);
		} else {
			return "";
		}
	}
}	
	
	
	
	
	
	
	
	
	
	
	
?>
