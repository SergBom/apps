<?php   
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');
/*-------------------------- Входные переменные -----------------------------*/
$uploaddir = "files/";
/*---------------------------------------------------------------------------*/
    $db = ConnectPDO('Deads');
/*---------------------------------------------------------------------------*/
 
 $f = array();
 
// Загружаем файл
				
				$wfile = 'files/19.04.17-21.04.17.txt';
				
			$f = file($wfile);
			
			$marker = trim($f[0]);
			if($marker == '==ЗАГС=='){
				echo "<b>$marker</b><br>";
			
				//$s = explode('|',$f[1]);	// Распаковка Источника - т.е. Откуда пришли данные
				$place = trim($f[1]);		//
				echo "<b>$place</b><br>";

				// Распаковка данных
				for ( $i=2; $i<count($f); $i++ ){
				
					$str = explode('|',$f[$i]);
				
					if( trim($str[0]) <> '' ){ 
				
						$FIO = trim($str[0]);
						$DR = dpars($str[1]);
						$Period1 = dpars($str[3]);
						$Period2 = dpars($str[4]);
						$MR = trim($str[2]);
				
						$sql = "INSERT INTO main SET
						FIO='$FIO', DR='$DR', MR='$MR', Period1='$Period1', Period2='$Period2', Place='$place'
						ON DUPLICATE KEY UPDATE FIO='$FIO', DR='$DR', Period1='$Period1', Place='$place'
						";

						echo $sql."<hr>";
					
						$db->query($sql);
					
					}
				}
				


				echo json_encode(array(
				"success" => true,
				//"fileName" => $fileName,
				"message" => "Файл загружен в хранилище"
				//"t"=>$f
//				'sql'=>$strSQL
				));
			} else {
				echo "Не верный маркер = '$marker'<br>";
			}
			
function dpars($d){
	$R = date_parse($d);
	return $R['year'] .".". $R['month'] .".". $R['day'];
}

?>