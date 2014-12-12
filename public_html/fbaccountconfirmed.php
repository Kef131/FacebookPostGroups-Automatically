<?php include("topadmin.php"); 
if((isset($_SESSION['fbs_userpass']))&&(isset($_SESSION['fbs_useraccount']))&&(isset($_GET['code']))) ///admin only
{
	
	if(isset($_GET['accid']))
	{
		$accid=$_GET['accid'];
	}
	if(isset($_POST['accid']))
	{
		$accid=$_POST['accid'];
	}
	
	
	$res=mysql_query('SELECT * FROM fbshare_fbaccounts WHERE userid="'.$_SESSION['fbs_admin'].'" AND accountid="'.$accid.'" ');
	$accountdetails=mysql_fetch_array($res);

///new details by user
$appid= trim($accountdetails['fb_username']);
$appsecret=trim($accountdetails['fb_email']);
///general
require_once("cronjobs/fbsdk/facebook.php");
require_once("cronjobs/functions.php");
$fb = new fb($appid, $appsecret);

$message_authorization="";
$message_groups="";
$message_groups_succes="";
$message_groups_top_text="";
$groups=0;
$newregroups=0;

///get max post vars
$max_post_vars=ini_get("max_input_vars");
if($max_post_vars=="" || !isset($max_post_vars))
{
	$max_post_vars=1000;
}
$max_post_vars=$max_post_vars-10;


///step 1 - grant access and update db
if(!isset($_POST['importgroups']))
{
$user_access_token = $fb->LoginStatus();
$user = $fb->getMyData();
	if($user_access_token!="")
	{
	//$res=mysql_query("UPDATE fbshare_fbaccounts SET fb_password='".$user_access_token."', fb_accountid='".$user['id']."', fb_username='".$user['username']."', accountstatus='1' WHERE accountid='".$accountdetails['accountid']."' ");
	$res=mysql_query("UPDATE fbshare_fbaccounts SET fb_password='".$user_access_token."', fb_accountid='".$user['id']."', accountstatus='1' WHERE accountid='".$accountdetails['accountid']."' ");
	$message_authorization.="<br>&iexcl;PERFECTO! Tu cuenta de Facebook est&aacute; autorizada y lista para ser utilizada.";

	
	$groups = $fb->getusergroups($user_access_token);
	
	if ($groups==0 || empty($groups)) 
	{$message_groups.="<b>No hay grupos disponibles para importar en esta cuenta.</b>";}
	
	else
	{	
	$message_groups_top_text.="<br><b style=\"font-size:14px; color:#900;\">Estos grupos est&aacute;n asociados con tu cuenta. Selecciona los grupos que desees importar:</b><br><br>";
	$newregroups=0;
	$message_groups.="<form method=\"post\" name=\"importgroupsform\">";
	$message_groups.="<div id=\"scrollgroups\" style=\"overflow: auto; width: 98%; height: 350px; padding:0px; margin: 0px; border-bottom:1px #3a5896 solid; border-top:1px #3a5896 solid; overflow-y: scroll;overflow-x:hidden;\">";
	
		foreach ($groups['data'] as $group) 
		{
		$group_name = $group['name'];
		$group_id = $group['id'];
		
		$resgroupexists=mysql_query('SELECT * FROM fbshare_fbpages WHERE accountid="'.$accountdetails['accountid'].'" AND fbpageurl="'.$group['id'].'" ');
		$groupexists=mysql_num_rows($resgroupexists);
		
			if($groupexists==0 && ($newregroups<$max_post_vars))
			{
			$message_groups.="&nbsp;&nbsp;<input type=checkbox name=groupid_".$newregroups." value=\"".$group_id."___".urlencode(trim($group_name))."\" checked> ".$group_name." <br>";
			//$message_groups.="<input type=hidden name=groupname_".$newregroups." value=\"".urlencode(trim($group_name))."\"><br>";
			$newregroups++;
			}
		}
		$message_groups.="</div>"; //end overflow
		
		
		if($newregroups==0){$message_groups.="<b style=\"font-size:15px;\">Esta cuenta no tiene nuevos grupos disponibles para la importaci&oacute;n</b>";}

		$message_groups.="<input name=\"importgroups\" value=\"yes\" type=\"hidden\"> ";
		$message_groups.="<input name=\"user_access_token\" value=\"".$user_access_token."\" type=\"hidden\"> ";
		$message_groups.="<input name=\"totalnrofgroups\" value=\"".$newregroups."\" type=\"hidden\"> ";
		$message_groups.="<input name=\"accid\" value=\"".$accid."\" type=\"hidden\"> ";
		
		if($newregroups>0){$message_groups.="<br>&nbsp;&nbsp;&nbsp;<input class=\"submit\" style=\"width:200px\" value=\"Importar grupos seleccionados\" type=\"submit\">";}
		if($newregroups>0){$message_groups.="&nbsp;<input type=\"button\" class=\"submit\" value=\"No, gracias\" style=\"width:120px\" onClick=\"window.opener.location='existingfbaccounts.php';window.close();\"><br>";}

		$message_groups.="</form>";
		
		///new grouops found top check all
		if($newregroups>0)
		{
			$message_groups_top="&nbsp;&nbsp;<input type=checkbox id=checkall onClick=togglechecked() checked> <font style='font-weight:bold'>MARCAR / DESMARCAR TODOS LOS GRUPOS</font><br>";
		}
		
	} //end groups found

}
else
{
	$message.="Hubo un problema con la autorizaci&oacute;n de la cuenta de Facebook.<br><br>Int&eacute;ntalo de nuevo m&aacute;s tarde.";
}	
	

}

///step 2 - import groups
if(isset($_POST['importgroups']))
{

	$user_access_token = trim($_POST['user_access_token']);
	$groupinsert=false;
	$newaddedgrops=0;
		if($_POST['totalnrofgroups']>0)
		{
			for($iter=0; $iter<=$_POST['totalnrofgroups']; $iter++)
			{
				if(isset($_POST['groupid_'.$iter]))
				{
					//explode POST
					$group_var_exploded=explode("___",$_POST['groupid_'.$iter]);
					
					//$newgroupid=$_POST['groupid_'.$iter];
					//$newgroupname=mysql_real_escape_string(urldecode($_POST['groupname_'.$iter]));
					$newgroupid=trim($group_var_exploded[0]);
					$newgroupname=mysql_real_escape_string(urldecode($group_var_exploded[1]));
					
					mysql_query('INSERT INTO fbshare_fbpages VALUES ("","'.$_SESSION['fbs_admin'].'","'.$accountdetails['accountid'].'","'.trim($newgroupname).'","'.trim($newgroupid).'","1")');
					$groupinsert=true;
					$newaddedgrops++;
				}
			
			}
		
		}
		
		///message
		if($groupinsert==true && $newaddedgrops>0)
		{
			$message_groups_succes.="<br>".$newaddedgrops." grupos se han importado correctamente. Puedes a&ntilde;adir m&aacute;s grupos despu&eacute;s.";
		}
		if($newaddedgrops==0)
		{
			$message_groups_succes.="<br>No hay nuevos grupos para importar. Puedes a&ntilde;adir m&aacute;s grupos despu&eacute;s.";
		}
	
}
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//ES" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>PGF :: Confirmar cuenta</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="stylesheets/layout_styles.css" rel="stylesheet" type="text/css">
<link href="stylesheets/font_styles.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="jscript/jscript.js"></script>
<script type="text/javascript">
function togglechecked()
	{ 
		if(document.getElementById('checkall').checked==false) //unselect all
		{
		ch=false;
		}
		if(document.getElementById('checkall').checked==true) //select all
		{
		ch=true;
		}
      for (var i = 0; i < document.importgroupsform.elements.length; i++) 
	  {
        var e = document.importgroupsform.elements[i];

			if (e.type == 'checkbox' && e.id!='resetmessages')
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
<body style="height:100%; background-color:#F3F3F3">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td align="center" valign="top" >
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0"  align="center">
<tr>
<td width="100%" height="25" class="headerform">Verificaci&oacute;n de Facebook </td>
</tr>

<tr>
<td height="100%" style="text-align:left; padding-left:10px">
<?php if(!isset($_POST['importgroups']))
{ ?>
<div style="font-family:Arial; font-size:15px; color:#900; font-weight:bold; text-align:left">
<?php echo $message_authorization; ?>
</div>
<div height="200" style="font-family:Arial; font-size:12px; color:#000; font-weight:normal; text-align:left">
<?php echo $message_groups_top_text.$message_groups_top.$message_groups; ?>
</div>
<?php } ?>

<?php if(isset($_POST['importgroups']))
{ ?>
<div style="font-family:Arial; font-size:14px; color:#900; font-weight:bold; text-align:center; color:#900">
<?php echo $message_groups_succes; ?>
</div>
<?php } ?>

</td>
</tr>
<!-- close window -->
<?php if($newregroups==0){ ?>
<tr>
<td style="text-align:center; padding-left:10px; vertical-align:bottom" height="30" valign="bottom">
<div style="font-family:Arial; font-size:15px; color:#900; font-weight:bold; text-align:center"><br><br>
<input type="button" class="submit" value="Cerrar Ventana" style="width:120px" onClick="window.opener.location='existingfbaccounts.php';window.close();">
</div>
</td>
</tr>
<?php } ?>
</table>

</td>
</tr>
</table>
</body>
</html>
<?php

//hide scroll
if($newregroups==0){?><script>document.getElementById('scrollgroups').style.display='none';</script><?php } ?>

<?php }
else
{
@header("Location:index.php");
}
?>