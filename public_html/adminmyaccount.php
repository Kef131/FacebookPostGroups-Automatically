<?php include("topadmin.php"); 
if((isset($_SESSION['fbs_userpass']))&&(isset($_SESSION['fbs_useraccount'])))
{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>PGF | Mi cuenta</title>
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
<?php 
include("inc/header.php") ?>
</div>
<table width="100%" border="0" align="center" cellspacing="0" cellpadding="0" height="200">
<tr>
<td width="10"></td>
<td colspan="2"><div class="formheader">Mi Cuenta</div></td>
<td width="540"><div class="formheader">Avisos</div></td>
<td width="10">&nbsp;</td>
</tr>
<tr>
<td width="10" align="center" valign="top"></td>
<td align="left" valign="top">
<form action="adminmyaccount.php" method="post" name="changepassword" autocomplete="off">
<table width="463" border="0" cellpadding="0" cellspacing="0" class="tableimport2">
<tr>
<td height="25" colspan="2" class="headerform">Cambiar contrase&ntilde;a de Ingreso</td>
</tr>
<tr >
<td width="34%" height="30" class="signup-labels" id="f2">Contrase&ntilde;a anterior:</td>
<td width="66%"><input name="oldpassword"  type="password" class="inputbox" size="45" maxlength="30" autocomplete="off"></td>
</tr>
<tr>
<td height="30" class="signup-labels" id="f3">Nueva Contrase&ntilde;a:</td>
<td ><input name="newpass1" type="password" class="inputbox" size="45" maxlength="30"></td>
</tr>
<tr>
<td height="30" class="signup-labels" id="f4">Repetir contrase&ntilde;a:</td>
<td ><input name="newpass2" type="password" class="inputbox" size="45" maxlength="30"></td>
</tr>
<tr>
<td colspan="2" align="left"  class="signup-labels" height="25"><div id="error" style="visibility:hidden" class="message_error"><span class="message_error" style="visibility:hidden">Completa todos los campos.</span></div></td>
</tr>
<tr>
<td height="28" align="left">&nbsp;</td>
<td>
<input type="hidden" name="userid" value="<?php echo $_SESSION['fbs_admin'] ?>">
<input name="Submit"  type="button" class="submit" value="Guardar" onClick="verchangepassword()" style="width:70px "></td>
</tr>
</table>
</form>
<br>
<form action="adminmyaccount.php" method="post" name="changeemailaddress">
<table border="0" cellpadding="0" cellspacing="0" class="tableimport2">
<tr>
<td height="25" colspan="2" class="headerform">Cambiar datos</td>
</tr>
<tr>
<td width="34%" height="30"  id="f5" class="signup-labels">Email:</td>
<td ><input name="emailaddress"  type="text" class="inputbox" size="45"></td>
</tr>
<tr>
<td width="30%" height="30"  id="f5" class="signup-labels">Nombre de usuario:</td>
<td width="70%" ><input name="userfullname" type="text" class="inputbox" size="45"></td>
</tr>
<tr>
<td colspan="2" height="25"  class="signup-labels"><div id="error2" style="visibility:hidden" class="message_error"><span class="message_error" style="visibility:hidden">Por favor, rellena el campo de correo electr&oacute;nico</span></div></td>
</tr>
<tr>
<td height="28" align="left"  >&nbsp;</td>
<td  ><input name="Submit"  type="button" class="submit" value="Guardar" style="width:70px " onClick="verchangeemail()"></td>
</tr>
</table>
</form>
</td>
<td align="left" width="15px" valign="top">&nbsp;</td>
<td align="left" valign="top">
<table border="0" cellspacing="0" cellpadding="0" class="tableimport2">

<tr>
<td>
	<object type="text/html" data="http://webrenta.me/PGF_cont/avisos.php" width="520" height="323" noexternaldata="true"></object>
</td>
</tr>
</table>
</td>
<td align="left" valign="top">&nbsp;</td>
</tr>

<tr><td colspan="5" height="20"></td></tr>
</table> 
<div id="footer">
<?php  include("inc/footer.php"); ?>
</div>
</div>
</body>
</html>
<?php
if(isset($_POST['oldpassword']))
{changepassword();}
if(isset($_POST['emailaddress']))
{$result = mysql_query("UPDATE fbshare_users 
    				SET 
					useremailaddress='".trim($_POST['emailaddress'])."',
					username ='".trim($_POST['userfullname'])."'
					WHERE userid='".$_SESSION['fbs_admin']."' AND usertype='0' ");
$_SESSION['fbs_useraccount']=trim($_POST['userfullname']);
}
					
$result = mysql_query("SELECT * FROM fbshare_users WHERE userid='".$_SESSION['fbs_admin']."' AND usertype='0'");
$admininfo=mysql_fetch_array($result);
echo("<script>document.changeemailaddress.emailaddress.value='".$admininfo['useremailaddress']."'</script>");
echo("<script>document.changeemailaddress.userfullname.value='".$admininfo['username']."'</script>");
}
else
{
@header("Location:index.php");
}
?>