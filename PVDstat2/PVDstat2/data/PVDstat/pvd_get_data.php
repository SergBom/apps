<?php
include_once("../../../php/init.php");
#header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
//$Org_id = trim ((!empty($_GET['org'])) ? $_GET['org'] : "1" ); // 
/*---------------------------------------------------------------------------*/
$n = 0;
$port = 1521;
$html = "<!DOCTYPE html><html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<title>Servers PVD state</title>
</head>
<body>\n";
echo "<!DOCTYPE html><html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<title>Servers PVD state</title>
</head>
<body>\n";


    $db = ConnectOciDB('SB_PVD');

	$sql = "SELECT * FROM T\$TLINK ORDER BY NAME_LINK";
	$stid = oci_sql_exec($db, $sql);
	
	$html .= "<table border='1' cellspacing='0' cellpadding='1'>\n";
	echo "<table border='1' cellspacing='0' cellpadding='1'>\n";
	while($row = oci_fetch_array($stid)){
		$n++;
		
		$fp = @fsockopen($row['CONN'],$port,$errno,$errstr,5);
		    
		if($fp){ //Если удачное соединение
			$html .= "<tr style='color:green;background-color:#eeeeec'><td align='center'>".$n."</td>";
			echo "<tr style='color:green;background-color:#eeeeec'><td align='center'>".$n."</td>";
			$html .= '<td>'.$row['NAME_TO']."</td><td>".$row['CONN']."</td><td> -> порт 1521 открыт</td><td>"
						.date("Y-m-d H:i:s",strtotime("now"))."</td></tr>\n";
			echo '<td>'.$row['NAME_TO']."</td><td>".$row['CONN']."</td><td> -> порт 1521 открыт</td><td>"
						.date("Y-m-d H:i:s",strtotime("now"))."</td></tr>\n";
			fclose($fp);
			
			$st = oci_parse($db, 'begin dataimport(:p1); end;');
			oci_bind_by_name($st, ':p1', $row['NAME_LINK']);
			oci_execute($st);
			
		} else { //Если неудачное соединение
			$html .=  '<tr style="color:white;background-color:#f57900;"><td>'.$n.'</td>';
			echo '<tr style="color:white;background-color:#f57900;"><td>'.$n.'</td>';
			$html .= '<td>'.$row['NAME_TO']."</td><td>".$row['CONN']."</td><td> -> порт 1521 закрыт</td><td>"
					.date("Y-m-d H:i:s",strtotime("now"))."</td></tr>\n";
			echo '<td>'.$row['NAME_TO']."</td><td>".$row['CONN']."</td><td> -> порт 1521 закрыт</td><td>"
					.date("Y-m-d H:i:s",strtotime("now"))."</td></tr>\n";
			$html .= '<tr style="color:white;background-color:#f57900;"><td>'.$n.'</td><td colspan=4>error num: '.$errno.' : '.$errstr."</td></tr>\n";
			echo '<tr style="color:white;background-color:#f57900;"><td>'.$n.'</td><td colspan=4>error num: '.$errno.' : '.$errstr."</td></tr>\n";
			
		}
	}
	$html .=  "</table>\n";
	echo "</table>\n";
	$html .= "</body></html>";
    echo "</body></html>";

	//echo $html;
	$fh = fopen("/var/www/portal/public_html/apps/PVDstat/data/pvd_get_data.html","w+");
	fwrite( $fh, $html );
	fclose( $fh );

	mail_send( array(
		'to_email' 		=> 'admin@r51.rosreestr.ru',
		'from_email' 	=> 'root@web2.murmansk.net',
		'subject' 		=> 'REPORT PDV gets',
		'message' 		=> $html
	));
	
?>