<?php include("topadmin.php"); 
if((isset($_SESSION['fbs_userpass']))&&(isset($_SESSION['fbs_useraccount']))) ///admin only
{
//delete all from list
if(isset($_POST['deleteall']))
{
	//delete all messages
	$result = mysql_query('DELETE FROM fbshare_messages WHERE listid ="'.$_POST['listid'].'" AND userid="'.$_SESSION['fbs_admin'].'" ');
	
	//delete messages from campaign
	$result = mysql_query('DELETE FROM fbshare_campaigns_messages WHERE originallistid="'.$_POST['listid'].'" ');
	
	//disable campaigns using this list
	$result = mysql_query("UPDATE fbshare_campaigns SET 
						  campaign_enabled='0',
						  totalmessagespostedinthiscampaign='0'
						  WHERE userid='".$_SESSION['fbs_admin']."' AND listid='".$_POST['listid']."'");
}

//edit message
if(isset($_POST['messagetoedit']))
{
$messageid=$_POST['messagetoedit'];	

if(get_magic_quotes_gpc()) {
            if(ini_get('magic_quotes_sybase')) {
				$message = str_replace("''", "'", $_POST['message']);

            } else {
				
				$message = stripslashes($_POST['message']);
	
            }
        } else {
                $message = $_POST['message'];

        }
		
//update message
$result=mysql_query('UPDATE fbshare_messages SET message="'.mysql_real_escape_string(trim(nl2br($message))).'" WHERE messageid="'.$messageid.'" AND userid="'.$_SESSION['fbs_admin'].'" ');

//update message in campaign messages
$result=mysql_query('UPDATE fbshare_campaigns_messages SET message="'.mysql_real_escape_string(trim(nl2br($message))).'" WHERE originalmessageid="'.$messageid.'" AND userid="'.$_SESSION['fbs_admin'].'" ');

$_SESSION['fbs_error']='Mensaje editado con &eacute;xito.';
}
	
$res1=mysql_query('SELECT * FROM fbshare_messagelists WHERE listid="'.$_GET['listid'].'" AND userid="'.$_SESSION['fbs_admin'].'" ');
$listdetails=mysql_fetch_array($res1);

///existing messages
$res2=mysql_query('SELECT * FROM fbshare_messages WHERE listid="'.$_GET['listid'].'" AND userid="'.$_SESSION['fbs_admin'].'" ORDER BY messageid ASC ');
$totalmessages=mysql_num_rows($res2);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>PGF :: Mensajes </title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="stylesheets/layout_styles.css" rel="stylesheet" type="text/css">
<link href="stylesheets/font_styles.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="jscript/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="jscript/jscript.js"></script>
<script language="javascript" type="text/javascript" src="jscript/ajaxcalls.js"></script>
<!--loading screen -->
<link href="stylesheets/loading.css" rel="stylesheet" type="text/css">
<!--end loading screen -->
</head>
<body>
<!--loading screen code -->
<div id="loading" class="loading-invisible">
  <p>Cargando los mensajes existentes. Por favor, espera ...<br><br><img src="images/loader2.gif" alt="Cargando..." /></p>
</div>
<script type="text/javascript">
  document.getElementById("loading").className = "loading-visible";
  var hideDiv = function(){document.getElementById("loading").className = "loading-invisible";};
  var oldLoad = window.onload;
  var newLoad = oldLoad ? function(){hideDiv.call(this);oldLoad.call(this);} : hideDiv;
  window.onload = newLoad;
</script>
<!--end loading screen code -->
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td align="center" valign="top" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tablenowidth" align="center" height="460">
<tr>
<td width="100%" height="25" class="headerform">Administrar mensajes de Facebook</td>
</tr>
<tr>
<td height="20" class="admineditbox">
<p class="formheader_messages">Mensajes existentes en la lista "<?php echo $listdetails['listname'] ?>"</p>
</td>
</tr>
<?php if($totalmessages==0) { ?>
<tr>
<td height="50" colspan="2" class="admineditbox">
<span class="message_error_admin">
<img src="images/info.png" alt="help" align="absmiddle" hspace="2"> 
No tienes ning&uacute;n mensajes guardados en esta lista. </span><br><br><br><br>
<input type="button" onClick="window.location='addmessagesintolist.php?listid=<?php echo $_GET['listid'] ?>'" class="submit" style="width:240px" value="Haz clic aqu&iacute; para a&ntilde;adir mensajes">
</td>
</tr>
<?php } else { 
?>
<tr>
<td colspan="2" class="admineditbox" valign="top">
<div class="warning_messages" style="margin-bottom:5px">
<img src="images/warning.png" align="absmiddle" border="0"> Utiliza texto sin formato, nuevas l&iacute;neas y enlaces a im&aacute;genes, v&iacute;deos o sitios web. NO uses c&oacute;digo HTML o caracteres especiales ni ASCII.
</div>
	<!-- scroll -->
	<?php if($totalmessages>4) { ?>
    <div style="overflow: auto; width: 99%; height: 300px; padding:0px; margin: 0px; overflow-y: scroll;overflow-x:hidden; ">
	<table width="100%" cellpadding="0" cellspacing="3" border="0">
    <?php 
$i=1;
while($messagedetails=mysql_fetch_array($res2))
{
$deletelink="<a href=\"#\" alt=\"Borrar mensajes\" class=\"slink\" onclick=\"document.getElementById('actiondetails_del').style.visibility='hidden';if(confirm('&iquest;Seguro que quieres borrar este mensaje?')){deletemessage(".$messagedetails['messageid'].",".$_GET['listid'].");document.getElementById('messid_".$messagedetails['messageid']."').style.display='none';document.getElementById('actiondetails_del').innerHTML='!Mensaje eliminado con &eacute;xito!';document.getElementById('actiondetails_del').style.visibility='visible';}\">
<img src=\"images/fbpages_delete.png\" width=\"23\" align=\"absmiddle\" border=\"0\" alt=\"Borrar Mensaje\" title=\"Borrar Mensaje\"></a>";
		
$editlink="<a href=\"#\" alt=\"Edit message\" class=\"slink\" onclick=\"collpase_all_messages(".$totalmessages.");document.getElementById('actiondetails_del').style.visibility='hidden';document.getElementById('editmessage_".$i."').style.display='block'; 
document.editmessageform_".$i.".message.focus();\">
<img src=\"images/messages_edit.png\" width=\"23\" align=\"absmiddle\" border=\"0\" alt=\"Editar mensaje\" title=\"Edit message\"></a>";
		
echo("<tr style=\"color:#000000\" bgcolor=\"#FFFFFF\" onmouseover=\"this.bgColor='#FFFFCC';\" onmouseout=\"this.bgColor='#FFFFFF';\" id=\"messid_".$messagedetails['messageid']."\">
<td class=\"sort3td_details\" height=\"30\"><a name=\"anchormess_".$messagedetails['messageid']."\"></a>".$messagedetails['message']."
<div class=\"message_details_delete\">".$editlink.$deletelink."</div>

<div id=\"editmessage_".$i."\" style=\"display:none;\">
<form method=\"post\" action=\"managemessagesintolist.php?listid=".$_GET['listid']."#anchormess_".$messagedetails['messageid']."\" style=\"margin:0; margin-top:3px; margin-bottom:3px\" name=\"editmessageform_".$i."\">
<textarea name=\"message\" id=\"message\" cols=\"110\" rows=\"2\" class=\"inputbox_message\" onKeyUp=\"limitText(this.form.message,this.form.countdown,5000);\" onKeyDown=\"limitText(this.form.message,this.form.countdown,5000);\" onfocus=\"limitText(this.form.message,this.form.countdown,5000);\">".trim(nl2br_revert_bis($messagedetails['message']))."</textarea>
<input readonly type=\"text\" name=\"countdown\" size=\"3\" value=\"5000\" class=\"inputbox_counter\" >
<img src=\"images/save_message.png\" border=\"0\" align=\"absmiddle\" hspace=\"2\"  onClick=\"if(trimAll(document.editmessageform_".$i.".message.value)!=''){document.editmessageform_".$i.".submit();}\" title=\"Guardar mensaje\" >
<input type=\"hidden\" name=\"messagetoedit\" value=\"".$messagedetails['messageid']."\">
</form>
</div>

</td>
</tr>");
$i++;
} ?>
    </table>
    </div>
    <?php }  ?>
	<!-- end scroll -->
	<?php if($totalmessages<=4) { ?>
	<table width="99%" cellpadding="0" cellspacing="3" border="0">
    <?php 
$i=1;
while($messagedetails=mysql_fetch_array($res2))
{
$deletelink="<a href=\"#\" alt=\"Borrar mensaje\" class=\"slink\" onclick=\"document.getElementById('actiondetails_del').style.visibility='hidden';if(confirm('&iquest;Seguro que quieres borrar este mensaje?')){deletemessage(".$messagedetails['messageid'].",".$_GET['listid'].");document.getElementById('messid_".$messagedetails['messageid']."').style.display='none';document.getElementById('actiondetails_del').innerHTML='!Mensaje eliminado con &eacute;xito!';document.getElementById('actiondetails_del').style.visibility='visible';}\">
<img src=\"images/fbpages_delete.png\" width=\"23\" align=\"absmiddle\" border=\"0\" alt=\"Borrar mensaje\" title=\"Borrar mensaje\"></a>";
		
$editlink="<a href=\"#\" alt=\"Editar mensaje\" class=\"slink\" onclick=\"collpase_all_messages(".$totalmessages.");document.getElementById('actiondetails_del').style.visibility='hidden';document.getElementById('editmessage_".$i."').style.display='block'; 
document.editmessageform_".$i.".message.focus();\">
<img src=\"images/messages_edit.png\" width=\"23\" align=\"absmiddle\" border=\"0\" alt=\"Editar mensaje\" title=\"Editar mensaje\"></a>";
		
echo("<tr style=\"color:#000000\" bgcolor=\"#FFFFFF\" onmouseover=\"this.bgColor='#FFFFCC';\" onmouseout=\"this.bgColor='#FFFFFF';\" id=\"messid_".$messagedetails['messageid']."\">
<td class=\"sort3td_details\" height=\"30\">".$messagedetails['message']."
<div class=\"message_details_delete\">".$editlink.$deletelink."</div>

<div id=\"editmessage_".$i."\" style=\"display:none;\">
<form method=\"post\" style=\"margin:0; margin-top:3px; margin-bottom:3px\" name=\"editmessageform_".$i."\">
<textarea name=\"message\" id=\"message\" cols=\"110\" rows=\"2\" class=\"inputbox_message\" onKeyUp=\"limitText(this.form.message,this.form.countdown,5000);\" onKeyDown=\"limitText(this.form.message,this.form.countdown,5000);\" onfocus=\"limitText(this.form.message,this.form.countdown,5000);\">".trim(nl2br_revert_bis($messagedetails['message']))."</textarea>
<input readonly type=\"text\" name=\"countdown\" size=\"3\" value=\"5000\" class=\"inputbox_counter\" >
<img src=\"images/save_message.png\" border=\"0\" align=\"absmiddle\" hspace=\"2\"  onClick=\"if(trimAll(document.editmessageform_".$i.".message.value)!=''){document.editmessageform_".$i.".submit();}\" title=\"Save message\" >
<input type=\"hidden\" name=\"messagetoedit\" value=\"".$messagedetails['messageid']."\">
</form>
</div>

</td>
</tr>");
$i++;
} ?>
    </table>
<?php } ?>

</td>
</tr>
<?php } ?>
<tr>
<td colspan="2" class="admineditbox" style="padding-bottom:5px;" valign="bottom">
    <div id="actiondetails_del" style="visibility:hidden" class="warning_notices_del_message">&amp;iexcl;Mensaje eliminado con &eacute;xito!</div>
<?php if($totalmessages>0) { ?>
<form name="deleteall" method="post" style="margin:0;padding:0;">
<input type="button" onClick="if (confirm('&iquest;Seguro que quieres borrar estos mensajes?')){document.deleteall.submit();}" class="submit" style="width:180px" value="Borrar todos los mensajes">
<input type="hidden" name="listid" value="<?php echo $_GET['listid'] ?>">
<input type="hidden" name="deleteall" value="yes">
</form>
<?php } ?>
</td>
</tr>
</table>
</td>
</tr>
</table>
</body>
</html>
<?php
if(isset($_SESSION['fbs_error']) && isset($_POST['messagetoedit']))//new notice
		{
		echo("<script>document.getElementById('actiondetails_del').innerHTML='".$_SESSION['fbs_error']."';document.getElementById('actiondetails_del').style.visibility='visible';</script>");
		unset($_SESSION['fbs_error']);
		}
}
else
{
@header("Location:index.php");
}
?>