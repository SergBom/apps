<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');
if ( is_session_started() === FALSE ) session_start();

    $db = ConnectMyDB('portal');

//	session_start();
//	print_r($_SESSION);	
	
////////////////////////////////////////////////////////////////////
$sql = "SELECT * FROM `prt#app`  ORDER BY parameter, enum";
if ($resultDb = $db->query($sql)) {

	$paths = array();
	$requires = array();
	$controllers = array();

	while($record = $resultDb->fetch_assoc()){
		if($record['parameter']=='loader.paths'){
			array_push($paths, $record['variant']);
		} else
		if($record['parameter']=='application.requires'){
			array_push($requires, $record['variant']);
		} else
		if($record['parameter']=='application.controllers'){
			array_push($controllers, $record['variant']);
		}
	}
	
	$s_paths = implode( ',', $paths );
	$s_requires = implode( ',', $requires );
	$s_controllers = implode( ',', $controllers );
	
	if( @$_SESSION['authenticated'] == 'yes' ){
		$launch = "Ext.create('Portal.view.PortalViewport');";
	} else {
		$launch = "Ext.create('Portal.view.sys.PortalLogin');";
	}
	
		$text = <<<TE1
			// @require @packageOverrides
			Ext.Loader.setConfig({
				paths: {
					Ext: '/ext62/build',
					$s_paths
				}
			});

			Ext.application({
				requires: [
					'Ext.Loader',
					$s_requires
				],
				
				controllers: [
					'Portal',
					$s_controllers
				],
				
				name: 'Portal',

				launch: function() {
					$launch
				}
			});
TE1;
	echo $text;	
}

?>