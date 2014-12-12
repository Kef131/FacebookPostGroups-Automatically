<?php include("topadmin.php"); 
if((isset($_SESSION['fbs_userpass']))&&(isset($_SESSION['fbs_useraccount']))) ///admin only
{

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//ES" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>PGF :: Comenzar</title>
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
<td align="left"><div class="formheader">Comenzando</div></td>
</tr>
<tr>
<td width="10" align="center" valign="top"></td>
<td align="left" valign="top" >
<table width="99%" border="0" cellpadding="0" cellspacing="0" class="tablenowidth" align="left">
<tr>
<td height="25" colspan="2" class="headerform">Primeros pasos</td>
</tr>
<tr>
  <td height="137" class="admineditbox">
  <div class="suggestions_help">
    <p><img src="images/info.png" alt="help" hspace="2" vspace="6" align="absmiddle" >
      <strong>Como agregar nuevas cuentas de Facebook</strong><br>
      <strong style="color:#900">Para poder utilizar el PGF debes crear tu propia aplicaci&oacute;n de Facebook para cada cuenta y debes tener la cuenta en Facebook verificada.<br>
        Cada cuenta debe ser a&ntilde;adida junto con su appID u appSECRET en <STRONG><a href="existingfbaccounts.php" target="_blank" class="slink">&raquo;RECURSOS DE FACEBOOK &raquo; CUENTAS DE FACEBOOK</a></STRONG></strong><br>
      <br>
      <strong style="color:#900">I. Creando una nueva aplicaci&oacute;n en Facebook <a href="https://www.youtube.com/watch?v=Wn99Rm0aWG8" target="_blank" class="slink">[Clic aqu&iacute; para ver el video en Youtube]</a></strong> (tambi&eacute;n lo encuentras en la secci&oacute;n <a href="tutoriales.php" target="_blank" class="slink"><strong>&raquo;</strong> Ayuda <strong>&raquo;</strong> Videos Tutoriales.</a>)<br>
      1. Ingresa a secci&oacute;n de  Facebook developers con una cuenta de perfil de Facebook verificada: <a href="https://developers.facebook.com" target="_blank" class="slink"><strong>https://developers.facebook.com</strong></a> <br />
      Si te pregunta: reg&iacute;strate como Facebook developer.<br />
  <br />
      2.  En el men&uacute; principal en la parte de arriba, haz clic en<strong> &quot;Apps</strong> &raquo; <strong>Create new App</strong>&quot;.<br />
  <br />
      3.  Escoje un nombre para la aplicaci&oacute;n (ejem: Publicador <strong>PGF</strong>),  y un Namespace(opcional), selecciona en categor&iacute;a "<strong>Aplicaciones para p&aacute;ginas</strong>" y haz clic en <strong>&quot;Creaci&oacute;n de Aplicaciones&quot;</strong>.<br />
  <br />
      4. Ve al men&uacute; <strong> &quot;Settings&quot;</strong> y pon los siguientes datos:<br />
  &nbsp;&nbsp;  4.1. <strong>Display Name</strong>: Escoje un nombre&ndash; en Facebook esto aparecer&aacute; como "publicado v&iacute;a&rdquo;<br />
  &nbsp;&nbsp;  4.2. <strong>Namespace</strong>: Escoje un namespace(opcional)<br />
  &nbsp;&nbsp;  4.3. <strong>App Domains</strong> agrega: <strong style="color:#900">
   <?php $sname=str_replace("www.","",$_SERVER['HTTP_HOST']); echo $sname ?>
  </strong><br />
  &nbsp;&nbsp; 4.4. Clic en <strong>&quot;+Add Platform</strong>&quot; y selecciona &quot;<strong>Website</strong>&quot;.<br />
  &nbsp;&nbsp; 4.4.1. En <strong>Site URL</strong> pon:  <strong style="color:#900"><?php echo "http://www.".$sname; ?></strong><br />
      
      
  &nbsp;&nbsp;&nbsp;4.5. Ingresa tu Email en campo de &quot;<strong>Contact Email</strong>&quot;.<br />
  <strong>Y haz clic en el bot&oacute;n &quot;Save Changes&quot;.</strong><br>
  <br />
      5. Ve a la secci&oacute;n "<strong>App Details</strong>" y en la parte de media de la pagina encontrar&aacute;s y le das clic a <strong>&quot;Configure App Center Permissions&quot;</strong>:&nbsp;<strong><br />
        </strong>Busca<strong> &quot;Default Activity Privacy&quot;</strong> - y pon<strong> &quot;P&uacute;blico&quot;,</strong> dale clic en <strong>&quot;Guardar&quot;</strong> y en <strong>&quot;Save Changes&quot;</strong>.</p>
      6. Ve a la secci&oacute;n "<strong>Status&amp;Review</strong>" y cambia:&nbsp;<strong><br />
      Do you want to make this app and all its live features available to the general public?</strong> - <strong>YES</strong><br /><br />
      7. Ve a la secci&oacute;n "<strong>Dashboard</strong>&rdquo; y haz clic en <strong>&quot;App  Secret &raquo; Show&quot;</strong>. Te pedir&aacute; tu contrase&ntilde;a de Facebook para revelar la <strong>&quot;Application Secret&quot;</strong>.<br />
      <br>
      <strong style="color:#900">II. A&ntilde;adiendo una nueva cuenta de Facebook en PGF</strong><br>
      <strong>Primero, </strong>Entra a tu sistema publicador <strong>PGF</strong>, en la secci&oacute;n<strong> <a href="existingfbaccounts.php" target="_blank" class="slink">"Recursos de Facebook &raquo; Cuentas de Facebook"</a> </strong>haz clic en el bot&oacute;n <strong>"Agregar nueva cuenta".<br>
        </strong>Cuando agregues una nueva cuenta de Facebook, pon una descripci&oacute;n sugestiva, la Application ID y appSecret asociadas a esa cuenta.<br>
        Despu&eacute;s de agregar la cuenta al sistema, debes permitir acceso a la aplicaci&oacute;n de Facebook que creaste - haz clic en el bot&oacute;n<strong> "Conceder acceso". <br>
        </strong>Concede todos los derechos que te pide el dialogo de Facebook, y aseg&uacute;rate de seleccionar<strong> "P&uacute;blico" </strong>cuando pregunte por los permisos de publicaci&oacute;n.</strong><br />
      <br>
      <strong style="color:#900">III. Agregando otras cuentas de Facebook al Publicador PGF.</strong><br>
      <strong>Si deseas utilizar varias cuentas de Facebook, antes de a&ntilde;adir una nueva cuenta, cierra la sesi&oacute;n primera de Facebook (en el navegador). <br>
      A continuaci&oacute;n, inicia sesi&oacute;n con tu otra cuenta de Facebook y sigue los pasos descritos anteriormente de nuevo.<br />
        Tendr&aacute;s que hacer esto s&oacute;lo una vez por cada cuenta de Facebook que agregues.</strong><br>
  </div></td>
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