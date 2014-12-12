<?php include("topadmin.php"); 
if((isset($_SESSION['fbs_userpass']))&&(isset($_SESSION['fbs_useraccount']))) ///admin only
{

//import messages
if (isset($_POST['importbulkmessages']))
{
	uploadfile();
}
	
///adde new message manually
if(isset($_POST['addmessagemanually']))
{
	if(get_magic_quotes_gpc()) {
            if(ini_get('magic_quotes_sybase')) {
				$message = str_replace("''", "'", $_POST['message']);


            } else {
				
				$message = stripslashes($_POST['message']);
	
            }
        } else {
                $message = $_POST['message'];

        }
		
$order   = array("\r\n", "\n", "\r");
$replace = '<br />';
$message = str_replace($order, $replace, $message);

//add into messages
mysql_query('INSERT INTO fbshare_messages VALUES ("","'.$_POST['addmessagemanually'].'" ,"'.$_SESSION['fbs_admin'].'","'.mysql_real_escape_string(trim($message)).'")');

//add in campiagn messages that uses the same list
$lastmessid=mysql_insert_id();

$rescampiagns=mysql_query("SELECT * FROM fbshare_campaigns_messages WHERE originallistid='".$_POST['addmessagemanually']."' GROUP BY campaignid");
$usedincampiagn=mysql_num_rows($rescampiagns);
if($usedincampiagn>0)
{
	while($campaigndetails=mysql_fetch_array($rescampiagns))
	{
		//insert messages in message list
		mysql_query('INSERT INTO fbshare_campaigns_messages VALUES("","'.$lastmessid.'","'.$_POST['addmessagemanually'].'","'.$campaigndetails['campaignid'].'","'.$_SESSION['fbs_admin'].'","'.trim(mysql_real_escape_string($message)).'","","0")');
		
		//update campaign + increase nr of messages
		$resoldcampiagn=mysql_query("SELECT * FROM fbshare_campaigns WHERE campaignid='".$campaigndetails['campaignid']."' ");
		$oldcampiagnmes=mysql_fetch_array($resoldcampiagn);
		$new_totalmessagespostedinthiscampaign=$oldcampiagnmes['totalmessagespostedinthiscampaign']+1;
		
		mysql_query("UPDATE fbshare_campaigns SET 
			totalmessagespostedinthiscampaign='".$new_totalmessagespostedinthiscampaign."' WHERE userid='".$_SESSION['fbs_admin']."' AND campaignid='".$campaigndetails['campaignid']."'");
	}

}

$_SESSION['fbs_error']='El mensaje se ha guardado correctamente.';
}
	
$res1=mysql_query('SELECT * FROM fbshare_messagelists WHERE listid="'.$_GET['listid'].'" AND userid="'.$_SESSION['fbs_admin'].'" ');
$listdetails=mysql_fetch_array($res1);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>PGF :: Agregar mensajes</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="stylesheets/layout_styles.css" rel="stylesheet" type="text/css">
<link href="stylesheets/font_styles.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="jscript/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="jscript/jscript.js"></script>
<script type="text/javascript" src="jscript/ddaccordion.js"></script>
<script type="text/javascript">
ddaccordion.init({
	headerclass: "acc1", //Shared CSS class name of headers group
	contentclass: "acc2", //Shared CSS class name of contents group
	revealtype: "click", //Reveal content when user clicks or onmouseover the header? Valid value: "click" or "mouseover"
	mouseoverdelay: 200, //if revealtype="mouseover", set delay in milliseconds before header expands onMouseover
	collapseprev: true, //Collapse previous content (so only one open at any time)? true/false 
	defaultexpanded: [0], //index of content(s) open by default [index1, index2, etc]. [] denotes no content.
	onemustopen: false, //Specify whether at least one header should be open always (so never all headers closed)
	animatedefault: false, //Should contents open by default be animated into view?
	persiststate: false, //persist state of opened contents within browser session?
	toggleclass: ["", ""], //Two CSS classes to be applied to the header when it's collapsed and expanded, respectively ["class1", "class2"]
	togglehtml: ["prefix", "<img src='images/plus.png' align='absmiddle' border='0' /> ", "<img src='images/minus.png' align='absmiddle' border='0' /> "], 
	//two images added to the end of the header //Additional HTML added to the header when it's collapsed and expanded, respectively  ["position", "html1", "html2"] (see docs)
	animatespeed: "normal", //speed of animation: integer in milliseconds (ie: 200), or keywords "fast", "normal", or "slow"
	oninit:function(expandedindices){ //custom code to run when headers have initalized
		//do nothing
	},
	onopenclose:function(header, index, state, isuseractivated){ //custom code to run whenever a header is opened or closed
		//do nothing
	}
})

</script>
<!-- end accordion -->
</head>
<body>
<table width="100%" border="0" cellpadding="0" cellspacing="0" height="380">
<tr>
<td align="center" valign="top" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tablenowidth" align="center" height="380">
<tr>
<td width="100%" height="25" class="headerform">Mensajes</td>
</tr>

<tr>
<td height="20" colspan="2" class="admineditbox">
<p class="formheader_messages">Agregar mensaje(s) en lista "<?php echo $listdetails['listname'] ?>".</p>
</td>
</tr>


<tr>
<td colspan="2" class="admineditbox" valign="top">
<div class="warning_messages">
<img src="images/warning.png" align="absmiddle" border="0"> Utiliza texto sin formato, nuevas l&iacute;neas y enlaces a im&aacute;genes, v&iacute;deos o sitios web. NO uses c&oacute;digo HTML o caracteres especiales ni ASCII. </div>
<!-- manual add messages -->
 <div class="acc1">Agregar mensaje en lista "<?php echo $listdetails['listname'] ?>".</div>
 <div class="acc2">
<form name="addmessagemanually" method="post" style="margin:0">
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="acc3">
<tr>
  <td align="left">
 <textarea name="message" id="message" cols="130" rows="3" class="inputbox_message" onKeyUp="limitText(this.form.message,this.form.countdown,5000);" onKeyDown="limitText(this.form.message,this.form.countdown,5000);"></textarea>
  <p class="div_counter">Facebook permite que los mensajes contengan un m&aacute;ximo de 5000 caracteres. Caracteres restantes:
<input readonly type="text" name="countdown" size="4" value="5000" class="inputbox_counter" > </p>
  </td>
</tr>
<tr>
<td align="left" height="25">
<div id="error1" style="visibility:hidden" class="message_error"> </div></td>
</tr>
<tr>
  <td height="25">
  <input type="hidden" name="addmessagemanually" value="<?php echo $_GET['listid'] ?>">
  <input type="button" onClick="vercreatemessage()" class="submit" style="width:120px" value="Guardar Mensaje">
  </td>
</tr>
</table>

</form>
</div>

<!--end manual -->

<!-- bulk add messages -->
 <div class="acc1">Importar mensajes nuevos en lista "<?php echo $listdetails['listname'] ?>".</div>
 <div class="acc2">
<form name="importbulkmessages" method="post" style="margin:0" enctype="multipart/form-data">
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="acc3">
<tr>
  <td align="left">
Archivo: <input name="fis" type="file"  id="fis" class="inputbox"  size="28" >
<p class="div_counter">Formatos permitidos: TXT y CSV (un mensaje por l&iacute;nea).</p>
  </td>
</tr>
<tr>
<td align="left" height="25">
<div id="error2" style="visibility:hidden" class="message_error"> </div></td>
</tr>
<tr>
  <td height="25">
  <input type="hidden" name="importbulkmessages" value="<?php echo $_GET['listid'] ?>">
  <input type="button" onClick="veruploadfile()" class="submit" style="width:120px" value="Importar archivo">
  </td>
</tr>
</table>

</form>
</div>

</td>
</tr>

<!--end bulk -->

<tr>
  <td class="admineditbox">&nbsp; </td>
</tr>
</table>
</td>
</tr>
</table>
</body>
</html>
<?php
if(isset($_SESSION['fbs_error']) && isset($_POST['addmessagemanually']))//new notice
		{
		echo("<script>document.getElementById('error1').innerHTML='".$_SESSION['fbs_error']."';document.getElementById('error1').style.visibility='visible';</script>");
		unset($_SESSION['fbs_error']);
		}
if(isset($_SESSION['fbs_error']) && isset($_POST['importbulkmessages']))//new notice for bulk
		{
		echo("<script>document.getElementById('error2').innerHTML='".$_SESSION['fbs_error']."';document.getElementById('error2').style.visibility='visible';</script>");
		unset($_SESSION['fbs_error']);
		}
}
else
{
@header("Location:index.php");
}
?>