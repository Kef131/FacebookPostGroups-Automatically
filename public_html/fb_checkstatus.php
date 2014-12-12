<?php header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
include("topadmin.php");
$search = '';
if (isset($_GET['search'])) 
	$search = $_GET['search'];
	
$result = mysql_query('SELECT * FROM  fbshare_fbaccounts WHERE accountid ="'.$search.'"');
$accountdetails=mysql_fetch_array($result);

//check status
require_once("cronjobs/fbsdk/facebook.php");
require_once("cronjobs/functions.php");
$fb = new fb($appid, $appsecret);
$user_access_token = $accountdetails['fb_password'];

////renew access token
$fb->renewAccessToken($user_access_token);

///update db
$result = mysql_query('UPDATE fbshare_fbaccounts SET accountstatus="1" WHERE accountid ="'.$search.'"');

$text=fb_status_to_string_xml("1");

echo $text;
?>