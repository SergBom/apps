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


$httml = <<<HTTML
<!doctype html>
<html>
<head>
<meta charset="windows-1251">
<title>Автопробег</title>
<style type="text/css">
.header{font-weight:bold;font-size:x-large;}
.subheader{font-weight:bold;}
.t_header{text-align:center;border-bottom-style:solid;}
.t_cell0{border-bottom-width: thin;border-bottom-style: solid;}
.t_cell1{border-bottom-style: solid;border-bottom-width: thin;border-left-width: thin;border-left-style: solid;}
.t_cell2{font-style: italic;text-decoration: underline;}
.t_tour{border-bottom-width: thin;border-bottom-style: dashed;}
.t_ref{font-style: italic;text-decoration: underline;border-width: thin;border-style: dotted dotted solid;}
.tabheader{border:1px solid black;width:100%;}
.tabmain{border: medium solid black;width: 100%;}
</style>
</head>
<body>
<table class="tabheader">
  <tbody>
    <tr>
      <td class="header">Учет поездок</td>
      <td align="right" class="subheader">Неделя: c $ws по $we</td>
    </tr>
  </tbody>
</table>
<br>
<table class="tabmain">
  <tbody>
    <tr>
      <th class="t_header" scope="col">Дата</th>
      <th class="t_header" scope="col">Время</th>
      <th class="t_header" scope="col">Продолжительность</th>
      <th colspan="2" class="t_header" scope="col">Отдел</th>
      <th class="t_header" scope="col">Тип поездки</th>
      <th class="t_header" scope="col">Состояние</th>
    </tr>
HTTML;
	

	$sql = "SELECT * FROM `v#planer` WHERE begin_date>='$ws' AND begin_date<='$we' ORDER BY begin_date, begin_time ";
	//echo $sql;
	if ( $result = $db->query( $sql ) ) {
		
		while ($row = $result->fetch_assoc()) {
			
			/*** Преобразование полученных данных ***/
			if( (int)$row['delta'] > 0 AND (int)$row['tour_success'] == 0 ){$row['tour_success']='2';}
			switch ($row['tour_success']){
				case "0": $row['tour_success'] = 'Запланирована'; break;
				case "1": $row['tour_success'] = 'Завершена'; break;
				case "2": $row['tour_success'] = 'Просрочена'; break;
			}
			
			$httml .= "<tr><td>{$row['begin_date']} {$row['week_day']}</td>
				<td>{$row['begin_time']}</td>
				<td class='t_cell1'>{$row['duration']} ч.</td>
				<td colspan='2' class='t_cell1'>{$row['otdel']}</td>
				<td class='t_cell1'>{$row['type_tour']}</td>
				<td class='t_cell1'>{$row['tour_success']}</td></tr>";

			
			/*** Добавляем список маршрутов к записи***/
			$httml .= "<tr><td colspan='2'>&nbsp;</td><td class='t_cell2'>Маршрут:</td><td colspan='4'>&nbsp;</td></tr>";
			
			$r2 = $db->query( "SELECT * FROM `v#tours` WHERE tour_id='{$row['id']}'" );
			while ($row2 = $r2->fetch_assoc()) {

				$httml .= "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
						<td class='t_tour'>{$row2['tour_from']}</td>
						<td class='t_tour'>=&gt;</td>
						<td class='t_tour'>{$row2['tour_to']}</td>
						<td class='t_tour' align='right'>{$row2['distance']} км</td></tr>";
				
			}
			/***------------------------------------***/

			/*** Добавляем коментарий к записи***/
			$httml .= "<tr><td>&nbsp;</td><td>&nbsp;</td><td class='t_cell2'>Пояснение:</td><td colspan='4'>&nbsp;</td></tr>";
			$httml .= "<tr><td colspan='2' class='t_cell0'>&nbsp;</td><td colspan='5' class='t_ref'>&nbsp;{$row['reference']}</td></tr>";
			/***------------------------------------***/
			
		}

		$httml .= "</tbody></table><p>&nbsp;</p></body></html>";
		
		echo $httml;
	}

?>