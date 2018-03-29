<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
$id = trim ((!empty($_GET['id'])) ? $_GET['id'] : "" ); // 
$dept_id = trim ((!empty($_GET['dept_id'])) ? $_GET['dept_id'] : "" ); // 
/*---------------------------------------------------------------------------*/

if($id and $dept_id){

		$conn = ConnectGRP(1); // Присоска к базе

		$sql = "ALTER TABLE adr_address_voc DISABLE ALL TRIGGERS";
		$stid = oci_parse($conn, $sql);
		oci_execute($stid);
		
		
		$sql = "
		select id from (
			select t4.* from  adr_address_voc t4
				where t4.par_id in (
					select t3.par_id from  adr_address_voc t3
					where t3.par_id in (
						select t2.id from  adr_address_voc t2
						where t2.par_id in (
							select t1.id from adr_address_voc t1
							where t1.par_id in ($id)
					)
				)
			)
		union
			select t3.* from  adr_address_voc t3
				where t3.par_id in (

					select t2.id from  adr_address_voc t2
					where t2.par_id in (
						select t1.id from adr_address_voc t1
						where t1.par_id in ($id)
					)
				)
			union
				select t20.* from  adr_address_voc t20
				where t20.par_id in (
					select t21.id from adr_address_voc t21
					where t21.par_id in ($id)
				)
			union
				select t10.* from adr_address_voc t10
				where t10.par_id in ($id)

			) 
				where dept_id <> $dept_id
		";
		$stid = oci_parse($conn, $sql);
		oci_execute($stid);

		$cnt = 1;
		$ar = array();
		while ($row = oci_fetch_array($stid)){
			$sql = "update adr_address_voc set DEPT_ID=$dept_id, MOVED='Н' where id = ".$row['ID'];
			echo "($cnt) - $sql";
			$stid2 = oci_parse($conn, $sql);
			if (! oci_execute($stid2, OCI_NO_AUTO_COMMIT) ){
				echo "";
				$e = oci_error($stdi2); 
				echo "<p>".$e['message']."</p>"; 
				$ar[] = $row['ID'];
			} else {
				oci_commit($conn);
				echo " - OK<br>";
			}
			$cnt ++;
		}


		$sql = "ALTER TABLE adr_address_voc ENABLE ALL TRIGGERS";
		$stid = oci_parse($conn, $sql);
		oci_execute($stid);
		

		
		@oci_free_statement($stid);
		@oci_free_statement($stid2);
		@oci_close($conn);

		echo "<p>КОНЕЦ</p>========================";
		echo "<p>Список не прошедших:</p>";
		
		foreach( $ar as $v){
			echo "$v,<br>";
		}
		
		
} else {
	echo "<p>Требуется ввести параметры:</p>";
	echo "<p>id и dept_id:</p>";
	echo "http://portal.murmansk.net/php/tir2/db-adr_address_voc.php?id=165282001&dept_id=1";
}
