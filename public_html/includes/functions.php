<?php 
global $cookie;
global $location;
global $cookiearr;
global $ch;
$cookie="cookie.txt";
$ssl_ver=0;
function get_remote_file($url, $timeout = 60)
{
                  $ch = curl_init();
                  curl_setopt ($ch, CURLOPT_URL, $url);
                  curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
                  curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
                  $file_contents = curl_exec($ch);
                  curl_close($ch);
                  return ($file_contents) ? $file_contents : FALSE;
}
function read_header($ch, $string)
{
    global $cookie_file_path, $cookie;
	global $location;
    global $cookiearr;
    global $ch;
 
    $length = strlen($string);
    if(!strncmp($string, "Location:", 9))
    {
      $location = trim(substr($string, 9, -1));
	 
    }
    if(!strncmp($string, "Set-Cookie:", 11))
    {
      $cookiestr = trim(substr($string, 11, -1));
      $cookie = explode(';', $cookiestr);
      $cookie = explode('=', $cookie[0]);
      $cookiename = trim(array_shift($cookie));
      $cookiearr[$cookiename] = trim(implode('=', $cookie));
    }
    $cookie = "";
    if(trim($string) == "")
    {
      foreach ($cookiearr as $key=>$value)
      {
        $cookie .= "$key=$value; ";
      }
      $cookie = trim ($cookie, "; ");
      curl_setopt($ch, CURLOPT_COOKIE, $cookie);
      curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    }

    return $length;
}
function read_header_post($ch, $string)
{
    global $cookie_file_path, $cookie;
	global $location;
    global $cookiearr;
    global $ch;
 
    $length = strlen($string);
    if(!strncmp($string, "Location:", 9))
    {
      $location = trim(substr($string, 9, -1));
	 
    }
    if(!strncmp($string, "Set-Cookie:", 11))
    {
      $cookiestr = trim(substr($string, 11, -1));
      $cookie = explode(';', $cookiestr);
      $cookie = explode('=', $cookie[0]);
      $cookiename = trim(array_shift($cookie));
      $cookiearr[$cookiename] = trim(implode('=', $cookie));
    }
    $cookie = "";
    if(trim($string) == "")
    {
      foreach ($cookiearr as $key=>$value)
      {
        $cookie .= "$key=$value; ";
      }
      $cookie = trim ($cookie, "; ");
      curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd()."/temp/postnowcookie.txt");
      curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd()."/temp/postnowcookie.txt");
    }

    return $length;
}
function curl_file_get_contents($url)
{
 $curl = curl_init();
 $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
 
 curl_setopt($curl,CURLOPT_URL,$url);	//The URL to fetch. This can also be set when initializing a session with curl_init().
 curl_setopt($curl,CURLOPT_RETURNTRANSFER,TRUE);	//TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
 curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,5);	//The number of seconds to wait while trying to connect.	
 
 curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);	//The contents of the "User-Agent: " header to be used in a HTTP request.
 curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);	//To follow any "Location: " header that the server sends as part of the HTTP header.
 curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE);	//To automatically set the Referer: field in requests where it follows a Location: redirect.
 curl_setopt($curl, CURLOPT_TIMEOUT, 60);	//The maximum number of seconds to allow cURL functions to execute.
 curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);	//To stop cURL from verifying the peer's certificate.
 
 $contents = curl_exec($curl);
 curl_close($curl);
 return $contents;
}
///FB related functions
function fb_logout()
{
global $cookie_file_path, $cookie;
global $location;
global $cookiearr;
global $ch;
global $ssl_ver;

curl_setopt($ch, CURLOPT_URL, 'http://m.facebook.com/logout.php');
curl_setopt($ch, CURLOPT_POST, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$logout = curl_exec ($ch);
}


function checkfbaccountlogin($fbemail,$fbpassword)
{
	
	global $cookie_file_path, $cookie;
	global $location;
	global $cookiearr;
	global $ch;
	$ssl_ver=0;
	
	$email=urlencode($fbemail);
	$pass=urlencode($fbpassword);
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADERFUNCTION, 'read_header');
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $ssl_ver);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.2) Gecko/20100115 Firefox/3.6 (.NET CLR 3.5.30729)");
	curl_setopt($ch, CURLOPT_ENCODING, "");
	curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().$cookie);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
	curl_setopt($ch, CURLOPT_FORBID_REUSE, 1); 
	curl_setopt($ch, CURLOPT_URL,"http://m.facebook.com/sharer.php"); //sharer redirect -> login
    $fbhome = curl_exec ($ch);



