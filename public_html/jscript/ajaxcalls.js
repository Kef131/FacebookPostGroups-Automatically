function showHint(str)
 { 
divid='txtHint'+str;
if (str=="")
  {
  document.getElementById(divid).innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById(divid).innerHTML=xmlhttp.responseText;
    }
	
	else
	{
		document.getElementById(divid).innerHTML='<img src=images/loader.gif align=absmiddle border=0> Comprobando estado. Un momento...';
	}
  }
xmlhttp.open("GET","fb_checkstatus.php?search="+str,true);
xmlhttp.send();

}


function deletemessage(str,listid)
 { 
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
 	 {// code for IE6, IE5
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		  }
			xmlhttp.onreadystatechange=function()
		  {

 	 }
xmlhttp.open("GET","aj_deletemessage.php?messageid="+str+"&listid="+listid,true);
xmlhttp.send();
}


function vercampaignname(str)
{
divid='checkcampaignname';
if (str=="")
  {
  document.getElementById(divid).innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById(divid).innerHTML=xmlhttp.responseText;
    }
	
	else
	{
		document.getElementById(divid).innerHTML='<img src=images/loader.gif align=absmiddle border=0> Comprobando nombre de campa&ntilde;a...';
	}
  }
xmlhttp.open("GET","fb_checkcampaignname.php?search="+str,true);
xmlhttp.send();
}
function vercampaignname2(str,edit)
{
divid='checkcampaignname';
if (str=="")
  {
  document.getElementById(divid).innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById(divid).innerHTML=xmlhttp.responseText;
    }
	
	else
	{
		document.getElementById(divid).innerHTML='<img src=images/loader.gif align=absmiddle border=0> Comprobando nombre de campa&ntilde;a...';
	}
  }
xmlhttp.open("GET","fb_checkcampaignname.php?search="+str+"&edit="+edit,true);
xmlhttp.send();
}



function changepostonmessages(accountid)
{
str=accountid;
divid='fbpostondiv'; 
if (str=="")
  {
  document.getElementById(divid).innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById(divid).innerHTML=xmlhttp.responseText;
    }
	
	else
	{
		document.getElementById(divid).innerHTML='<img src=images/loader.gif align=absmiddle border=0> Actualizando datos de esta cuenta...';
	}
  }
xmlhttp.open("GET","fb_updateshareon.php?search="+str,true);
xmlhttp.send();

}

function changepostonmessages2(accountid,pageid,campaignid)
{
str=accountid;
divid='fbpostondiv'; 
if (str=="")
  {
  document.getElementById(divid).innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById(divid).innerHTML=xmlhttp.responseText;
    }
	
	else
	{
		document.getElementById(divid).innerHTML='<img src=images/loader.gif align=absmiddle border=0> Actualizando datos de esta cuenta...';
	}
  }
xmlhttp.open("GET","fb_updateshareon_edit.php?search="+str+"&pageid="+pageid+"&campaignid="+campaignid,true);
xmlhttp.send();
}

function changepostonmessages3(accountid,pageid,campaignid)
{
str=accountid;
divid='fbpostondiv'; 
if (str=="")
  {
  document.getElementById(divid).innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById(divid).innerHTML=xmlhttp.responseText;
    }
	
	else
	{
		document.getElementById(divid).innerHTML='<img src=images/loader.gif align=absmiddle border=0> Actualizando datos de esta cuenta...';
	}
  }
xmlhttp.open("GET","fb_updateshareon_postnow.php?search="+str+"&pageid="+pageid+"&campaignid="+campaignid,true);
xmlhttp.send();
}

