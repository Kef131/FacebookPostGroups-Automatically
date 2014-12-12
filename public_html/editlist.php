<?php include("topadmin.php"); 
if((isset($_SESSION['fbs_userpass']))&&(isset($_SESSION['fbs_useraccount']))) ///admin only
{
	//get page content
$res=mysql_query('SELECT * FROM fbshare_messagelists WHERE userid="'.$_SESSION['fbs_admin'].'" AND listid="'.$_GET['listid'].'" ');
$listdetails=mysql_fetch_array($res);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//ES" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>PGF :: Editar Lista</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="stylesheets/layout_styles.css" rel="stylesheet" type="text/css">
<link href="stylesheets/font_styles.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="jscript/jscript.js"></script>
</head>
<body class="bg">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td align="center" valign="top" >
<form action="fbmessagelist.php" method="post" style="margin:0; padding:0" name="fbpagelist">
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tablenowidth" align="center">
<tr>
<tr>
<td height="25" colspan="2" class="headerform">Editar lista de mensajes</td>
</tr>
<tr>
<td width="22%" class="admineditbox">Nombre de la lista:</td>
<td width="78%" class="admineditbox"><input type="text" class="inputbox" style="width:400px;" name="listname" value="<?php echo $listdetails['listname'] ?>"> </td>
</tr>
<tr>
<td class="admineditbox">Descripci&oacute;n:</td>
<td class="admineditbox"><input type="text" class="inputbox" style="width:400px;" name="listdescription" value="<?php echo $listdetails['listdescription'] ?>"></td>
</tr>
<tr>
  <td height="20" colspan="2" class="admineditbox">
  <span id="error" style="visibility:hidden" class="message_error_admin"></span></td>
</tr>
<tr>
<td colspan="2" class="admineditbox">
<input type="hidden" name="editlist" value="<?php echo $_GET['listid'] ?>">
<input type="button" class="submit" value="Editar Lista" style="width:120px" onClick="createnewfblist()"></td>
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