
<html>
<head><meta charset='utf-8' /></head>
<body>
<?php

include_once("../../php/init.php");
//header('Content-type: text/html; charset=utf-8');

    $db = ConnectMyDB('Scan_docs');
	$db->query('TRUNCATE docs');

//$directory = "Y:\scan_docs";
$directory = "/mnt/archivedocs/scan_docs";
//$dpd=0;

foreach (new DirectoryIterator($directory) as $fileInfo) {
     if($fileInfo->isDot()) continue;
//     echo "Dir:[".$fileInfo->getFilename()."]";
//	 echo "[".date ("Y-m-d",$fileInfo->getMTime()) . "]";
	 
	$ite=new RecursiveDirectoryIterator($directory.'/'.$fileInfo->getFilename());
	$bytestotal=0;
	$nbfiles=0;
	foreach (new RecursiveIteratorIterator($ite) as $filename=>$cur) {
		if (!$cur->isDir()){ //
			if ( $cur->getExtension()=='pdf' || $cur->getExtension()=='sig' ){ // Выбираем только PDF и SIG
			//if ( date ("d",$cur->getMTime())==date("d") ){ // 
				//$filesize=$cur->getSize();
				$bytestotal+=$cur->getSize();
				$nbfiles++;
	//			echo "$filename => $filesize | ". date ("Y-m-d",$cur->getMTime()) ."<br>";
			//}
			}
		}
	}
	//$bytestotal=number_format($bytestotal);
	//echo "Total: $nbfiles files, $bytestotal bytes\n<br>"; 
	//echo "[".$fileInfo->getSize()."]<br>\n";
	 
	$sql = "INSERT INTO docs SET cad_num='{$fileInfo->getFilename()}', count_files=$nbfiles, count_size=$bytestotal, cdate=str_to_date('".date ("Y-m-d",$fileInfo->getMTime())."', '%Y-%m-%d')";
	$db->query($sql);
//	echo $sql."<br>";
}


	$sql = "select (select count(*) from v\$cad_nums ";
	$sql .= " ) as count_dpd,";
	$sql .= " (select IFNULL(round(sum(count_size)/(1024*1024*1024),1),0) from v\$cad_nums ";
	$sql .= " ) as count_size,";
	$sql .= " (select IFNULL(sum(count_files),0) from v\$cad_nums ";
	$sql .= " ) as count_files";

	$out = "<table>";
	
	if ( $result = $db->query( $sql ) ) {
		while ($row = $result->fetch_assoc()) {
			$out .= "<tr><td>Кодичество ДПД</td><td>".$row['count_dpd']."</td></tr>
				<tr><td>Объем ДПД:</td><td>".$row['count_size']."Gb</td></tr>
				<tr><td>Общее количество документов:</td><td>".$row['count_files']."</td></tr>";
		}
	}
	$out .= "</table>";

	echo $out;

	$f = fopen("/var/www/portal/public_html/apps/scandocs/scanfiles.html", "w");
	fwrite($f, $out); 
	fclose($f);
	
        mail_send( array(
                'to_email'              => 'admin@r51.rosreestr.ru',
                'from_email'    => 'root@web2.murmansk.net',
                'subject'               => 'REPORT DPD',
                'message'               => $out
        ));
	

?>
</body>
</html>