<?php
include("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");

// resume the session
if ( is_session_started() === FALSE ) session_start();
$SON=$_SESSION['portal']['username'];
$SOD=$_SESSION['portal']['domain_name'];
//setcookie("PortalLogin",$SON);
//setcookie("PortalDomain",$SOD);
// Unset all of the session variables.
$_SESSION = array();
// Finally, destroy the session.
session_destroy();



// Старт сессии для восстановления логина
if ( is_session_started() === FALSE ) session_start();
// Unset all of the session variables.
$_SESSION['portal']['username'] = $SON;
$_SESSION['portal']['domain_name'] = $SOD;


// send result back to Ext JS
$result = array();
$result['success'] = true;
$result['msg'] = 'logout';
echo json_encode($result);