<?php   
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
header('Content-type: text/html; charset=utf-8');
/*-------------------------- Входные переменные -----------------------------*/
/*---------------------------------------------------------------------------*/
    $link = ConnectMyDB2('portal');
/*---------------------------------------------------------------------------*/
 
$id	  = isset($_POST['id'])		? $_POST['id']	:  "";
$name = isset($_POST['name'])	? $_POST['name']:  "";
$url  = isset($_POST['url'])	? htmlentities( $_POST['url'] )	:  "";
$coment  = isset($_POST['coment'])  ? $_POST['coment']  :  "";
	// echo $_POST['admin']."   ";
$admin  = isset($_POST['admin'])  ? $_POST['admin']  :  "0";
$admin  = ($admin=='on')  ? 1 : 0;
$flag  = $_POST['flag'];
	  
//echo $admin."   ";
	   
if ($flag==1){//существующий
	$strSQL = "UPDATE `tabMainLinks`
                    SET `name` = '$name', `url` = '$url', `coment` = '$coment', `admin` = $admin
					WHERE `id` = $id";
//	$result = $link->query($strSQL);
	
} else if ($flag==0){//new
	$strSQL = "INSERT INTO tabMainLinks (`parentid`,`name`,`url`,`coment`,`admin`) VALUES ('$id', '$name', '$url', '$coment', '$admin')";
}
//echo $strSQL;
$result = $link->query($strSQL);
?>