<?php
header('Content-type: text/html; charset=utf-8');
$file_init = "/php/init2.php";
$isCLI = ( php_sapi_name() == 'cli' );
if($isCLI){
	include_once("/var/www/portal/public_html".$file_init);
}else{
	include_once("{$_SERVER['DOCUMENT_ROOT']}".$file_init);
}


	$db		= ConnectPDO('Portal');



$st = $db->query("SELECT id,IF(par_id=0,1000000,par_id) par_id,name FROM `prt#otdels` WHERE org_id=1");// and id<>0");

$all_array = array();
$i=0;
while($rows = $st->fetch()){
	$all_array[$i]['id']=$rows['id'];
	$all_array[$i]['par_id']=$rows['par_id'];
	$all_array[$i]['name']=$rows['name'];
	$all_array[$i]['leaf']='false';
	$i++;
}

		
$count= count ($all_array);
//echo $count;

$cats = getCats(1000000,$count);
echo json_encode( array('children'=>$cats) ); //,'expanded'=>'true'

		
function getCats($intparentid,$count){
	//echo "вызван ололо с параметрами ".$intparentid ." ".$count. "<br>";
	global $all_array;
	$tree = array();
	for ($i = 0; $i<$count; $i++) {
		if($all_array[$i]['par_id']==$intparentid){
			// echo "cjdgf<br>";
			//$ololo=all_array[$i]['parentid'];
			$temp=getCats($all_array[$i]['id'],$count);
			if (!empty($temp)){
				$data3 = array('id'=>$all_array[$i]['id']
					,'name'=>$all_array[$i]['name']
					//,'url'=>$all_array[$i]['url']
					//,'coment'=>$all_array[$i]['coment']
					//,'admin'=>$all_array[$i]['admin']
					,'iconCls'=>"icon-folder-explore"
					,'children'=>$temp);
			}else{
				$data3 = array('id'=>$all_array[$i]['id'],'name'=>$all_array[$i]['name']
					//,'url'=>$all_array[$i]['url'],'coment'=>$all_array[$i]['coment'],'admin'=>$all_array[$i]['admin']
					,'iconCls'=>"icon-folder"
					,'leaf'=>'true');
			}
	
		array_push($tree, $data3); 
		}
	}
	return $tree;
}


//
					
?>