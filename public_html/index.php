<?php include("topadmin.php"); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>PGF PRO | Ingreso</title>
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
<table width="728" border="0" align="center" cellpadding="1" cellspacing="1">
	<tr>
    	<td colspan="2" height="120">
	        <img src="images/INICIO.jpg">
        </td>
    </tr>
	<tr>
        <td width="67%" valign="top" style="padding-right:20px; border-right: 1px solid #3A5896" >
        <h3 class="headerform_l" align="center">Innovador sistema de programación de Mensajes.<br>
  	    </h3>
    	  <p align="justify">Con el sistema PGF programar sus mensajes en páginas y grupos de Facebook en los que participa, es muy sencillo. El sistema proporciona una plataforma segura y una apariencia simple y fácil de usar.</p>
    	  <h3 class="headerform_l">Sistema OnLine 24 Horas<br>
  	    </h3>
    	  <p align="justify">El sistema funciona las 24 horas del día en un servidor dedicado donde su información es segura.    	  </p>
    	  <p align="justify">Esto significa que incluso si usted está lejos de un sistema informático está  publicando, sin necesidad de tener la computadora encendida para hacer publicaciones, ya que el sistema está alojado en un servidor en el que se puede acceder desde casi cualquier dispositivo conectado a internet.
    	  <br>
    	  </p>
    	  <p align="justify">Nuestro sistema paga por recomendar, les llamamos Socios comerciales y ganan dinero por su recomendacion en Multi-Nivel.<br>
    	  </p></td>
        <td width="51%" align="center" bgcolor="#FFFFFF">
        <form action="verify.php" method="post" name="login_form" autocomplete="off">
            <table border="0" align="center" cellpadding="0" cellspacing="0"  s>
          <tr>
                <td height="20" align="left" class="headerform_l" style="font-size:13px">PGF :: Ingreso de Usuarios</td>
              </tr>
            <tr>
                <td height="10" class="no_height" >&nbsp;</td>
              </tr>
              <tr>
                <td align="middle" valign="top" >
                <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="1">
                    <tr>
                      <td valign="top" align="center" >				  
                      <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">  
                        <tr><td colspan="3" height="22">&nbsp;</td>
                        </tr>
                        <tr>
                        <td width="3%">&nbsp;</td>
                          <td width="25%" height="28" align="left" valign="middle" class="tdlogin"><b> Usuario:</b></td>
                          <td width="72%" align="left" valign="middle">
                            <input name="uname" id="uname" type="text" tabindex="1" size="24" maxlength="35" class="inputbox" onFocus="style.borderColor='#7a8fba'" onBlur="style.borderColor='#3b589a';" onMouseOver="style.borderColor='#7a8fba'" onMouseOut="style.borderColor='#3b589a'"  onKeyPress="return submitenter_login(this,event)" style="border-radius: 3px 3px 3px 3px; ">                      
                            </td>
                        </tr>
                        <tr>
                        <td>&nbsp;</td>
                          <td height="28" align="left" valign="middle"  class="tdlogin"><b>Contraseña:</b></td>
                          <td align="left" valign="middle" >
    						<input name="pass" id="pass" type="password" class="inputbox" size="24" maxlength="35" onFocus="style.borderColor='#7a8fba'" onBlur="style.borderColor='#3b589a';" onMouseOver="style.borderColor='#7a8fba'" onMouseOut="style.borderColor='#3b589a'"  onKeyPress="return submitenter_login(this,event)" autocomplete="off" style="border-radius: 3px 3px 3px 3px; "></td>
                        </tr>
                        <tr>
                          <td align="left" colspan="2" >&nbsp;</td>
                          <td align="left"><span id="error" style="visibility:hidden" class="message_error">Todos los campos son obligatorios.</span></td>
                        </tr>
                        <tr>
                          <td height="35" colspan="2" valign="middle" >&nbsp;</td>
                          <td align="left" valign="middle" >
                            <input name="button" type="button" class="button4" title="Login" onClick="verlogin()" onMouseOver="this.style.borderColor='#3b598b';" onMouseOut="this.style.borderColor='';" value="Login"></td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr><td height="28" align="left" valign="top" style="padding-left:7px ">
                    <a class="fplink" href="lostpassword.php"><img src="images/login.png"  border="0" align="absmiddle"> ¿Olvidaste tu contraseña?<p style="text-align: right">Clic aqui para recuperarla.</p></a></td>
                    </tr>
                </table></td>
     </tr>
            </table>
          </form> 
        </td>
  	</tr>
    <tr>
    	<td colspan="2" style="padding-top:-20px">
      <p class="headerform_l">[[SocioID]]</p>
      <table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
        <tbody>
          <tr>
            <td align="left"><a href="https://www.webrenta.me/publicaenfb.php?a_aid=[[SocioID]]" target="_blank" style="color:#3A5896; text-decoration: none">Publicador en Grupos de Facebook&copy; PGF pro</a></td>
            <td width="50%" align="right">Copyright ©2010-2014, <a href="http://www.webrenta.me?a_aid=[[client_custom_fields.1]]"  style="color:#3A5896; text-decoration: none">www.webrenta.me</a></td>
          </tr>
        </tbody>
      </table></td>
    </tr>
</table>
</body>
</html>
<?php
if($_SESSION['fbs_error']=="0")//invalid user/password
{
echo("<script>document.getElementById('error').style.visibility='visible';document.getElementById('error').innerHTML='Incorrect login details.';document.forms[0].elements[0].focus();</script>");
unset($_SESSION['fbs_error']);
}
?>