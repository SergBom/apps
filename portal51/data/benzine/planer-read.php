<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('portal_benzine');
/*---------------------------------------------------------------------------*/

/****************************************************************
	Постоянные величины
	Исходные значения
*/
setlocale(LC_ALL, 'rus');
//$wdays = array('понедельник','вторник','среда','четверг','пятница','суббота','воскресенье');
$one_day = 24*60*60;
$week_day = $one_day * 7;
$cur_time = time(); 
$data = array();
/****************************************************************/

/*-------------------------- Входные переменные -----------------------------*/
$Y = (int)substr($_GET['startOfWeek'],1,4);
$M = (int)substr($_GET['startOfWeek'],5,2);
$D = (int)substr($_GET['startOfWeek'],8,2);
/*---------------------------------------------------------------------------*/

/* Ищем начало и конец недели */
$week_start = strtotime("last Monday",mktime(0,0,0,$M,$D,$Y));
$week_end = $week_start+ 6 * $one_day  ; 
$ws = date('Y-m-d', $week_start+$week_day);
$we = date('Y-m-d', $week_end+$week_day);

//$current_week = 1;




	$sql = "SELECT * FROM `v#planer` WHERE begin_date>='$ws' AND begin_date<='$we' ORDER BY begin_date, begin_time ";
	//echo $sql;
	if ( $result = $db->query( $sql ) ) {
		
		while ($row = $result->fetch_assoc()) {
			
			/*** Преобразование полученных данных ***/
			//$row['week_day'] = $wdays[ date("N",strtotime($row['begin_date'])) - 1 ];
			//$row['begin_time'] = substr($row['begin_time'],0,5);
			if( (int)$row['delta'] > 0 AND (int)$row['tour_success'] == 0 ){$row['tour_success']='2';}
			//$row['tour_success'] = ($row['tour_success']=='1')?true:false;
			
			/*** Добавляем список маршрутов к записи***/
			$data_tour = array();
			$r2 = $db->query( "SELECT * FROM `v#tours` WHERE tour_id='{$row['id']}'" );
			array_push($data_tour, '<table class="prt-table-vid1"><tr><td colspan="3"><b>Маршрут:</b></td></tr>');
			while ($row2 = $r2->fetch_assoc()) {
				array_push($data_tour, '<tr><td class="prt-table-td1">'.$row2['tour_from'].'</td><td>-></td><td class="prt-table-td1">'.$row2['tour_to'].'</td>
					<td class="prt-table-td1">'.$row2['distance'].' км</td></tr>');
			}
			array_push($data_tour, "</table>");
			$row['tour'] = implode('<br>',$data_tour); 
			/***------------------------------------***/
			
			array_push($data, $row);
		}

		echo json_encode(array('success'=>true,'data'=>$data));
	}

?>