<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');

/*---------------------------------------------------------------------------*/
    $db = ConnectMyDB('portal');
/*---------------------------------------------------------------------------*/
	$adata = $_GET; //(object)$_GET;



//print_r($adata);
if( isset($adata['org_id']) ){
	
	$ifOrgID = " AND t1.org_id=".$adata['org_id'];
	
		// collect request parameters
		$start  = isset($adata['start'])  ? $adata['start']  :  0;
		$limit  = isset($adata['limit'])  ? " LIMIT $start,".$adata['limit']  : " LIMIT $start,25";
//		$end	= $start + $limit;
		$sort   = isset($adata['sort'])   ? json_decode($adata['sort'])   : null;
		$inFilter = ($adata['filter']<>"") ? mb_strtolower($adata['filter'],"UTF-8") : "";
		$filters =  ($adata['filter']<>"") ? " and (lower(userFm) like '%".$inFilter."%' OR lower(userIm) like '%".$inFilter."%' OR lower(userOt) like '%".$inFilter."%')" : "";
//		$filters = str_replace('property','field',$filters );
		
//		echo $adata['missing']."<br>";
		$missing = ( $adata['missing'] === 'true' ) ? "" : " and off=0";
		$say     = ( $adata['sayAll'] === 'true' )  ? "" : " and say=1";
//		echo $missing."<br>";
		
	
		$sortDirection = @$sort[0]->direction;
		$sortProperty  = (@$sort[0]->property) ? @$sort[0]->property : "FIO"; 

//		if (isset($sort[0]->property)){
//			echo "sort = ".$sort[0]->property;
//			print_r($sort);
//		}

		//print_r( $filters );
		//		echo "<p>$Filter</p>";
		//$Filter = setFilter( $filters );

	

	$sql = "SELECT SQL_CALC_FOUND_ROWS 
	t1.*, concat(t1.userFm,' ',t1.userIm,' ',t1.userOt) as FIO,
	t2.name as org_name,
	t3.name otdel_name,
	t3.code otdel_code,
	t4.name dolzhnost_name,
	t4.code dolzhnost_code,
	t5.name address_name
	from (
		select concat('p-',id) as id,             org_id,userFm,userIm,userOt,userImageSrc,otdel_id,dolzhnost_id,address_id,tel1,tel2,telIP,email,say,'' as dateOff,'' as dateOn,'0' as off
			from `prt#pbook`
		union
		select concat('u-',id) as id,             org_id,userFm,userIm,userOt,userImageSrc,otdel_id,dolzhnost_id,address_id,tel1,tel2,telIP,email,say,      dateOff,      dateOn,       off
			from `prt#users`
		) t1

	left join `prt#orgs` t2 on t1.org_id=t2.id
	left join `prt#otdels` t3 on t1.otdel_id=t3.id
	left join `prt#dolzhnost` t4 on t1.dolzhnost_id=t4.id
	left join `kl#address` t5 on t1.address_id=t5.id
	WHERE 1=1
		$say
		$ifOrgID
		$missing
		$filters
						ORDER BY t3.code asc,t4.code asc, t1.userFm,t1.userIm,t1.userOt, $sortProperty $sortDirection
	";
//-- left join `portal_benzine`.`address` t5 on t1.address_id=t5.id
//-- left join `portal_benzine`.`city` t6 on t5.city_id=t6.id

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