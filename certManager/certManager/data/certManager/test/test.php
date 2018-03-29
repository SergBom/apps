<?php
header('Content-type: text/html; charset=utf-8'); 

$filename = 'Cert/Cert/CATechnoKad.cer';


  $certs = array();
  //$pkcs12 = file_get_contents( "pkcs12file.pem" );
	//$pkcs12 = getCert($filename);

    $certificateCAcerContent = file_get_contents($filename);
    $pkcs12 = '-----BEGIN CERTIFICATE-----' .PHP_EOL
        . chunk_split(base64_encode($certificateCAcerContent), 64,PHP_EOL)
        . '-----END CERTIFICATE-----' .PHP_EOL;
	
	
	
	
	
	var_dump ($pkcs12);
  // No password
  openssl_pkcs12_read( $pkcs12, $certs, "" );
  
  openssl_x509_read($pkcs12);
  
  echo openssl_error_string();
  var_dump( $certs );
  
  
  
  
function getCert($file)
{
    $certificateCAcerContent = file_get_contents($file);

    $certificateCApemContent = '-----BEGIN CERTIFICATE-----' .PHP_EOL
        . chunk_split(base64_encode($certificateCAcerContent), 64,PHP_EOL)
        . '-----END CERTIFICATE-----' .PHP_EOL;

    return openssl_x509_parse($certificateCApemContent);
}  
?>