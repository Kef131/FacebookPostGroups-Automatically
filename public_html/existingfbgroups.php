<?php include("topadmin.php"); 
if((isset($_SESSION['fbs_userpass']))&&(isset($_SESSION['fbs_useraccount']))) ///admin only
{
//post id delete->delete
if(isset($_POST['pagetodelete']))
{
mysql_query('DELETE FROM fbshare_fbpages WHERE pageid="'.$_POST['pagetodelete'].'"');
//delete campaigns and logs

//browse campaigns and delete messages and campaigns logs
$res1=mysql_query("SELECT * FROM fbshare_campaigns WHERE userid='".$_SESSION['fbs_admin']."' AND messagespostedon='".$_POST['pagetodelete']."'");
while($campiagndet=mysql_fetch_array($res1))
{
	///delete from messages campaigns
	mysql_query("DELETE FROM fbshare_campaigns_messages WHERE userid='".$_SESSION['fbs_admin']."' AND campaignid='".$campiagndet['campaignid']."'");
	//delete from logs
	mysql_query("DELETE FROM fbshare_logs WHERE userid='".$_SESSION['fbs_admin']."' AND campaignid='".$campiagndet['campaignid']."'");
}

//delete from campaigns
mysql_query("DELETE  FROM fbshare_campaigns WHERE userid='".$_SESSION['fbs_admin']."' AND messagespostedon='".$_POST['pagetodelete']."'");

///delete from groups
mysql_query("DELETE FROM fbshare_group_campaigns WHERE userid='".$_SESSION['fbs_admin']."' AND groupid='".$_POST['pagetodelete']."'");


$_SESSION['fbs_error']='Grupo borrado.';
}

//edit
if(isset($_POST['editpage']))
{
	if(get_magic_quotes_gpc()) {
            if(ini_get('magic_quotes_sybase')) {
				$fbpagedescription = str_replace("''", "'", $_POST['fbpagedescription']);
                $fbpageurl = str_replace("''", "'", $_POST['fbpageurl']);


            } else {
				
				$fbpagedescription = stripslashes($_POST['fbpagedescription']);
				$fbpageurl = stripslashes($_POST['fbpageurl']);
	
            }
        } else {
                $fbpagedescription = $_POST['fbpagedescription'];
				$fbpageurl = $_POST['fbpageurl'];

        }
mysql_query('UPDATE fbshare_fbpages SET fbpagedescription="'.$fbpagedescription.'", fbpageurl="'.$fbpageurl.'", accountid="'.$_POST['accountid'].'" WHERE pageid="'.$_POST['pageid'].'" ');
$_SESSION['fbs_error']='Grupo editado.';
}

///add new fb group
if(isset($_POST['addnewgroup']))
{

 if(get_magic_quotes_gpc()) {
            if(ini_get('magic_quotes_sybase')) {
				$fbpagedescription = str_replace("''", "'", $_POST['fbpagedescription']);
                $fbpageurl = str_replace("''", "'", $_POST['fbpageurl']);


            } else {
				
				$fbpagedescription = stripslashes($_POST['fbpagedescription']);
				$fbpageurl = stripslashes($_POST['fbpageurl']);
	
            }
        } else {
                $fbpagedescription = $_POST['fbpagedescription'];
				$fbpageurl = $_POST['fbpageurl'];

        }
mysql_query('INSERT INTO fbshare_fbpages VALUES ("","'.$_SESSION['fbs_admin'].'","'.$_POST['accountid'].'","'.mysql_real_escape_string(trim($fbpagedescription)).'","'.mysql_real_escape_string(trim($fbpageurl)).'","1")');
$_SESSION['fbs_error']='Grupo creado.';

}


//get all user's accounts
$res=mysql_query('SELECT * FROM fbshare_fbaccounts WHERE userid="'.$_SESSION['fbs_admin'].'" ORDER BY fb_email DESC');
$totalaccounts=mysql_num_rows($res);

//get all user's pages

$accountidclause="";
if(isset($_GET['newccaccountid']))
{
	$accountidclause= 'AND b.accountid='.$_GET['newccaccountid'].' ';
}
else
{
	$resccounts=mysql_fetch_array($res);
	$accountidclause= 'AND b.accountid='.$resccounts['accountid'].' ';
	
}
$res=mysql_query('SELECT * FROM fbshare_fbpages a, fbshare_fbaccounts b WHERE a.userid="'.$_SESSION['fbs_admin'].'" AND a.accountid=b.accountid AND a.isgroup=1 GROUP BY a.pageid ORDER BY a.fbpagedescription ASC');
$totalpages_all_users=mysql_num_rows($res);

$res=mysql_query('SELECT * FROM fbshare_fbpages a, fbshare_fbaccounts b WHERE a.userid="'.$_SESSION['fbs_admin'].'" AND a.accountid=b.accountid AND a.isgroup=1 '.$accountidclause.' GROUP BY a.pageid ORDER BY a.fbpagedescription ASC');
$totalpages=mysql_num_rows($res);


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//ES" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>PGF :: Grupos de Facebook</title>
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

<?php if($totalpages>2) { ?>
<script type="text/javascript">    
    hs.graphicsDir = 'jscript/window/graphics/';
    hs.outlineType = 'rounded-white';
	hs.width='600';
	hs.height='220';
	hs.align='center';
</script>
<?php } else { ?>
<script type="text/javascript">    
    hs.graphicsDir = 'jscript/window/graphics/';
    hs.outlineType = 'rounded-white';
	hs.width='600';
	hs.height='220';
	hs.align='center';
</script>

<?php } ?>
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
<td align="left"><div class="formheader">Grupos de Facebook</div></td>
</tr>
<tr>
<td width="10" align="center" valign="top"></td>
<td align="left" valign="top" >
<table width="99%" border="0" cellpadding="0" cellspacing="0" class="tablenowidth" align="left">
<tr>
<td height="25" colspan="2" class="headerform">Grupos de Facebook Existentes</td>
</tr>
<?php

if($totalaccounts==0 || $totalpages_all_users==0)
{
?>
<tr>
<td height="70" colspan="2" class="admineditbox">
<?php if($totalaccounts==0) { ?>
<span class="message_error_admin"><br>
Un Grupo debe estar asociado a una cuenta de Facebook. Por favor, crea primero una cuenta de Facebook.<br><br><br><br><br><br><br><br><br>
<input type="button" class="submit" value="Haz clic aqu&iacute; para crear una nueva cuenta" style="width:250px" onClick="window.location='existingfbaccounts.php'">
</span>
<?php } ?>
<?php if($totalaccounts!=0 && $totalpages_all_users==0) { ?>
<span class="message_error_admin">No tienes ning&uacute;n grupo de Facebook almacenado. Por favor utiliza el bot&oacute;n de abajo para agregar un nuevo grupo.</span>
<?php } ?>
</td>
</tr>
<?php } 
else
{ 

if($totalaccounts!=0 && $totalpages_all_users!=0) { 
//get all user's accounts
$res_acc=mysql_query('SELECT * FROM fbshare_fbaccounts WHERE userid="'.$_SESSION['fbs_admin'].'" AND accountstatus=1 ORDER BY fb_email DESC');
$totalaccountsgr=mysql_num_rows($res_acc);

if($totalaccountsgr>0)
{
$fbaccounts_combo="<form method=\"get\" name=\"showgroupsaccount\"><span class=message_error_admin>Mostrar grupos existentes de la cuenta: </span><select class=\"combo\" name=\"newccaccountid\" style=\"width:395px\" onchange=\"document.showgroupsaccount.submit()\">";
$fbaccounts_combo2="<form method=\"get\" name=\"showgroupsaccount2\"><span class=message_error_admin>Cambiar cuenta de Facebook: </span><select class=\"combo\" name=\"newccaccountid\" style=\"width:395px\" onchange=\"document.showgroupsaccount2.submit()\">";

while($accountdetailsgr=mysql_fetch_array($res_acc))
	{
	$optchecked="";
	if($_GET['newccaccountid']==$accountdetailsgr['accountid']){$optchecked=" selected";}
	$fbaccounts_combo.="<option value=\"".$accountdetailsgr['accountid']."\" ".$optchecked.">".$accountdetailsgr['fb_description']." - ".$accountdetailsgr['fb_email']."</option>";	
	$fbaccounts_combo2.="<option value=\"".$accountdetailsgr['accountid']."\" ".$optchecked.">".$accountdetailsgr['fb_description']." - ".$accountdetailsgr['fb_email']."</option>";	
	}
	
$fbaccounts_combo.="</select></form>";
$fbaccounts_combo2.="</select></form>";
}

?>
<tr>
<td height="35" class="admineditbox"><?php echo $fbaccounts_combo; ?></td>
</tr>
<?php if($totalpages!=0) {  ?>
<tr>
<td height="30"class="admineditbox">
<table cellpadding="0" cellspacing="1" id="results" style="width:960px">
	<tr>
		<td height="25" class="sort2td">Nombre del grupo</td>
        <td class="sort2td" width="180">ID del Grupo</td>
		<td width="200" class="sort2td">De la  cuenta  Facebook</td>
		<td class="sort2td" width="107">Acciones</td>
	</tr>
</table>
<div style="overflow: auto; width: 960px; height: 350px; padding:0px; margin: 0px; overflow-y: scroll;overflow-x:hidden;">
<table width="100%" cellpadding="0" cellspacing="1">
<?php

	while($accountdetails=mysql_fetch_array($res))
	{
		
	$editlink="<a onClick=\"return hs.htmlExpand(this, { objectType: 'ajax', anchor:' right '} );\" href=\"editfbgroup.php?pageid=".$accountdetails['pageid']."\">
	<img src=\"images/fbpages_edit.png\" align=\"absmiddle\" border=\"0\" hspace=\"2\" alt=\"Editar Grupo\" title=\"Editar Grupo\" onClick=\"document.getElementById('actiondetails').style.display='none';\"></a>";

	echo("<tr style=\"color:#000000\" bgcolor=\"#F9F9F9\" onmouseover=\"this.bgColor='#FFFFCC';\" onmouseout=\"this.bgColor='#F9F9F9';\">
	<td class=\"sort2td_details\" height=\"45\">".$accountdetails['fbpagedescription']."</td>
	<td class=\"sort2td_details\" width=\"180\">".$accountdetails['fbpageurl']."</td>
	<td class=\"sort2td_details\" width=\"200\"><img src=\"images/info.png\" style=\"cursor:help\" align=\"absmiddle\" onMouseOver=\"Tip('appID de Facebook: ".$accountdetails['fb_email']."&nbsp;&nbsp;&nbsp;')\" onMouseOut=\"UnTip()\"> ".$accountdetails['fb_description']."</td>
	<td class=\"sort2td_details\" width=\"92\">".$editlink." &nbsp;<a href=\"#\" alt=\"Borrar Grupo\" class=\"slink\" onclick=\"if(confirm('Esta acci&oacute;n eliminar&aacute; todas tus campa&ntilde;as de Facebook asociadas a este grupo.\\n&iquest;Est&aacute;s seguro de querer eliminar este grupo?')){document.idetodelete.pagetodelete.value='".$accountdetails['pageid']."';document.idetodelete.submit();return false;} else{return false;}\"><img src=\"images/fbpages_delete.png\" align=\"absmiddle\" border=\"0\" alt=\"Borrar Grupo\" title=\"Borrar Grupo\" onClick=\"document.getElementById('actiondetails').style.display='none';\"></a></td>
	</tr>");

	}  ?>
	</table>
    </div>
</td>
</tr>
<?php } ?>
<?php } }?>
<tr>
    <td colspan="2" class="admineditbox" height="20">
    <div id="actiondetails" style=" display:none" class="warning_notices"> </div>
</td>
</tr>
<?php if($totalaccounts>0) { ?>
    <tr>
    <td colspan="2" class="admineditbox">
    <a onClick="return hs.htmlExpand(this, { objectType: 'ajax'} );" href="newfbgroup.php">
    <input type="button" class="submit" value="Agregar nuevo Grupo" style="width:140px" onClick="document.getElementById('actiondetails').style.display='none';">
    </a>
    <form method="post" name="idetodelete">
	<input type="hidden" name="pagetodelete" value="0">
	</form>
    </td>
    </tr>
<?php } ?>

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
<?php if($totalpages>0)
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