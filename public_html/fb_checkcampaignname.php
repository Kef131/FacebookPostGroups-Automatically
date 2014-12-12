<?php header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
include("topadmin.php");
$search = '';
if (isset($_GET['search'])) 
	$search = strtoupper($_GET['search']);
	
if(isset($_GET['edit']))
{
$result = mysql_query('SELECT * FROM fbshare_campaigns WHERE UPPER(campaignname)="'.$search.'" AND campaignid!="'.$_GET['edit'].'" AND userid="'.$_SESSION['fbs_admin'].'"');	
}
if(!isset($_GET['edit']))
{
$result = mysql_query('SELECT * FROM fbshare_campaigns WHERE UPPER(campaignname)="'.$search.'" AND userid="'.$_SESSION['fbs_admin'].'"');	
}	

$cexists=mysql_num_rows($result);

if($cexists>0)
{
$text="<img src=images/fbfailed.png width=18 align=absmiddle border=0> El nombre de la campa&ntilde;a ya existe.";
}
else
{$text="<img src=images/fbok.png width=18 align=absmiddle border=0> El nombre de la campa&ntilde;a esta disponible.";}

if (trim($search)!='')
{
echo $text;
}

?>