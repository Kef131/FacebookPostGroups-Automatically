<?php include("topadmin.php"); 
if((isset($_SESSION['fbs_userpass']))&&(isset($_SESSION['fbs_useraccount']))) ///admin only
{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//ES" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>PGF :: Nueva Cuenta</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="stylesheets/layout_styles.css" rel="stylesheet" type="text/css">
<link href="stylesheets/font_styles.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="jscript/jscript.js"></script>
</head>
<body class="bg">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td align="center" valign="top" >
<form action="existingfbaccounts.php" method="post" style="margin:0; padding:0" name="fbaccountform">
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tablenowidth" align="center">
<tr>
<td height="25" colspan="2" class="headerform">Agregar Nueva Cuenta</td>
</tr>
<tr>
<td width="32%" class="admineditbox">Descripci&oacute;n de la Cuenta:</td>
<td width="68%" class="admineditbox"><input type="text" class="inputbox" style="width:240px;" name="fb_description"></td>
</tr>
<tr>
<td class="admineditbox">AppID de Facebook:</td>
<td class="admineditbox"><input type="text" class="inputbox" style="width:330px;" name="fb_username" placeholder="Ingresa tu AppID de Facebook"></td>
</tr>
<tr>
<td class="admineditbox">AppSecret de Facebook:</td>
<td class="admineditbox"><input type="text" class="inputbox" style="width:330px;" name="fb_email" placeholder="Ingresa tu appSECRET de Facebook"></td>
</tr>
<tr>
<td class="admineditbox">
OAuth Token[leave empty]:</td>
<td class="admineditbox"><input type="text" class="inputbox" style="width:330px;" name="fb_password" readonly placeholder="Deja este campo vac&iacute;o">
</td>
</tr>
<tr>
<td height="20" colspan="2" class="admineditbox">
<span id="error" style="visibility:hidden" class="message_error_admin">La nueva cuenta de Facebook fu&eacute; creada.</span></td>
</tr>
<tr>
<td colspan="2" class="admineditbox">
<input type="hidden" name="addnewaccount" value="yes">
<input type="button" class="submit" value="Crear Cuenta" style="width:120px" onClick="createnewfbaccount()"></td>
</tr>
</table>
</form>
</td>
</tr>
</table>
</body>
</html>
<?php

}
else
{
@header("Location:index.php");
}
?>