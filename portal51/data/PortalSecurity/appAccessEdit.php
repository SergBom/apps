<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init2.php");
header('Content-type: text/html; charset=utf-8');
if ( is_session_started() === FALSE ) session_start();

/*-------------------------- Входные переменные -----------------------------*/
	$user_id = isset( $_SESSION['portal']['user_id'] ) ? $_SESSION['portal']['user_id'] : '';
/*---------------------------------------------------------------------------*/
    $db = ConnectPDO('portal');
/*---------------------------------------------------------------------------*/

//session_start();

$appClassName = ($_POST['appClassName']) ? $_POST['appClassName'] : '';
//$username = $_SESSION['username'];
//$domain = $_SESSION['domain_id'];
//$portal_auth['user_id']

/*		$result['success'] = true;
		$result['msg'] = 1;
*/

$result = array();
$sql = "CALL `proc#app_edit`('$appClassName', '$user_id')";
//echo "$sql<br>";
$access = $db->query($sql)->fetchColumn();
//echo $access."<br>";

	if ( $access == 0 ) {
		$result['success'] = false;
		$result['msg'] = 0;
	} else{
		$result['success'] = true; //$access;
		$result['msg'] = 1;
	}

echo json_encode($result);
?>
