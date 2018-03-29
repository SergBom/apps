<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
    $db = ConnectMyPDO('portal');

////////////////////////////////////////////////////////////////////
$portal_name = $db->query("SELECT env FROM config WHERE value='portal_title'")->fetchColumn();

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
<title><?php echo $portal_name;?></title>
<script>var Ext=Ext||{};
Ext.theme={name:""};
var PortalName="<?php echo $portal_name;?>";
</script>
<script src="/ext62/build/ext-all.js"></script>
<script src="/ext62/build/classic/theme-classic/theme-classic.js"></script>
<link rel="stylesheet" href="/ext62/build/classic/theme-classic/resources/theme-classic-all.css">
<script src="/ext62/build/classic/locale/locale-ru.js"></script>
<script src="/ext-addons-6.2.1/packages/exporter/build/classic/exporter.js"></script>
<link rel="stylesheet" href="/resources/my.css">
<script src="util/Util.js"></script>
<script src="util/SessionMonitor.js"></script>
<script src="util/Glyphs.js"></script>
<script src="util/moment-with-locales.min.js"></script>
<script type="text/javascript"><?php include('app.php');?></script>
</head>
<body><?php //session_start();	print_r($_SESSION); ?></body>
</html>