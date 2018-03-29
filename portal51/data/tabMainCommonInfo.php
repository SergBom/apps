<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
//header('Content-type: text/html; charset=utf-8');

    $db = ConnectMyPDO('portal');
	
if(isset($_POST['html'])){
	$sql = "INSERT INTO `tabMainCommonInfo` SET html='{$_POST['html']}'";
	$result = $db->query($sql);
	$html = $_POST['html'];
}else{

	$html = $db->query("
select tt.html from tabMainCommonInfo tt where tt.insertDate = ( 
select max(d) from 
(select max(t1.insertDate) d FROM tabMainCommonInfo t1
	union
select max(t2.updateDate) d FROM tabMainCommonInfo t2
) t3)
or
 tt.updateDate = ( 
select max(d) from 
(select max(t4.insertDate) d FROM tabMainCommonInfo t4
	union
select max(t5.updateDate) d FROM tabMainCommonInfo t5
) t6)
")->fetchColumn();
}

	echo "{
			success: true,
			data: {
			html: '$html'
			}
	}
	";
	
?>	