<?php
//error_reporting(E_ALL ^ E_NOTICE);
if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml")) {
	header("Content-type: application/xhtml+xml"); } else {
	header("Content-type: text/xml");
    }
echo "<?xml version='1.0' encoding='utf-8'?>\n";
?>
