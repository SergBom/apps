#!/bin/sh

d=/var/www/portal/public_html/portal51/data/ScanDocs

cd $d
/usr/bin/php -f $d/sc.php -- 0
