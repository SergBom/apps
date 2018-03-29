<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

$uploaddir = "files/";

/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('portal');
/*---------------------------------------------------------------------------*/

	//$_SESSION['portal']['user_id'] = $_SESSION['user_id'];
	//$_SESSION['portal']['username'] = $_SESSION['username'];

	//print_r($adata);
	
	//$user_id = isset( $_SESSION['portal']['user_id'] ) ?  $_SESSION['portal']['user_id']  : '';
	//$org_id  = isset( $portal_auth['domain_id'] ) ? ($portal_auth['domain_id'])? $portal_auth['domain_id']:1  : 1;


	$adata = (object)$_POST;
	//$id = $adata->id;
/*	
if(	$id==0 ){ // Add
	$db->query("INSERT INTO `refusals` SET
			cad_num='".trim($adata->cad_num)."',
			address='".trim($adata->address)."',
			reference='".trim($adata->reference)."',
			userInsert='$user_id',
			userUpdate='$user_id'"
		);		
	$id = $db->insertId();
} else { // Edit
	$db->query("UPDATE `refusals` SET
			cad_num='".trim($adata->cad_num)."',
			address='".trim($adata->address)."',
			reference='".trim($adata->reference)."',
			userUpdate='$user_id'
			WHERE id=$id"
		);		
}
*/


			$fileDoc = "xml_jcal_".str_pad(0, 5, "0", STR_PAD_LEFT).".xml";
			$xml = simplexml_load_file( $uploaddir.$fileDoc );
			
			//print_r($xml->days);
			
			echo "<br>==<br>";
			$attr = iterator_to_array($xml->attributes());
			//print_r($attributes); echo "<br>";
			echo 'year='.$attr['year'][0]."<br>";
			//echo $attributes['lang'][0]."<br>";
			//echo $attributes['date'][0]."<br>";
						
			echo "<br>==<br>";
			
			
			foreach($xml->holidays[0] as $a=>$b) {
				
				echo $b['id']." = ".$b['title']."<br>";
				
				$sql= "SELECT count(*) cnt FROM `prt#jcal_holiday` WHERE id_h=".$b['id']." AND y='".$attr['year'][0]."'";
				echo "$sql<br>";
				
				$c = $db->getOne("SELECT count(*) cnt FROM `prt#jcal_holiday` WHERE id_h=".$b['id']." AND y='".$attr['year'][0]."'");
				if($c == '0'){
					$db->query("INSERT INTO `prt#jcal_holiday` SET
					id_h='".$b['id']."',
					y='".$attr['year'][0]."',
					title='".$b['title']."'"
					);						
				}
				
				
			}

			echo "<br>==<br>";
			foreach($xml->days[0] as $a=>$b) {
				
				$dd = $attr['year'][0].".".$b['d'];
				
				$sql= "SELECT count(*) cnt FROM `prt#jcal` WHERE c_date='".$dd."'";
				echo "$sql<br>";
				$c = $db->getOne($sql);
				
				
				echo "d=".$dd."   t=".$b['t']."   h=".$b['h']."<br>";
				
				if($c == '0'){
					$db->query("INSERT INTO `prt#jcal` SET
					c_year='".$attr['year'][0]."',
					c_date='".$dd."',
					c_typeday='".$b['t']."',
					c_holiday='".$b['h']."'"
					
					);						
				}
				
				
				
			}
			
			echo "<br>==<br>";
			
			
			

?>