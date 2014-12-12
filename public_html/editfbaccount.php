<?php include("topadmin.php"); 
if((isset($_SESSION['fbs_userpass']))&&(isset($_SESSION['fbs_useraccount']))&&(isset($_GET['accountid']))) ///admin only
{
	$res=mysql_query('SELECT * FROM fbshare_fbaccounts WHERE userid="'.$_SESSION['fbs_admin'].'" AND accountid="'.$_GET['accountid'].'" ');
	$accountdetails=mysql_fetch_array($res);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//ES" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>PGF :: Editar cuenta</title>
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
<td height="25" colspan="2" class="headerform">Editar cuenta de Facebook</td>
</tr>
<tr>
<td width="35%" class="admineditbox">Descripci&oacute;n de la Cuenta :</td>
<td class="admineditbox"><input type="text" class="inputbox" style="width:330px;" name="fb_description" value="<?php echo $accountdetails['fb_description'] ?>"></td>
</tr>
<tr>
<td class="admineditbox">AppID de Facebook:</td>
<td class="admineditbox"><input type="text" class="inputbox" style="width:330px;" name="fb_username" placeholder="Ingresa tu appID de Facebook" value="<?php echo $accountdetails['fb_username'] ?>"></td>
</tr>
<tr>
<td class="admineditbox">AppSecret de Facebook:</td>
<td class="admineditbox"><input type="text" class="inputbox" style="width:330px;" name="fb_email" placeholder="Ingresa tu appSECRET de Facebook" value="<?php echo $accountdetails['fb_email'] ?>"></td>
</tr>
<tr>
<td class="admineditbox">
OAuth Token[Dejar vac&iacute;o] :</td>
<td class="admineditbox"><input type="text" class="inputbox" style="width:330px;" name="fb_password" readonly placeholder="Deja este campo vac&iacute;o" value="<?php echo $accountdetails['fb_password'] ?>">
</td>
</tr>
<tr>
<td height="20" colspan="2" class="admineditbox">
<span id="error" style="visibility:hidden" class="message_error_admin">La cuenta de Facebook fu&eacute; editada.</span></td>
</tr>
<tr>
<td colspan="2" class="admineditbox">
<input type="hidden" name="editaccunt" value="yes">
<input type="hidden" name="accountid" value="<?php echo $_GET['accountid'] ?>">
<input type="button" class="submit" value="Actualizar" style="width:120px" onClick="createnewfbaccount()"></td>
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