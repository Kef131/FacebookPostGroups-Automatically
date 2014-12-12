<?php include("topadmin.php"); 
if((isset($_SESSION['fbs_userpass']))&&(isset($_SESSION['fbs_useraccount']))) ///admin only
{
//html settings
if(isset($_POST['cron_nr_posts']))
{
  if(get_magic_quotes_gpc()) {
            if(ini_get('magic_quotes_sybase')) {
                $cron_nr_posts = str_replace("''", "'", $_POST['cron_nr_posts']);
				$cron_pause_between_posts= str_replace("''", "'", $_POST['cron_pause_between_posts']);
				$cron_send_notifications= str_replace("''", "'", $_POST['cron_send_notifications']);
				$cron_send_notifications_to= str_replace("''", "'", $_POST['cron_send_notifications_to']);
            } else {
	
				$cron_nr_posts = stripslashes($_POST['cron_nr_posts']);
				$cron_pause_between_posts= stripslashes($_POST['cron_pause_between_posts']);
				$cron_send_notifications= stripslashes($_POST['cron_send_notifications']);
				$cron_send_notifications_to= stripslashes($_POST['cron_send_notifications_to']);		
            }
        } else {
                $cron_nr_posts = $_POST['cron_nr_posts'];
				$cron_pause_between_posts= $_POST['cron_pause_between_posts'];
				$cron_send_notifications= $_POST['cron_send_notifications'];
				$cron_send_notifications_to= $_POST['cron_send_notifications_to'];
        }
mysql_query('UPDATE fbshare_general_settings SET cron_nr_posts="'.mysql_real_escape_string(trim($cron_nr_posts)).'",cron_pause_between_posts="'.mysql_real_escape_string(trim($cron_pause_between_posts)).'",
cron_send_notifications="'.mysql_real_escape_string(trim($cron_send_notifications)).'",cron_send_notifications_to="'.mysql_real_escape_string(trim($cron_send_notifications_to)).'" WHERE userid="'.$_SESSION['fbs_admin'].'" ');
}

//FB app
if(isset($_POST['appid']))
{
  if(get_magic_quotes_gpc()) {
            if(ini_get('magic_quotes_sybase')) {
                $appid = str_replace("''", "'", $_POST['appid']);
				$appsecret= str_replace("''", "'", $_POST['appsecret']);
            } else {
	
				$appid = stripslashes($_POST['appid']);
				$appsecret= stripslashes($_POST['appsecret']);	
            }
        } else {
                $appid = $_POST['appid'];
				$appsecret= $_POST['appsecret'];
        }
mysql_query('UPDATE fbshare_general_settings SET appid="'.mysql_real_escape_string(trim($appid)).'",appsecret="'.mysql_real_escape_string(trim($appsecret)).'" WHERE userid="'.$_SESSION['fbs_admin'].'" ');
}



?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//ES" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>PGF :: Configuraci&oacute;n de la aplicaci&oacute;n</title>
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
<td><div class="formheader">Configuraci&oacute;n de la aplicaci&oacute;n</div></td>
</tr>
<tr>
<td colspan="2" align="left" valign="top">
</tr>
<tr>
<td width="10" align="center" valign="top"></td>
<td align="left" valign="top" >
<form action="adminindex.php" method="post">
<table width="99%" border="0" cellpadding="0" cellspacing="0" class="tablenowidth" align="left">
<tr>
<td height="25" colspan="2" class="headerform">Actualizaci&oacute;n General</td>
</tr>
<tr>
<td width="33%" class="admineditbox_settings">
<img src="images/info.png" alt="Ayuda" style="cursor:help" align="absmiddle" onMouseOver="Tip('Este es el n&uacute;mero de mensajes enviados por vez por el cron job.&nbsp;&nbsp;&nbsp;&nbsp;')" onMouseOut="UnTip()">
N&uacute;mero m&aacute;ximo de mensajes enviados por vez:</td>
<td width="67%" class="admineditbox_settings">
<select class="combo" style="width:130px" name="cron_nr_posts">
<option value="1" <?php if($cron_nr_posts=='1'){echo("selected");} ?>>1 publicaci&oacute;n</option>
<option value="3" <?php if($cron_nr_posts=='3'){echo("selected");} ?>>3 publicaciones</option>
<option value="5" <?php if($cron_nr_posts=='5'){echo("selected");} ?>>5 publicaciones</option>
<option value="10" <?php if($cron_nr_posts=='10'){echo("selected");} ?>>10 publicaciones</option>
<option value="15" <?php if($cron_nr_posts=='15'){echo("selected");} ?>>15 publicaciones</option>
<option value="25" <?php if($cron_nr_posts=='25'){echo("selected");} ?>>25 publicaciones</option>
<option value="50" <?php if($cron_nr_posts=='50'){echo("selected");} ?>>50 publicaciones</option>
<option value="75" <?php if($cron_nr_posts=='75'){echo("selected");} ?>>75 publicaciones</option>
<option value="100" <?php if($cron_nr_posts=='100'){echo("selected");} ?>>100 publicaciones</option>
</select> 
[Valor recomendado: 25 publicaciones]
</td>
</tr>
<tr>
<td width="33%" class="admineditbox_settings">
<img src="images/info.png" alt="Ayuda" style="cursor:help" align="absmiddle" onMouseOver="Tip('La pausa en segundos entre la publicaci&oacute;n de mensajes sucesivos.&nbsp;&nbsp;&nbsp;&nbsp;')" onMouseOut="UnTip()">
Pausa entre la publicaci&oacute;n de los mensajes:</td>
<td width="67%" class="admineditbox_settings"> 

<select class="combo" style="width:105px" name="cron_pause_between_posts">
<option value="0.5" <?php if($cron_pause_between_posts=='0.5'){echo("selected");} ?>>0.5 segundos</option>
<option value="1" <?php if($cron_pause_between_posts=='1'){echo("selected");} ?>>1 segundos</option>
<option value="3" <?php if($cron_pause_between_posts=='3'){echo("selected");} ?>>3 segundos</option>
<option value="5" <?php if($cron_pause_between_posts=='5'){echo("selected");} ?>>5 segundos</option>
<option value="7" <?php if($cron_pause_between_posts=='7'){echo("selected");} ?>>7 segundos</option>
<option value="10" <?php if($cron_pause_between_posts=='10'){echo("selected");} ?>>10 segundos</option>
<option value="15" <?php if($cron_pause_between_posts=='15'){echo("selected");} ?>>15 segundos</option>
<option value="30" <?php if($cron_pause_between_posts=='30'){echo("selected");} ?>>30 segundos</option>
<option value="60" <?php if($cron_pause_between_posts=='60'){echo("selected");} ?>>60 segundos</option>
</select> 
[Valor recomendado: 5 segundos]</td>
</tr>
<tr>
<td class="admineditbox_settings">
<img src="images/info.png" alt="Ayuda" style="cursor:help" align="absmiddle" onMouseOver="Tip('Habilita esta funci&oacute;n si Quieres recibir una notificaci&oacute;n por correo electr&oacute;nico cuando termine la campaña.&nbsp;&nbsp;&nbsp;&nbsp;')" onMouseOut="UnTip()">
&iquest;Habilitar notificaci&oacute;n por correo electr&oacute;nico?</td>
<td class="admineditbox_settings">

<input type="radio" value="1"  name="cron_send_notifications" checked> SI
<input type="radio" value="0"  name="cron_send_notifications" <?php if(!$cron_send_notifications) {echo "checked";} ?>>  NO
</td>
</tr>
<tr>
<td class="admineditbox_settings">
<img src="images/info.png" alt="Ayuda" style="cursor:help" align="absmiddle" onMouseOver="Tip('La notificaci&oacute;n por correo electr&oacute;nico ser&aacute; enviado a este Email.&nbsp;&nbsp;&nbsp;&nbsp;')" onMouseOut="UnTip()">
Enviar&aacute; la notificaci&oacute;n por correo electr&oacute;nico a:</td>
<td class="admineditbox_settings"><input type="text" class="inputbox" style="width:300px;" value="<?php echo $cron_send_notifications_to ?>" name="cron_send_notifications_to">
</td>
</tr>
<tr>
<td height="40" colspan="2" class="admineditbox_settings">
<input name="Submit"  type="submit" class="submit" value="Actualizar" style="width:80px "></td>
</tr>
</table>
</form>
</td>
</tr>
<tr>
<tr><td colspan="2" height="10"> </td></tr>
<td width="10" align="center" valign="top" style="display:none"></td>
<td align="left" valign="top" style="display:none">
<form action="adminindex.php" method="post" name="fbappsettings">
<table width="99%" border="0" cellpadding="0" cellspacing="0" class="tablenowidth" align="left">
<tr>
<td height="25" colspan="2" class="headerform">Actualizaci&oacute;n de los Detalles de la Aplicaci&oacute;n de Facebook</td>
</tr>
<tr>
<td width="33%" class="admineditbox_settings">
<img src="images/info.png" alt="Ayuda" style="cursor:help" align="absmiddle" onMouseOver="Tip('Ingresa los datos de tu APP de Facebook.&nbsp;&nbsp;&nbsp;&nbsp;')" onMouseOut="UnTip()"> ID de la Aplicaci&oacute;n de Facebook [App ID]:</td>
<td width="67%" class="admineditbox_settings"><input type="text" class="inputbox" style="width:300px;" value="<?php echo $appid ?>" name="appid">
</td>
</tr>
<tr>
<td class="admineditbox_settings">
<img src="images/info.png" alt="Ayuda" style="cursor:help" align="absmiddle" onMouseOver="Tip('Clave secreta dada por el sistema de apps de Facebook developer.&nbsp;&nbsp;&nbsp;&nbsp;')" onMouseOut="UnTip()"> Clave Secreta [App Secret]:
</td>
<td class="admineditbox_settings"><input type="text" class="inputbox" style="width:300px;" value="<?php echo $appsecret ?>" name="appsecret">
</td>
</tr>
<tr>
<td height="40" colspan="2" class="admineditbox_settings">
<input name="Submit"  type="submit" class="submit" value="Actualizar" style="width:80px"></td>
</tr>
</table>
</form>
</td>
</tr>
<tr><td colspan="2" height="30">
</td>
</tr>
</table>
<div id="footer">
<?php  include("inc/footer.php"); ?>
</div>
</div>
<script language="JavaScript" type="text/javascript" src="jscript/tooltip/wz_tooltip.js"></script>
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