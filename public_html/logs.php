<?php include("topadmin.php"); 
if((isset($_SESSION['fbs_userpass']))&&(isset($_SESSION['fbs_useraccount']))) ///admin only
{
if(isset($_POST['deletelogs']))
{	
mysql_query("DELETE  FROM fbshare_logs WHERE userid='".$_SESSION['fbs_admin']."'");	
}
//date range and pages
if(isset($_POST['seldate']))
{
$dateheader=" - Date Range: ".trim(month_number_to_string_eu_format($_POST['startdate']))." <b>&raquo;</b> ".trim(month_number_to_string_eu_format($_POST['enddate']));
$csv_dateheader=trim(month_number_to_string_eu_format($_POST['startdate']))." &raquo; ".trim(month_number_to_string_eu_format($_POST['enddate']));
$startdate = trim($_POST['startdate']);
$enddate = trim($_POST['enddate']);

}
else
{
	if(isset($_GET['seldate']))
	{
	$dateheader=" - Date Range: ".trim(month_number_to_string_eu_format($_GET['startdate']))." <b>&raquo;</b> ".trim(month_number_to_string_eu_format($_GET['enddate']));
	$csv_dateheader=trim(month_number_to_string_eu_format($_GET['startdate']))." &raquo; ".trim(month_number_to_string_eu_format($_GET['enddate']));
	$startdate = trim($_GET['startdate']);
	$enddate = trim($_GET['enddate']);
	}
	else 
	{
	$startdate = date("d-m-Y",strtotime("-1 days"));
	$enddate = date("d-m-Y");
	$dateheader=" - &Uacute;ltimas 48 horas: ".trim(month_number_to_string_eu_format($startdate))." <b>&raquo;</b> ".trim(month_number_to_string_eu_format($enddate));
	$csv_dateheader=trim(month_number_to_string_eu_format($startdate))." &raquo; ".trim(month_number_to_string_eu_format($enddate));
	}
}



//ALL TIME
$rez1=0;
$rez2=0;
$rez3=0;
$rez4=0;
$rez5=0;



$resall=mysql_query('SELECT * FROM fbshare_logs');
$rez1=mysql_num_rows($resall);

$resall=mysql_query('SELECT * FROM fbshare_campaigns');
$rez2=mysql_num_rows($resall);

$resall=mysql_query('SELECT * FROM fbshare_campaigns WHERE is_campaign_finished=1');
$rez3=mysql_num_rows($resall);

$resall=mysql_query('SELECT * FROM fbshare_campaigns WHERE is_campaign_finished=0');
$rez4=mysql_num_rows($resall);


//END ALL TIME

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//ES" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>PGF :: Sistema de Registros</title>
<link rel="shortcut icon" href="images/favicon.ico">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="stylesheets/layout_styles.css" rel="stylesheet" type="text/css">
<link href="stylesheets/font_styles.css" rel="stylesheet" type="text/css">
<link href="stylesheets/jqueryslidemenu.css" rel="stylesheet" type="text/css">
<link href="stylesheets/pagination.css" rel="stylesheet" type="text/css">
<!--[if lte IE 7]>
<style type="text/css">
html .jqueryslidemenu{height: 1%;} 
</style>
<![endif]-->
<script type="text/javascript" src="jscript/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="jscript/jqueryslidemenu.js"></script>
<script language="javascript" type="text/javascript" src="jscript/jscript.js"></script>
<script type="text/javascript" src="jscript/calendar/calendar.js"></script>
<script type="text/javascript" src="jscript/calendar/lang/calendar-en.js"></script>
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
<body class="bg">
<?php if(isset($_POST['todo'])) { ?>
<!--loading screen code -->
<div id="loading" class="loading-invisible">
  <p>Actualizando Resultados. Un momento por favor...<br><br><img src="images/loader.gif" alt="Cargardo..." /></p>
</div>
<?php } 
else { ?>
<div id="loading" class="loading-invisible">
  <p style="margin:0; padding:0; ">Cargando resultados. Un momento por favor...<br><br><img src="images/loader.gif" alt="Cargando..." /></p>
</div>
<?php } ?>
<script type="text/javascript">
  document.getElementById("loading").className = "loading-visible";
  var hideDiv = function(){document.getElementById("loading").className = "loading-invisible";};
  var oldLoad = window.onload;
  var newLoad = oldLoad ? function(){hideDiv.call(this);oldLoad.call(this);} : hideDiv;
  window.onload = newLoad;
</script>
<!--end loading screen code -->

<?php include("inc/spacetop.php") ?>
<div id="container"> 
<div id="header">
<?php include("inc/header.php") ?>
</div>

<table width="98%" border="0" cellpadding="0" cellspacing="0" align="center">
<tr>
<td>
<form method="post" name="datestats" action="logs.php">
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
<td colspan="3"><div class="formheader_logs">Registros del Sistema</div></td>
</tr>
<tr>
<td colspan="2" style="padding-left:3px">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
<td align="left">
<div class="warning_stat_logs" style="height:75px; font-size:13px; width:99% ">
<span class="message_error_admin">Mostrar resultados de las fechas: </span> <br><br>
<input type="text" style="width:120px;" class="inputbox" onKeyPress="return submitenter(this,event)" name="startdate" id="startdate" value="<?php echo $startdate ?>"><a href="#" onClick="javascript: return showCalendar('startdate', '%d-%m-%Y');"><img src="images/cal.gif" hspace="2" border="0" align="absmiddle"></a> - 
&nbsp;<input type="text" style="width:120px;" class="inputbox" onKeyPress="return submitenter(this,event)" size="16" name="enddate" id="enddate" value="<?php echo $enddate ?>"><a href="#" onClick="javascript: return showCalendar('enddate', '%d-%m-%Y');"><img src="images/cal.gif" hspace="2" border="0" align="absmiddle"></a>
&nbsp;<input type="button" onClick="verdate()" value="Aplicar" class="submit"  style="width:80px">
<div class="message_error_date" id="message_error_date" style="visibility:hidden ">Por favor rellena ambos campos de fechas.</div>
<input type="hidden" name="seldate" value="yes">
<input type="hidden" name="page" value="1">
</div>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td class="datefield">
</td>
</tr>
</table>
</form>
</td>
</tr>
<tr>
<td colspan="2" align="left" valign="top">
<?php 
//get existing info ->show or not details
	if($startdate!=$enddate)
	{
	$result=mysql_query('SELECT * FROM  fbshare_logs b WHERE (DATE(b.loggedon) >= "'.date_str_to_db($startdate).'" AND DATE(b.loggedon)<="'.date_str_to_db($enddate).'") ORDER BY b.loggedon ASC, b.campaignid ASC');
    $result_limit=mysql_query('SELECT * FROM  fbshare_logs b WHERE (DATE(b.loggedon) >= "'.date_str_to_db($startdate).'" AND DATE(b.loggedon)<="'.date_str_to_db($enddate).'") ORDER BY b.loggedon ASC, b.campaignid ASC');
	}
	if($startdate==$enddate)
	{
	$result=mysql_query('SELECT * FROM fbshare_logs b WHERE DATE(b.loggedon) = "'.date_str_to_db($enddate).'"  ORDER BY b.loggedon ASC, b.campaignid ASC');
	$result_limit=mysql_query('SELECT * FROM fbshare_logs b WHERE DATE(b.loggedon) = "'.date_str_to_db($enddate).'"  ORDER BY b.loggedon ASC, b.campaignid ASC');
	}
$nrlogs=mysql_num_rows($result);
//total senders
if($nrlogs==0) {echo("<span class=\"heading\" style=\"padding-bottom:20px;padding-top:20px; padding-left:2px; padding-top:0px; color:#3a5896\">Sin registros en la base de datos.<br><br></span>");}
else
{
/////get total from branches
//date range
	if(isset($_POST['seldate']))
	{
	$startdate2 = trim($_POST['startdate']);
    $enddate2 = trim($_POST['enddate']);
	$dateheader2=trim(month_number_to_string_eu_format($_POST['startdate']))." <b>&raquo;</b> ".trim(month_number_to_string_eu_format($_POST['enddate']));
	}
		else 
		{
		if(isset($_GET['seldate']))
			{
			$startdate2 = trim($_GET['startdate']);
			$enddate2 = trim($_GET['enddate']);
			$dateheader2=trim(month_number_to_string_eu_format($_GET['startdate']))." <b>&raquo;</b> ".trim(month_number_to_string_eu_format($_GET['enddate']));
			}
		else 
			{
			$startdate2 = date("d-m-Y",strtotime("-7 days"));
			$enddate2 = date("d-m-Y");
			$dateheader2=trim(month_number_to_string_eu_format($startdate2))." <b>&raquo;</b> ".trim(month_number_to_string_eu_format($enddate2));
			}	
		}
	
?>
<div class="heading" style="padding-bottom:10px; padding-left:2px; padding-top:0px; border-bottom:2px #3a5896 solid; color:#3a5896">Registros de PGF <?php echo $dateheader ?></div>
<?php if($nrlogs>15){ ?>
<div style="overflow: auto; width: 100%; height: 400px; padding:0px; margin: 0px; border-bottom:1px #3a5896 solid; overflow-y: scroll;overflow-x:hidden;">
<table width="100%" cellpadding="0" cellspacing="1" border="0">
<?php
///show users/pages
$formatDate=0;
while($logsdb=mysql_fetch_array($result_limit))
{
if($formatDate==0)
{
$date = $logsdb['loggedon'];
}
else
{
$date = $formatDate;
}
$currentDate = strtotime($date);
$futureDate = $currentDate+45;
$formatDate = date("d-m-Y, H:i:s", $futureDate);

echo("<tr style=\"color:#000000\" bgcolor=\"#F9F9F9\" onmouseover=\"this.bgColor='#FFFFCC';\" onmouseout=\"this.bgColor='#F9F9F9';\">
<td class=\"sort2td_details_logs\" width=\"140\"><font color=#3a5896>[".$formatDate."]</font> </td>
<td class=\"sort2td_details_logs\" height=\"40\"><b>".$logsdb['logtext']."</b></td></tr>");
}   
?>
</table>
</div>
<?php } //end records > 15 
	//start records <15 
else
{ ?>
	<table width="100%" cellpadding="0" cellspacing="1" border="0">
<?php
///show records
	while($logsdb=mysql_fetch_array($result_limit))
	{
	echo("<tr style=\"color:#000000\" bgcolor=\"#F9F9F9\" onmouseover=\"this.bgColor='#FFFFCC';\" onmouseout=\"this.bgColor='#F9F9F9';\">
	<td class=\"sort2td_details_logs\" width=\"140\"><font color=#3a5896>[".$logsdb['loggedon']."]</font> </td>
	<td class=\"sort2td_details_logs\" height=\"40\"><b>".$logsdb['logtext']."</b></td></tr>");
	} 
	?>
	</table> 
<?php }//end records <15 ?>


<?php } ?>

<tr>
<td align="left" style="padding-top:20px;">

<input type="button" class="submit" title="Actualizar" style="width:100px" onClick="document.datestats.submit()" value="Actualizar">
<input type="button" class="submit" title="Borrar registros" style="width:120px" onClick="if(confirm('&iquest;Seguro quieres borrar el registro?')){document.deletelogs.submit();}" value="Borrar registros">
<form name="deletelogs" method="post">
<input type="hidden" name="deletelogs" value="yes">
</form>
</td>
</tr>
<tr>
<td  height="20"></td></tr>
</table>
<div id="footer">
<?php  include("inc/footer.php"); ?>
</div>
</div>
</body>
<span id="chromeFix"></span>
</html>
<?php
}
else
{
@header("Location:index.php");
}
?>