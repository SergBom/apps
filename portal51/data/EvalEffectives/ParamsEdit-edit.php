<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');
if ( is_session_started() === FALSE ) session_start();

/*-------------------------- Входные переменные -----------------------------*/
	//@$_SESSION['portal']['user_id'] = $_SESSION['user_id'];
	//@$_SESSION['portal']['username'] = $_SESSION['username'];
	$user_id  = isset( $_SESSION['portal']['user_id'] )   ?  $_SESSION['portal']['user_id']   : '';
	$otdel_id = isset( $_SESSION['portal']['otdel_id'] )  ?  $_SESSION['portal']['otdel_id']  : '0';
	$org_id   = isset( $_SESSION['portal']['domain_id'] ) ?  $_SESSION['portal']['domain_id'] : '0';
	$user_FIO = isset( $_SESSION['portal']['FIO'] ) ?  $_SESSION['portal']['FIO'] : '';

/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('EvalEffective');
/*---------------------------------------------------------------------------*/

	$data = array();
	$adata = (object)$_POST;
	
	print_r($adata);
	//print_r($_SESSION['portal']);
	
	$dateOtchet = substr($adata->dateOtchet,0,10);
	//echo "User_id='$user_id'<br>";
	$sql = "select id from `GZC#GZI` where R_id={$adata->rec_id} and user_id=$user_id and dateOtchet='$dateOtchet'";
	$id = $db->getOne( $sql );
	//echo "id='$id'";
	if ( $id == '' ){ // Значит новая запись

		$db->query("INSERT INTO `GZC#GZI` SET 
			R_id={$adata->rec_id},
			user_id={$user_id},
			dateOtchet='{$dateOtchet}',
			otdel_id={$otdel_id},
			FIO='{$user_FIO}',
			value='{$adata->value}'
			");

		$ID= $db->insertId();
		
	} else { // Значит обновляем запись
		
		$db->query("UPDATE `GZC#GZI` SET 
			otdel_id={$otdel_id},
			value='{$adata->value}',
			user_id={$user_id},
			FIO='{$user_FIO}'
			WHERE id={$id}
			");
		
		$ID= $id;
		
	}

	//echo $adata->param;
	
	if($adata->param == 'N_planprov'){

		$sql = "select t1.id as id, t2.id as rec_id  from `GZC#GZI` t1
				left join `GZC#Record` t2 on t1.R_id=t2.id
				where t2.R1='N_plan' and user_id=$user_id and dateOtchet='$dateOtchet'";
		//$id = $db->getOne( $sql );
		$result = $db->query( $sql );
		$row = $result->fetch_assoc();
		
		//echo "$sql<br>";
		
		if ( $row['id'] == '' ){ // Значит новая запись

			$rec_id = $db->getOne( "select id from `GZC#Record` where R1='N_plan'" );
		
			$db->query("INSERT INTO `GZC#GZI` SET 
			R_id={$rec_id},
			user_id={$user_id},
			FIO='{$user_FIO}',
			dateOtchet='{$dateOtchet}',
			otdel_id={$otdel_id},
			value='{$adata->value}'
			");

			$ID= $db->insertId();
		
		} else { // Значит обновляем запись
		
			$db->query("UPDATE `GZC#GZI` SET 
				value='{$adata->value}'
				WHERE id={$row['id']}
				");
		
			$ID= $row['id'];
		
		}

	
	}





	
	array_push($data, array(
			'id'=>$ID,
			'R_id'=>$adata->rec_id,
			'user_id'=>$user_id,
			'dateOtchet'=>$dateOtchet,
			'value'=>$adata->value
		)
	);
	echo json_encode(array('success'=>'true','data'=>$data));

		
?>