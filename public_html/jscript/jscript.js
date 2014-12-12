///number
var numberOK = "0123456789";
var bedOK = "0123456789-";
var decimalOK = "0123456789.";
var checkpass = "0123456789-_qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM";
//validating number
// calculate the ASCII code of the given character
function CalcKeyCode(aChar) 
{
  var character = aChar.substring(0,1);
  var code = aChar.charCodeAt(0);
  return code;
}
function IsDateValid(date1)
{
var str  = date1;
var dt  = parseInt(str.substring(0,2),10);
var mon  = parseInt(str.substring(3,5),10);
var yr   = parseInt(str.substring(6,10),10);
var l=date1.length;
if(l!=10)
	{return false;}
if((dt<1)||(dt>31))
	{return false}
if((mon<1)||(mon>12))
	{return false;}
if((yr<1900)||(yr>2500))
	{return false;}
	return true;
}

function checkNumber(val) {
  var strPass = val.value;
  var strLength = strPass.length;
  var lchar = val.value.charAt((strLength) - 1);
  var cCode = CalcKeyCode(lchar);
  if (cCode < 48 || cCode > 57 ) {
    var myNumber = val.value.substring(0, (strLength) - 1);
    val.value = myNumber;
  }
  return false;
}
function checkDecNumber(val) {
  var strPass = val.value;
  var strLength = strPass.length;
  var lchar = val.value.charAt((strLength) - 1);
  var cCode = CalcKeyCode(lchar);
  if ((cCode < 48 || cCode > 57 )&&(cCode!=46)) {
    var myNumber = val.value.substring(0, (strLength) - 1);
    val.value = myNumber;
  }
  return false;
}

function trimAll( strValue ) {
var objRegExp = /^(\s*)$/;

    //check for all spaces
    if(objRegExp.test(strValue)) {
       strValue = strValue.replace(objRegExp, '');
       if( strValue.length == 0)
          return strValue;
    }

   //check for leading & trailing spaces
   objRegExp = /^(\s*)([\W\w]*)(\b\s*$)/;
   if(objRegExp.test(strValue)) {
       //remove leading and trailing whitespace characters
       strValue = strValue.replace(objRegExp, '$2');
    }
  return strValue;
}

function check_email(e) 
{
	ok = "1234567890qwertyuiop[]asdfghjklzxcvbnm.@-_QWERTYUIOPASDFGHJKLZXCVBNM";
	var i=0;
	for(i=0; i < e.length ;i++)
	{
		if(ok.indexOf(e.charAt(i))<0)
		{ 
			return (false);
		}
	} 
		
	if (document.images) 
	{
		re = /(@.*@)|(\.\.)|(^\.)|(^@)|(@$)|(\.$)|(@\.)/;
		re_two = /^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
		if (!e.match(re) && e.match(re_two)) 
		{
			return (-1);
		} 
	}	
}

