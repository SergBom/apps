<?php
$isCLI = ( php_sapi_name() == 'cli' );
if($isCLI){
	include_once("/var/www/portal/public_html/php/init.php");
	//include_once("/var/www/portal/public_html/php/ldap/ldap-func.php");
}else{
	include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
	//include_once("{$_SERVER['DOCUMENT_ROOT']}/php/ldap/ldap-func.php");
}
//header('Content-type: text/html; charset=utf-8');
$html = "<!DOCTYPE html><html><head><meta charset='utf-8'></head><body>";
echo "\n\n\n";


    $db = ConnectMyDB('Scan_docs');
	$db->query('TRUNCATE docs2');

$dir = array();
$path = array("scan_docs","scan_docs2","scan_docs3","scan_docs4");
foreach ( $path as $v ){
	array_push($dir, "/mnt/archivedocs/".$v);
}
//$dir[0] = "/mnt/archivedocs/".$path[0];
//$dir[1] = "/mnt/archivedocs/".$path[1];
//$dpd=0;

$b_date = date("Y-m-d H:i:s");
$html1 = $b_date." <br>";
$html .= $html1;
echo "$html1\n";

foreach( $dir as $k_dir=>$directory ){

	///// Каталоги с годами
	//$dir = new DirectoryIterator($directory);
	//echo count($dir);
	$html1 = "Dir='$directory' <br>";
	$html .= $html1;
	echo "$html1\n";
	
	foreach (new DirectoryIterator($directory) as $YearInfo) {
		if($YearInfo->isDot()) continue;
	
		$Y = $YearInfo->getFilename();
		//echo "Y=$Y<br>";
		if((int)$Y < 2015 )  continue;

		//$sql = "INSERT INTO docs2 SET 	par_id=0, name='$Y'";
		//$db->query($sql);
		$html1 = "Год = ".$Y."<br>";
		$html .= $html1;
		echo "$html1\n";
	
		$year_id = 0; //$db->insertId();
	
		if($YearInfo->isDir()){
		///// Каталоги с кадастровыми номерами
		foreach (new DirectoryIterator($directory.'/'.$Y) as $CadnumInfo) {
			if($CadnumInfo->isDot()) continue;
		
			$sql = "INSERT INTO docs2 SET 	par_id='$year_id', name='{$CadnumInfo->getFilename()}', path='{$path[$k_dir]}',
											cyear='$Y', tp=1, cdate=str_to_date('".date("Y-m-d",$CadnumInfo->getMTime())."', '%Y-%m-%d')";
			$db->query($sql);
			//echo $sql."<br>";
		
			$cadnum_id = $db->insertId();

			$CadnumPath = $CadnumInfo->getPathname();
			//echo "Подсчет каталога: ".$CadnumPath."<br>";
			//echo $CadnumPath."<br>\n";
		
			if($CadnumInfo->isDir()){
				///// Каталоги с томами
				foreach (new DirectoryIterator( $CadnumPath  ) as $VolInfo) {
					if($VolInfo->isDot()) continue;
			
					$sql = "INSERT INTO docs2 SET 	par_id='$cadnum_id', name='{$VolInfo->getFilename()}', path='{$path[$k_dir]}',
													cyear='$Y', tp=2, cdate=str_to_date('".date("Y-m-d",$VolInfo->getMTime())."', '%Y-%m-%d')";
					$db->query($sql);
					//echo $sql."<br>";

					$vol_id = $db->insertId();
			
					$VolPath = $VolInfo->getPathname();
					//echo "=== $VolPath<br>";

					if($VolInfo->isDir()){
						///// Файлы со сканами
						foreach (new DirectoryIterator( $VolPath  ) as $FileInfo) {
							if( $FileInfo->isDot() ) continue;
				
							if( $FileInfo->getExtension()=='pdf' || $FileInfo->getExtension()=='sig'){
								$tp = ($FileInfo->getExtension()=='pdf') ? '3' : '4';
								$sql = "INSERT INTO docs2 SET 	par_id='$vol_id', name='{$FileInfo->getFilename()}', cyear='$Y', path='{$path[$k_dir]}',
																tp=$tp, cnt_size={$FileInfo->getSize()}, cdate=str_to_date('".date("Y-m-d",$FileInfo->getMTime())."', '%Y-%m-%d')";
								$db->query($sql);
								//echo "-".$sql."<br>";

								$file_id = $db->insertId();
							}
						}
					}
				}
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
			echo $sql."<br>";*/
		}
		}
	}
}


$e_date = date("Y-m-d H:i:s");	

	$html1 = $e_date." <br>";
	$html .= $html1;
	echo "$html1\n";

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