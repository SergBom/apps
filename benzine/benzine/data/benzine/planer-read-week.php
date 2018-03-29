<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('portal_benzine');
/*---------------------------------------------------------------------------*/

	$adata = (object)$_POST; //json_decode($info);

	
setlocale(LC_ALL, 'rus');

$wdays = array('понедельник','вторник','среда','четверг','пятница','суббота','воскресенье');


echo date('Y-m-d').'   '.date('w');
echo "<hr>";

$one_day = 24*60*60;
$week_day = $one_day * 7;
$cur_time = time(); 

$week_start = strtotime("last Monday",mktime(0,0,0,date("n",$cur_time),date("j",$cur_time),date("Y",$cur_time))); 
$week_end = $week_start+ 6 * $one_day  ; 
echo "<br>";
echo "<br>";

$current_week = 1;

for( $i=0; $i<=6; $i++){
	echo date('d.m.Y  ', $week_start + $current_week * $week_day + $i * $one_day); 
	echo $wdays[$i]."<br>";
}



echo "<hr>";

echo date('d.m.Y H:i:s', $week_start), '<br/>', date('d.m.Y H:i:s', time()), "<br/>", date('d.m.Y H:i:s', $week_end); 
echo "<br>";
echo "<hr>";

echo date('d.m.Y', strtotime('Monday', $cur_time)).'<br/>';
echo date('d.m.Y', strtotime('Sunday', $cur_time)).'<br/>';


	$data = array();

	$sql = "SELECT * FROM type_tours ORDER BY name";
	if ( $result = $db->query( $sql ) ) {
		
		while ($row = $result->fetch_assoc()) {
			array_push($data, $row);
		}

		//echo json_encode(array('success'=>true,'data'=>$data));
	}

?>