function isValidURL(url) {
var urlRegxp = /http:\/\/[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}/; 
if (urlRegxp.test(url) != true) {
return false;
} else {
return true;
}
}
///change code
function changesecuritycode() ///functie pt schimbarea codului de siguranta
{
	var antifloodcode = document.getElementById('antifloodcode');
	
	if (antifloodcode)
	{
		antifloodcode.src +='?'+ Math.round(Math.random()*100000);
	}

	return false;
}
///admin login
function verlogin()
{
	document.getElementById('error').style.visibility='hidden';
	document.getElementById('error').innerHTML='Todos los campos son requeridos, Por favor rellenalos.';
	if(trimAll(document.forms[0].elements[0].value)=='')
	{
	document.getElementById('error').innerHTML='Ingresa tu nombre de usario.';
	document.getElementById('error').style.visibility='visible';
	document.forms[0].elements[0].focus();
	return false;
	} 
	
	if(trimAll(document.forms[0].elements[1].value)=='')
	{
		document.getElementById('error').innerHTML='Ingresa tu contrase&ntilde;a.';
		document.getElementById('error').style.visibility='visible';
		document.forms[0].elements[1].focus();
		return false;
	}
document.forms[0].submit();
}
//change password
function verchangepassword()
{
	document.getElementById('error').style.visibility='hidden';
	document.getElementById('error2').style.visibility='hidden';
	document.getElementById('error').innerHTML='Completa todos los campos.';
	document.getElementById('f2').style.color='';
	document.getElementById('f3').style.color='';
	document.getElementById('f4').style.color='';
	document.getElementById('f5').style.color='';
	var a,b,c;
	var checkpass = "0123456789-_qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM";
	a=document.forms[0].elements[0].value;
	b=document.forms[0].elements[1].value;
	c=document.forms[0].elements[2].value;
	if(trimAll(a)=='' || trimAll(b)=='' || trimAll(c)=='')
	{
	 if(trimAll(c) =='' ) 
	 {
 		document.getElementById('f4').style.color='red';
		document.forms[0].elements[2].focus();
	 }
	 if(trimAll(b) =='' ) 
	 {
 		document.getElementById('f3').style.color='red';
		document.forms[0].elements[1].focus();
	 }
	  if(trimAll(a) =='' ) 
	 {
		 document.getElementById('f2').style.color='red';
		 document.forms[0].elements[0].focus();
	 }
	  document.getElementById('error').style.visibility='visible';
	  return false;
	}
	else
	{
	if(trimAll(b)!='') 
	 		{
					for (i = 0;  i < b.length;  i++)
						{
						ch = b.charAt(i);
						for (j = 0;  j < checkpass.length;  j++)
						if (ch == checkpass.charAt(j))
						break;
						if (j == checkpass.length)
						{	
						document.getElementById("error").innerHTML="La contrase&ntilde;a no puede tener espacios en blanco o caracteres especiales";
						document.getElementById('f3').style.color='red';
						document.getElementById('error').style.visibility='visible';
		 				document.forms[0].elements[1].focus();
						 return false;
						break;
						}
					}
			}
	if(b.length<7)
	{
		document.getElementById("error").innerHTML="La nueva contrase&ntilde;a debe tener al menos 7 caracteres.";
		document.getElementById('f3').style.color='red';
		document.getElementById('error').style.visibility='visible';
		document.forms[0].elements[1].focus();
		 return false;
	}
	if(trimAll(b) != trimAll(c))
		{
	    document.getElementById('error').innerHTML="La nueva contrase&ntilde;a no coincide.";
		document.getElementById('error').style.visibility='visible';
		document.getElementById('f3').style.color='red';
		document.getElementById('f4').style.color='red';
		document.forms[0].elements[1].focus();
		return false;
		}
	}
document.forms[0].submit();
}
///admin 
function verchangeemail()
{
	document.getElementById('error').style.visibility='hidden';
	document.getElementById('error2').style.visibility='hidden';
	document.getElementById('error2').innerHTML='Ingresa tu email.';
	document.getElementById('f2').style.color='';
	document.getElementById('f3').style.color='';
	document.getElementById('f4').style.color='';
	document.getElementById('f5').style.color='';
	
	var a;
	a=document.forms[1].elements[0].value;
	 if(trimAll(a) =='' ) 
	 {
 		document.getElementById('f5').style.color='red';
		document.forms[1].elements[0].focus();
		document.getElementById('error2').style.visibility='visible';
	    return false;
	 }
	 if(!check_email(a)) 
		{
		document.getElementById('error2').innerHTML="Ingresa un email v&aacute;lido.";
		document.getElementById('error2').style.visibility='visible';
		document.getElementById('f5').style.color='red';
		document.forms[1].elements[0].focus();
		return false;
		}
		document.forms[1].submit();
}

