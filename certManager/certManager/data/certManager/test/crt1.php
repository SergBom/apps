<?php 
header('Content-type: text/html; charset=utf-8'); 

$a = array('boyko.cer','UCFK.cer','UCFK2.cer');

$certificateCAcer = './'. $a[0];

$certificateCAcerContent = file_get_contents($certificateCAcer);

/* Convert .cer to .pem, cURL uses .pem */
$certificateCApemContent = '-----BEGIN CERTIFICATE-----'."\n"
. chunk_split(base64_encode($certificateCAcerContent), 64,"\n")
.'-----END CERTIFICATE-----'.PHP_EOL;

// $certificateCApem = $certificateCAcer.'.pem';
// file_put_contents($certificateCApem, $certificateCApemContent);  
// $cert = file_get_contents($certificateCApem);

 $ssl = openssl_x509_parse($certificateCApemContent);

 
 // $ssl = openssl_x509_parse ( file_get_contents($certificateCAcer) );

$baseDir = <<<TAG
\\fs-pz22\FS_COMMON\INSTALL\CryptoPro
TAG;
$startPath = '/mnt/fs-pz22_cert';

//r_dir( $startPath );


 
 
 echo $ssl['hash'] ."<br>";
 echo "From: " . date("Y-m-d H:i:s", $ssl['validFrom_time_t']) ."<br>";
 echo "To: " . date("Y-m-d H:i:s", $ssl['validTo_time_t']) ."<br>";
 echo $ssl['issuer']['ST'] ." ". $ssl['issuer']['street'] ."<br>";
 echo $ssl['subject']['SN'] . " " . $ssl['subject']['GN'] ."<br>";
//
 print_r($ssl);


function r_dir($dir) {
    $odir = opendir($dir);

    while (($file = readdir($odir)) !== false ){
        if($file == '.' || $file == '..' ){ continue; }
        else {
            $filename = $dir . DIRECTORY_SEPARATOR . $file;
            //echo substr($file,-4)."<br>";
            if(  substr($file,-4) == '.cer'  ) {
                echo $filename ."<br>";

                $ssl = getCert($filename);

                echo $ssl['hash'] ."<br>";
                echo "From: " . date("Y-m-d H:i:s", $ssl['validFrom_time_t']) ."<br>";
                echo "To: " . date("Y-m-d H:i:s", $ssl['validTo_time_t']) ."<br>";
                echo $ssl['issuer']['ST'] ." ". $ssl['issuer']['street'] ."<br>";
                echo $ssl['subject']['SN'] . " " . $ssl['subject']['GN'] ."<br>";


                echo "<hr>";

            }
        }

        if( is_dir($filename) ){
            r_dir( $filename );
        }

    }

}

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



?>