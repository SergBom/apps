<!DOCTYPE html>
<head>
<!--meta charset="utf-8" /-->
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!--script language="javascript" type="text/javascript" src="/ajax/dhtmlx3/dhtmlxGrid/codebase/dhtmlxgrid.js"></script>
<script language="javascript" type="text/javascript" src="/ajax/dhtmlx3/dhtmlxGrid/codebase/ext/dhtmlxgrid_drag.js"></script>
<script language="javascript" type="text/javascript" src="/ajax/dhtmlx3/dhtmlxGrid/codebase/ext/dhtmlxgrid_group.js"></script>
<script language="javascript" type="text/javascript" src="/ajax/dhtmlx3/dhtmlxGrid/codebase/ext/dhtmlxgrid_export.js"></script>
<script language="javascript" type="text/javascript" src="/ajax/dhtmlx3/dhtmlxGrid/codebase/ext/dhtmlxgrid_srnd.js"></script>
<script language="javascript" type="text/javascript" src="/ajax/dhtmlx3/dhtmlxGrid/codebase/ext/dhtmlxgrid_filter.js"></script>
<script language="javascript" type="text/javascript" src="/ajax/dhtmlx3/dhtmlxGrid/codebase/dhtmlxgridcell.js"></script>
<script language="javascript" type="text/javascript" src="/ajax/dhtmlx3/dhtmlxTreeGrid/codebase/dhtmlxtreegrid.js"></script>
<!--script language="javascript" type="text/javascript" src="/ajax/dhtmlx2/dhtmlxGrid/codebase/ext/dhtmlxgrid_splt.js"></script-->
<!--script language="javascript" type="text/javascript" src="/ajax/dhtmlx2/dhtmlxGrid/codebase/excells/dhtmlxgrid_excell_sub_row.js"></script-->
</head>
<body>
<?php
//include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
//<script src="/dhtmlx3/codebase/dhtmlx2.js"></script>
?>


<div class="x-hide-display">
        <table><thead><tr>
            <th width="120"><input type="button" value="Excel" onClick="mygridC.toExcel('/php/grid/generate.php')"></th>
            </tr></thead></table>
        <div id='dhtmlxgrid_common' style='width: 700px; height: 500px;'></div>
    </div>

	
<script type="text/javascript">	
	

session = <?php echo $_GET['ID'];?>;

mygridC = new dhtmlXGridObject('dhtmlxgrid_common');
mygridC.setSkin("dhx_skyblue");
mygridC.setHeader('<span id="ErrorHeader1C"></span>,#cspan,#cspan,#cspan,#cspan');
mygridC.attachHeader('<span id="ErrorHeader2C"></span>,#cspan,#cspan,#cspan,#cspan');
mygridC.attachHeader('&nbsp;,Документов к выгрузке,Документов не прошло выходной ФЛК,Объектов выгружено,% прохождения выходного ФЛК');
// mygridC.attachFooter('<b>Total:</b>,#cspan,{#stat_total}');
mygridC.setInitWidths('200,100,100,100,100');
mygridC.setColAlign("left,center,center,center,center");
mygridC.setColSorting("str,int,int,int,int");
// mygridC.setColTypes("ro,ed,txt,txt,ro,co");
mygridC.enableMultiline(true);
// mygridC.enableMathEditing(true);
mygridC.init();
mygridC.load("tir-stat-common.php?PSESSION="+session);

document.getElementById("ErrorHeader1C").innerHTML = "<b>Ошибки ФЛК</b>";

var loader = dhx.ajax.getSync('tir-session-header.php?PSESSION='+session);

document.getElementById('ErrorHeader2C').innerHTML= "<b>"+loader.xmlDoc.responseText+"</b>";


</script>	
</body>