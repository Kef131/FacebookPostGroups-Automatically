<?php include("topadmin.php"); 
if((isset($_SESSION['fbs_userpass']))&&(isset($_SESSION['fbs_useraccount']))) ///admin only
{
//post id delete->delete list
if(isset($_POST['listodelete']))
{
mysql_query('DELETE FROM fbshare_messagelists WHERE listid="'.$_POST['listodelete'].'"');

//delete messages in the list
mysql_query('DELETE FROM fbshare_messages WHERE listid="'.$_POST['listodelete'].'" AND userid="'.$_SESSION['fbs_admin'].'" ');

//delete messages from campaign
mysql_query('DELETE FROM fbshare_campaigns_messages WHERE originallistid="'.$_POST['listodelete'].'" ');

//disable campaigns
mysql_query("UPDATE fbshare_campaigns SET 
			campaign_enabled='0',
			totalmessagespostedinthiscampaign='0'
			WHERE userid='".$_SESSION['fbs_admin']."' AND listid='".$_POST['listodelete']."'");

$_SESSION['fbs_error']='Lista de mensajes borrada exitosamente.';
}


///add new list
if(isset($_POST['addnewlist']))
{

 if(get_magic_quotes_gpc()) {
            if(ini_get('magic_quotes_sybase')) {
				$listname = str_replace("''", "'", $_POST['listname']);
                $listdescription = str_replace("''", "'", $_POST['listdescription']);


            } else {
				
				$listname = stripslashes($_POST['listname']);
				$listdescription = stripslashes($_POST['listdescription']);
	
            }
        } else {
                $listname = $_POST['listname'];
				$listdescription = $_POST['listdescription'];

        }
mysql_query('INSERT INTO fbshare_messagelists VALUES ("","'.$_SESSION['fbs_admin'].'","'.mysql_real_escape_string(trim($listname)).'","'.mysql_real_escape_string(trim($listdescription)).'")');
$_SESSION['fbs_error']='Lista de mensajes creada con &eacute;xito.';

}


//edit list
if(isset($_POST['editlist']))
{
	
if(get_magic_quotes_gpc()) {
            if(ini_get('magic_quotes_sybase')) {
				$listname = str_replace("''", "'", $_POST['listname']);
                $listdescription = str_replace("''", "'", $_POST['listdescription']);


            } else {
				
				$listname = stripslashes($_POST['listname']);
				$listdescription = stripslashes($_POST['listdescription']);
	
            }
        } else {
                $listname = $_POST['listname'];
				$listdescription = $_POST['listdescription'];

        }
mysql_query('UPDATE fbshare_messagelists SET listname="'.$listname.'", listdescription="'.$listdescription.'" WHERE listid="'.$_POST['editlist'].'" ');
$_SESSION['fbs_error']='Lista de mensajes editada con &eacute;xito.';

}


//get all user's lists
$res=mysql_query('SELECT * FROM fbshare_messagelists WHERE userid="'.$_SESSION['fbs_admin'].'" ORDER BY listname ASC');
$totallists=mysql_num_rows($res);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//ES" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>PGF :: Listas de Mensajes guardados</title>
<link rel="shortcut icon" href="images/favicon.ico">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="stylesheets/layout_styles.css" rel="stylesheet" type="text/css">
<link href="stylesheets/font_styles.css" rel="stylesheet" type="text/css">
<link href="stylesheets/jqueryslidemenu.css" rel="stylesheet" type="text/css">
<!--[if lte IE 7]>
<style type="text/css">
html .jqueryslidemenu{height: 1%;} 
</style>
<![endif]-->
<script type="text/javascript" src="jscript/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="jscript/jqueryslidemenu.js"></script>
<script language="javascript" type="text/javascript" src="jscript/jscript.js"></script>

<!-- window box -->
<script type="text/javascript" src="jscript/window/highslide-full.js"></script>
<link href="stylesheets/window/highslide.css" rel="stylesheet" type="text/css">

<?php if($totallists>2) { ?>
<script type="text/javascript">    
    hs.graphicsDir = 'jscript/window/graphics/';
    hs.outlineType = 'rounded-white';
	hs.width='560';
	hs.height='195';
	hs.anchor='bottom left';
</script>
<?php } else { ?>
<script type="text/javascript">    
    hs.graphicsDir = 'jscript/window/graphics/';
    hs.outlineType = 'rounded-white';
	hs.width='560';
	hs.height='195';
	hs.anchor='center left';
</script>

<?php } ?>


<script type="text/javascript">
hs.Expander.prototype.onAfterClose = function() {
   if(this.a.id == 'reload') {window.location='fbmessagelist.php'; return false;}
};
</script>

<!-- end  window box -->

<!-- sortable table -->
<link href="stylesheets/sortabletable.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="jscript/sortabletable.js"></script>
<script type="text/javascript" src="jscript/numberksorttype.js"></script>
<script type="text/javascript" src="jscript/uscurrencysorttype.js"></script>
</head>
<body class="bg">
<?php include("inc/spacetop.php") ?>
<div id="container"> 
<div id="header">
<?php include("inc/header.php") ?>
</div>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td></td>
<td align="left"><div class="formheader">Listas de Mensajes guardados</div></td>
</tr>
<tr>
<td width="10" align="center" valign="top"></td>
<td align="left" valign="top" >
<table width="99%" border="0" cellpadding="0" cellspacing="0" class="tablenowidth" align="left">
<tr>
<td height="25" colspan="2" class="headerform">Listas existentes</td>
</tr>
<?php
if($totallists==0)
{
?>
<tr>
<td height="70" colspan="2" class="admineditbox">
<span class="message_error_admin">No tienes ninguna lista. Por favor utiliza el bot&oacute;n de abajo para crear una nueva lista.</span>
</td>
</tr>
<?php } 
else
{ ?>
<tr>
<td height="30"class="admineditbox">
<table width="99%" cellpadding="0" cellspacing="1" id="results" class="sort-table">
<thead>
	<tr>
		<td height="25" class="sort2td">Nombre de la lista<img class="sort-arrow" border="0" src="images/blank.png"></td>
        <td class="sort2td" width="270">Descripci&oacute;n<img class="sort-arrow" border="0" src="images/blank.png"></td>
		<td width="240" class="sort2td" sorttype="None">Detalles</td>
		<td class="sort2td" width="160" sorttype="None">Acciones</td>
	</tr>
</thead>
<?php

	while($accountdetails=mysql_fetch_array($res))
	{
	//get total messages in this list
	$res1=mysql_query('SELECT * FROM fbshare_messages WHERE listid="'.$accountdetails['listid'].'" AND userid="'.$_SESSION['fbs_admin'].'" ');
	$totalmessages=mysql_num_rows($res1);
	
	//get total campaigns with this list
	$res1=mysql_query('SELECT * FROM fbshare_campaigns WHERE listid="'.$accountdetails['listid'].'" AND userid="'.$_SESSION['fbs_admin'].'" ');
	$totalcampaigns=mysql_num_rows($res1);
	
	$detailslink=" &raquo; ".$totalmessages." mensajes guardados en esta lista.";
	$detailslink.="<br> &raquo; Esta lista se utiliza en  ".$totalcampaigns." campa&ntilde;as.";
		
	$editlink="<a onClick=\"return hs.htmlExpand(this, { objectType: 'ajax', anchor:' right'} );\" href=\"editlist.php?listid=".$accountdetails['listid']."\">
	<img src=\"images/fbpages_edit.png\" align=\"absmiddle\" border=\"0\" hspace=\"2\" alt=\"Editar detalles de la lista\" title=\"Editar detalles de la lista\" onClick=\"document.getElementById('actiondetails').style.display='none';\"></a>";
	
	$addmessages="<a id=\"reload\" onClick=\"return hs.htmlExpand(this, { objectType: 'iframe', anchor:' right', height:'455', width:'800'} );\" href=\"addmessagesintolist.php?listid=".$accountdetails['listid']."\">
	<img src=\"images/fbmessages_upload.png\" align=\"absmiddle\" border=\"0\" hspace=\"2\" alt=\"A&ntilde;adir nuevos mensajes en esta lista\" title=\"A&ntilde;adir nuevos mensajes en esta lista\" onClick=\"document.getElementById('actiondetails').style.display='none';\"></a>";
	
	$editmessages="<a id=\"reload\" onClick=\"return hs.htmlExpand(this, { objectType: 'iframe', anchor:' right', height:'510', width:'850'} );\" href=\"managemessagesintolist.php?listid=".$accountdetails['listid']."\">
	<img src=\"images/fbmessages_edit.png\" align=\"absmiddle\" border=\"0\" hspace=\"2\" alt=\"Administrar los mensajes de esta lista\" title=\"Administrar los mensajes de esta lista\" onClick=\"document.getElementById('actiondetails').style.display='none';\"></a>";
	



	echo("<tr style=\"color:#000000\" bgcolor=\"#F9F9F9\" onmouseover=\"this.bgColor='#FFFFCC';\" onmouseout=\"this.bgColor='#F9F9F9';\">
	<td class=\"sort2td_details\" height=\"45\"><b>".$accountdetails['listname']."</b></td>
	<td class=\"sort2td_details\">".$accountdetails['listdescription']."</td>
	<td class=\"sort2td_details\">".$detailslink."</td>
	<td class=\"sort2td_details\">".$editlink.$addmessages.$editmessages." &nbsp;<a href=\"#\" alt=\"Borrar pagina\" class=\"slink\" onclick=\"if(confirm('Esta lista contiene ".$totalmessages." mensajes.\\nTambi&eacute;n se eliminar&aacute;nn todos los mensajes guardados dentro de esta lista.\\n&iquest;Est&aacute;s seguro que deseas eliminar esta lista?')){document.idetodelete.listodelete.value='".$accountdetails['listid']."';document.idetodelete.submit();return false;} else{return false;}\"><img src=\"images/fbpages_delete.png\" align=\"absmiddle\" border=\"0\" alt=\"Borrar lista\" title=\"Borrar lista\" onClick=\"document.getElementById('actiondetails').style.display='none';\"></a></td>
	</tr>");

	}  ?>
	</table>
</td>
</tr>
<?php } ?>
<tr>
    <td colspan="2" class="admineditbox" height="20">
    <div id="actiondetails" style=" display:none" class="warning_notices"> </div>
</td>
</tr>

    <tr>
    <td colspan="2" class="admineditbox">
    <a onClick="return hs.htmlExpand(this, { objectType: 'ajax'} );" href="newlist.php">
    <input type="button" class="submit" value="Crear nueva Lista" style="width:140px" onClick="document.getElementById('actiondetails').style.display='none';">
    </a>
    <form action="fbmessagelist.php" method="post" name="idetodelete">
	<input type="hidden" name="listodelete" value="0">
	</form>
    </td>
    </tr>


</table>
</td>
</tr>
<tr><td colspan="2" height="10">
</td>
</tr>
</table>
<div id="footer">
<?php  include("inc/footer.php"); ?>
</div>
</div>
<script language="JavaScript" type="text/javascript" src="jscript/tooltip/wz_tooltip.js"></script>
</body>
<?php if($totallists>0)
{ ?>
<script type="text/javascript">
if (navigator.appName.indexOf('Microsoft') != -1)
{
var st = new SortableTable(document.getElementById("results"),["CaseInsensitiveString"]);
}
else
{
var st = new SortableTable(document.getElementById("results"),["CaseInsensitiveString","CaseInsensitiveString","None","None"]);
}


</script>
<?php } ?>

<span id="chromeFix"></span>
<script language="JavaScript" type="text/javascript" src="jscript/tooltip/wz_tooltip.js"></script>
</html>
<?php
if(isset($_SESSION['fbs_error']))//new notice
		{
		echo("<script>document.getElementById('actiondetails').innerHTML='".$_SESSION['fbs_error']."';document.getElementById('actiondetails').style.display='block';</script>");
		unset($_SESSION['fbs_error']);
		}
}
else
{
@header("Location:index.php");
}
?>