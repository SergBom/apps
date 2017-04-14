<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('portal');
/*---------------------------------------------------------------------------*/
	$adata = $_GET; //(object)$_GET;



//print_r($adata);
if( isset($adata['org_id']) ){
	
	$ifOrgID = " AND org_id=".$adata['org_id'];
	
		// collect request parameters
		$start  = isset($adata['start'])  ? $adata['start']  :  0;
		$limit  = isset($adata['limit'])  ? " LIMIT $start,".$adata['limit']  : " LIMIT $start,25";
//		$end	= $start + $limit;
		$sort   = isset($adata['sort'])   ? json_decode($adata['sort'])   : null;

		$inFilter = ($adata['filter']<>"") ? mb_strtolower($adata['filter'],"UTF-8") : "";
		$filters =  ($adata['filter']<>"") ? " and (lower(userFm) like '%".$inFilter."%' OR lower(userIm) like '%".$inFilter."%' OR lower(userOt) like '%".$inFilter."%')" : "";
//		$filters = str_replace('property','field',$filters );
		
		// Показывать отсутствующих
		$missing = ( $adata['missing'] === 'true' ) ? "" : " and off=0";
		
		// Показывать невидимых
		$say     = ( $adata['sayAll'] === 'true' )  ? "" : " and say=1";
		
	
		$sortDirection = @$sort[0]->direction;
		$sortProperty  = (@$sort[0]->property) ? @$sort[0]->property : "FIO"; 

//		if (isset($sort[0]->property)){
//			echo "sort = ".$sort[0]->property;
//			print_r($sort);
//		}

		//print_r( $filters );
		//		echo "<p>$Filter</p>";
		//$Filter = setFilter( $filters );

	$sql = "SELECT SQL_CALC_FOUND_ROWS * from  v_pbook 	WHERE 1=1
		$say
		$ifOrgID
		$missing
		$filters
		ORDER BY otdel_code,dolzhnost_code,userFm,userIm,userOt, $sortProperty $sortDirection
	";

	//$limit
	//echo "<p>".$sql."</p>";
	
	if ( $result = $db->query( $sql ) ) {
	
		$CountRow = $db->getOne( "SELECT FOUND_ROWS() CountRows" );
		
		$data = array();
		
		while ($row = $result->fetch_assoc()) {
			
			if( $row['userImageSrc']=='' ){
				// Определяем картинку по умолчанию - Мальчик или Девочка  = /resources/user_images/people
				//echo trim($row['userOt'])."<br>";
				//$m = substr($row['userOt'], -5);
				//print_r($m);
				
				if(	preg_match("/ич$/i", trim($row['userOt']) )===1 ) {
					$row['userImageSrc'] = "/resources/images/people/man_brown.png";
				} else if( preg_match("/на$/i", trim($row['userOt']) )===1 ) {
					$row['userImageSrc'] = "/resources/images/people/user_female2.png";
				} else {
					$row['userImageSrc'] = "/resources/images/people/royal_user.png";
				}
			}
			
			array_push($data, $row);
		}

		$c = array('success'=>'true',"total"=>$CountRow,'data'=>$data);
		echo json_encode($c); //, JSON_FORCE_OBJECT);
	}

}else{
	
}	
		
?>