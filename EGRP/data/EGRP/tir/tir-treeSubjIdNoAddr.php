<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
$ext_id = trim ((!empty($_POST['ext_id'])) ? $_POST['ext_id'] : "" ); // 
/*---------------------------------------------------------------------------*/






if ($ext_id) {
	
	if( strpos($ext_id,":") ){
		$ext_id = @split(':',$ext_id)[1];
	}
echo $ext_id;
	

					//$connLOC = ConnectLocalTIR(); // Присоска к базе
	$connSSD = ConnectOciDB('EGRP'); // Присоска к базе

		$sql = "select id 
					from ent_entities
					where id not in (
						select ent_id 
							from ent_address
							where ent_id in
								( select id 
									from ent_entities
									connect by prior id = arch_id
									start with id = $ext_id
								)
						)
						connect by prior id = arch_id
						start with id = $ext_id";
					
					
		$stid = oci_parse($connSSD, $sql);
		oci_execute($stid);

	// Форматирование результатов
		echo "<table style='border: 1px solid black; width:100%;'>\n";
		while ($row = oci_fetch_assoc($stid)) {
			echo "<tr style='border: 1px solid #a5a5a5;'>\n";
				echo "    <td style='padding:2px;border-right: 1px solid #a5a5a5;'></td><td>".$row['ID']."</td>\n";
			echo "</tr>\n";
		}
		echo "</table>\n";
		
//	oci_close($connSSD);
					
}

?>