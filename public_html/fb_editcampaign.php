<?php include("topadmin.php"); 
if((isset($_SESSION['fbs_userpass']))&&(isset($_SESSION['fbs_useraccount'])) && (isset($_GET['campaignid']))) ///admin only
{
//save campaign
if(isset($_POST['editcampaign']))
{
		
  sleep(0.2);
  if(get_magic_quotes_gpc()) {
            if(ini_get('magic_quotes_sybase')) {
						$campaignname = str_replace("''", "'", $_POST['campaignname']);
						$accountid = str_replace("''", "'", $_POST['accountid']);
						$listid = str_replace("''", "'", $_POST['listid']);
						$messagespostedon = str_replace("''", "'", $_POST['messagespostedon']);
						$howtopostmessages = str_replace("''", "'", $_POST['howtopostmessages']);
						$campaign_enabled = str_replace("''", "'", $_POST['campaign_enabled']);
						$campaign_run = str_replace("''", "'", $_POST['campaign_run']);
						$campaign_run_specific_day = str_replace("''", "'", $_POST['campaign_run_specific_day']);
						$campaign_run_day = str_replace("''", "'", $_POST['campaign_run_day']);
						$campaign_run_month_day = str_replace("''", "'", $_POST['campaign_run_month_day']);
						$campaign_run_minutes_post_x_messages = str_replace("''", "'", $_POST['campaign_run_minutes_post_x_messages']);
						$campaign_run_day_post_x_messages = str_replace("''", "'", $_POST['campaign_run_day_post_x_messages']);
						$campaign_run_messages_to_post_minutes = str_replace("''", "'", $_POST['campaign_run_messages_to_post_minutes']);
						$campaign_run_messages_to_post_every_hour = str_replace("''", "'", $_POST['campaign_run_messages_to_post_every_hour']);
						$campaign_run_messages_to_post_every_day = str_replace("''", "'", $_POST['campaign_run_messages_to_post_every_day']);
						$campaign_run_messages_to_post_every_week = str_replace("''", "'", $_POST['campaign_run_messages_to_post_every_week']);
						$campaign_repeat_type = str_replace("''", "'", $_POST['campaign_repeat_type']);
			
			
            } else {
	
				$campaignname = stripslashes($_POST['campaignname']);
				$accountid = stripslashes($_POST['accountid']);
				$listid = stripslashes($_POST['listid']);
				$messagespostedon = stripslashes($_POST['messagespostedon']);
				$howtopostmessages = stripslashes($_POST['howtopostmessages']);
				$campaign_enabled = stripslashes($_POST['campaign_enabled']);
				$campaign_run = stripslashes($_POST['campaign_run']);
				$campaign_run_specific_day = stripslashes($_POST['campaign_run_specific_day']);
				$campaign_run_day = stripslashes($_POST['campaign_run_day']);
				$campaign_run_month_day = stripslashes($_POST['campaign_run_month_day']);
				$campaign_run_minutes_post_x_messages = stripslashes($_POST['campaign_run_minutes_post_x_messages']);
				$campaign_run_day_post_x_messages = stripslashes($_POST['campaign_run_day_post_x_messages']);
				$campaign_run_messages_to_post_minutes = stripslashes($_POST['campaign_run_messages_to_post_minutes']);
				$campaign_run_messages_to_post_every_hour = stripslashes($_POST['campaign_run_messages_to_post_every_hour']);
				$campaign_run_messages_to_post_every_day = stripslashes($_POST['campaign_run_messages_to_post_every_day']);
				$campaign_run_messages_to_post_every_week = stripslashes($_POST['campaign_run_messages_to_post_every_week']);
				$campaign_repeat_type = stripslashes($_POST['campaign_repeat_type']);
				
            }
        } else {
                $campaignname = $_POST['campaignname'];
				$accountid = $_POST['accountid'];
				$listid = $_POST['listid'];
				$messagespostedon = $_POST['messagespostedon'];
				$howtopostmessages = $_POST['howtopostmessages'];
				$campaign_enabled = $_POST['campaign_enabled'];
				$campaign_run = $_POST['campaign_run'];
				$campaign_run_specific_day = $_POST['campaign_run_specific_day'];
				$campaign_run_day = $_POST['campaign_run_day'];
				$campaign_run_month_day = $_POST['campaign_run_month_day'];
				$campaign_run_minutes_post_x_messages = $_POST['campaign_run_minutes_post_x_messages'];
				$campaign_run_day_post_x_messages = $_POST['campaign_run_day_post_x_messages'];
				$campaign_run_messages_to_post_minutes = $_POST['campaign_run_messages_to_post_minutes'];
				$campaign_run_messages_to_post_every_hour = $_POST['campaign_run_messages_to_post_every_hour'];
				$campaign_run_messages_to_post_every_day = $_POST['campaign_run_messages_to_post_every_day'];
				$campaign_run_messages_to_post_every_week = $_POST['campaign_run_messages_to_post_every_week'];
				$campaign_repeat_type = $_POST['campaign_repeat_type'];
			
        }
		
		$campaign_run_specific_day=date_str_to_db($campaign_run_specific_day);
		
		///UPDATE CAMPAIGN
		if(isset($_POST['resetmessages']) && $_POST['resetmessages']=="yes") //update the messages too
		{
		
		///messages list
		$resmessages=mysql_query("SELECT * FROM fbshare_messages WHERE listid='".$listid."' AND userid='".$_SESSION['fbs_admin']."' ");
		$totalmessagespostedinthiscampaign=mysql_num_rows($resmessages);
		
		//delete from messages to send
		mysql_query('DELETE FROM fbshare_campaigns_messages WHERE campaignid="'.$_POST['campaignid'].'" ');
		
		
		//insert into messges to send
		while($messdet=mysql_fetch_array($resmessages))
		{
			if(get_magic_quotes_gpc()) {
            if(ini_get('magic_quotes_sybase')) {
				$message_new = str_replace("''", "'", $messdet['message']);


            } else {
				
				$message_new = stripslashes($messdet['message']);
	
            }
        } else {
                $message_new = $messdet['message'];

        }
			
			mysql_query('INSERT INTO fbshare_campaigns_messages VALUES("","'.$messdet['messageid'].'","'.$messdet['listid'].'","'.$_POST['campaignid'].'","'.$_SESSION['fbs_admin'].'","'.trim(mysql_real_escape_string($message_new)).'","","0")');
		}
		
		
		//campaign table
		mysql_query("UPDATE fbshare_campaigns SET 
					campaignname='".$campaignname."',
					accountid='".$accountid."',
					listid='".$listid."',
					messagespostedon='".$messagespostedon."',
					howtopostmessages='".$howtopostmessages."',
					campaign_enabled='".$campaign_enabled."',
					campaign_run='".$campaign_run."',
					campaign_run_specific_day='".$campaign_run_specific_day."',
					campaign_run_day='".$campaign_run_day."',
					campaign_run_month_day='".$campaign_run_month_day."',
					campaign_run_minutes_post_x_messages='".$campaign_run_minutes_post_x_messages."',
					campaign_run_day_post_x_messages='".$campaign_run_day_post_x_messages."',
					campaign_run_messages_to_post_minutes='".$campaign_run_messages_to_post_minutes."',
					campaign_run_messages_to_post_every_hour='".$campaign_run_messages_to_post_every_hour."',
					campaign_run_messages_to_post_every_day='".$campaign_run_messages_to_post_every_day."',
					campaign_run_messages_to_post_every_week='".$campaign_run_messages_to_post_every_week."',
					campaign_repeat_type='".$campaign_repeat_type."',
					totalmessagespostedinthiscampaign='".$totalmessagespostedinthiscampaign."',
					isgroup='".$_POST['isgroup']."'
					WHERE campaignid='".$_POST['campaignid']."' AND userid='".$_SESSION['fbs_admin']."' ");
		}
		else
		{
			//campaign table
					mysql_query("UPDATE fbshare_campaigns SET 
					campaignname='".$campaignname."',
					accountid='".$accountid."',
					messagespostedon='".$messagespostedon."',
					howtopostmessages='".$howtopostmessages."',
					campaign_enabled='".$campaign_enabled."',
					campaign_run='".$campaign_run."',
					campaign_run_specific_day='".$campaign_run_specific_day."',
					campaign_run_day='".$campaign_run_day."',
					campaign_run_month_day='".$campaign_run_month_day."',
					campaign_run_minutes_post_x_messages='".$campaign_run_minutes_post_x_messages."',
					campaign_run_day_post_x_messages='".$campaign_run_day_post_x_messages."',
					campaign_run_messages_to_post_minutes='".$campaign_run_messages_to_post_minutes."',
					campaign_run_messages_to_post_every_hour='".$campaign_run_messages_to_post_every_hour."',
					campaign_run_messages_to_post_every_day='".$campaign_run_messages_to_post_every_day."',
					campaign_run_messages_to_post_every_week='".$campaign_run_messages_to_post_every_week."',
					campaign_repeat_type='".$campaign_repeat_type."',
					isgroup='".$_POST['isgroup']."'
					WHERE campaignid='".$_POST['campaignid']."' AND userid='".$_SESSION['fbs_admin']."' ");
			
		}

		

////GROUPS HERE
///delete first from groups to post
mysql_query('DELETE FROM fbshare_group_campaigns WHERE campaignid="'.$_POST['campaignid'].'" ');

//post in groups - get the group ids
$groupinsert=false;
if($_POST['isgroup']=="1" && $_POST['totalnrofgroups']>0)
{
	for($iter=0; $iter<=$_POST['totalnrofgroups']; $iter++)
	{
		if(isset($_POST['groupid_'.$iter]))
		{
			$newgroupid=$_POST['groupid_'.$iter];
			mysql_query('INSERT INTO fbshare_group_campaigns VALUES("","'.$_SESSION['fbs_admin'].'","'.$_POST['campaignid'].'","'.$accountid.'","'.$newgroupid.'")');
			$groupinsert=true;
		}
	
	}
	
}

///if is group and no groups selected - > disable
if($_POST['isgroup']=="1" && $groupinsert==false)
{
	mysql_query('UPDATE fbshare_campaigns SET campaign_enabled=0 WHERE campaignid="'.$_POST['campaignid'].'" ');
}


$_SESSION['fbs_error']='Campa&ntilde;a editada con &eacute;xito.';

}
	
	
///get campaign details
$res=mysql_query("SELECT * FROM fbshare_campaigns WHERE campaignid='".$_GET['campaignid']."' AND userid='".$_SESSION['fbs_admin']."' ");
$campaigndetails=mysql_fetch_array($res);


//get all fb active accounts
$res=mysql_query('SELECT * FROM fbshare_fbaccounts WHERE userid="'.$_SESSION['fbs_admin'].'" AND accountstatus=1 ORDER BY fb_email DESC');
$totalaccounts=mysql_num_rows($res);
if($totalaccounts>0)
{
$fbaccounts_combo="<select class=\"combo\" name=\"accountid\" style=\"width:395px\" onchange=\"document.forms[0].isgroup.value=0;changepostonmessages(this.value);\">";

while($accountdetails=mysql_fetch_array($res))
	{
	$selected="";
	if($campaigndetails['accountid']==$accountdetails['accountid']) {$selected=" selected";}

	$fbaccounts_combo.="<option value=\"".$accountdetails['accountid']."\" ".$selected.">".$accountdetails['fb_description']." [".$accountdetails['fb_email']."]</option>";	
	}
	
$fbaccounts_combo.="</select>";
}

//get all lists
$res=mysql_query('SELECT * FROM fbshare_messagelists WHERE userid="'.$_SESSION['fbs_admin'].'" ORDER BY listname DESC');
$totallists=mysql_num_rows($res);
$messagesinlists=false;
if($totallists>0)
{
$fblists_combo="<select class=\"combo\" name=\"listid\" style=\"width:395px\" disabled>";
while($accountdetails=mysql_fetch_array($res))
	{
	//get number of messages
	$res2=mysql_query('SELECT * FROM fbshare_messages WHERE userid="'.$_SESSION['fbs_admin'].'" AND listid="'.$accountdetails['listid'].'"');
	$totmessagesinlist=mysql_num_rows($res2);
		if($totmessagesinlist>0)
		{
		$selected="";
		if($campaigndetails['listid']==$accountdetails['listid']) {$selected=" selected";}
		
		$fblists_combo.="<option value=\"".$accountdetails['listid']."\" ".$selected.">".$accountdetails['listname']." [".$totmessagesinlist." messages]</option>";
		$messagesinlists=true;
		}
	}
	
$fblists_combo.="</select>";
}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>PGF :: Editar campa&ntilde;a</title>
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
<link href="stylesheets/tabs.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="jscript/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="jscript/jqueryslidemenu.js"></script>
<script language="javascript" type="text/javascript" src="jscript/jscript.js"></script>
<script language="javascript" type="text/javascript" src="jscript/ajaxcalls.js"></script>
<script type="text/javascript">
function togglechecked()
	{ 
		if(document.createnewcampaign.checkall.checked==false) //unselect all
		{
		ch=false;
		}
		if(document.createnewcampaign.checkall.checked==true) //select all
		{
		ch=true;
		}
      for (var i = 1; i < document.createnewcampaign.elements.length; i++) 
	  {
        var e = document.createnewcampaign.elements[i];

			if (e.type == 'checkbox' && e.id!='resetmessages')
			 {
				   if(ch)
				   {
							if(e.checked==false)
							{
							e.checked = ch;
							}
				   }
				   if(!ch)
				   {
				   e.checked = ch;
				   }
			  
			 }
			
      }
    }
	
function enablelistcombo()
{
	if(document.createnewcampaign.resetmessages.checked==false) //enable
		{
			document.createnewcampaign.listid.disabled=true;
		}
	if(document.createnewcampaign.resetmessages.checked==true) //enable
		{
			document.createnewcampaign.listid.disabled=false;
		}
		
}
</script>
<script type="text/javascript" src="jscript/calendar/calendar.js"></script>
<script type="text/javascript" src="jscript/calendar/lang/calendar-es.js"></script>
<script type="text/javascript">
var oldLink = null;
function setActiveStyleSheet(link, title) {
  var i, a, main;
  for(i=0; (a = document.getElementsByTagName("link")[i]); i++) {
    if(a.getAttribute("rel").indexOf("style") != -1 && a.getAttribute("title")) {
      a.disabled = true;
      if(a.getAttribute("title") == title) a.disabled = false;
    }
  }
  if (oldLink) oldLink.style.fontWeight = 'normal';
  oldLink = link;
  link.style.fontWeight = 'bold';
  return false;
}
function selected(cal, date) {
  cal.sel.value = date; 
  if (cal.dateClicked && (cal.sel.id == "sel1" || cal.sel.id == "sel3"))
  cal.callCloseHandler();
}
function closeHandler(cal) {
  cal.hide();                 
 _dynarch_popupCalendar = null;
}
function showCalendar(id, format, showsTime, showsOtherMonths) {
  var el = document.getElementById(id);
  if (_dynarch_popupCalendar != null) {
    _dynarch_popupCalendar.hide();         
  } else {
    var cal = new Calendar(1, null, selected, closeHandler);
    if (typeof showsTime == "string") {
      cal.showsTime = true;
      cal.time24 = (showsTime == "24");
    }
    if (showsOtherMonths) {
      cal.showsOtherMonths = true;
    }
    _dynarch_popupCalendar = cal;                
    cal.setRange(1900, 2100);     
    cal.create();
  }
  _dynarch_popupCalendar.setDateFormat(format);    
  _dynarch_popupCalendar.parseDate(el.value);     
  _dynarch_popupCalendar.sel = el;                 
   _dynarch_popupCalendar.showAtElement(el.nextSibling, "Br");      

  return false;
}

var MINUTE = 60 * 1000;
var HOUR = 60 * MINUTE;
var DAY = 24 * HOUR;
var WEEK = 7 * DAY;
function isDisabled(date) {
  var today = new Date();
  return (Math.abs(date.getTime() - today.getTime()) / DAY) > 10;
}

function flatSelected(cal, date) {
  var el = document.getElementById("preview");
  el.innerHTML = date;
}

function showFlatCalendar() {
  var parent = document.getElementById("display");
  var cal = new Calendar(0, null, flatSelected);
  cal.weekNumbers = false;
  cal.setDisabledHandler(isDisabled);
  cal.setDateFormat("%A, %B %e");
  cal.create(parent);
  cal.show();
}
</script>
<link rel="stylesheet" type="text/css" media="all" href="jscript/calendar/skins/aqua/theme.css" title="Aqua" />
<!--loading screen -->
<link href="stylesheets/loading.css" rel="stylesheet" type="text/css">
<!--end loading screen -->
</head>
<body class="bg" onLoad="changepostonmessages2(document.forms[0].accountid.value,<?php echo $campaigndetails['messagespostedon'] ?>,<?php echo $campaigndetails['campaignid'] ?>);">
<!--loading screen code -->
<?php if(isset($_POST['editcampaign']))
{ ?>
<div id="loading" class="loading-invisible">
  <p>Actualizando la base de datos. Por favor, espera...<br><br><img src="images/loader2.gif" alt="Cargando..." /></p>
</div>

<script type="text/javascript">
  document.getElementById("loading").className = "loading-visible";
  var hideDiv = function(){document.getElementById("loading").className = "loading-invisible";};
  var oldLoad = window.onload;
  var newLoad = oldLoad ? function(){hideDiv.call(this);oldLoad.call(this);} : hideDiv;
  window.onload = newLoad;
</script>
<?php } ?>
<!--end loading screen code -->
<?php include("inc/spacetop.php") ?>
<div id="container"> 
<div id="header">
  <?php include("inc/header.php") ?>
</div>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td></td>
<td><div class="formheader">Campa&ntilde;as de Facebook &raquo; <a href="fb_managecampaigns.php" class="navlink">Campa&ntilde;as existentes</a> &raquo; Editar Campa&ntilde;as</div></td>
</tr>
<tr>
<td colspan="2" align="left" valign="top">
</tr>
<tr>
<td width="10" align="center" valign="top"></td>
<td align="left" valign="top" >
<form  method="post" name="createnewcampaign">
<table width="99%" border="0" cellpadding="0" cellspacing="0" class="tablenowidth" align="left">
<tr>
<td height="25" colspan="2" class="headerform">Configuraci&oacute;n de la campa&ntilde;a</td>
</tr>
<tr>
<td  class="admineditbox_settings">
<img src="images/info.png" alt="Ayuda" style="cursor:help" align="absmiddle" onMouseOver="Tip('Este es el nombre de tu campa&ntilde;a en Facebook y debe describir el objetivo de la campa&ntilde;a.&nbsp;&nbsp;&nbsp;&nbsp;')" onMouseOut="UnTip()">
Nombre de la campa&ntilde;a:</td>
<td class="admineditbox_settings" width="730" >
<input type="text" autocomplete=off style="width:390px;" class="inputbox" name="campaignname" onKeyDown="vercampaignname2(this.value,<?php echo $campaigndetails['campaignid'] ?>)" onKeyUp="vercampaignname2(this.value,<?php echo $campaigndetails['campaignid'] ?>)" onBlur="vercampaignname2(this.value,<?php echo $campaigndetails['campaignid'] ?>)"  onChange="vercampaignname2(this.value,<?php echo $campaigndetails['campaignid'] ?>)" onClick="vercampaignname2(this.value,<?php echo $campaigndetails['campaignid'] ?>)" 
onFocus="vercampaignname2(this.value,<?php echo $campaigndetails['campaignid'] ?>)" value="<?php echo $campaigndetails['campaignname'] ?>"> 
<font id="checkcampaignname" class="message_error_admin_check"></font>
</td>
</tr>
<tr>
<td  class="admineditbox_settings">
<img src="images/info.png" alt="Ayuda" style="cursor:help" align="absmiddle" onMouseOver="Tip('Selecciona la cuenta de Facebook que se utilizar&aacute; para esta campa&ntilde;a. Debe ser una cuenta verificada.&nbsp;&nbsp;&nbsp;&nbsp;')" onMouseOut="UnTip()">
Cuenta de Facebook a utilizar:</td>
<td  class="admineditbox_settings"><?php echo $fbaccounts_combo ?></td>
</tr>
<tr>
<td class="admineditbox_campaigns_top">
<img src="images/info.png" alt="Ayuda" style="cursor:help" align="absmiddle" onMouseOver="Tip('Elije donde se publicar&aacute;n los mensajes.&nbsp;&nbsp;&nbsp;&nbsp;')" onMouseOut="UnTip()">
Esta campa&ntilde;a se publicar&aacute; en:</td>
<td class="admineditbox_campaigns_top">
<div id="fbpostondiv" class="postonmessages">

</div>
</td>
</tr>
<tr>
<td class="admineditbox_campaigns_top">
<img src="images/info.png" alt="Ayuda" style="cursor:help" align="absmiddle" onMouseOver="Tip('La aplicaci&oacute;n enviar&aacute; los mensajes de esta lista.\nCambiar la lista de mensajes tambi&eacute;n restablecer&aacute; el n&uacute;mero de mensajes de esta campa&ntilde;a.')" onMouseOut="UnTip()">
Lista de mensajes para usar:</td>
<td class="admineditbox_campaigns_top"><?php echo $fblists_combo ?>
<div style="margin:0; padding:0; margin-top:6px;">
<input type="checkbox" value="si" name="resetmessages" id="resetmessages" onClick="enablelistcombo()">
Actualizar, Cambiar y restablecer la lista de mensajes para esta campa&ntilde;a
</div>
</td>
</tr>
<tr>
<td  class="admineditbox_campaigns_top">
<img src="images/info.png" alt="Ayuda" style="cursor:help" align="absmiddle" onMouseOver="Tip('Si tu lista de mensajes contiene mas de un mensaje, elije la forma en que los mensajes ser&aacute;n publicados.&nbsp;&nbsp;&nbsp;&nbsp;')" onMouseOut="UnTip()">
Los mensajes se publicar&aacute;n:</td>
<td class="admineditbox_campaigns_top">
<input type="radio" name="howtopostmessages" value="0" checked>Grupo por grupo de forma consecutiva<br>
<input type="radio" name="howtopostmessages" value="1" <?php if($campaigndetails['howtopostmessages']=="1"){echo("checked");} ?>>Al azar en cada grupo
</td>
</tr>
<tr>
<td  class="admineditbox_settings_top" style="display:none">
<img src="images/info.png" alt="Ayuda" style="cursor:help" align="absmiddle" onMouseOver="Tip('Elige si deseas habilitar esta campaAąa de forma predeterminada. SerA!s capaz de activar o desactivar la campaAąa mA!s tarde.&nbsp;&nbsp;&nbsp;&nbsp;')" onMouseOut="UnTip()">
La campa&ntilde;a est&aacute; habilitada:</td>
<td class="admineditbox_settings_top" style="display:none">
<input type="radio" value="1" name="campaign_enabled" checked > 
Si <br>
<input type="radio" value="0" name="campaign_enabled"> 
No
</td>
</tr>
<tr>
<td class="admineditbox_campaigns_top">
<img src="images/info.png" alt="Ayuda" style="cursor:help" align="absmiddle" onMouseOver="Tip('Elije la forma en que esta campa&ntilde;a publicar&aacute; los mensajes.&nbsp;&nbsp;&nbsp;&nbsp;')" onMouseOut="UnTip()">
Agenda de la campa&ntilde;a:</td>
<td class="admineditbox_campaigns_top">
<table width="100%" cellpadding="0"  cellspacing="0" border="0" align="left">

    <tr style="display:none">
    <td height="26" align="left">
	<input type="radio" value="6" name="campaign_run" <?php if($campaigndetails['campaign_run']=="6"){echo("checked");} ?>> 
     Desactivada</td>
    <td></td>
  </tr>
  <tr style="display:none">
    <td height="26" align="left">
	<input type="radio" value="2" name="campaign_run" <?php if($campaigndetails['campaign_run']=="2"){echo("checked");} ?>> 
      Desactivada</td>
    <td></td>
  </tr>
  <tr style="display:none">
    <td height="26" align="left">
	<input type="radio" value="4" name="campaign_run" <?php if($campaigndetails['campaign_run']=="4"){echo("checked");} ?>>
Desactivada</td>
    <td></td>
  </tr>
    <tr>
    <td height="26" align="left">
	<input type="radio" value="9" name="campaign_run" <?php if($campaigndetails['campaign_run']=="9"){echo("checked");} ?>>
<input type="hidden" name="campaign_run_messages_to_post_minutes" value="<?php if($campaigndetails['campaign_run_messages_to_post_minutes']!=0){echo $campaigndetails['campaign_run_messages_to_post_minutes'];} ?>">
Un lote de mensajes cada
<input type="text" style="width:30px" name="campaign_run_minutes_post_x_messages" class="inputbox"  value="<?php if($campaigndetails['campaign_run_minutes_post_x_messages']!=0){echo $campaigndetails['campaign_run_minutes_post_x_messages'];}?>"> 
minutos (M&aacute;nimo 60 minutos)</td>
    <td></td>
  </tr>
      <tr>
<td height="26" align="left">
<input type="radio" value="1" name="campaign_run" <?php if($campaigndetails['campaign_run']=="1"){echo("checked");} ?>>
<input type="hidden" name="campaign_run_messages_to_post_every_hour"  value="<?php if($campaigndetails['campaign_run_messages_to_post_every_hour']!=0){echo $campaigndetails['campaign_run_messages_to_post_every_hour'];} ?>">
Un lote de mensajes cada hora</td>
    <td></td>
  </tr>
     <tr>
    <td height="26" align="left">
	<input type="radio" value="7" name="campaign_run" <?php if($campaigndetails['campaign_run']=="7"){echo("checked");} ?>>
<input type="hidden"  name="campaign_run_messages_to_post_every_day"  value="<?php if($campaigndetails['campaign_run_messages_to_post_every_day']!=0){echo $campaigndetails['campaign_run_messages_to_post_every_day'];} ?>">
Un lote de mensajes cada d&iacute;a</td>
    <td></td>
  </tr>
    <tr>
    <td height="26" align="left">
	<input type="radio" value="5" name="campaign_run" <?php if($campaigndetails['campaign_run']=="5"){echo("checked");} ?>> 
     Un lote de mensajes cada mes, en     
        <select name="campaign_run_month_day" class="combo" style="width:60px ">
          <option value="1" selected>1ro.</option>
          <option value="2" <?php if($campaigndetails['campaign_run_month_day']=="2"){echo("selected");} ?>>2do</option>
          <option value="3" <?php if($campaigndetails['campaign_run_month_day']=="3"){echo("selected");} ?>>3ro</option>
          <option value="4" <?php if($campaigndetails['campaign_run_month_day']=="4"){echo("selected");} ?>>4to</option>
          <option value="5" <?php if($campaigndetails['campaign_run_month_day']=="5"){echo("selected");} ?>>5to</option>
          <option value="6" <?php if($campaigndetails['campaign_run_month_day']=="6"){echo("selected");} ?>>6to</option>
          <option value="7" <?php if($campaigndetails['campaign_run_month_day']=="7"){echo("selected");} ?>>7mo</option>
          <option value="8" <?php if($campaigndetails['campaign_run_month_day']=="8"){echo("selected");} ?>>8vo</option>
          <option value="9" <?php if($campaigndetails['campaign_run_month_day']=="9"){echo("selected");} ?>>9no</option>
          <option value="10" <?php if($campaigndetails['campaign_run_month_day']=="10"){echo("selected");} ?>>10mo</option>
          <option value="11" <?php if($campaigndetails['campaign_run_month_day']=="11"){echo("selected");} ?>>11ro</option>
          <option value="12" <?php if($campaigndetails['campaign_run_month_day']=="12"){echo("selected");} ?>>12do</option>
          <option value="13" <?php if($campaigndetails['campaign_run_month_day']=="13"){echo("selected");} ?>>13ro</option>
          <option value="14" <?php if($campaigndetails['campaign_run_month_day']=="14"){echo("selected");} ?>>14to</option>
          <option value="15" <?php if($campaigndetails['campaign_run_month_day']=="15"){echo("selected");} ?>>15to</option>
          <option value="16" <?php if($campaigndetails['campaign_run_month_day']=="16"){echo("selected");} ?>>16to</option>
          <option value="17" <?php if($campaigndetails['campaign_run_month_day']=="17"){echo("selected");} ?>>17mo</option>
          <option value="18" <?php if($campaigndetails['campaign_run_month_day']=="18"){echo("selected");} ?>>18vo</option>
          <option value="19" <?php if($campaigndetails['campaign_run_month_day']=="19"){echo("selected");} ?>>19no</option>
          <option value="20" <?php if($campaigndetails['campaign_run_month_day']=="20"){echo("selected");} ?>>20mo</option>
          <option value="21" <?php if($campaigndetails['campaign_run_month_day']=="21"){echo("selected");} ?>>21ro</option>
          <option value="22" <?php if($campaigndetails['campaign_run_month_day']=="22"){echo("selected");} ?>>22do</option>
          <option value="23" <?php if($campaigndetails['campaign_run_month_day']=="23"){echo("selected");} ?>>23ro</option>
          <option value="24" <?php if($campaigndetails['campaign_run_month_day']=="24"){echo("selected");} ?>>24to</option>
          <option value="25" <?php if($campaigndetails['campaign_run_month_day']=="25"){echo("selected");} ?>>25to</option>
          <option value="26" <?php if($campaigndetails['campaign_run_month_day']=="26"){echo("selected");} ?>>26to</option>
          <option value="27" <?php if($campaigndetails['campaign_run_month_day']=="27"){echo("selected");} ?>>27mo</option>
          <option value="28" <?php if($campaigndetails['campaign_run_month_day']=="28"){echo("selected");} ?>>28vo</option>
          <option value="29" <?php if($campaigndetails['campaign_run_month_day']=="29"){echo("selected");} ?>>29no</option>
          <option value="30" <?php if($campaigndetails['campaign_run_month_day']=="30"){echo("selected");} ?>>30mo</option>
          <option value="31" <?php if($campaigndetails['campaign_run_month_day']=="31"){echo("selected");} ?>>31ro</option>
      </select> 
        d&iacute;a</td>
    <td></td>
  </tr>
         <tr>
    <td height="26" align="left">
<input type="radio" value="8" name="campaign_run" <?php if($campaigndetails['campaign_run']=="8"){echo("checked");} ?>>
<input type="hidden" name="campaign_run_messages_to_post_every_week"  value="<?php if($campaigndetails['campaign_run_messages_to_post_every_week']!=0){echo $campaigndetails['campaign_run_messages_to_post_every_week'];} ?>">
Un lote de mensajes cada semana en        
<select name="campaign_run_day_post_x_messages" id="campaign_run_day_post_x_messages" class="combo" style="width:100px ">
  <option value="1" selected>Lunes</option>
  <option value="2" <?php if($campaigndetails['campaign_run_day_post_x_messages']=="2"){echo("selected");} ?>>Martes</option>
  <option value="3" <?php if($campaigndetails['campaign_run_day_post_x_messages']=="3"){echo("selected");} ?>>Miercoles</option>
  <option value="4" <?php if($campaigndetails['campaign_run_day_post_x_messages']=="4"){echo("selected");} ?>>Jueves</option>
  <option value="5" <?php if($campaigndetails['campaign_run_day_post_x_messages']=="5"){echo("selected");} ?>>Viernes</option>
  <option value="6" <?php if($campaigndetails['campaign_run_day_post_x_messages']=="6"){echo("selected");} ?>>S&aacute;bado</option>
  <option value="7" <?php if($campaigndetails['campaign_run_day_post_x_messages']=="7"){echo("selected");} ?>>Domingo</option>

</select></td>
    <td></td>
  </tr>
    <tr>
    <td height="26" align="left">
<input type="radio" value="3" name="campaign_run" onClick="document.createnewcampaign.campaign_repeat_type[0].checked=true" <?php if($campaigndetails['campaign_run']=="3"){echo("checked");} ?>> 
Un lote de mensajes en la fecha espec&iacute;fica
        <?php $todaydate = date("d-m-Y"); ?>
		<input type="text" class="inputbox" maxlength="10" size="16" name="campaign_run_specific_day" id="campaign_run_specific_day" value="<?php echo date_str_to_db($campaigndetails['campaign_run_specific_day']); ?>"><a href="#" onClick="javascript: return showCalendar('campaign_run_specific_day', '%d-%m-%Y');"><img src="images/cal.gif" hspace="2" border="0" align="absmiddle"></a></td>
    <td></td>
  </tr>
  
</table>
</td>
</tr>
<tr>
<td  class="admineditbox_settings_top">
<img src="images/info.png" alt="Ayuda" style="cursor:help" align="absmiddle" onMouseOver="Tip('Elije lo que suceder&aacute; despu&eacute;s de que se publicar&aacute;n todos los mensajes de la campa&ntilde;a.&nbsp;&nbsp;&nbsp;&nbsp;')" onMouseOut="UnTip()">
Frecuencia de la campa&ntilde;a:</td>
<td  class="admineditbox_settings_top">
<input type="radio" name="campaign_repeat_type" value="0" checked>
Despu&eacute;s de la publicaci&oacute;n del primer lote de mensajes, la campa&ntilde;aa se detendr&aacute;.<br>
<input type="radio" name="campaign_repeat_type" value="1" <?php if($campaigndetails['campaign_repeat_type']=="1"){echo("checked");} ?>> 
Despu&eacute;s de la publicaci&oacute;n de todos los mensajes, la campa&ntilde;a se iniciar&aacute; de nuevo.
</td>
</tr>
<tr>
<td align="left" colspan="2" class="admineditbox_settings">
<div id="error1" style="visibility:hidden; margin-bottom:0px" class="warning_notices">Elige un nombre para la campa&ntilde;a.</div></td>
</tr>
<tr>
<td colspan="2" class="admineditbox_settings">
<input name="Submit" type="button" onClick="window.location='fb_managecampaigns.php' " class="submit" value="Cancelar" style="width:130px ">
<input name="Submit2" type="button" onClick="vercreatecampaign()" class="submit" value="Actualizar campa&ntilde;a" style="width:150px ">
<input type="hidden" name="editcampaign" value="yes">
<input type="hidden" name="campaignid" value="<?php echo $campaigndetails['campaignid']  ?>">
<input type="hidden" name="isgroup" value="<?php echo $campaigndetails['isgroup']  ?>">
</td>
</tr>
</table>
</form>

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
<span id="chromeFix"></span>
</html>
<?php
if(isset($_SESSION['fbs_error']))//new notice
		{
		echo("<script>document.getElementById('error1').innerHTML='".$_SESSION['fbs_error']."';document.getElementById('error1').style.visibility='visible';</script>");
		unset($_SESSION['fbs_error']);
		}
}
else
{
@header("Location:index.php");
}
?>