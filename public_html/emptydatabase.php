<?php include("topadmin.php"); 
if((isset($_SESSION['fbs_userpass']))&&(isset($_SESSION['fbs_useraccount']))) ///admin only
{
//empty database
if(isset($_POST['empty']))
{
///delete accounts
mysql_query("DELETE FROM fbshare_fbaccounts WHERE userid='".$_SESSION['fbs_admin']."'");
///delete pages
mysql_query("DELETE FROM fbshare_fbpages WHERE userid='".$_SESSION['fbs_admin']."'");

///delete messages
mysql_query("DELETE FROM fbshare_messagelists WHERE userid='".$_SESSION['fbs_admin']."'");
mysql_query("DELETE FROM fbshare_messages WHERE userid='".$_SESSION['fbs_admin']."'");

//delete from campaigns
mysql_query("DELETE FROM fbshare_campaigns WHERE userid='".$_SESSION['fbs_admin']."'");
mysql_query("DELETE FROM fbshare_campaigns_messages WHERE userid='".$_SESSION['fbs_admin']."'");
mysql_query("DELETE FROM fbshare_group_campaigns WHERE userid='".$_SESSION['fbs_admin']."'");

//empty logs
mysql_query("DELETE FROM fbshare_logs WHERE userid='".$_SESSION['fbs_admin']."'");

/*app secret and id
mysql_query("UPDATE fbshare_general_settings 
    				SET 
					appid='',
					appsecret =''
					WHERE userid='".$_SESSION['fbs_admin']."' AND usertype='0' "); */

echo("<script>window.location='logs.php'</script>");
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Admin Area :: Facebook Sharer Database</title>
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
<td align="left"><div class="formheader">Administration &raquo; Facebook Sharer Database </div></td>
</tr>
<tr>
</tr>
<tr>
<td width="10" align="center" valign="top"></td>
<td align="left" valign="top" >
<table width="99%" border="0" cellpadding="0" cellspacing="0" class="tablenowidth" align="left">
<tr>
<td height="25" colspan="2" class="headerform">Empty Facebook Sharer Database</td>
</tr>
<tr>
<td align="left" class="transtabs">
<div class="warning2">
<img src="images/warning.png" align="absmiddle" border="0">
USE THIS SECTION ONLY IF YOU WANT TO <font style="text-decoration:underline">EMPTY THE DATABASE</font>. <br><br>
This action will remove all:<br><br>
&raquo; saved  accounts<br>
&raquo; saved lists<br>
&raquo; saved messages<br>
&raquo; saved pages<br>
&raquo; existing campaigns<br>
&raquo; system logs<br>


<br><br>
<form method="post" name="emptydb">
<input type="hidden" name="empty" value="yes">
<input type="button" value="EMPTY THE DATABASE" class="button3_now" style="color:#FF3300; font-size:13px; width:180px; height:26px" title="EMPTY THE DATABASE" onClick="if(confirm('Are you sure you want to empty the database?')){document.emptydb.submit();}">
</form>
<br>
</div>
</td>
</tr>
</table>
</td>
</tr>
<tr><td colspan="2" height="10"></td></tr>
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