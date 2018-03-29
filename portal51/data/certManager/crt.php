<?php
header('Content-type: text/html; charset=utf-8');
$file_init = "/php/init2.php";
$isCLI = ( php_sapi_name() == 'cli' );
if($isCLI){
	include_once("/var/www/portal/public_html".$file_init);
}else{
	include_once("{$_SERVER['DOCUMENT_ROOT']}".$file_init);
}

$db = ConnectPDO('security');

$db->query("TRUNCATE `cert2`");

$baseDir = <<<TAG
\\\\fs-pz22\FS_COMMON\INSTALL\CryptoPro
TAG;
$startPath = '/mnt/fs-pz22_cert';

$log_file = "crt.log.html";
logg("",1);


r_dir( $startPath );


///////////////////////////////////////////////////////////////
function r_dir($dir) {
    global $baseDir, $db, $startPath;
    $odir = opendir($dir);

    while (($file = readdir($odir)) !== false ){
        if($file == '.' || $file == '..' ){ continue; }
        else {
            $filename = $dir . DIRECTORY_SEPARATOR . $file;
            //echo substr($file,-4)."<br>";
            if(  substr($file,-4) == '.cer'  ) {

                $ssl = getCert($filename);

                $a1 = array( $startPath, '/' );
                $a2 = array( $baseDir, '\\' );

                $sfilename = addslashes(str_ireplace( $a1, $a2, $filename ));

				logg($sfilename ."<br>". $dir ."<br>");

                logg($ssl['hash'] ."<br>");
                $validFrom  =  "'".date("Y-m-d H:i:s", $ssl['validFrom_time_t'])."'";
                logg("From: " . $validFrom ."<br>");
                $validTo    =  "'".date("Y-m-d H:i:s", $ssl['validTo_time_t'])."'";
                logg("To: " . $validTo ."<br>");

                //if( isset($ssl['subject']['CN']) && isset($ssl['issuer']['CN'])  ){
                if( ($ssl['subject']['O'] == $ssl['issuer']['O']) || ($ssl['subject']['CN'] == $ssl['issuer']['CN'])  ){
                    logg("<b><font color='red'>Корневой сертификат УЦ</b>"."</font><br>");
                    $is_root = 1;
                } else {
                    logg("<b>Личный сертификат</b>"."<br>");
                    $is_root = 0;
                }

                //echo "Subject: SN+GN: '" . @$ssl['subject']['SN'] . " " . @$ssl['subject']['GN'] ."'<br>";
                $subj_CN    = "'".@$ssl['subject']['CN']."'";
                $subj_SNG   = "'".@$ssl['subject']['SN']." ".@$ssl['subject']['GN']."'";
                $subj_Title = "'".@$ssl['subject']['title']."'";
                $subj_O     = "'".@$ssl['subject']['O']."'";
                $subj_Email = "'".@$ssl['subject']['emailAddress']."'";
                logg("Subject: CN: " . $subj_CN ."<br>");
                logg("Subject: SNG: ". $subj_SNG ."<br>");
                logg("Subject: title: " . $subj_Title ."<br>");
                logg("Subject: O: " . $subj_O ."<br>");
                logg("Subject: email: " . $subj_Email ."<br>");


                $uc_CN      = "'".@$ssl['issuer']['CN']."'";
                $uc_Address = "'".@$ssl['issuer']['C'] . ", " . @$ssl['issuer']['L'] . ", " . @$ssl['issuer']['street']."'";
                $uc_OU      = "'".@$ssl['issuer']['OU']."'";
                $uc_O       = "'".@$ssl['issuer']['O']."'";
                $uc_Email   = "'".@$ssl['issuer']['emailAddress']."'";
                $filepath   = "'".$sfilename."'";

                logg("UC: email: " . $uc_Email . "<br>");
                logg("UC: Address: " . $uc_Address . "<br>");
                //echo "UC: " . $ssl['issuer']['unstructuredName'] . "'<br>");
                logg("UC: OU: " . $uc_OU . "<br>");
                logg("UC: O: " . $uc_O . "<br>");
                logg("UC: CN: " . $uc_CN . "<br>");

                $sql = "INSERT INTO `cert2` SET 
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

                $db->query($sql);



                logg($sql . "<hr>");
                //print_r($ssl);

            }
        }

        if( is_dir($filename) ){
            r_dir( $filename );
        }
    }
}
///////////////////////////////////////////////////
function getCert($file)
{
    $certificateCAcerContent = file_get_contents($file);

    /* Convert .cer to .pem, cURL uses .pem */
    $certificateCApemContent = '-----BEGIN CERTIFICATE-----' .PHP_EOL
        . chunk_split(base64_encode($certificateCAcerContent), 64,PHP_EOL)
        . '-----END CERTIFICATE-----' .PHP_EOL;

    // $certificateCApem = $certificateCAcer.'.pem';
    // file_put_contents($certificateCApem, $certificateCApemContent);
    // $cert = file_get_contents($certificateCApem);

    return openssl_x509_parse($certificateCApemContent);
}

function logg($string,$new=0){
	global $log_file;
	switch ($new) {
		case 0:
			file_put_contents($log_file,$string,FILE_APPEND);
			break;
		case 1:
			file_put_contents($log_file,'<!DOCTYPE html>\n<html>\n<head>\n<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><body>');
			break;
		case 2:
			file_put_contents($log_file,'</body></html>',FILE_APPEND);
			break;
	}
}
		


?>