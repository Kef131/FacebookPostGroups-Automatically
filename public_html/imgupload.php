<?php include("topadmin.php"); 
if((isset($_SESSION['fbs_userpass']))&&(isset($_SESSION['fbs_useraccount']))) ///admin only
{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//ES" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>PGF :: Im&aacute;genes</title>
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
<td align="left">
<div class="formheader">Galer&iacute;a de Im&aacute;genes</div>
</td>
</tr>
<tr>
<td width="10" align="center" valign="top"></td>
<td align="left" valign="top" >
<table width="99%" border="0" cellpadding="0" cellspacing="0" class="tablenowidth" align="left">
<tr>
<td height="25" colspan="2">
 
 <!--IFRAME-->
 <object type="text/html" data="galeria/index.php" width="980" height="800" border="0" > </object> 
</tr>

</table>
</td>
</tr>
<tr><td colspan="2" height="20">
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