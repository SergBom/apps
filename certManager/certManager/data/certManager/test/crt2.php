<?php
///**********************************************************************///
header('Content-type: text/html; charset=utf-8'); 
$file_init = "/php/init2.php";
$isCLI = ( php_sapi_name() == 'cli' );
if($isCLI){
	include_once("/var/www/portal/public_html".$file_init);
}else{
	include_once("{$_SERVER['DOCUMENT_ROOT']}".$file_init);
}
///**********************************************************************///
$db = ConnectPDO('security');
///**********************************************************************///

//$rPath = 'N:\\INSTALL\\CryptoPro\\$ert_2016';
$rPath = 'Cert';
//$rPath = '\\\\fs-pz22\\FS_COMMON\\INSTALL\\CryptoPro';
//$rPath = ereg_replace( "/$", "", ereg_replace( "[\\]", "/", $rPath ));


$sth = $db->query("SHOW COLUMNS FROM certs where Field<>'id'");
$Columns = $sth->fetchAll(PDO::FETCH_COLUMN, 0);
print_r( $Columns );


echo $rPath;

$aField = array();


//$a = array('boyko.cer','vvv.cer','UCFK.cer','UCFK2.cer');

if( is_dir($rPath) ) {
	echo "<br>Begin<br>";
	r_dir($rPath);
}

function r_dir($dir) {

	global $aField;

	$odir = opendir($dir);

	while (($file = readdir($odir)) !== false ){
		
		if($file == '.' || $file == '..' ){ continue; }
		
//		$file = iconv('cp1251','utf-8',$file);
//		echo "$filename<br>";
		$filename = $dir . DIRECTORY_SEPARATOR . $file;
		
		if( is_dir($filename) ){
				r_dir( $filename );
		} else {
            $filename = $dir . DIRECTORY_SEPARATOR . $file;
            //echo substr($file,-4)."<br>";
            if(  substr($file,-4) == '.cer'  ) {
				
				echo "<font color='red'>".iconv('cp1251','utf-8',$filename)."</font><br>";
				
				$crt = getCert($filename);

	var_dump( $crt);
				echo "<hr>";
	
				//$aCert = array();
				$aField = array();
				recurs($crt );
				print_r( $aField);
				
				
                /*$sql = "INSERT INTO `cert2` SET 
                    is_root = $is_root,
                    sn = '{$ssl['hash']}',
                    validFrom = $validFrom,
                    validTo = $validTo,
                    subj_CN = $subj_CN,
                    subj_SNG = $subj_SNG,
	                subj_Title = $subj_Title,
	                subj_O     = $subj_O,
	                subj_Email = $subj_Email,
	                uc_CN      = $uc_CN,
	                uc_Address = $uc_Address,
	                uc_OU      = $uc_OU,
	                uc_O       = $uc_O,    
	                uc_Email   = $uc_Email,  
	                filepath   = $filepath

";

                $db->query($sql);*/
				
				
				echo "<hr><hr>";
				
				
			}
		}
	}		

}

function getCert($file)
{
    $certificateCAcerContent = file_get_contents($file);

    $certificateCApemContent = '-----BEGIN CERTIFICATE-----' .PHP_EOL
        . chunk_split(base64_encode($certificateCAcerContent), 64,PHP_EOL)
        . '-----END CERTIFICATE-----' .PHP_EOL;

    return openssl_x509_parse($certificateCApemContent);
}

///*************************************************************************///

function recurs( $A, $level=0, $key=""){
	
	global $Columns, $db, $aField;
	//$aField = array();
	
	foreach($A as $k => $v ){
		if( is_array($v) ) {
			
			$astr = str_pad("", $level*3, '=');
			//echo  "<b> $astr $k : </b><br>" ;
			if( $k == 'extensions' ){ $k = $key; }
			else { $k = $k ."_". $key; }
			
			recurs( $v,	$level+1, $k );
			
		} else {
			$astr = str_pad("", $level*3, '=');
			$v = preg_replace_callback('/\\\\x([0-9A-F]{2})/', function($a){ return pack('H*', $a[1]); }, $v);
			
			switch ($k){
				case 'version': $v++;
				case 'serialNumber': $v = dechex($v); break;
				case 'validFrom': $v;  break;
				case 'validTo': $v; break;
				case 'validFrom_time_t': $v  =  "'".date("Y-m-d H:i:s", $v)."'";break;
				case 'validTo_time_t': $v  =  "'".date("Y-m-d H:i:s", $v)."'";break;
				case 'extendedKeyUsage':
					$v = str_replace(',','<br>',$v);break;
				case 'name':
					$v = str_replace('/','<br>',$v);break;
				//case 'crlDistributionPoints': $v = "Список отзыва: "
				case 'basicConstraints':
					
					if( explode(':',explode(',',$v)[0])[1] == 'FALSE' ){$v = 0;}else{$v = 1;}
					
					break;
			}
			
			if( in_array( $key.$k,  $Columns ) ){
				$l  = $key.$k. "='". $v."'" ;
				$aField[] =	$l;
				//echo  "$l<br>";
			}
			
		}
	
	}
	
}


?>