<?php include("topadmin.php"); 
if((isset($_SESSION['fbs_userpass']))&&(isset($_SESSION['fbs_useraccount']))) ///admin only
{
//post now
if(isset($_POST['postnow']))
{
	fb_post_now();
}
//get all fb active accounts
$res=mysql_query('SELECT * FROM fbshare_fbaccounts WHERE userid="'.$_SESSION['fbs_admin'].'" AND accountstatus=1 ORDER BY fb_email DESC');
$totalaccounts=mysql_num_rows($res);
if($totalaccounts>0)
{
$fbaccounts_combo="<select class=\"combo\" name=\"accountid\" style=\"width:395px\" onchange=\"document.forms[0].isgroup.value=0;changepostonmessages3(this.value)\">";

while($accountdetails=mysql_fetch_array($res))
	{
	$sel="";
	if($_POST['accountid']==$accountdetails['accountid']){$sel=" selected";}
	$fbaccounts_combo.="<option value=\"".$accountdetails['accountid']."\" ".$sel.">".$accountdetails['fb_description']." - ".$accountdetails['fb_email']."</option>";	
	}
	
$fbaccounts_combo.="</select>";
}
else
{
	$fbaccounts_combo="No hay cuentas de Facebook en la base de datos.";
}

//get all lists
$res=mysql_query('SELECT * FROM fbshare_messagelists WHERE userid="'.$_SESSION['fbs_admin'].'" ORDER BY listname DESC');
$totallists=mysql_num_rows($res);
$messagesinlists=false;
if($totallists>0)
{
$fblists_combo="<select class=\"combo\" name=\"listid\" style=\"width:395px\">";
while($accountdetails=mysql_fetch_array($res))
	{
	//get number of messages
	$res2=mysql_query('SELECT * FROM fbshare_messages WHERE userid="'.$_SESSION['fbs_admin'].'" AND listid="'.$accountdetails['listid'].'"');
	$totmessagesinlist=mysql_num_rows($res2);
		if($totmessagesinlist>0)
		{
		$fblists_combo.="<option value=\"".$accountdetails['listid']."\">".$accountdetails['listname']." - ".$totmessagesinlist." mensajes</option>";
		$messagesinlists=true;
		}
	}
	
$fblists_combo.="</select>";
}



?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//ES" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>PGF :: Publicador Instantaneo</title>
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
<script type="text/javascript" src="jscript/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="jscript/jqueryslidemenu.js"></script>
<script language="javascript" type="text/javascript" src="jscript/jscript.js"></script>
<script language="javascript" type="text/javascript" src="jscript/ajaxcalls.js"></script>

<!-- window box -->
<script type="text/javascript" src="jscript/window/highslide-full.js"></script>
<link href="stylesheets/window/highslide.css" rel="stylesheet" type="text/css">

<script type="text/javascript">    
    hs.graphicsDir = 'jscript/window/graphics/';
    hs.outlineType = 'rounded-white';
	hs.anchor='center left';
</script>
<!-- end window box -->

<!--loading screen -->
<link href="stylesheets/loading.css" rel="stylesheet" type="text/css">
<!--end loading screen -->
<script type="text/javascript">
function togglechecked()
	{ 
		if(document.sharemessage.checkall.checked==false) //unselect all
		{
		ch=false;
		}
		if(document.sharemessage.checkall.checked==true) //select all
		{
		ch=true;
		}
      for (var i = 1; i < document.sharemessage.elements.length; i++) 
	  {
        var e = document.sharemessage.elements[i];

			if (e.type == 'checkbox')
			 {
				   if(ch)
				   {
							if(e.checked==false)
							{
							e.checked = ch;
							}
				   }
				   if(!ch)
				   {
				   e.checked = ch;
				   }
			  
			 }
			
      }
    }
</script>
</head>
<body class="bg" onLoad="document.forms[0].message.focus();">
<!--loading screen code -->

<div id="loading" class="loading-invisible">
  <p>PGF pro. Espera un momento...<br><br><img src="images/loader2.gif" alt="Cargando..." /></p>
</div>


<!--end loading screen code -->
<?php include("inc/spacetop.php") ?>
<div id="container"> 
<div id="header">
<?php include("inc/header.php") ?>
</div>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td></td>
<td><div class="formheader">Publicador Instantaneo</div></td>
</tr>
<tr>
<td width="10" align="center" valign="top"></td>
<td align="left" valign="top">
<form action="postnow.php" method="post" name="sharemessage">
<table width="99%" border="0" cellpadding="0" cellspacing="0" class="tablenowidth" align="left">
<tr>
<td height="25" colspan="2" class="headerform">Detalles del mensaje</td>
</tr>
<tr>
<td colspan="2" class="admineditbox_settings">
<div class="warning_crons" style="margin-top:0; margin-bottom:0">
<img src="images/warning.png" align="absmiddle" border="0"> Este apartado es para probar tus mensajes antes de programar tus campa&ntilde;as. Tambi&eacute;n puedes utilizar los mensajes guardados de tus listas.
</div>
</td>
</tr>
<tr>
  <td width="250" class="admineditbox_settings">
  <img src="images/info.png" alt="Ayuda" style="cursor:help" align="absmiddle" onMouseOver="Tip('Selecciona la cuenta de Facebook que se utilizar&aacute; para enviar el mensaje. Debe ser una cuenta verificada.&nbsp;&nbsp;&nbsp;&nbsp;')" onMouseOut="UnTip()">
    Cuenta de Facebook a usar:</td>
  <td width="729" class="admineditbox_settings"><?php echo $fbaccounts_combo ?></td>
</tr>
<tr>
<td class="admineditbox_settings_top">
<img src="images/info.png" alt="Ayuda" style="cursor:help" align="absmiddle" onMouseOver="Tip('Elige el lugar para publicar el mensaje.&nbsp;&nbsp;&nbsp;&nbsp;')" onMouseOut="UnTip()">
El mensaje se va a publicar en:</td>
<td class="admineditbox_settings_top">
<div id="fbpostondiv" class="postonmessages"> </div>
</td>
</tr>
<tr>
  <td class="admineditbox_settings_top">
  <img src="images/info.png" alt="Ayuda" style="cursor:help" align="absmiddle" onMouseOver="Tip('Facebook permite los mensajes con un m&aacute;ximo de 5000 caracteres. Compartir&aacute; el mismo mensaje de forma consecutiva con una pausa 10 segundos. &nbsp;&nbsp;&nbsp;&nbsp;')" onMouseOut="UnTip()">
    Mensaje a publicar:<br>
   <a onClick="return hs.htmlExpand(this, { objectType: 'iframe', height:'460', width:'850'} );" href="postnow_addmessage.php" class="slink" style="margin-left:30px">[Agregar un mensaje guardado]</a>
    </td>
  <td class="admineditbox_settings_top">
<textarea name="message" id="message" cols="130" rows="5" class="inputbox_message" onKeyUp="limitText(this.form.message,this.form.countdown,5000);" onKeyDown="limitText(this.form.message,this.form.countdown,5000);" onBlur="limitText(this.form.message,this.form.countdown,5000);" onFocus="limitText(this.form.message,this.form.countdown,5000);"><?php echo $_POST['message'] ?>
</textarea>
<p class="div_counter">Facebook permite los mensajes con un m&aacute;ximo de 5000 caracteres. Caracteres restantes:
  <input readonly type="text" name="countdown" size="4" value="5000" class="inputbox_counter" > </p>
  </td>
</tr>
<tr>
<td></td>
<td class="admineditbox_settings_top" style="padding-bottom:0px; padding-top:0px;">
<div id="error1" style="visibility:hidden; margin:0px; width:700px" class="warning_notices">Pon un mensaje para publicar.</div>
</td>
</tr>
<tr>
<td height="25" class="admineditbox_settings"> </td>
<td class="admineditbox_settings">
<input name="post"  type="button" class="submit" value="Publicar Ahora" style="width:120px" onClick="if(trimAll(document.sharemessage.message.value)!=''){document.getElementById('loading').className = 'loading-visible';document.sharemessage.submit();} else {document.getElementById('error1').style.visibility='visible';}" >
<input type="hidden" name="isgroup" value="0">
<input type="hidden" name="postnow" value="yes">
</td>
</tr>
</table>
</form>

</td>
</tr>
<tr><td colspan="2" height="10">
</td>
</tr>
</table>
<div id="footer">
<?php  include("inc/footer.php"); ?>
</div>
</div>
<script language="JavaScript" type="text/javascript" src="jscript/tooltip/wz_tooltip.js"></script>
<script>changepostonmessages3(document.sharemessage.accountid.value)</script>
</body>
<span id="chromeFix"></span>
</html>
<?php

if(isset($_SESSION['fbs_error']))//new notice
		{
		echo("<script>document.getElementById('error1').innerHTML='".$_SESSION['fbs_error']."';document.getElementById('error1').style.visibility='visible';</script>");
		unset($_SESSION['fbs_error']);
		}

}
else
{
@header("Location:index.php");
}
?>