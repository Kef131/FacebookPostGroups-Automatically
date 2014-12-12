<?php include("topadmin.php"); 
if((isset($_SESSION['fbs_userpass']))&&(isset($_SESSION['fbs_useraccount']))) ///admin only
{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//ES" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>PGF :: Comandos Cron jobs</title>
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
<td align="left"><div class="formheader">Administraci&oacute;n &raquo; Comandos Cron Jobs </div></td>
</tr>
<tr>
<td width="10" align="center" valign="top"></td>
<td align="left" valign="top" >
<table width="99%" border="0" cellpadding="0" cellspacing="0" class="tablenowidth" align="left">
<tr>
<td height="25" colspan="2" class="headerform">Cron jobs (S&oacute;lo para prueba)</td>
</tr>
<tr>
  <td height="137" class="admineditbox">
  <div class="warning_crons">
<img src="images/warning.png" align="absmiddle" border="0"> Estos comandos estan en tu sistema actualizados. 
</div>
  <div class="suggestions">
S&oacute;lo utilizalo para correr pruebas no los uses como costubre para no ser bloqueado por Facebook, consulta al administrador de tu cuenta.

</div>
  <img src="images/info.png" alt="help" hspace="2" align="absmiddle" style="cursor:help">
  <strong>Comando para colocar en Muro: </strong><a href="http://<?php echo str_replace("/integrationcode.php","",$_SERVER["HTTP_HOST"]) ?>/PGF/cronjobs/fb_wall.php" target="_blank">
  <strong style="color:#039"> Correr ahora</strong></a><br><br><br>

  <img src="images/info.png" alt="help" hspace="2" align="absmiddle" style="cursor:help"><strong> Comando para colocar en Fan Pages: </strong> <a href="http://<?php echo str_replace("/integrationcode.php","",$_SERVER["HTTP_HOST"]) ?>/PGF/cronjobs/fb_pages.php" target="_blank">
  <strong style="color:#039"> Correr ahora</strong></a><br><br><br>

    <img src="images/info.png" alt="help" hspace="2" align="absmiddle" style="cursor:help"><strong> Comando para colocar en Grupos: </strong><a href="http://<?php echo str_replace("/integrationcode.php","",$_SERVER["HTTP_HOST"]) ?>/PGF/cronjobs/fb_groups.php" target="_blank">
  <strong style="color:#039"> Correr ahora</strong></a><br><br><br>

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