///add new admin
function verifyaddnewadmin()
{
	document.getElementById('error').style.visibility='hidden';
	document.getElementById('error').innerHTML='Completa los campos requeridos resaltados en Rojo.';
	document.getElementById('f2').style.color='';
	document.getElementById('f3').style.color='';
	document.getElementById('f4').style.color='';
	document.getElementById('f5').style.color='';
	var a,b,c,d;
	var checkpass = "0123456789-_qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM";
	a=document.forms[0].elements[0].value;
	b=document.forms[0].elements[1].value;
	c=document.forms[0].elements[2].value;
	d=document.forms[0].elements[3].value;
	if(trimAll(a)=='' || trimAll(b)=='' || trimAll(c)=='' || trimAll(d)=='')
	{
	 if(trimAll(d) =='' ) 
	 {
 		document.getElementById('f5').style.color='red';
		document.forms[0].elements[3].focus();
	 }
	 if(trimAll(c) =='' ) 
	 {
 		document.getElementById('f4').style.color='red';
		document.forms[0].elements[2].focus();
	 }
	 if(trimAll(b) =='' ) 
	 {
 		document.getElementById('f3').style.color='red';
		document.forms[0].elements[1].focus();
	 }
	  if(trimAll(a) =='' ) 
	 {
		 document.getElementById('f2').style.color='red';
		 document.forms[0].elements[0].focus();
	 }
	  document.getElementById('error').style.visibility='visible';
	  return false;
	}
	else
	{
	if(trimAll(a)!='') 
	 		{
					for (i = 0;  i < a.length;  i++)
						{
						ch = a.charAt(i);
						for (j = 0;  j < checkpass.length;  j++)
						if (ch == checkpass.charAt(j))
						break;
						if (j == checkpass.length)
						{	
						document.getElementById("error").innerHTML="El nombre de la cuenta no puede tener espacios o caracteres especiales.";
						document.getElementById('f2').style.color='red';
						document.getElementById('error').style.visibility='visible';
		 				document.forms[0].elements[0].focus();
						 return false;
						break;
						}
					}
			}
			if(trimAll(b)!='') 
	 		{
					for (i = 0;  i < b.length;  i++)
						{
						ch = b.charAt(i);
						for (j = 0;  j < checkpass.length;  j++)
						if (ch == checkpass.charAt(j))
						break;
						if (j == checkpass.length)
						{	
						document.getElementById("error").innerHTML="La contrase&ntilde;a no puede tener espacios o caracteres especiales.";
						document.getElementById('f3').style.color='red';
						document.getElementById('error').style.visibility='visible';
		 				document.forms[0].elements[1].focus();
						 return false;
						break;
						}
					}
			}
		if(b.length<7)
		{
			document.getElementById("error").innerHTML="contrase&ntilde;a debe tener al menos 7 caracteres.";
			document.getElementById('f3').style.color='red';
			document.getElementById('error').style.visibility='visible';
			document.forms[0].elements[1].focus();
			return false;
		}
		if(trimAll(b) != trimAll(c))
			{
			document.getElementById('error').innerHTML="Las contrase&ntilde;as no coinciden.";
			document.getElementById('error').style.visibility='visible';
			document.getElementById('f3').style.color='red';
			document.getElementById('f4').style.color='red';
			document.forms[0].elements[1].focus();
			return false;
			}
	  if(!check_email(d)) 
		{
		document.getElementById('error').innerHTML="Ingresa un email válido.";
		document.getElementById('error').style.visibility='visible';
		document.getElementById('f5').style.color='red';
		document.forms[0].elements[4].focus();
		return false;
		}
			
	
	}
	document.forms[0].submit();
}

function verlostpassword()
{
var a,b,c;
document.getElementById('error').style.visibility='hidden';
document.getElementById('error').innerHTML="Todos los campos son obligatorios.";
a=document.forms[0].code.value;
b=document.forms[0].uname.value;
c=trimAll(document.forms[0].email.value);
if(trimAll(a)=='')
		{
		document.getElementById('error').innerHTML="Ingresa el código de seguridad.";
		document.getElementById('error').style.visibility='visible';
		document.forms[0].code.focus();
		return false;
		}
if(trimAll(b)=='')
		{
		document.getElementById('error').innerHTML="Ingresa tu nombre de usuario.";
		document.getElementById('error').style.visibility='visible';
		document.forms[0].uname.focus();
		return false;
		}
if(trimAll(c)=='')
		{
		document.getElementById('error').innerHTML="Ingresa tu email.";
		document.getElementById('error').style.visibility='visible';
		document.forms[0].email.focus();
		return false;
		}
		if(!check_email(c)) 
		{
		 document.getElementById("error").innerHTML="Invalid email address format.";
		 document.getElementById('error').style.visibility='visible';
		 document.forms[0].email.focus();
		 return false;
		}
		document.forms[0].submit();
}
function submitenter(myfield,e)
{
var keycode;
if (window.event) keycode = window.event.keyCode;
else if (e) keycode = e.which;
else return true;

if (keycode == 13)
   {
   verdate();
   return false;
   }
else
   return true;
}

function submitenter_login(myfield,e)
{
var keycode;
if (window.event) keycode = window.event.keyCode;
else if (e) keycode = e.which;
else return true;

if (keycode == 13)
   {
   verlogin();
   return false;
   }
else
   return true;
}

function submitenter_lostpass(myfield,e)
{
var keycode;
if (window.event) keycode = window.event.keyCode;
else if (e) keycode = e.which;
else return true;

if (keycode == 13)
   {
   verlostpassword();
   return false;
   }
else
   return true;
}


