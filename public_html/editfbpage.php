<?php include("topadmin.php"); 
if((isset($_SESSION['fbs_userpass']))&&(isset($_SESSION['fbs_useraccount']))) ///admin only
{

//get page content
$res=mysql_query('SELECT * FROM fbshare_fbpages WHERE userid="'.$_SESSION['fbs_admin'].'" AND pageid="'.$_GET['pageid'].'" ');
$pagedetails=mysql_fetch_array($res);


//get all fb accounts
$res=mysql_query('SELECT * FROM fbshare_fbaccounts WHERE userid="'.$_SESSION['fbs_admin'].'" AND accountstatus=1 ORDER BY fb_email DESC');
$totalaccounts=mysql_num_rows($res);
$combo="<select class=\"combo\" name=\"accountid\" style=\"width:395px\">";
while($accountdetails=mysql_fetch_array($res))
	{
	$selected="";
	if($pagedetails['accountid']==$accountdetails['accountid']) {$selected=" selected";}
	$combo.="<option value=\"".$accountdetails['accountid']."\" ".$selected.">".$accountdetails['fb_description']." - ".$accountdetails['fb_email']."</option>";	
	}
	
$combo.="</select>";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//ES" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>PGF :: Editar Paginas</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="stylesheets/layout_styles.css" rel="stylesheet" type="text/css">
<link href="stylesheets/font_styles.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="jscript/jscript.js"></script>
</head>
<body class="bg">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td align="center" valign="top" >
<form action="existingfbpages.php" method="post" style="margin:0; padding:0" name="fbpageform">
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tablenowidth" align="center">
<tr>
<td height="25" colspan="2" class="headerform">Editar pagina de Facebook</td>
</tr>
<tr>
<td width="30%" class="admineditbox">Descripci&oacute;n de la p&aacute;gina:</td>
<td class="admineditbox"><input type="text" class="inputbox" style="width:390px;" name="fbpagedescription" value="<?php echo $pagedetails['fbpagedescription'] ?>"></td>
</tr>
<tr>
<td class="admineditbox">ID de la P&aacute;gina:</td>
<td class="admineditbox"><input type="text" class="inputbox" style="width:390px;" name="fbpageurl" value="<?php echo $pagedetails['fbpageurl'] ?>"></td>
</tr>
<tr>
<td class="admineditbox">Asociada con la cuenta:</td>
<td class="admineditbox"><?php echo $combo ?></td>
</tr>
<tr>
<td height="20" colspan="2" class="admineditbox">
<span id="error" style="visibility:hidden" class="message_error_admin">Se cre&oacute; la nueva p&aacute;gina de Facebook.</span></td>
</tr>
<tr>
<td class="admineditbox">
<input type="hidden" name="editpage" value="yes">
<input type="hidden" name="pageid" value="<?php echo $_GET['pageid'] ?>">
<input type="button" class="submit" value="Actualizar Pagina" style="width:120px" onClick="createnewfbpage()"></td>
<td class="admineditbox">Encuentra el ID de tu grupo: <a href="facebook_ID.php" target="_blank" style="color:#900; font-size:13px; font-weight:bold">Clic aqu&iacute;.</a></td>
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