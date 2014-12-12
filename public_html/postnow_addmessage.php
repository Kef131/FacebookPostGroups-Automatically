<?php include("topadmin.php"); 
if((isset($_SESSION['fbs_userpass']))&&(isset($_SESSION['fbs_useraccount']))) ///admin only
{

//get all lists
$res=mysql_query('SELECT * FROM fbshare_messagelists WHERE userid="'.$_SESSION['fbs_admin'].'" ORDER BY listname DESC');
$totallists=mysql_num_rows($res);
$messagesinlists=false;
$fblists_combo="";


if($totallists>0)
{
$fblists_combo="<select class=\"combo\" name=\"listid\" style=\"width:450px\" onchange=\"document.showmessages.submit()\">";
$fblists_combo.="<option value=\"-1\" selected>Selecciona una lista de Mensajes</option>";
while($accountdetails=mysql_fetch_array($res))
	{
	//get number of messages
	$res2=mysql_query('SELECT * FROM fbshare_messages WHERE userid="'.$_SESSION['fbs_admin'].'" AND listid="'.$accountdetails['listid'].'"');
	$totmessagesinlist=mysql_num_rows($res2);
		if($totmessagesinlist>0)
		{
		$selected="";
		if($_POST['listid']==$accountdetails['listid']){$selected="selected";}
		$fblists_combo.="<option value=\"".$accountdetails['listid']."\" ".$selected.">".$accountdetails['listname']." - ".$totmessagesinlist." Mensajes guardados en esta lista</option>";
		$messagesinlists=true;
		}
	}
	
$fblists_combo.="</select>";
}


///get messages 
$totalmessages=0;
$res2=mysql_query('SELECT * FROM fbshare_messages WHERE userid="'.$_SESSION['fbs_admin'].'" AND listid="'.$_POST['listid'].'"');
$totalmessages=mysql_num_rows($res2);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//ES" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>PGF :: Agregar mensajes Guardados</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="stylesheets/layout_styles.css" rel="stylesheet" type="text/css">
<link href="stylesheets/font_styles.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="jscript/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="jscript/jscript.js"></script>
</head>
<body>
<table width="100%" border="0" cellpadding="0" cellspacing="0" height="420">
<tr>
<td align="center" valign="top" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tablenowidth" align="center" height="420">
<tr>
<td width="100%" height="25" class="headerform">Agregar un mensaje guardado</td>
</tr>
<?php if($totallists>0) { ?>
<tr>
<td height="20" class="admineditbox">
<p>
<form name="showmessages" method="post">
<input type="hidden" name="showmessages" value="1">
<?php echo $fblists_combo ?></p>
</form>
</td>
</tr>
<?php if($totalmessages==0 && isset($_POST['listid']) && $_POST['listid']!=-1) { ?>
<tr>
<td height="50" colspan="2" class="admineditbox">
<span class="message_error_admin">
<img src="images/info.png" alt="help" align="absmiddle" hspace="2"> 
No tienes ning&uacute;n mensaje guardado. </span><br><br><br>
</td>
</tr>
<?php } else { 
?>
<tr>
<td colspan="2" class="admineditbox" height="30">

	<!-- scroll -->
	<?php if($totalmessages>4) { ?>
    <div style="overflow: auto; width: 99%; height: 300px; padding:0px; margin: 0px; overflow-y: scroll;overflow-x:hidden; ">
	<table width="100%" cellpadding="0" cellspacing="3" border="0">
    <?php 
$i=1;
while($messagedetails=mysql_fetch_array($res2))
{

$message_to_pass=$messagedetails['message'];
$message_to_pass=str_replace("\r\n","",$message_to_pass);
$message_to_pass=str_replace("\n","",$message_to_pass);
$message_to_pass=str_replace("\r","",$message_to_pass);
$message_to_pass=str_replace("<br />","<br>",$message_to_pass);
$message_to_pass=str_replace("'","\'",$message_to_pass);


$postnowlink="<a href=\"#\" alt=\"Editar mensaje\" class=\"slink\">
<img onclick=\"str_rplc_mes_textarea_postnow('".$message_to_pass."');parent.window.hs.close();\" src=\"images/insert.png\" width=\"23\" align=\"absmiddle\" border=\"0\" alt=\"Insertar mensaje\" title=\"Insertar mensaje\"></a>";
		
echo("<tr style=\"color:#000000\" bgcolor=\"#FFFFFF\" onmouseover=\"this.bgColor='#FFFFCC';\" onmouseout=\"this.bgColor='#FFFFFF';\" id=\"messid_".$messagedetails['messageid']."\">
<td class=\"sort3td_details\" height=\"30\"><a name=\"anchormess_".$messagedetails['messageid']."\"></a>".stripslashes($messagedetails['message'])."
<div class=\"message_details_delete\">".$postnowlink."</div>
</td>
</tr>");
$i++;
} ?>
    </table>
    </div>
    <?php }  ?>
	<!-- end scroll -->
	<?php if($totalmessages<=6) { ?>
	<table width="99%" cellpadding="0" cellspacing="3" border="0">
    <?php 
$i=1;
while($messagedetails=mysql_fetch_array($res2))
{
		
$message_to_pass=$messagedetails['message'];
$message_to_pass=str_replace("\r\n","",$message_to_pass);
$message_to_pass=str_replace("\n","",$message_to_pass);
$message_to_pass=str_replace("\r","",$message_to_pass);
$message_to_pass=str_replace("<br />","<br>",$message_to_pass);
$message_to_pass=str_replace("'","\'",$message_to_pass);


$postnowlink="<a href=\"#\" alt=\"Editar mensaje\" class=\"slink\">
<img onclick=\"str_rplc_mes_textarea_postnow('".$message_to_pass."');parent.window.hs.close();\" src=\"images/insert.png\" width=\"23\" align=\"absmiddle\" border=\"0\" alt=\"Insertar mensaje\" title=\"Insertar mensaje\"></a>";
		
echo("<tr style=\"color:#000000\" bgcolor=\"#FFFFFF\" onmouseover=\"this.bgColor='#FFFFCC';\" onmouseout=\"this.bgColor='#FFFFFF';\" id=\"messid_".$messagedetails['messageid']."\">
<td class=\"sort3td_details\" height=\"30\">".$messagedetails['message']."
<div class=\"message_details_delete\">".$postnowlink."</div>
</td>
</tr>");
$i++;
} ?>
    </table>
<?php } ?>

</td>
</tr>
<?php }  } else { ?>
<tr><td height="50" colspan="2" class="warning4">No tienes ninguna lista de mensajes guardados.</td></tr>
<?php } ?>
<tr><td colspan="2" class="admineditbox">&nbsp; </td></tr>
</table>
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