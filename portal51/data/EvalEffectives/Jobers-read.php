<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

//	@$_SESSION['portal']['user_id'] = $_SESSION['user_id'];
//	@$_SESSION['portal']['username'] = $_SESSION['username'];
	@$user_id = isset( $_SESSION['portal']['user_id'] )   ?  $_SESSION['portal']['user_id']    : '';
	@$org_id  = isset( $_SESSION['portal']['domain_id'] ) ?  $_SESSION['portal']['domain_id']  : '';

	$data = array();
//	$adata = (object)$_GET; //json_decode($info);

//	$Date = trim ((!empty($_GET['db'])) ? $_GET['db'] : date('Y-m-d') ); // 

    $db = ConnectMyDB('EvalEffective');
//tg.info, tu.otdel_id, t3.id,t3.name,t3.sname,t3.cn
	$sql = "select tJ.id, tJ.d_id, tJ.user_id, FIO as user, tg.groupname,
  IFNULL(t3.name,'') otdel,
  IFNULL(t3.sname,'') sotdel,
  IFNULL(t3.cn,'') cn

 from `EvalEffective`.`GZC#Jobers` tJ
	
	left join `EvalEffective`.`GZC#JobersGroup` `tg` on `tg`.`id` = `tJ`.`d_id`
	left join ( 
						select id,name,sname,cn from `portal`.`prt#otdels` where par_id=0 
						 union
						select t2.id,t1.name,t1.sname,t1.cn from `portal`.`prt#otdels` t1
							 join `portal`.`prt#otdels` t2 on t2.par_id=t1.id
							 where t1.id<>0
							 ) t3 on tJ.otdel_id=t3.id
	order by tJ.FIO							 
	";
	//$sql = "select * from `GZC#v#Jobers` order by user";
	
	
	if ( $result = $db->query( $sql ) ) {
	
		while ($row = $result->fetch_assoc()) {
			array_push($data, $row);
		}

		echo json_encode(array('success'=>'true','data'=>$data));
	}

?>