preg_match("/<form.*action=(\"([^\".]*)\"|'([^']*)'|[^>\s]*)([^>]*)?>/is", $fbhome, $formaction);
urlencode(preg_match("/<input type=\"hidden\" name=\"charset_test\" value=\"(.*)\" \/>/U", $fbhome, $chartest));

//lsd
  $lsd = substr($fbhome, strpos($fbhome, "name=\"lsd\""));
  $lsd = substr($lsd, strpos($lsd, "value=") + 7);
  $lsd = substr($lsd, 0, strpos($lsd, "\""));
//m_ts
  $m_ts = substr($fbhome, strpos($fbhome, "name=\"m_ts\""));
  $m_ts = substr($m_ts, strpos($m_ts, "value=") + 7);
  $m_ts = substr($m_ts, 0, strpos($m_ts, "\""));
 ///li
  $li = substr($fbhome, strpos($fbhome, "name=\"li\""));
  $li = substr($li, strpos($li, "value=") + 7);
  $li = substr($li, 0, strpos($li, "\""));
  
 $formaction=str_replace('"','',$formaction[1]); 
  

curl_setopt($ch, CURLOPT_URL, $formaction);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "lsd=".$lsd."&m_ts=".$m_ts."&li=".$li."&charset_test=".$chartest[1]."&email=$email&pass=$pass&ajax=0&width=0&pxr=0&gps=0&version=1&login=Log+in&signup_layout=header_button&laststage=first&_fb_noscript=true");
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$loggedin = curl_exec ($ch);



curl_setopt($ch, CURLOPT_URL, "http://m.facebook.com/profile.php?refid=0");
curl_setopt($ch, CURLOPT_POST, 0);
$loggedin = curl_exec ($ch);


//check if login fine
preg_match("/<input type=\"hidden\" name=\"fb_dtsg\" value=\"(.*)\" autocomplete=\"off\" \/>/U", $loggedin, $dtsg);

if($dtsg[1]!='') //login ok
	{
	return 1;
	}
if($dtsg[1]=="") //login not ok
	{
	return 2;
	}	
	
}

/*general functions*/

function passstrrand($length)// password random string
{
	$str = "";
	while(strlen($str)<$length){
	$random=rand(48,122);
	if( ($random>97 && $random<121)){ //47->58: number; 65->90 : A-Z; 97->121: a-z
	$str.=chr($random);
	} 
	
	}
		return $str;
	}

function date_str_to_db($string)
{
$datetoconvert=$string;
$vars = explode("-", $datetoconvert);
$datetodb=$vars[2]."-".$vars[1]."-".$vars[0];
return $datetodb;
}

function date_str_to_db_time($string)
{
$datetoconvert=$string;

$vars_tmp = explode(" ", $datetoconvert);

$vars = explode("-", $vars_tmp[0]);
$datetodb=$vars[2]."-".$vars[1]."-".$vars[0].", ".$vars_tmp[1];

return $datetodb;
}

