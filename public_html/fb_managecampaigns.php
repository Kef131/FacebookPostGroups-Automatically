<?php include("topadmin.php"); 
if((isset($_SESSION['fbs_userpass']))&&(isset($_SESSION['fbs_useraccount']))) ///admin only
{
//post id delete->delete campaign
if(isset($_POST['deletecampaign']))
{
mysql_query("DELETE  FROM fbshare_campaigns WHERE userid='".$_SESSION['fbs_admin']."' AND campaignid='".$_POST['campaigntodelete']."'");
mysql_query("DELETE  FROM fbshare_campaigns_messages WHERE userid='".$_SESSION['fbs_admin']."' AND campaignid='".$_POST['campaigntodelete']."'"); 
mysql_query("DELETE  FROM fbshare_group_campaigns WHERE userid='".$_SESSION['fbs_admin']."' AND campaignid='".$_POST['campaigntodelete']."'");
mysql_query("DELETE  FROM fbshare_logs WHERE userid='".$_SESSION['fbs_admin']."' AND campaignid='".$_POST['campaigntodelete']."'"); 

$_SESSION['fbs_error']='Campa&ntilde;a eliminada correctamente.';
}

//disable campaign
if(isset($_POST['disablecampaign']))
{
	mysql_query("UPDATE fbshare_campaigns SET campaign_enabled='0' WHERE userid='".$_SESSION['fbs_admin']."' AND campaignid='".$_POST['campaignid']."'");
}

//enable campaign
if(isset($_POST['enablecampaign']))
{
	mysql_query("UPDATE fbshare_campaigns SET campaign_enabled='1' WHERE userid='".$_SESSION['fbs_admin']."' AND campaignid='".$_POST['campaignid']."'");
}

///reset campiagn
if(isset($_POST['resetcampaign']))
{
	mysql_query("UPDATE fbshare_campaigns SET 
				is_campaign_finished='0',
				totalmessagesposted='0'
				WHERE userid='".$_SESSION['fbs_admin']."' AND campaignid='".$_POST['campaignid']."'");
	
	mysql_query("UPDATE fbshare_campaigns_messages SET 
				nroftimesposted='0'
				WHERE userid='".$_SESSION['fbs_admin']."' AND campaignid='".$_POST['campaignid']."'");
}



//get all user's campaigns
$res=mysql_query('SELECT * FROM fbshare_campaigns WHERE userid="'.$_SESSION['fbs_admin'].'" ORDER BY campaignid DESC');
$totalcampaigns=mysql_num_rows($res);


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>PGF :: Administrar Campa&ntilde;as</title>
<link rel="shortcut icon" href="images/favicon.ico">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="stylesheets/layout_styles.css" rel="stylesheet" type="text/css">
<link href="stylesheets/font_styles.css" rel="stylesheet" type="text/css">
<link href="stylesheets/jqueryslidemenu.css" rel="stylesheet" type="text/css">
<link href="stylesheets/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="stylesheets/bootstrap.css" rel="stylesheet" type="text/css">
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

<?php if($totalcampaigns>2) { ?>
<script type="text/javascript">    
    hs.graphicsDir = 'jscript/window/graphics/';
    hs.outlineType = 'rounded-white';
	hs.width='600';
	hs.height='210';
	hs.anchor='bottom left';
</script>
<?php } else { ?>
<script type="text/javascript">    
    hs.graphicsDir = 'jscript/window/graphics/';
    hs.outlineType = 'rounded-white';
	hs.width='600';
	hs.height='210';
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
<td align="left"><div class="formheader">Campa&ntilde;as de Facebook &raquo; Campa&ntilde;as existentes</div></td>
</tr>
<tr>
<td width="10" align="center" valign="top"></td>
<td align="left" valign="top" >
<table width="99%" border="0" cellpadding="0" cellspacing="0" class="tablenowidth" align="left">
<tr>
<td height="25" colspan="2" class="headerform">Campa&ntilde;as existentes de Facebook</td>
</tr>
<?php

if($totalcampaigns==0)
{
?>
<tr>
<td height="70" colspan="2" class="admineditbox">
<span class="message_error_admin">No hay campa&ntilde;a de Facebook en la base de datos.<br><br>
</span>
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
		<td height="25" class="sort2td">Nombre de la campa&ntilde;a<img class="sort-arrow" border="0" src="images/blank.png"></td>
        <td width="130" class="sort2td">Tipo<img class="sort-arrow" border="0" src="images/blank.png"></td>
        <td width="90" class="sort2td">Creado el<img class="sort-arrow" border="0" src="images/blank.png"></td>
        <td width="80" class="sort2td">Hecho<img class="sort-arrow" border="0" src="images/blank.png"></td>
        <td width="70" class="sort2td">Iniciado<img class="sort-arrow" border="0" src="images/blank.png"></td>
        <td width="85" class="sort2td">Terminado<img class="sort-arrow" border="0" src="images/blank.png"></td>
		<td width="70" class="sort2td">Estado<img class="sort-arrow" border="0" src="images/blank.png"></td>
		<td class="sort2td" width="110" sorttype="None">Acciones</td>
	</tr>
</thead>
<?php

	while($details=mysql_fetch_array($res))
	{
		
	$editlink=" <a href=\"fb_editcampaign.php?campaignid=".$details['campaignid']."\"><img src=\"images/fbpages_edit.png\" align=\"absmiddle\" border=\"0\" alt=\"Editar campa&ntilde;a\" title=\"Editar campa&ntilde;a\"></a>";
	
	
 	
	$started="";
	$finished="";
	$campiagndetails="";
	$campaignstatus="";
	$campaignstatus_actions="";
	$cruns="";
	$ctype="";
	
	///campiagn details tooltip
	$res_det=mysql_query("SELECT * FROM fbshare_fbaccounts WHERE userid='".$_SESSION['fbs_admin']."' AND  accountid='".$details['accountid']."'");
	$det=mysql_fetch_array($res_det);
	$campiagndetails.="&raquo; Cuenta de Facebook en uso: ".$det['fb_description']." [".$det['fb_email']."]<br>";

	//get wall/or page
	if($details['messagespostedon']=="0")
	{
	$campiagndetails.="&raquo; Publicaci&oacute;n en: muro de la cuenta<br>";
	$ctype="Publicar en muro";
	}
	else
	{
	$res_det=mysql_query("SELECT * FROM fbshare_fbpages WHERE userid='".$_SESSION['fbs_admin']."' AND pageid='".$details['messagespostedon']."'");
	$det=mysql_fetch_array($res_det);
		if($details['isgroup']=="0")
		{
		$campiagndetails.="&raquo; Publicaci&oacute;n en p&aacute;ginas: ".$det['fbpagedescription']."<br>";
		$ctype="Publicar en pagina de Fan";
		}
		if($details['isgroup']=="1")
		{
		$resnrgrp=mysql_query("SELECT * FROM fbshare_group_campaigns WHERE campaignid='".$details['campaignid']."' ");
		$postnrgroups=mysql_num_rows($resnrgrp);
		$campiagndetails.="&raquo; Publicaci&oacute;n en ".$postnrgroups." grupo(s)<br>";
		$ctype="Publicaci&oacute;n en Grupos";
		}
	}
	
	///get list
	$res_det=mysql_query("SELECT * FROM fbshare_messagelists WHERE userid='".$_SESSION['fbs_admin']."' AND  listid='".$details['listid']."'");
	$det=mysql_fetch_array($res_det);
	
	if($det['listname']=="")
	{$clistname="<font color=#FF0000>Esta lista se ha eliminado</font>";}
	else
	{
	$clistname=$det['listname'];	
	}
	
	$campiagndetails.="&raquo; Lista de mensajes: ".$clistname." ";
	
	//total messages:
	$campiagndetails.="[".$details['totalmessagespostedinthiscampaign']." mensaje(s) en la lista]<br>";

	
	//where it posts
	switch($details['campaign_run'])
	{
		case 0: $cruns="Un mensaje ser&aacute; publicado para cuando se habilite la campa&ntilde;a";break;
		case 1: $cruns=$details['campaign_run_messages_to_post_every_hour']." mensaje de la lista, cada hora";break;
		case 2: $cruns="Un mensaje de la lista se publicar&aacute; todos los d&iacute;as";break;
		case 3: $cruns="Un mensaje de la lista se publicar&aacute; una vez, en ".date_str_to_db($details['campaign_run_specific_day']);break;
		case 4: $cruns="Un mensaje de la lista se publicar&aacute; cada semana, en ".day_to_string($details['campaign_run_day']);break;
		case 5: $cruns="Un mensaje de la lista se publicar&aacute; cada mes ".day_to_string2($details['campaign_run_month_day'])." d&iacute;a";break; 
		case 6: $cruns="Un mensaje de la lista se publicar&aacute; cada hora";break;
		case 7: $cruns=$details['campaign_run_messages_to_post_every_day']." mensaje(s) en la lista, cada d&iacute;a";break;
		case 8: $cruns=$details['campaign_run_messages_to_post_every_week']." mensaje(s) en la lista, cada semana, en ".day_to_string($details['campaign_run_day_post_x_messages']);break;
		case 9: $cruns=$details['campaign_run_messages_to_post_minutes']." mensaje(s) en la lista, cada ".$details['campaign_run_minutes_post_x_messages']." minutos";break;
		
		
	}
	$campiagndetails.="&raquo; Calendario de campa&ntilde;a: ".$cruns."<br>";
	//campaign loop
	if($details['campaign_repeat_type']=="1")
	{
		$campiagndetails.="&raquo; Campa&ntilde;a recurente: SI";
	}
	else
	{
		$campiagndetails.="&raquo; Campa&ntilde;a recurente: NO";
	}
	///lats time run
	$campiagndetails.="<br>&raquo; &uacute;ltima vez que la campa&ntilde;a se ejecut&oacute;: ".date_str_to_db_time($details['campaign_last_time_run']);

	$statusiconwithtext="<img src=\"images/info.png\" style=\"cursor:help\" hspace=\"1\" align=\"absmiddle\" onMouseOver=\"Tip('".$campiagndetails."&nbsp;&nbsp;&nbsp;')\" onMouseOut=\"UnTip()\">";
	
	//end campaign details tooltip
	
	//enabled/disabled
	if($details['campaign_enabled']=="1")
	{
		$campaignstatus="<font color=#0000FF>Activa</font>";
		$campaignstatus_actions="<a href=\"#\" alt=\"Desactivar Campa&ntilde;a\" class=\"slink\" onclick=\"if(confirm('&iquest;Desactivar esta campa&ntilde;a?')){document.disablecampaign.campaignid.value='".$details['campaignid']."';document.disablecampaign.submit();return false;} else{return false;}\"><img src=\"images/pause.png\" align=\"absmiddle\" border=\"0\" alt=\"Desactivar campa&ntilde;a\" title=\"Desactivar campa&ntilde;a\" onClick=\"document.getElementById('actiondetails').style.display='none';\"></a>";
	}
	else
	{
		$campaignstatus="<font color=#FF0000>Pausada</font>";
		$campaignstatus_actions="<a href=\"#\" alt=\"Campa&ntilde;a activa\" class=\"slink\" onclick=\"if(confirm('&iquest;Activar esta campa&ntilde;a?')){document.enablecampaign.campaignid.value='".$details['campaignid']."';document.enablecampaign.submit();return false;} else{return false;}\"><img src=\"images/play.png\" align=\"absmiddle\" border=\"0\" alt=\"Activar campa&ntilde;a\" title=\"Activar campa&ntilde;a\" onClick=\"document.getElementById('actiondetails').style.display='none';\"></a>";
	}
	
	//started
	if($details['is_campaign_started']=="1")
	{
		$started="<font color=#090>SI</font>";
	}
	if($details['is_campaign_started']=="0")
	{
		$started="NO";
	}
	
	///fished
	if($details['is_campaign_finished']=="1")
	{
		$finished="<font color=red>SI</font>";
		$resetlink=" &nbsp;<a href=\"#\" alt=\"restablecer campa&ntilde;a\" class=\"slink\" onclick=\"if(confirm('&iquest;Restablecer esta campa&ntilde;a?')){document.resetcampaign.campaignid.value='".$details['campaignid']."';document.resetcampaign.submit();return false;} else{return false;}\"><img src=\"images/resetcampaign.png\" align=\"absmiddle\" border=\"0\" alt=\"Restablecer campa&ntilde;a\" title=\"Restablecer campa&ntilde;a\" onClick=\"document.getElementById('actiondetails').style.display='none';\"></a>";
	}
	if($details['is_campaign_finished']=="0")
	{
		$finished="NO";
		$resetlink="";

	}

	echo("<tr style=\"color:#000000\" bgcolor=\"#F9F9F9\" onmouseover=\"this.bgColor='#FFFFCC';\" onmouseout=\"this.bgColor='#F9F9F9';\">
	<td class=\"sort2td_details\" height=\"45\">".$statusiconwithtext." ".$details['campaignname']."</td>
	<td class=\"sort2td_details\">".$ctype."</td>
	<td class=\"sort2td_details\">".timestamp_str_to_db($details['createdon'])."</td>
	<td class=\"sort2td_details\">".$details['howmanytimesthecampaignrun']." vez(es)</td>
	<td class=\"sort2td_details\">".$started."</td>
	<td class=\"sort2td_details\">".$finished.$resetlink."</td>
	<td class=\"sort2td_details\">".$campaignstatus."</td>
	<td class=\"sort2td_details\">".$campaignstatus_actions.$editlink." <a href=\"#\" alt=\"Eliminar campa&ntilde;a\" class=\"slink\" onclick=\"if(confirm('&iquest;Est&aacute;s seguro que quieres borrar esta campa&ntilde;a?')){document.idetodelete.campaigntodelete.value='".$details['campaignid']."';document.idetodelete.submit();return false;} else{return false;}\"><img src=\"images/fbpages_delete.png\" align=\"absmiddle\" border=\"0\" alt=\"Borrar campa&ntilde;a\" title=\"Borrar campa&ntilde;a\" onClick=\"document.getElementById('actiondetails').style.display='none';\"></a></td>
	</tr>");

	}  ?>
	</table>
</td>
</tr>
<?php } ?>
<tr>
    <td colspan="2" class="admineditbox" height="20">
    <div id="actiondetails" style="display:none" class="warning_notices"> </div>
</td>
</tr>

    <tr>
    <td colspan="2" class="admineditbox">
    <form action="fb_managecampaigns.php" method="post" name="idetodelete" style="margin:0;padding:0;">
	<input type="hidden" name="campaigntodelete" value="0">
    <input type="hidden" name="deletecampaign" value="yes">
	</form>
    
    <form action="fb_managecampaigns.php" method="post" name="disablecampaign" style="margin:0;padding:0;">
	<input type="hidden" name="campaignid" value="0">
    <input type="hidden" name="disablecampaign" value="yes">
	</form>
    
    <form action="fb_managecampaigns.php" method="post" name="enablecampaign" style="margin:0;padding:0;">
	<input type="hidden" name="campaignid" value="0">
    <input type="hidden" name="enablecampaign" value="yes">
	</form>
    
    <form action="fb_managecampaigns.php" method="post" name="resetcampaign" style="margin:0;padding:0;">
	<input type="hidden" name="campaignid" value="0">
    <input type="hidden" name="resetcampaign" value="yes">
	</form>
    
    
    </td>
    </tr>
    <tr>
    <td height="20" class="admineditbox" style="padding-left:15px; padding-bottom:5px;">
    <input type="button" class="submit" value="Crear nueva campa&ntilde;a" style="width:180px" onClick="window.location='fb_createnewcampaign.php'">
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
<?php if($totalcampaigns>0)
{ ?>
<script type="text/javascript">
if (navigator.appName.indexOf('Microsoft') != -1)
{
var st = new SortableTable(document.getElementById("results"),["CaseInsensitiveString"]);
}
else
{
var st = new SortableTable(document.getElementById("results"),["CaseInsensitiveString","CaseInsensitiveString","CaseInsensitiveString","CaseInsensitiveString","CaseInsensitiveString","CaseInsensitiveString","CaseInsensitiveString","None"]);
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