function verdate()
{ 
var dat1,dat2;
dat1=document.datestats.startdate.value;
dat2=document.datestats.enddate.value;
	document.getElementById('message_error_date').style.visibility='hidden';
	if(trimAll(dat1)=='' || trimAll(dat2)=='')
	{
	document.getElementById('message_error_date').style.visibility='visible';
	return false;
	}
   if((trimAll(dat1)!='')&&(!IsDateValid(dat1)))
	{
	document.getElementById("message_error_date").innerHTML="Please enter a valid date. Format : dd-mm-yyyy";
	document.getElementById('message_error_date').style.visibility='visible';
	document.datestats.startdate.focus();
	return false;
	}
	if((trimAll(dat2)!='')&&(!IsDateValid(dat2)))
	{
	document.getElementById("message_error_date").innerHTML="Please enter a valid date. Format : dd-mm-yyyy";
	document.getElementById('message_error_date').style.visibility='visible';
	document.datestats.enddate.focus();
	return false;
	}
document.datestats.submit();
}

function displayexpdivs()
{
	var issel=document.getElementById('checkuniq').checked;
	if(issel==true)
	{
		document.getElementById('expdivnouniq').style.display='none';
		document.getElementById('expdivnouniq2').style.display='block';
	}
	if(issel==false)
	{
		document.getElementById('expdivnouniq2').style.display='none';
		document.getElementById('expdivnouniq').style.display='block';
	
	}
	
}

function togglechecked(){ 
	   var ch;

	   
		if(document.getElementById('allbox').checked==false) {ch=false; }
		if(document.getElementById('allbox').checked==true) {ch=true;}
      for (var i = 0; i < document.listedcontacts.elements.length; i++) 
	  {
        var e = document.listedcontacts.elements[i];

			if (e.type == 'checkbox')
			 {
				e.checked = ch;
			 }
			
      }
   }
	


   
function isValidIPAddress(ipaddr) {
   var re = /^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/;
   if (re.test(ipaddr)) {
      var parts = ipaddr.split(".");
      if (parseInt(parseFloat(parts[0])) == 0) { return false; }
      for (var i=0; i<parts.length; i++) {
         if (parseInt(parseFloat(parts[i])) > 255) { return false; }
      }
      return true;
   } else {
      return false;
   }
}



function hide_smtp_vars()
{
	document.getElementById('s1').style.display='none';

}
function show_smtp_vars()
{
	document.getElementById('s1').style.display='block';
	
}

function hide_cron_vars()
{
	document.getElementById('s0').style.display='none';

}
function show_cron_vars()
{
	document.getElementById('s0').style.display='block';
}

function selectbg(trid,checkid)
{

if(document.getElementById(checkid).checked==true)
{document.getElementById(trid).className='trselected';}
if(document.getElementById(checkid).checked==false)
{document.getElementById(trid).className='';}

}

function togglechecked_trsel(total)
{ 
	   var ch;
       var classn;
	   
		if(document.getElementById('allbox').checked==false) {ch=false; classn='';}
		if(document.getElementById('allbox').checked==true) {ch=true;classn='trselected';}
	  
	  //set the bg
	  for (var i = 1; i <= total; i++) 
	  {
        var trbg = 'contactsbg'+i;
		var checkid= 'checkid'+i;
		document.getElementById(trbg).className=classn;	
		document.getElementById(checkid).checked=ch;	
      }
	  
   }
 function togglechecked_trsel_users(total)
{ 
	   var ch;
       var classn;
	   
		if(document.getElementById('allbox').checked==false) {ch=false; classn='';}
		if(document.getElementById('allbox').checked==true) {ch=true;classn='trselected';}
	  
	  //set the bg
	  for (var i = 1; i <= total; i++) 
	  {
        var trbg = 'contactsbg'+i;
		document.getElementById(trbg).className=classn;
	    eval("document.listedcontacts.mass_to_del_"+i+".checked="+ch+";");
	
      }
	  
   }
   
function createnewfbaccount()
{
var a,b,c;
document.getElementById('error').style.visibility='hidden';
c=document.fbaccountform.fb_description.value;
a=document.fbaccountform.fb_email.value;
b=document.fbaccountform.fb_username.value;

if(trimAll(c)=='')
		{
		document.getElementById('error').innerHTML="Please insert the description.";
		document.getElementById('error').style.visibility='visible';
		document.fbaccountform.fb_description.focus();
		return false;
		}
if(trimAll(b)=='')
		{
		document.getElementById('error').innerHTML="Please insert your Facebook Application ID.";
		document.getElementById('error').style.visibility='visible';
		document.fbaccountform.fb_username.focus();
		return false;
		}
if(trimAll(a)=='')
		{
		document.getElementById('error').innerHTML="Please insert your Facebook application SECRET.";
		document.getElementById('error').style.visibility='visible';
		document.fbaccountform.fb_email.focus();
		return false;
		}

		document.fbaccountform.submit();
}