function timestamp_str_to_db($string)
{
$datetoconvert=$string;
$vars = explode(" ", $datetoconvert);
$vars = explode("-", $vars[0]);
$datetodb=$vars[2]."-".$vars[1]."-".$vars[0];
return $datetodb;
}
/*my account*/
//admin change personal info
function admin_changeinfo()
{
$oldpassword=md5(trim(htmlspecialchars($_POST['oldpassword'])));
$adminemail=trim(htmlspecialchars($_POST['email']));
if($oldpassword!=$_SESSION['userpass'])
	{
	$script = "<script language=\"javascript\">
				document.getElementById(\"error\").style.visibility='visible';
				document.getElementById(\"error\").innerHTML='Incorrect Old Password.'</script>";
	echo $script;
	}
	else
		{
		$id=$_SESSION['fbs_admin'];
		$newpassword=md5(trim(htmlspecialchars($_POST['newpass1'])));
		mysql_query('UPDATE fbshare_users SET  
		userpassword="'.$newpassword.'",
		useremailaddress="'.$adminemail.'"
		WHERE userid="'.$id.'"');
		$script = "<script language=\"javascript\">
					document.getElementById(\"error\").style.visibility='visible';
					document.getElementById(\"error\").innerHTML='Personal data changed.'</script>";
		echo $script;
		$_SESSION['userpass']=md5(trim(htmlspecialchars($_POST['newpass1'])));
		}
}

///remind password
function forgotpassword()
{
$uname=trim($_POST['uname']);
$email=trim($_POST['email']);
//from users
$result = mysql_query('SELECT * FROM fbshare_users WHERE username="'.$uname.'"');
$nrus=mysql_num_rows($result);
if($nrus==0)//no account with this name
{
$_SESSION['fbs_error']="2";
}
else
{
	    $admindetails=mysql_fetch_array($result);
		if($admindetails['useremailaddress']!=$email)//incorrect email address
		{$_SESSION['fbs_error']="2";}
		else
		{
		$_SESSION['fbs_error']="3";
		$generatedpassword=passstrrand(8);
		$newpassword=md5($generatedpassword);
	
		mysql_query('UPDATE fbshare_users SET userpassword="'.$newpassword.'" WHERE userid="'.$admindetails['userid'].'"');
		}
}
	if($_SESSION['fbs_error']=="3")
	{
	include("includes/phpmailer/class.phpmailer.php");
	$emailbody='
	<html>
	<head>
	<title>Password Recovery</title>
	<style>
	.text
	{ 
	font-size:13px;
	font-weight:800; 
	font-family:arial; 
	color: #000000;
	}
	.text2
	{ 
	font-size:13px;
	font-family: tahoma,arial; 
	color: #000000;
	}
	</style>
	</head>
	<body>
	<table border=0 width=400>
	<tr>
	<td width=400 height=10 colspan=2 class=text2>
	PGF - Password recovery</td>
	</tr>
	<tr>
	<td width=400 height=20 colspan=2 class=text2>
	<font color=blue><br><b>Recibiste este correo porque solicitaste una nueva contrase&ntilde;a</b></font><br><br>
	<font color=black face=Arial>Por razones de seguridad, el sistema te ha generado una nueva contrase&ntilde;a.
	<br>
	</font>
	</td>
	</tr>
	<tr>
	<td width=650 class=text2><br><b>Tus datos de ingreso: </b><br></td>
	</tr>
	<tr>
	<td width=100 class=text2>Usuario:</td>
	<td width=300 class=text2>'.$admindetails['username'].'</td>
	</tr>
	<tr>
	<td width=100 class=text2>Tu nueva contrase&ntilde;a:</td>
	<td width=300 class=text2>'.$generatedpassword.'</td>
	</tr>
	</table> 
	</body>
	</html>
	';
	/////////////////////email

	$Mail = new PHPMailer();
	$Mail->IsHTML(true);
    $Mail->AddReplyTo($email,"PGF Admin");
	$Mail->From     = "noreply@webrenta.me";
	$Mail->FromName = "PGF"; 
	$Mail->MsgHTML($emailbody);
	$Mail->Subject  = "Recuperaci&oacute;n de contrasena";
	$Mail->AddAddress($email, $admindetails['username']);
	$Mail->Send();
	$Mail->ClearAddresses();
	}
}

//admin change password
function changepassword()
{
if(isset($_POST['oldpassword']))
{
$oldpassword=md5(trim(htmlspecialchars($_POST['oldpassword'])));
if($oldpassword!=$_SESSION['fbs_userpass'])
	{
	$script = "<script language=\"javascript\">
				document.getElementById(\"error\").style.visibility='visible';
				document.getElementById(\"error\").innerHTML='Anterior Contrase&ntilde;a incorrecta.'</script>";
	echo $script;
	}
   else
   { 
		$id=$_SESSION['fbs_admin'];
		$newpassword=md5(trim(htmlspecialchars($_POST['newpass1'])));
		mysql_query('UPDATE fbshare_users SET userpassword="'.$newpassword.'"  WHERE userid="'.$id.'"');
		$_SESSION['userpass']=$newpassword;
		$script = "<script language=\"javascript\">
					document.getElementById(\"error\").style.visibility='visible';
					document.getElementById(\"error\").innerHTML='Contrase&ntilde;a cambiada.'</script>";
		echo $script;
	}
	}
}

function date_range($sd,$ed)
{
$tmp = array();
$sdu = strtotime($sd);
$edu = strtotime($ed);
while ($sdu <= $edu) {
$tmp[] = date('l, F m ,Y',$sdu);
$sdu = strtotime('+1 day',$sdu);
}
return ($tmp);
}
function date_str_to_db_array_grph($string)
{
$datetoconvert=$string;
$vars = explode("-", $datetoconvert);
$datetodb=$vars[1]."/".$vars[0]."/".$vars[2];
return $datetodb;
}
function month_number_to_string($string)
{
$datetoconvert=$string;
$vars = explode("-", $datetoconvert);
$monthname = date("F", mktime(0, 0, 0, $vars[1], 10)); 
$datetodb=$vars[2]." ".$monthname." ".$vars[0];
return $datetodb;
}
function month_to_string($string)
{
$monthname = date("F", mktime(0, 0, 0, $string, 10)); 
return $monthname;
}
function day_to_string($string)
{
 switch ($string) {

            case 1: $dayname = 'Monday'; break;
            case 2: $dayname = 'Tuesday'; break;
            case 3: $dayname = 'Wednesday'; break;
            case 4: $dayname = 'Thursday'; break;
            case 5: $dayname = 'Friday'; break;
            case 6: $dayname = 'Saturday'; break;
            case 7: $dayname = 'Sunday'; break;
			}
			return $dayname;
}
function day_to_string2($string)
{
$day = date("jS", mktime(0, 0, 0, 0,$string, 10)); 
return $day;
}


function fb_status_to_string($string)
{
 switch ($string) {

            case 0: $status = '<font color=#FF0000>Cuenta SIN autorizar</font>'; break;
            case 1: $status = '<font color=#0000FF>Cuenta autorizada</font>'; break;
            case 2: $status = '<font color=#FF0000>Cuenta SIN autorizar</font>'; break;
			}
			return $status;
}

function fb_status_to_string_xml($string)
{
 switch ($string) {

            case 1: $status = '<font color=#0000FF>Cuenta autorizada y lista para usar</font>'; break;
            case 2: $status = '<font color=#FF0000>Cuenta SIN autorizar</font>'; break;
			}
			return $status;
}

function month_number_to_string_eu_format($string)
{
$datetoconvert=$string;
$vars = explode("-", $datetoconvert);
$monthname = date("F", mktime(0, 0, 0, $vars[1], 10)); 
$datetodb=$vars[0]." ".$monthname." ".$vars[2];
return $datetodb;
}

function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 $scriptpath=explode("/admin/",$pageURL);
 return $scriptpath[0];
}




function findexts ($filename) 
 { 
 $filename = strtolower($filename) ; 
 $exts = split("[/\\.]", $filename) ; 
 $n = count($exts)-1; 
 $exts = $exts[$n]; 
 return $exts; 
 } 

function uploadfile()
{
$listid=$_POST['importbulkmessages'];
set_time_limit(0);

//delete csv file if exists
$filename='temp/newcsvfile.csv';
if(file_exists('temp/newcsvfile.csv')) 
@unlink($filename);
//delete txt file if exists
$filename='temp/newfile.txt';
if(file_exists('temp/newfile.txt')) 
@unlink($filename);

///start upload
include('includes/classupload/class.upload.php');	

$handle = new Upload($_FILES['fis']);
	if ($handle->uploaded) 
	{
	$handle->file_new_name_body = 'newfile';
	$handle->file_auto_rename = false;
	$handle->file_overwrite = true;
	$handle->Process("temp");
	if ($handle->processed) 
		{
		$handle->clean();
		$ext=findexts($handle->file_src_name);
		
		if(strtoupper($ext)=='TXT'){$filename='temp/newfile.txt';}
		if(strtoupper($ext)=='CSV'){$filename='temp/newfile.csv';}
		
		$handle = fopen($filename, "r");
		$data = fread($handle, filesize($filename));
		$messages=explode("\n",$data);
		$totalmessages=sizeof($messages);
		$endposition=$totalmessages-1;
		$totmessages=0;
		for($i=0;$i<=$endposition;$i++)
				{
					if((trim($messages[$i]!='')) && (!empty($messages[$i])))
					{
					//replace quotes
					$messages[$i]=str_replace('"','',$messages[$i]);
					$messages[$i]=str_replace("'","",$messages[$i]);
					
					$order   = array("\r\n", "\n", "\r");
					$replace = '<br />';
					$messages[$i] = str_replace($order, $replace, $messages[$i]);
					
						if(trim($messages[$i])!='')
						{
						mysql_query('INSERT INTO fbshare_messages VALUES ("","'.$listid.'","'.$_SESSION['fbs_admin'].'","'.mysql_real_escape_string(trim($messages[$i])).'")');
						$totmessages++;
						}
					}
				}
				
				if($totmessages>0)
				{
				$_SESSION['fbs_error']=$totmessages.' messages successfully imported.';
				}
				else
				{
				$_SESSION['fbs_error']='File upload failed. Only CSV and TXT files are accepted.';
				}
			} 
			else 
			{
			$_SESSION['fbs_error']='File upload failed. Only CSV and TXT files are accepted.';}
			} 
			else 
			{$_SESSION['fbs_error']='File upload failed. Only CSV and TXT files are accepted.';}

	
}
function nl2br_revert_bis($string) {
    $br = preg_match('`<br>[\\n\\r]`',$string) ? '<br>' : '<br />';
    return preg_replace('`'.$br.'([\\n\\r])`', '$1', $string);
} 

function jsspecialchars( $string = '') {
    $string = preg_replace("/\r*\n/","\\n",$string);
    $string = preg_replace("/\//","\\\/",$string);
    $string = preg_replace("/\"/","\\\"",$string);
    $string = preg_replace("/'/"," ",$string);
    return $string;
}


function fb_post_now()
{
//global $appid;
//global $appsecret;

if(isset($_POST['message']) && trim($_POST['message']!="" && isset($_POST['accountid']))) //if not empty message and account
{
require_once("cronjobs/functions.php");

//Get account details
$reslogin=mysql_query("SELECT * FROM fbshare_fbaccounts WHERE accountid='".$_POST['accountid']."' AND accountstatus='1' ");
$fblogindetails=mysql_fetch_array($reslogin);
$email = urlencode($fblogindetails['fb_email']);
$pass = urlencode($fblogindetails['fb_password']);
$poster_user_id = urlencode($fblogindetails['fb_accountid']);

$appid= trim($fblogindetails['fb_username']);
$appsecret= trim($fblogindetails['fb_email']);



/////START PROCESS MESSAGE
$status=trim($_POST['message']);

//SPINTAX
$spintax_message = new spintax();
$status=$spintax_message->process($status);

//PROCESS NEW LINES
$status=str_replace("<br>","||",$status);
$status=str_replace("<br />","||",$status);
$status=str_replace("<br/>","||",$status);
$status=str_replace("||","\n",$status);
$status=str_replace("\'","'",$status);

//get URL
$url_to_send=get_url_from_string($status);
		
//check if it is IMAGE
$isimage=false;
if($url_to_send!=="")
{
	if (preg_match('/(\.jpg|\.png|\.jpeg|\.gif|\.bmp)$/', $url_to_send)) 
	{
		$isimage=true;
	}
}

//IF RSS
$isrss=false;
$firstcharsinmessage=substr(trim($status),0,5);
if($firstcharsinmessage=="#rss#")
{
	$isrss=true;
	$isimage=false;
	$rssurl=trim(str_replace("#rss#","",trim($status)));
	
	//process the rss
	$rss = simplexml_load_file(trim($rssurl));
	$items = $rss->channel->item;
	$status=trim($items[0]->title);
	$status_rss=trim($items[0]->title);
	$url_to_send=trim($items[0]->link);
	$url_to_send_rss=trim($items[0]->link);
	if($url_to_send==""){$url_to_send=$rssurl;}


}
//END RSS
//CHECK if is comment for last post
$isgroupcomment=false;
$firstcharinmessage=substr(trim($status), 0,2);
///if is comment 
//replace # from message
if($firstcharinmessage=="##")
{
$isgroupcomment=true;
$status_no_diez=str_replace("##","",trim($status));
}
//END if is comment for last post

//CHECK if is comment for user's post
$ismygroupcomment=false;
$firstcharinmessage=substr(trim($status), 0,2);
///if is comment 
//replace # from message
if($firstcharinmessage=="@@")
{
$ismygroupcomment=true;
$status_no_diez=str_replace("@@","",trim($status));
}
//END if is comment for user's post

//END PROCESS MESSAGE

/////////////////////////////START POSTING////////////////////////

//////////////WALL

if($_POST['messagespostedon']==0)
{
unset($_SESSION['lastphotoid']);
$groupid=$fblogindetails['fb_accountid'];
require_once("cronjobs/fbsdk/facebook.php");
$fb = new fb($appid, $appsecret);


		//if is image
		if($isimage==true)
		{
		$new_status=str_replace('\n'.trim($url_to_send),'',$status);
		$new_status=str_replace("\n".trim($url_to_send),"",$new_status);
		$new_status=str_replace(trim($url_to_send),"",$new_status);

		$post = $fb->uploadOnPage($groupid,$pass,$new_status,$url_to_send);
		if($post!="Message posted!")
		{
		$post = $fb->postOnPage($groupid,$pass,$status,$url_to_send);	
		}
		} 
		//if regular post
		else
		{
		
		if($isrss==true)
		{
		$post = $fb->postOnPage($groupid,$pass,$status_rss,$url_to_send_rss);
		}
		if($isrss==false)
		{
		$new_status=str_replace('\n'.trim($url_to_send),'',$status);
		$new_status=str_replace("\n".trim($url_to_send),"",$new_status);
		$new_status=str_replace(trim($url_to_send),"",$new_status);
		$post = $fb->postOnPage($groupid,$pass,$new_status,$url_to_send);
		}
		
		}
///logs
$_SESSION['fbs_error']='Request to post on wall sent. FB response: '.$post;	
}
////////////END WALL

////////////FAN PAGE
if($_POST['messagespostedon']>0)
{
unset($_SESSION['lastphotoid']);
require_once("cronjobs/fbsdk/facebook.php");
$fb = new fb($appid, $appsecret);

///GET page id
$resfbpage=mysql_query("SELECT * FROM fbshare_fbpages WHERE pageid='".$_POST['messagespostedon']."'");
$respage=mysql_fetch_array($resfbpage);
$pageid=$respage['fbpageurl'];

///get page token
$pages = $fb->get_page_token($pass, $pageid);
	
if(trim($pages)=="Wrong page id") ///different ID
{
$_SESSION['fbs_error']='Invalid page ID. Please check again your page ID';	
return 0;
}
else
{
$page_at=$pages;	
}

		if($isimage==true)
		{
		$new_status=str_replace('\n'.trim($url_to_send),"",$status);
		$new_status=str_replace("\n".trim($url_to_send),"",$new_status);
		$new_status=str_replace(trim($url_to_send),"",$new_status);
		
		$post = $fb->uploadOnPage($pageid,$page_at,$new_status,$url_to_send);
		if($post!="Message posted!")
		{
		$post = $fb->postOnPage($pageid,$page_at,$status,$url_to_send);	
		}
		}
		//if regular post
		else
		{
		
			if($isrss==true)
			{
			$post = $fb->postOnPage($pageid,$page_at,$status_rss,$url_to_send_rss);
			}
			
			if($isrss==false)
			{
			$new_status=str_replace('\n'.trim($url_to_send),"",$status);
			$new_status=str_replace("\n".trim($url_to_send),"",$new_status);
			$new_status=str_replace(trim($url_to_send),"",$new_status);
			
			$post = $fb->postOnPage($pageid,$page_at,$new_status,$url_to_send);
			}
		
		}


///logs
$_SESSION['fbs_error']='Request to post on fan page sent. FB response: '.$post;	
}
//////////END FAN PAGE

////////////ON GROUPS
if($_POST['messagespostedon']=='-1')
{
$posted=0;
$batcharray=array();
$flush_array=false;
			
if($isgroupcomment==false && $ismygroupcomment==false)
{
$_SESSION['fbs_error']='Posted in groups IDs: ';
}
if($isgroupcomment==true || $ismygroupcomment==true)
{
$_SESSION['fbs_error']='Added comment to post in groups IDs: ';
}

for($iter=0; $iter<=$_POST['totalnrofgroups']; $iter++)
	{
		//if 10 - > exit();
		if($posted==9){return 0;}
		
		if(isset($_POST['groupid_'.$iter]))
		{
			
			$resgroupname=mysql_query("SELECT * FROM fbshare_fbpages WHERE accountid='".$_POST['accountid']."' AND isgroup='1' AND pageid='".$_POST['groupid_'.$iter]."' ");
			$resgroupdetailsres=mysql_fetch_array($resgroupname);
			$newgroupid=$resgroupdetailsres['fbpageurl'];
			
			///comments
			if($isgroupcomment==true || $ismygroupcomment==true)
			{
			unset($_SESSION['lastphotoid']);
			require_once("cronjobs/fbsdk/facebook.php");
			$fb = new fb($appid, $appsecret);
			
			
			if($isgroupcomment==true) ///if is comment to last post 
					{
					    $groupcommentid = $fb->get_last_comment_id($newgroupid,$pass);	
						if($groupcommentid!="Invalid comment id")
						{
							$spintax_message = new spintax();
							$status_spin=$spintax_message->process($status_no_diez);
							$post = $fb->commentOnGroup($groupcommentid,$pass,$status_spin,$url_to_send);
							$_SESSION['fbs_error']=$_SESSION['fbs_error'].'['.$resgroupdetailsres['fbpageurl'].'] ';
							$posted++;
							sleep(1);
						}
						else
						{
							$post="Invalid comment id.";
							$_SESSION['fbs_error']='Request to comment sent. FB response: Invalid comment id.';
							return 0;
						}
					}//end if comment to last post
					
				if($ismygroupcomment==true) //if comment to my last post
						{
						$groupcommentid = $fb->get_my_last_comment_id($newgroupid,$poster_user_id,$pass);
									
							if($groupcommentid!="Invalid comment id")
							{
								$spintax_message = new spintax();
								$status_spin=$spintax_message->process($status_no_diez);
								$post = $fb->commentOnGroup($groupcommentid,$pass,$status_spin,$url_to_send);
								$_SESSION['fbs_error']=$_SESSION['fbs_error'].'['.$resgroupdetailsres['fbpageurl'].'] ';
								$posted++;
								sleep(1);
							}
							else
							{
								$post="Invalid comment id.";
								$_SESSION['fbs_error']='Request to comment sent. FB response: Invalid comment id.';
								return 0;
							}
						}//end if comment to my last post
			
			}///end if comments
			
			///group posts
			if($isgroupcomment==false && $ismygroupcomment==false)
			{
	
			require_once("cronjobs/fbsdk/facebook.php");
			$fb = new fb($appid, $appsecret);
			
			//SPINTAX only if no rss
			if($isrss==false)
			{
			$status_to_spin=$status;
			$spintax_message = new spintax();
			$status=$spintax_message->process($status_to_spin);

			///this is regular post - > get link again
			$url_to_send=get_url_from_string($status);
			
			//check if previous url != url to send => unset session -> upload again
			if($previous_url_to_send!=$url_to_send)
			{
				unset($_SESSION['lastphotoid']);
			}	
			//previous url
			$previous_url_to_send=$url_to_send;
			
			//status no link
			$status_no_link=str_replace($url_to_send,"",$status);
			//end get link again
			}
			////END SPINTAX
			
			//////////////start batch posting
			//IMAGE
						
				if($isimage==true)
							{
							$new_status=str_replace("\n".trim($url_to_send),"",$status);
							$new_status=str_replace(trim($url_to_send),"",$status);
									if(!isset($_SESSION['lastphotoid'])) //session not set -> upload image
									{
								     $post = $fb->uploadOnPage_first_Batch($newgroupid,$pass,$new_status,$url_to_send);
									 	if($post!="Message posted!") ///FIRST UPLOAD NOT OK
										{
										$_SESSION['fbs_error']='Request to comment sent. FB response: Error while uploading first image.';
										return 0;	//not ok
										}
									 $_SESSION['fbs_error']=$_SESSION['fbs_error'].'['.$resgroupdetailsres['fbpageurl'].'] ';
									 $posted++;
									 sleep(0.5);
									}
									else
									{
										///replace URL from message
										$url_new_from_message="";
										$url_new_from_message=get_url_from_string($new_status);
										$new_status_nolinks="";
										$new_status_nolinks=str_replace("\n".trim($url_new_from_message),"",$new_status);
										$new_status_nolinks=str_replace(trim($url_new_from_message),"",$new_status);
										$url_to_send='https://www.facebook.com/photo.php?fbid='.$_SESSION['lastphotoid'];
										
										$post = $fb->postOnPage($newgroupid,$pass,$new_status_nolinks,$url_to_send);
										$_SESSION['fbs_error']=$_SESSION['fbs_error'].'['.$resgroupdetailsres['fbpageurl'].'] ';
									 $posted++;
									 sleep(0.5);
									}		
							
							} //END IMAGE
							else //REGULAR POST
							{
								if($isrss==false)
								{
									$post = $fb->postOnPage($newgroupid,$pass,$status_no_link,$url_to_send);
								}
								if($isrss==true)
								{
									$post = $fb->postOnPage($newgroupid,$pass,$status_rss,$url_to_send_rss);
								}
								$_SESSION['fbs_error']=$_SESSION['fbs_error'].'['.$resgroupdetailsres['fbpageurl'].'] ';
								$posted++;
								sleep(0.5);
								
							} //END REGULAR POST

			}
			//end group posts
			
		} //END if isset post var
	
	} //END loop groups



}
//////////END ON GROUPS

////////////////////////////END POSTING///////////////////////////



	
} //end if not empty message and account
else //
{
$_SESSION['fbs_error']='The message was not posted.';	
}

	

}

?>