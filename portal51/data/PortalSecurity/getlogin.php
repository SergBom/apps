<?php
//if ( is_session_started() === FALSE )
	session_start();
header('Content-type: text/html; charset=utf-8');


//$un =  ( null !== @$_SESSION['portal']['username']) ? $_SESSION['portal']['username'] : '';
//$ud =  ( null !== @$_SESSION['portal']['domain_name']) ? $_SESSION['portal']['domain_name'] : 'MURMANSK.NET';

$un =  ( null !== @$_COOKIE["PortalLogin"]) ? $_COOKIE["PortalLogin"] : '';
$ud =  ( null !== @$_COOKIE["PortalDomain"]) ? $_COOKIE["PortalDomain"] : 'MURMANSK.NET';


echo json_encode(
	array('login' => $un,
		'domain' => $ud
	)
);
//echo $un;

?>