function createnewfbpage()
{
var a,b,c;
document.getElementById('error').style.visibility='hidden';
c=trimAll(document.fbpageform.fbpagedescription.value);
b=trimAll(document.fbpageform.fbpageurl.value);
if(trimAll(c)=='')
		{
		document.getElementById('error').innerHTML="Please insert the page description.";
		document.getElementById('error').style.visibility='visible';
		document.fbpageform.fbpagedescription.focus();
		return false;
		}
if(trimAll(b)=='')
		{
		document.getElementById('error').innerHTML="Please insert the ID of the page.";
		document.getElementById('error').style.visibility='visible';
		document.fbpageform.fbpageurl.focus();
		return false;
		}
		
		document.fbpageform.submit();
}

function createnewfbgroup()
{
var a,b,c;
document.getElementById('error').style.visibility='hidden';
c=trimAll(document.fbpageform.fbpagedescription.value);
b=trimAll(document.fbpageform.fbpageurl.value);
if(trimAll(c)=='')
		{
		document.getElementById('error').innerHTML="Please insert the group description.";
		document.getElementById('error').style.visibility='visible';
		document.fbpageform.fbpagedescription.focus();
		return false;
		}
if(trimAll(b)=='')
		{
		document.getElementById('error').innerHTML="Please insert the ID of the group.";
		document.getElementById('error').style.visibility='visible';
		document.fbpageform.fbpageurl.focus();
		return false;
		}
		
		document.fbpageform.submit();
}


function createnewfblist()
{

var a,b,c;
document.getElementById('error').style.visibility='hidden';
a=trimAll(document.fbpagelist.listname.value);
b=trimAll(document.fbpagelist.listdescription.value);
if(trimAll(a)=='')
		{
		document.getElementById('error').innerHTML="Please insert the list name.";
		document.getElementById('error').style.visibility='visible';
		document.fbpagelist.listname.focus();
		return false;
		}
if(trimAll(b)=='')
		{
		document.getElementById('error').innerHTML="Please insert the list description.";
		document.getElementById('error').style.visibility='visible';
		document.fbpagelist.listdescription.focus();
		return false;
		}
document.fbpagelist.submit();
}

function limitText(limitField, limitCount, limitNum) {
	if (limitField.value.length > limitNum) {
		limitField.value = limitField.value.substring(0, limitNum);
	} else {
		limitCount.value = limitNum - limitField.value.length;
	}
}

function vercreatemessage()
{
var a;
document.getElementById('error1').style.visibility='hidden';
document.getElementById('error2').style.visibility='hidden';
a=trimAll(document.addmessagemanually.message.value);

if(trimAll(a)=='')
		{
		document.getElementById('error1').innerHTML="The message cannot be empty.";
		document.getElementById('error1').style.visibility='visible';
		document.addmessagemanually.message.focus();
		return false;
		}

document.addmessagemanually.submit();

}


function veruploadfile()
{
var fis;
fis=document.getElementById('fis').value;
document.getElementById('error1').style.visibility='hidden';
document.getElementById('error2').style.visibility='hidden';

		if(trimAll(fis)=='')
		{
		document.getElementById('error2').innerHTML="Please choose the file to upload.";
		document.getElementById('error2').style.visibility='visible';
		return false;
		}
		
	document.importbulkmessages.submit();
	
}

function collpase_all_messages(totalmessages)
{
	
	for(i=1;i<=totalmessages;i++)
	{
		divid='editmessage_'+i;
		document.getElementById(divid).style.display='none';
	}
	
}

function  vercreatecampaign()
{
var a;
document.getElementById('error1').style.visibility='hidden';
a=trimAll(document.createnewcampaign.campaignname.value);

if(trimAll(a)=='')
		{
		document.getElementById('error1').innerHTML="Please choose a name for your campaign.";
		document.getElementById('error1').style.visibility='visible';
		document.createnewcampaign.campaignname.focus();
		return false;
		}

document.createnewcampaign.submit();
}

function str_rplc_mes_textarea(str_pass)
{
str2=str_pass.replace(/<br>/gi, "\n");
top.document.forms[0].message.value=str2;
}

function str_rplc_mes_textarea_postnow(str_pass)
{
str2=str_pass.replace(/<br>/gi, "\n");
top.document.forms[0].message.value=str2;
top.document.forms[0].message.focus();
}