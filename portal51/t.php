<?php
$sleep = 0;
while($sleep  < 60){
	$sleep ++;
	sleep(1);
	file_put_contents('sleep.log',$sleep.PHP_EOL,FILE_APPEND);
}



?>