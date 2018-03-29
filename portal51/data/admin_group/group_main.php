<?php   
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');
/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $link = ConnectMyDB('portal');
/*---------------------------------------------------------------------------*/

	//$strSQL = "SELECT id, name, app_nik  FROM `portal`.`prt#groups` ORDER  by id";
	$strSQL = "select u.id,u.username,u.main_group, concat(u.userFm,' ',u.userIm,' ',u.userOt)as fio,
						d.name domain,
						gm.name main_group_name, gm.app_nik main_group_nik
						FROM `portal`.`prt#users` u
						left join `prt#v#groups_main` gm on gm.id=u.main_group
						left join `prt#domains` d on d.id=u.domain_id
						WHERE username<>'admin'
						ORDER BY fio";
$result = $link->query($strSQL);

$data = array();


while($row = mysqli_fetch_array($result)) {
   array_push($data, $row);
}
$c = array('success'=>0,'data'=>$data);
echo json_encode($c);
 
?>


