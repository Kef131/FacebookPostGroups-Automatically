<?php 



if (!($conect = @mysql_connect($host,$user,$password)))
	{
		print "<h3 align=\"center\" style=\"color:#2A91CC\">No se pudo conectar con el servidor! Por favor Avisa al webmaster.</h3>";
		echo "<hr align=\"center\" width=\"90%\">";
		echo "<h4 align=\"center\" style=\"color:#A7A7A7\">";
		echo mysql_error();
		echo "<h4>";
		exit();
	}
/* Selecting datbase */
if (!@mysql_select_db($database))
	{
		print "<h3 align=\"center\" style=\"color:#2A91CC\">No se pudo conectar con la base de datos! Por favor Avisa al webmaster.</h3>";
		echo "<hr align=\"center\" width=\"90%\">";
		echo "<h4 align=\"center\" style=\"color:#A7A7A7\">";
		echo mysql_error();
		echo "<h4>";
		exit();
	}

//general settings
/**
	*@var $_SESSION identifica al usuario loggeado, de ser así, carga la configuración de FACEBOOk (revisar cómo se obtiene
	*estas configuracíones en el API de FACEBOOK)
	*en caso  contrario carga la sesión de un usuario tipo Guest.
**/

if(isset($_SESSION['fbs_admin']))
{
	$result=mysql_query('SELECT * FROM fbshare_general_settings WHERE userid="'.$_SESSION['fbs_admin'].'"');
}
else
{
	$result=mysql_query('SELECT * FROM fbshare_general_settings WHERE settingid=1'); ///crons
}

$generalsettings=mysql_fetch_array($result);

global $cron_nr_posts;
global $cron_pause_between_posts;
global $cron_send_notifications;
global $cron_send_notifications_to;
//global $appid; -> fb_username
//global $appsecret; -> fb_email

//app settings
//$appid=$generalsettings['appid'];
//$appsecret=$generalsettings['appsecret'];

///cron settings
$cron_nr_posts=$generalsettings['cron_nr_posts'];
$cron_pause_between_posts=$generalsettings['cron_pause_between_posts'];
$cron_send_notifications=$generalsettings['cron_send_notifications'];
$cron_send_notifications_to=$generalsettings['cron_send_notifications_to'];



?>