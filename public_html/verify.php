<?php include("topadmin.php");

/**
	*Se puede utilizar la librería MagicQuotes la cual ya ha sido declarada obsoleta por la web oficial 
	*de PHP desde la versión 5.3.0 y se ha deshabilitado a partir de la versión 5.4.0
	********************************************************************************************************************
	*Citando desde http://php.net/manual/es/security.magicquotes.php
	*"Es aconsejable trabajar con las comillas mágicas deshabilitadas y, 
	* en su lugar, hacer un filtrado en tiempo de ejecución y bajo demanda."
**/

if(!get_magic_quotes_gpc()) 
	{
		$n = mysql_real_escape_string(trim($_POST['uname']));
		$p = md5(mysql_real_escape_string(trim($_POST['pass'])));
	}
	else 
	{
		$n = htmlspecialchars(trim($_POST['uname']));
		$p = md5(htmlspecialchars(trim($_POST['pass']))); 
	}
	//check if user exists
	$result = mysql_query("SELECT * FROM fbshare_users WHERE username='".$n."'");
	$userexists = mysql_num_rows($result);
		if($userexists==0) //no->redirect
		{
			$_SESSION['fbs_error']="0";
			echo("<script>window.location='index.php'</script>");
			exit;
		}

	$result = mysql_query("SELECT * FROM fbshare_users WHERE username='".$n."' and userpassword='".$p."'");
	$nresults = mysql_num_rows($result);
			if($nresults!=0) //if user and password are correct
			{
				$userinfo =  mysql_fetch_array ($result);
				$_SESSION['fbs_userpass']=$userinfo['userpassword'];
				$_SESSION['fbs_useraccount']=$userinfo['username'];
				$_SESSION['fbs_admin']=$userinfo['userid'];
				echo("<script>window.location='fb_managecampaigns.php'</script>");
			}
			else // if user/password incorect
			{
			$_SESSION['fbs_error']="0";
			echo("<script>window.location='index.php'</script>");
			exit;
			}

?>