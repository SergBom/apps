<?php   
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');
/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $link = ConnectMyDB('portal');
/*---------------------------------------------------------------------------*/

$strSQL = "select * from tabMainLinks ORDER BY ord, name, id";
$result = $link->query($strSQL);
$all_array = array();
$i=1;
while($rows = mysqli_fetch_array($result)){
	$all_array[$i]['id']=$rows['id'];
	$all_array[$i]['parentid']=$rows['parentid'];
	$all_array[$i]['name']=$rows['name'];
	$all_array[$i]['url']=$rows['url'];
	$all_array[$i]['coment']=$rows['coment'];
	$all_array[$i]['admin']=$rows['admin'];
	$all_array[$i]['leaf']='false';
	$i++;
}

		
$count= count ($all_array);
//echo $count;
		
function getCats($intparentid,$count){
	//echo "вызван ололо с параметрами ".$intparentid ." ".$count. "<br>";
	global $all_array;
	$tree = array();
	for ($i = 1; $i<=$count; $i++) {
		if($all_array[$i]['parentid']==$intparentid){
			// echo "cjdgf<br>";
			//$ololo=all_array[$i]['parentid'];
			$temp=getCats($all_array[$i]['id'],$count);
			if (!empty($temp)){
				$data3 = array('id'=>$all_array[$i]['id']
					,'name'=>$all_array[$i]['name']
					,'url'=>$all_array[$i]['url']
					,'coment'=>$all_array[$i]['coment']
					,'admin'=>$all_array[$i]['admin']
					,'children'=>$temp);
			}else{
				$data3 = array('id'=>$all_array[$i]['id'],'name'=>$all_array[$i]['name'],
					'url'=>$all_array[$i]['url'],'coment'=>$all_array[$i]['coment']
					,'admin'=>$all_array[$i]['admin']
					,'leaf'=>'true');
			}
	
		array_push($tree, $data3); 
		}
	}
	return $tree;
}

$cats = getCats(0,$count);
//echo json_encode( array('text'=>".",'expanded'=>'true','children'=>$cats) );
echo json_encode( array('text'=>".",'expanded'=>'true','children'=>$cats) );

?>