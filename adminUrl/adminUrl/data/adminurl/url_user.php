<?php   
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');

/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $link = ConnectPDO('PORTAL');
	
/*---------------------------------------------------------------------------*/

//$strSQL = "select * from tabMainLinks where admin=0 ORDER BY name";
$strSQL = "select * from tabMainLinks where admin <= (select main_group from `prt#users`
	 where username='{$_SESSION['portal']['username']}') ORDER BY name";
$stm = $link->query($strSQL);
$all_array = array();
$i=0;

while($rows = $stm->fetch()){
	$all_array[$i]['id']=$rows['id'];
	$all_array[$i]['parentid']=$rows['parentid'];
	$all_array[$i]['name']=$rows['name'];
	$all_array[$i]['url']=$rows['url'];
	$all_array[$i]['coment']=$rows['coment'];
	$all_array[$i]['leaf']='false';
	$i++;
}

		
	$count = $i-1;// count ($all_array);
	//echo $count;
		
function getCats($intparentid,$count){
	//echo "вызван ололо с параметрами ".$intparentid ." ".$count. "<br>";
	global $all_array;
	$tree = array();
	for ($i = 0; $i<=$count; $i++) {
		if($all_array[$i]['parentid']==$intparentid){
			// echo "cjdgf<br>";
			//$ololo=all_array[$i]['parentid'];
			$temp=getCats($all_array[$i]['id'],$count);
			if (!empty($temp)){
				$data3 = array('id'=>$all_array[$i]['id']
					,'name'=>$all_array[$i]['name']
					,'url'=>$all_array[$i]['url']
					,'coment'=>$all_array[$i]['coment']
					,'children'=>$temp);
			}else{
				$data3 = array('id'=>$all_array[$i]['id'],'name'=>$all_array[$i]['name'],
					'url'=>$all_array[$i]['url'],'coment'=>$all_array[$i]['coment']
					,'leaf'=>'true');
			}
		
		array_push($tree, $data3); 
		}


	}
	return $tree;
}

$cats = getCats(0,$count);
$c = array('text'=>".",'children'=>$cats);
echo json_encode($c);
?>