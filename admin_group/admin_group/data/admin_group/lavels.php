<?php   

include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');
/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $link = ConnectMyDB('portal');
/*---------------------------------------------------------------------------*/

$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
  case 'GET':  // SELECT
	_get_data($link,$_GET);
    break;
  case 'POST':  // SELECT
	_post_data($link,$_POST);
    break;
}



function _get_data($link,$adata){	

	$data=array();
	
	if($adata['in_id_group']==1){ //Значит Админская группа приложений - выводим все приложения
		$strSQL = "SELECT id, text as name FROM `portal`.`prt#menu` where enabled=1 AND text<>'' ORDER  by text";
	}else{
		$strSQL = "SELECT distinct m.id, m.text as name FROM `portal`.`prt#menu` m
					LEFT JOIN `portal`.`prt#lavel_access` la ON m.id=la.id_app
					where m.enabled=1 AND m.text<>'' AND (la.id_group<>1 OR la.id_group is null) ORDER  by m.text";
	}
//	$strSQL = "SELECT id, alias as name FROM `portal`.`prt#app` where parameter='application.controllers' ORDER  by id";
	$res_app = $link->query($strSQL);

	$strSQL = "SELECT id_group,id_app,lavel FROM `portal`.`prt#lavel_access` where id_group={$adata['in_id_group']}";
	$res_lavel = $link->query($strSQL);
	$all_lavel=array();
	$i=1;
	while($rows = mysqli_fetch_array($res_lavel)){
		$all_lavel[$i]['id_group']=$rows['id_group'];
		$all_lavel[$i]['id_app']=$rows['id_app'];
		$all_lavel[$i]['lavel']=$rows['lavel'];
		//echo "id=" ,$all_group[$i]['id']," name=",$all_group[$i]['name'],"<br>";
		$i++;
	}
		
	$count= count ($all_lavel);
	while($app = mysqli_fetch_array($res_app)) {
		$app_ok['id_app']=$app['id'];
		$app_ok['id_group']=$adata['in_id_group'];
		$app_ok['name']=$app['name'];
//		$app_ok['zapret']=1;
		$app_ok['read']=0;
		$app_ok['write']=0;
		for ($i = 1; $i<=$count; $i++) {
			if($app['id']==$all_lavel[$i]['id_app']){
			//if($all_lavel[$i]['lavel']==0){ $app_ok['zapret']=1; }
				if($all_lavel[$i]['lavel']==1){ //$app_ok['zapret']=0;
					$app_ok['read']=1; }
				if($all_lavel[$i]['lavel']==2){ //$app_ok['zapret']=0;
					$app_ok['write']=1; }
			}
			//	$strSQL = "SELECT group_id FROM `portal`.`prt#user_group` where user_id=$var1 and group_id=$var2";
			//	$res_prava = $link->query($strSQL);
			//	list($pravo) = mysqli_fetch_array($res_prava);
			//	$pravo	  = isset($pravo)		? 1	:  0;
			//$row=array($group['name']=>$pravo);
			//	echo "тип row=".gettype($row)."тип user=".gettype($user);
			//	$user[$all_group[$i]['name']]=$pravo;
		}
  
  
		array_push($data, $app_ok);
	}
	
	$c = array('success'=>0,'data'=>$data);
	echo json_encode($c);
  
}


function _post_data($link,$adata){
	 
	//echo 'он хочет записывать';
	$v1=json_decode($adata['data'],true);
	//$v2=array_keys($v1);

	$id_app=$v1['id_app'];
	//echo "id_app=".$id_app."</br>";
	$id_group=$v1['id_group'];
	//echo "id_group=".$id_app."</br>";
	$lavel=0;
	//if($v1['read']==0){
	//$lavel=1;	
	//}
	
	$strSQL ="DELETE FROM `portal`.`prt#lavel_access` WHERE id_group=$id_group AND id_app=$id_app";
	$result = $link->query($strSQL);

	if($v1['read']==1){	
		$strSQL ="INSERT INTO `portal`.`prt#lavel_access` (`id_group`,`id_app`,`lavel`) VALUES ($id_group, $id_app, 1);";
	}
	if($v1['write']==1){
		$strSQL ="INSERT INTO `portal`.`prt#lavel_access` (`id_group`,`id_app`,`lavel`) VALUES ($id_group, $id_app, 2);";
	}

	$result = $link->query($strSQL);
	
	echo json_encode(array('success'=>0));
	
	
}

?>


