<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
//header('Content-type: text/html; charset=utf-8');

    $db = ConnectMyPDO('portal');

$PortalName = $db->query("SELECT env FROM config WHERE value='portal_title'")->fetchColumn();

	echo "{
			success: true,
			data: {
					PortalName: '$PortalName',
					userFIO: '{$_SESSION['portal']['FIO']}',
					userID: '{$_SESSION['portal']['user_id']}'
			}
	}
	";
?>	