
<html>
<head><meta charset='utf-8' /></head>
<body>
<?php

include_once("../../php/init.php");
//header('Content-type: text/html; charset=utf-8');

    $db = ConnectMyDB('Scan_docs');
	$db->query('TRUNCATE docs2');

//$directory = "Y:\scan_docs";
$directory = "/mnt/archivedocs/scan_docs";
//$dpd=0;



$rdir = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory), TRUE);
 
foreach ($rdir as $file)
{
	
   echo str_repeat('---', $rdir->getDepth()).$file.'<br>';
}




/*









///// Каталоги с годами
foreach (new DirectoryIterator($directory) as $YearInfo) {
	if($YearInfo->isDot()) continue;
	
	$Y = $YearInfo->getFilename();
	//echo "Y=$Y<br>";

	$sql = "INSERT INTO docs2 SET 	par_id=0, name='$Y', cyear='$Y'";
	$db->query($sql);
	echo $sql."<br>";
	
	$year_id = $db->insertId();
	
	
	///// Каталоги с кадастровыми номерами
	foreach (new DirectoryIterator($directory.'/'.$Y) as $CadnumInfo) {
		if($CadnumInfo->isDot()) continue;
		
		$sql = "INSERT INTO docs2 SET par_id='$year_id', name='{$CadnumInfo->getFilename()}', cyear='$Y'";
		$db->query($sql);
		echo $sql."<br>";

		echo $CadnumInfo->getPathname."<br>";
		
		///// Каталоги с томами
		foreach (new DirectoryIterator( rawurlencode ($directory.'/'.$CadnumInfo->getFilename() ) ) as $VolInfo) {
			if($VolInfo->isDot()) continue;
		
			$sql = "INSERT INTO docs2 SET par_id='$year_id', name='{$VolInfo->getFilename()}', cyear='$Y'";
			$db->query($sql);
			echo $sql."<br>";
		}
		
	//     echo "Dir:[".$fileInfo->getFilename()."]";
	//	 echo "[".date ("Y-m-d",$fileInfo->getMTime()) . "]";
	 /*
		// Подсчет кол-ва файлов и размер подкаталога
		$ite=new RecursiveDirectoryIterator($directory.'/'.$CadnumInfo->getFilename());
		$bytestotal=0;
		$nbfiles=0;
		foreach (new RecursiveIteratorIterator($ite) as $filename=>$cur) {
			echo "== $filename ==<br>";
			if (!$cur->isDir()){ //
				if ( $cur->getExtension()=='pdf' || $cur->getExtension()=='sig' ){ // Выбираем только PDF и SIG
				//if ( date ("d",$cur->getMTime())==date("d") ){ // 
					//$filesize=$cur->getSize();
					$bytestotal+=$cur->getSize();
					$nbfiles++;
				//	echo "$filename => $filesize | ". date ("Y-m-d",$cur->getMTime()) ."<br>";
				//}
				}
			}
		}*/
		//$bytestotal=number_format($bytestotal);
		//echo "Total: $nbfiles files, $bytestotal bytes\n<br>"; 
		//echo "[".$fileInfo->getSize()."]<br>\n";
	 
			/*count_files=$nbfiles,
			count_size=$bytestotal,
			cdate=str_to_date('".date ("Y-m-d",$CadnumInfo->getMTime())."', '%Y-%m-%d')";
		//$db->query($sql);
			echo $sql."<br>";
	}

}
	
	/*
	
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
	
*/
?>
</body>
</html>