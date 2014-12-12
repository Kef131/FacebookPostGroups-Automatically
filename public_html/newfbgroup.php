<?php include("topadmin.php"); 
if((isset($_SESSION['fbs_userpass']))&&(isset($_SESSION['fbs_useraccount']))) ///admin only
{
//get all fb accounts
$res=mysql_query('SELECT * FROM fbshare_fbaccounts WHERE userid="'.$_SESSION['fbs_admin'].'" AND accountstatus=1 ORDER BY fb_email DESC');
$totalaccounts=mysql_num_rows($res);
$combo="<select class=\"combo\" name=\"accountid\" style=\"width:395px\">";
while($accountdetails=mysql_fetch_array($res))
	{
	
	$combo.="<option value=\"".$accountdetails['accountid']."\">".$accountdetails['fb_description']." - ".$accountdetails['fb_email']."</option>";	
	}
	
$combo.="</select>";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//ES" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>PGF :: Nuevo Grupo</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="stylesheets/layout_styles.css" rel="stylesheet" type="text/css">
<link href="stylesheets/font_styles.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="jscript/jscript.js"></script>
</head>
<body class="bg">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td align="center" valign="top" >
<form  method="post" style="margin:0; padding:0" name="fbpageform">
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tablenowidth" align="center">
<tr>
<td height="25" colspan="2" class="headerform">Crear nuevo grupo de Facebook</td>
</tr>
<tr>
<td width="30%" class="admineditbox">Descripci&oacute;n del Grupo:</td>
<td class="admineditbox"><input type="text" class="inputbox" style="width:390px;" name="fbpagedescription"></td>
</tr>
<tr>
<td class="admineditbox">ID del Grupo:</td>
<td class="admineditbox"><input type="text" class="inputbox" style="width:390px;" name="fbpageurl" value=""></td>
</tr>
<tr>
<td class="admineditbox">Asociado a la cuenta:</td>
<td class="admineditbox"><?php echo $combo ?></td>
</tr>
<tr>
<td height="20" colspan="2" class="admineditbox">
<span id="error" style="visibility:hidden" class="message_error_admin">El nuevo grupo de Facebook fu&eacute; creado.</span></td>
</tr>
<tr>
<td class="admineditbox">
<input type="hidden" name="addnewgroup" value="yes">
<input type="button" class="submit" value="Crear Grupo" style="width:120px" onClick="createnewfbgroup()"></td>
<td class="admineditbox">Encuentra el ID del grupo : <a href="facebook_ID.php" target="_blank" style="color:#900; font-size:13px; font-weight:bold">CLIC AQU&Iacute;</a></td>
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