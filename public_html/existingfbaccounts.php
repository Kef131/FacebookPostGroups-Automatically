<?php include("topadmin.php"); 
if((isset($_SESSION['fbs_userpass']))&&(isset($_SESSION['fbs_useraccount']))) ///admin only
{
//post id delete->delete account
if(isset($_POST['emailidtodelete']))
{
$result=mysql_query('DELETE FROM fbshare_fbaccounts WHERE accountid="'.$_POST['emailidtodelete'].'" ');

///delete associated fb pages
mysql_query('DELETE  FROM fbshare_fbpages WHERE accountid="'.$_POST['emailidtodelete'].'" AND userid="'.$_SESSION['fbs_admin'].'" ');


//browse campaigns and delete messages and campaigns logs
$res1=mysql_query("SELECT * FROM fbshare_campaigns WHERE userid='".$_SESSION['fbs_admin']."' AND accountid='".$_POST['emailidtodelete']."'");
while($campiagndet=mysql_fetch_array($res1))
{
	///delete from messages campaigns
	mysql_query("DELETE FROM fbshare_campaigns_messages WHERE userid='".$_SESSION['fbs_admin']."' AND campaignid='".$campiagndet['campaignid']."'");
	//delete from logs
	mysql_query("DELETE FROM fbshare_logs WHERE userid='".$_SESSION['fbs_admin']."' AND campaignid='".$campiagndet['campaignid']."'");
}

//delete from campaigns
mysql_query("DELETE  FROM fbshare_campaigns WHERE userid='".$_SESSION['fbs_admin']."' AND accountid='".$_POST['emailidtodelete']."'");

//delete from group campiagns
mysql_query("DELETE  FROM fbshare_group_campaigns WHERE userid='".$_SESSION['fbs_admin']."' AND accountid='".$_POST['emailidtodelete']."'");

$_SESSION['fbs_error']='Cuenta borrada.';
}

//edit
if(isset($_POST['editaccunt']))
{
  if(get_magic_quotes_gpc()) {
            if(ini_get('magic_quotes_sybase')) {
				$fb_description = str_replace("''", "'", $_POST['fb_description']);
                $fb_email = str_replace("''", "'", $_POST['fb_email']);
				$fb_username = str_replace("''", "'", $_POST['fb_username']);

            } else {
				
				$fb_description = stripslashes($_POST['fb_description']);
				$fb_email = stripslashes($_POST['fb_email']);
				$fb_username = stripslashes($_POST['fb_username']);
	
            }
        } else {
			    $fb_description = $_POST['fb_description'];
                $fb_email = $_POST['fb_email'];
				$fb_username = $_POST['fb_username'];

        }
mysql_query('UPDATE fbshare_fbaccounts SET fb_description="'.$fb_description.'", fb_email="'.$fb_email.'", fb_username="'.$fb_username.'" WHERE accountid="'.$_POST['accountid'].'" ');
$_SESSION['fbs_error']='Cuenta editada.';

}

///add new fb account
if(isset($_POST['addnewaccount']))
{
//check if email exists
$res2=mysql_query('SELECT * FROM fbshare_fbaccounts WHERE userid="'.$_SESSION['fbs_admin'].'" AND fb_email="'.$_POST['fb_email'].'"');
$acountexists=mysql_num_rows($res2);


	
$accountok=0; //not verified

  if(get_magic_quotes_gpc()) {
            if(ini_get('magic_quotes_sybase')) {
				$fb_description = str_replace("''", "'", $_POST['fb_description']);
                $fb_email = str_replace("''", "'", $_POST['fb_email']);
				$fb_username = str_replace("''", "'", $_POST['fb_username']);

            } else {
				
				$fb_description = stripslashes($_POST['fb_description']);
				$fb_email = stripslashes($_POST['fb_email']);
				$fb_username = stripslashes($_POST['fb_username']);
	
            }
        } else {
                $fb_description = $_POST['fb_description'];
				$fb_email = $_POST['fb_email'];
				$fb_username = $_POST['fb_username'];

        }
mysql_query('INSERT INTO fbshare_fbaccounts VALUES ("","'.$_SESSION['fbs_admin'].'","'.$_SESSION['fbs_useraccount'].'","'.mysql_real_escape_string(trim($fb_description)).'","'.mysql_real_escape_string(trim($fb_email)).'","","","'.mysql_real_escape_string(trim($fb_username)).'","'.$accountok.'")');
$_SESSION['fbs_error']='Cuenta creada.';
}



//get all user's accounts
$res=mysql_query('SELECT * FROM fbshare_fbaccounts WHERE userid="'.$_SESSION['fbs_admin'].'" ORDER BY fb_description ASC');
$totalaccounts=mysql_num_rows($res);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//ES" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>PGF :: Cuentas de Facebook</title>
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
<script language="javascript" type="text/javascript" src="jscript/jscript.js"></script>
<script language="javascript" type="text/javascript" src="jscript/ajaxcalls.js"></script>
<!--Empieza Licencia-->
<link href="stylesheets/gb_styles.css" rel="stylesheet" type="text/css" />
</head>
<body>

<script type='text/javascript' src='jscript/window/highslide-full.js'></script>	
<script language='javascript' type='text/javascript' src='jscript/jqueryslidemenu.js'></script>
	<script>
	function movewindowcenter(url)
	{
		windowname=window.open(url,'oauthwindow','location=1,status=1,scrollbars=1, width=720, height=600',true);
		var l=(screen.width - 500)/2;
		var t=(screen.height - 500)/2;
		windowname.moveTo(l,t);
		windowname.focus();
	}
	 </script>
</body>
</html>
<!--Termina Licencia-->
<!-- window box -->
<link href="stylesheets/window/highslide.css" rel="stylesheet" type="text/css">

<?php if($totalaccounts>2) { ?>
<script type="text/javascript">    
    hs.graphicsDir = 'jscript/window/graphics/';
    hs.outlineType = 'rounded-white';
	hs.width='550';
	hs.height='260';
	hs.anchor='bottom left';
</script>
<?php } else { ?>
<script type="text/javascript">    
    hs.graphicsDir = 'jscript/window/graphics/';
    hs.outlineType = 'rounded-white';
	hs.width='550';
	hs.height='260';
	hs.anchor='center left';
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
<td align="left"><div class="formheader">Cuentas de Facebook</div></td>
</tr>
<tr>
<td width="10" align="center" valign="top"></td>
<td align="left" valign="top" >
<table width="99%" border="0" cellpadding="0" cellspacing="0" class="tablenowidth" align="left">
<tr>
<td height="25" colspan="2" class="headerform">Cuentas existentes de Facebook</td>
</tr>
<?php

if($totalaccounts==0)
{
?>
<tr>
<td height="60" colspan="2" class="admineditbox">
<span class="message_error_admin" style="padding-top:10px;">No tienes ninguna cuenta de Facebook registrada en el sistema. Usa el boton de agregar una nueva cuenta.<br>
Para el completo conjunto de instrucciones sobre c&oacute;mo crear tu aplicaci&oacute;n de Facebook asociada a tu cuenta, por favor revisa la <a href="comenzar.php" class="slink" style="color:#900; font-size:13px; font-weight:bold"> secci&oacute;n de ayuda </a> <br>
</span>
</td>
</tr>
<?php } 
else
{ 
?>
<tr>
<td height="30"class="admineditbox">
<table width="99%" cellpadding="0" cellspacing="1" id="results" class="sort-table">
<thead>
	<tr>
		<td height="25" class="sort2td">Descripci&oacute;n de la cuenta<img class="sort-arrow" border="0" src="images/blank.png"></td>
        <td class="sort2td" width="230">AppID de Facebook.<img class="sort-arrow" border="0" src="images/blank.png"></td>
		<td width="160" class="sort2td">Estado<img class="sort-arrow" border="0" src="images/blank.png"></td>
		<td class="sort2td" width="200" sorttype="None">Acciones</td
	></tr>
</thead>
<?php
require_once("cronjobs/fbsdk/facebook.php");
require_once("cronjobs/functions.php");


	while($accountdetails=mysql_fetch_array($res))
	{
	$fbauthlink="";
	
	$appid= $accountdetails['fb_username'];
    $appsecret= $accountdetails['fb_email'];
	
	$fb = new fb($appid, $appsecret);
	//
	if(trim($accountdetails['accountstatus'])=="0")
	{
	$url=$fb->get_auth_url(true,$accountdetails['fb_username']);
	///replace callback
	$finalurl=str_replace("existingfbaccounts.php","fbaccountconfirmed.php?accid=".$accountdetails['accountid'],$url['url']);
	
	$fbauthlink="<input type=\"button\" class=\"submit\" value=\"Conceder acceso\" style=\"width:120px\" onClick=\"movewindowcenter('".$finalurl."');\">";
	}
	else
	{
	$url=$fb->get_auth_url(true,$accountdetails['fb_username']);
	///replace callback
	$finalurl=str_replace("existingfbaccounts.php","fbaccountconfirmed.php?accid=".$accountdetails['accountid'],$url['url']);
	
	$fbauthlink="<input type=\"button\" class=\"submit\" value=\"Renovar acceso\" style=\"width:120px\" onClick=\"movewindowcenter('".$finalurl."');\">";	
	} 
		
	$editlink="<a onClick=\"return hs.htmlExpand(this, { objectType: 'ajax', anchor:' right '} );\" href=\"editfbaccount.php?accountid=".$accountdetails['accountid']."\">
	<img src=\"images/fbaccount_edit.png\" align=\"absmiddle\" border=\"0\" hspace=\"2\" alt=\"Editar Cuenta\" title=\"Editar Cuenta\" onClick=\"document.getElementById('actiondetails').style.display='none';\"></a>";
	
$refreshstatuslink="<img src=\"images/refreshstatus.png\" hspace=\"3\" align=\"absmiddle\" border=\"0\"  width=\"26\" alt=\"Revalidar token\" title=\"Revalidar token\" onClick=\"document.getElementById('actiondetails').style.display='none';showHint(".$accountdetails['accountid'].");\">";


//help tooltip
$statusicon="";
$res1=mysql_query("SELECT * FROM fbshare_fbpages WHERE userid='".$_SESSION['fbs_admin']."' AND accountid='".$accountdetails['accountid']."' ");
$totpages=mysql_num_rows($res1);
$statusicon.=" <font color=#FF0000>&raquo; ".$totpages." P&aacute;gina(s) y grupo(s) de Facebook asociado a esta cuenta.<br>";

$res2=mysql_query("SELECT * FROM fbshare_campaigns WHERE userid='".$_SESSION['fbs_admin']."' AND accountid='".$accountdetails['accountid']."' ");
$totpages=mysql_num_rows($res2);
$statusicon.=" &raquo; ".$totpages." campa&ntilde;a(s) en Facebook activa(s) asociada a esta cuenta. </font>";

$statusiconwithtext="<img src=\"images/info.png\" style=\"cursor:help\" hspace=\"1\" align=\"absmiddle\" onMouseOver=\"Tip('".$statusicon."&nbsp;&nbsp;&nbsp;')\" onMouseOut=\"UnTip()\">";
///end help tooltip


	echo("<tr style=\"color:#000000\" bgcolor=\"#F9F9F9\" onmouseover=\"this.bgColor='#FFFFCC';\" onmouseout=\"this.bgColor='#F9F9F9';\">
	<td class=\"sort2td_details\" height=\"45\">".$statusiconwithtext." ".$accountdetails['fb_description']."</td>
	<td class=\"sort2td_details\">".$accountdetails['fb_username']."</td>
	<td class=\"sort2td_details\"><div id=\"txtHint".$accountdetails['accountid']."\" >".fb_status_to_string($accountdetails['accountstatus'])."</div></td>
	<td class=\"sort2td_details\">".$editlink." &nbsp;<a href=\"#\" alt=\"Borrar cuenta\" class=\"slink\" onclick=\"if(confirm('Esta acci&oacute;n eliminar&aacute; todas sus campa&ntilde;a existentes y p&oacute;ginas de Facebook asociados con esta cuenta.\\n&iquest;Est&aacute;s seguro de que deseas eliminar esta cuenta?')){document.idetodelete.emailidtodelete.value='".$accountdetails['accountid']."';document.idetodelete.submit();return false;} else{return false;}\"><img src=\"images/fbaccount_delete.png\" align=\"absmiddle\" border=\"0\" alt=\"Borrar cuenta\" width=\"30\" title=\"Borrar cuenta\" onClick=\"document.getElementById('actiondetails').style.display='none';\"></a> ".$fbauthlink."</td>
</tr>");

	}  ?>
	</table>
</td>
</tr>
<?php }?>
    <tr>
    <td colspan="2" class="admineditbox" height="20">
    <div id="actiondetails" style=" display:none" class="warning_notices"> </div>
</td>
</tr>

    <tr>
    <td colspan="2" class="admineditbox">
    <a onclick="return hs.htmlExpand(this, { objectType: 'ajax'} );" href="newfbaccount.php">
    <input type="button" class="submit" value="Agregar nueva cuenta" style="width:140px" onclick="document.getElementById('actiondetails').style.display='none';">
    </a>
    <form action="existingfbaccounts.php" method="post" name="idetodelete">
	<input type="hidden" name="emailidtodelete" value="0">
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
<?php if($totalaccounts>0)
{ ?>
<script type="text/javascript">
if (navigator.appName.indexOf('Microsoft') != -1)
{
var st = new SortableTable(document.getElementById("results"),["CaseInsensitiveString"]);
}
else
{
var st = new SortableTable(document.getElementById("results"),["CaseInsensitiveString","CaseInsensitiveString","CaseInsensitiveString","None"]);
}


</script>
<?php } ?>

<span id="chromeFix"></span>
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