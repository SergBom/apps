<?php   
$isCLI = ( php_sapi_name() == 'cli' );
//$_include_path = ($isCLI) ? "/root/script" : $_SERVER['DOCUMENT_ROOT'];
//include_once($_include_path."/lib/init2.php");
$_include_path = ($isCLI) ? "/var/www/portal/public_html" : $_SERVER['DOCUMENT_ROOT'];
include_once($_include_path."/php/init2.php");
header('Content-type: text/html; charset=utf-8');
/*-------------------------- Входные переменные -----------------------------*/
//$workdir = "/media/hdb1/sftp/zags";

$workdir = $_include_path . "/portal51/data/HW_PC/hwpc";
echo $workdir ."<br>";

/*---------------------------------------------------------------------------*/
    $db = ConnectPDO('HW_PC');
/*---------------------------------------------------------------------------*/

//$filelist = scandir( $workdir . "/*.xml");

chdir( $workdir );

//	$filelist = array();
	if ($handle = opendir(".")) {
	    while ($entry = readdir($handle)) {
			
			if (substr($entry, -4) ==  ".xml") {

				echo  $entry ."<br>";
				
				$xml = simplexml_load_file($entry);
				
				if($xml ===  FALSE) continue;
				
				$DNSHostName = explode("_",$entry)[0];
				
				$Header = $xml->HEADER->attributes();
				$tmp = explode(".",$Header['Date']);
				$HDate = $tmp[2] . "-" . $tmp[1] . "-" . $tmp[0];
				$HTime = $Header['Time']; // . ":00";
				
				$CS = $xml->ComputerSystem->attributes();
				//$DNSHostName = $CS['DNSHostName'];
				$IPAddress = $CS['IPAddress'];
				$NumberOfProcessors = $CS['NumberOfProcessors'];
				$Manufacturer = $CS['Manufacturer'];
				$Model = $CS['Model'];
				$SystemType = $CS['SystemType'];
				$TotalPhysicalMemory = $CS['TotalPhysicalMemory'];
				$UserName = addslashes($CS['UserName']);
				
				$tmp = explode("\\" , $CS['UserName']);

				if( $tmp[0] == 'MURMANSK' ){
					$UName = $db->query("select concat(u.userFm,' ',u.userIm,' ',u.userOt) uname from `portal`.`prt#users` u WHERE username = '{$tmp[1]}'")->fetchColumn();
				} else {
					$UName = "";
				}
				
				
				
				$id = $db->query("SELECT id FROM main WHERE DNSHostName = '$DNSHostName'")->fetchColumn();
				if( $id == 0 ){
				
					$sql = "INSERT INTO main SET
						DNSHostName = '$DNSHostName',
						HDate = '$HDate',
						HTime = '$HTime',
						IPAddress = '$IPAddress',
						Manufacturer = '$Manufacturer',
						Model = '$Model',
						TotalPhysicalMemory = '$TotalPhysicalMemory',
						UserName = '$UserName',
						UName = '$UName'
						
						";
					$db->query( $sql );
					$id = $db->lastInsertId();	
				} else {
					$sql = "UPDATE main SET
						DNSHostName = '$DNSHostName',
						HDate = '$HDate',
						HTime = '$HTime',
						IPAddress = '$IPAddress',
						Manufacturer = '$Manufacturer',
						Model = '$Model',
						TotalPhysicalMemory = '$TotalPhysicalMemory',
						UserName = '$UserName',
						UName = '$UName'
						WHERE id=$id
						";
					$db->query( $sql );
				}
				
				echo $sql ."<br>";
				echo "ID='$id'<br>";
				
				
//				foreach($xml->HEADER->attributes() as $a => $b) {
//					echo $a,'="',$b,"\"\n";
//				}

				echo "---------<br>";

				foreach($xml->PhysicalMemory as $item)
				{
					foreach($item->element as $v){
						//print_r( $v->attributes() );
						//foreach($v->attributes() as $a => $b) {
						//	echo $a,'="',$b,"\"\n";
						//}
						$sql = "INSERT INTO PhysicalMemory SET
							main_id = $id,
							DeviceLocator = '{$v->attributes()['DeviceLocator']}',
							Name = '{$v->attributes()['Name']}',
							Capacity = '{$v->attributes()['Capacity']}',
							Status = '{$v->attributes()['Status']}'
							ON DUPLICATE KEY UPDATE main_id = $id,
								DeviceLocator = '{$v->attributes()['DeviceLocator']}',
								Name = '{$v->attributes()['Name']}',
								Capacity = '{$v->attributes()['Capacity']}',
								Status = '{$v->attributes()['Status']}'
						";
						
						echo $sql.	"<br>";	
						$db->query( $sql );
					}	
					
					echo	"<br>";
					
					
				}
				//print_r($xml);
				echo "---------<br>";

				foreach($xml->Processor as $item)
				{
					foreach($item->element as $v){
						$sql = "INSERT INTO Processor SET
							main_id = $id,
							DeviceID = '{$v->attributes()['DeviceID']}',
							Name = '". trim($v->attributes()['Name'])."',
							Caption = '{$v->attributes()['Caption']}',
							Family = '{$v->attributes()['Family']}',
							DataWidth = '{$v->attributes()['DataWidth']}',
							NumberOfCores = '{$v->attributes()['NumberOfCores']}',
							MaxClockSpeed = '{$v->attributes()['MaxClockSpeed']}',
							SocketDesignation = '{$v->attributes()['SocketDesignation']}',
							Status = '{$v->attributes()['Status']}'
							
							ON DUPLICATE KEY UPDATE main_id = $id,
								DeviceID = '{$v->attributes()['DeviceID']}',
								Name = '". trim($v->attributes()['Name'])."',
								Caption = '{$v->attributes()['Caption']}',
								Family = '{$v->attributes()['Family']}',
								DataWidth = '{$v->attributes()['DataWidth']}',
								NumberOfCores = '{$v->attributes()['NumberOfCores']}',
								MaxClockSpeed = '{$v->attributes()['MaxClockSpeed']}',
								SocketDesignation = '{$v->attributes()['SocketDesignation']}',
								Status = '{$v->attributes()['Status']}'
						";
						
						echo $sql.	"<br>";	
						$db->query( $sql );
					}	
					
					echo	"<br>";
					
					
				}

				echo "---------<br>";

				foreach($xml->DiskDrive as $item)
				{
					foreach($item->element as $v){
						$sql = "INSERT INTO DiskDrive SET
							main_id = $id,
							`Index` = '{$v->attributes()['Index']}',
							`InterfaceType` = '{$v->attributes()['InterfaceType']}',
							`Model` = '{$v->attributes()['Model']}',
							`MediaType` = '{$v->attributes()['MediaType']}',
							`Partitions` = '{$v->attributes()['Partitions']}',
							`Size` = '{$v->attributes()['Size']}',
							`Manufacturer` = '{$v->attributes()['Manufacturer']}',
							`Status` = '{$v->attributes()['Status']}'
							ON DUPLICATE KEY UPDATE main_id = $id,
								`Index` = '{$v->attributes()['Index']}',
								`InterfaceType` = '{$v->attributes()['InterfaceType']}',
								`Model` = '{$v->attributes()['Model']}',
								`MediaType` = '{$v->attributes()['MediaType']}',
								`Partitions` = '{$v->attributes()['Partitions']}',
								`Size` = '{$v->attributes()['Size']}',
								`Manufacturer` = '{$v->attributes()['Manufacturer']}',
								`Status` = '{$v->attributes()['Status']}'
						";
						
						echo $sql.	"<br>";	
						$db->query( $sql );
					}	
					
					echo	"<br>";
					
					
				}




				echo "*********<br><br>";

				unlink($entry);
			}			
	    }
	    closedir($handle);
	}







//print_r( $filelist);













?>