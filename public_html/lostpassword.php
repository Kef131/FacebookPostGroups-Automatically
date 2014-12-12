<?php include("topadmin.php"); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//ES" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>PGF PRO | Recuperaci&oacute;n de contrase&ntilde;a</title>
<link rel="shortcut icon" href="images/favicon.ico">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="stylesheets/font_styles.css" rel="stylesheet" type="text/css">
<link href="stylesheets/layout_styles.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="jscript/jscript.js"></script>
<style>
.headerform_l {
    border-bottom: 1px solid #3A5896;
    color: #3A5896;
    font-family: Arial;
    font-size: 14px;
    font-weight: bold;
    padding: 4px 4px 4px 8px;
    text-align: left;
    vertical-align: middle;
}
</style>
</head>
<body class="bg2" onLoad="document.forms[0].elements[0].focus()">
<table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
<tr><td height="120">&nbsp;</td></tr>
  <tr>
    <td>
	<form action="lostpassword.php" method="post" name="lostpassword">
         <table width="375" border="0" align="center" cellpadding="0" cellspacing="0" style="background-repeat:no-repeat; border:2px #3A5896 solid; border-radius: 7px 7px 7px 7px; box-shadow: 0px 0px 7px;" background="images/loginbg2.png">
          <tr valign="center" align="middle" bgcolor="#ffffff">
            <td align="left" class="headerform_l" height="20" style="font-size:13px">PGF PRO :: Recuperaci&oacute;n de contrase&ntilde;a</td>
          </tr>
		  <tr>
            <td height="18" class="no_height" >&nbsp;</td>
          </tr>
          <tr>
            <td align="middle" valign="top" >
			<table width="100%" height="100%" border="0" cellpadding="3" cellspacing="0">
                <tr>
                  <td valign="middle">				  
				  <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
					  <tr>
					    <td width="5%">&nbsp;</td>
                        <td width="27%" height="28" align="left" valign="middle" id="f1"  class="tdlogin2"><b>C&oacute;digo de seguridad :</b></td>
                        <td width="68%" align="left" valign="middle" >
<input  name="code" type="text"  size="5" maxlength="5" class="inputbox" onFocus="style.borderColor='#7a8fba'" onBlur="style.borderColor='#3b589a';" onMouseOver="style.borderColor='#7a8fba'" onMouseOut="style.borderColor='#3b589a'" onKeyPress="return submitenter_lostpass(this,event)" style="border-radius:3px 3px 3px 3px;">
						<img src="includes/antiflood/imagine.php" alt="Security code" hspace="1"  vspace="1" align="absmiddle" id="antifloodcode" style="border:1px #444557 solid">
						<a href="#" class="slink" onClick="changesecuritycode();"><img src="images/refreshcode.png" border="0" align="absmiddle" alt="Recargar C&oacute;digo de seguridad" title="Recargar C&oacute;digo de seguridad"></a>						</td>
                      </tr>
		
                      <tr>
					    <td width="5%">&nbsp;</td>
                        <td height="27" align="left" valign="middle" id="f2"  class="tdlogin2"><b>Usuario :</b></td>
                        <td width="68%" align="left" valign="middle" >
<input name="uname" id="uname" type="text" size="26" maxlength="35" class="inputbox"onFocus="style.borderColor='#7a8fba'" onBlur="style.borderColor='#3b589a';" onMouseOver="style.borderColor='#7a8fba'" onMouseOut="style.borderColor='#3b589a'" onKeyPress="return submitenter_lostpass(this,event)" style="border-radius:3px 3px 3px 3px;"></td>
                      </tr>
                      <tr>
					  <td width="5%">&nbsp;</td>
                        <td height="27" align="left" valign="middle" id="f3"  class="tdlogin2"><b>Email :</b></td>
                        <td align="left" valign="middle">
<input name="email" id="email" type="text" size="26" maxlength="35" class="inputbox" onFocus="style.borderColor='#7a8fba'" onBlur="style.borderColor='#3b589a';" onMouseOver="style.borderColor='#7a8fba'" onMouseOut="style.borderColor='#3b589a'" onKeyPress="return submitenter_lostpass(this,event)" style="border-radius:3px 3px 3px 3px;"></td>
                      </tr>
					  <tr >
					  <td width="5%">&nbsp;</td>
					    <td height="18">&nbsp;</td>
					    <td align="left" ><span id="error" style="visibility:hidden" class="message_error">Todos los campos son obligatorios</span></td>
				      </tr>
                      <tr>
					    <td width="5%">&nbsp;</td>
                        <td height="22" valign="middle">&nbsp;</td>
                        <td align="left" valign="middle">
						<input type="button" class="button4" value="Enviar"  onClick="verlostpassword()" onMouseOver="this.style.borderColor='#3b598b';" onMouseOut="this.style.borderColor='';"></td>
                      </tr>
                    </table>
				  </td>
                </tr>
				<tr>
				  <td height="30" align="left" style="padding-left:5px ">
				  <a class="fplink" href="index.php"><img src="images/login.png"  border="0" align="absmiddle"> Regresar al ingreso</a>
				  </td>
				</tr>
              </table>
			  </td>
          </tr>
        </table>
      </form>
    </td>
  </tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
</body>
</html>
<?php
if(isset($_POST['email']))
{ 
	if(strtoupper($_POST['code'])!=strtoupper($_SESSION['verificationcode'])) //incorrect code
	{
	echo("<script>document.getElementById('error').style.visibility='visible';document.getElementById('error').innerHTML='C&oacute;digo de seguridad erroneo.';document.forms[0].elements[0].focus();</script>");
	unset($_SESSION['fbs_error']);
	}
	else
	{
		forgotpassword();
		if($_SESSION['fbs_error']=="2")//invalid username or email
		{
		echo("<script>document.getElementById('error').style.visibility='visible';document.getElementById('error').innerHTML='Datos equivocados.';document.forms[0].elements[0].focus();</script>");
		unset($_SESSION['fbs_error']);
		}
		if($_SESSION['fbs_error']=="3")//password sent
		{
		echo("<script>document.getElementById('error').style.visibility='visible';document.getElementById('error').innerHTML='Tu nueva contrase&ntilde;a fu&eacute; enviada.';</script>");
		unset($_SESSION['fbs_error']);
		}
	}
}
?>