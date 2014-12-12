<?php header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
include("topadmin.php");
$search = '';


if (isset($_GET['search'])) 
	$search = $_GET['search'];

///get total nr of groups
$result_gr = mysql_query('SELECT * FROM fbshare_fbpages WHERE accountid ="'.$search.'" AND userid="'.$_SESSION['fbs_admin'].'" AND isgroup="1" ORDER BY fbpagedescription ASC');
$total_groups=mysql_num_rows($result_gr);

///get total nr of pages
$result_pg = mysql_query('SELECT * FROM fbshare_fbpages WHERE accountid ="'.$search.'" AND userid="'.$_SESSION['fbs_admin'].'" AND isgroup="0" ORDER BY fbpagedescription ASC');
$total_pages=mysql_num_rows($result_pg);

//get total pages and groups
$result = mysql_query('SELECT * FROM fbshare_fbpages WHERE accountid ="'.$search.'" AND userid="'.$_SESSION['fbs_admin'].'" ORDER BY isgroup ASC');
$totalpagesandgroups=mysql_num_rows($result);

$text="<input type=radio onclick='document.forms[0].isgroup.value=0' name=messagespostedon value=0 checked><font style='font-weight:bold'> En el muro de la cuenta de Facebook</font><br>";

if($totalpagesandgroups>0)
{

///show pages first
if($total_pages>0)
{
	while($pagesdetails=mysql_fetch_array($result_pg))
	{
$text.="<input onclick='document.forms[0].isgroup.value=0;' type=radio name=messagespostedon value=".$pagesdetails['pageid']." > <font style='color:blue; font-weight:bold'>En la fan page: </font>".$pagesdetails['fbpagedescription']."<br>";
	}

}

///show group option
if($total_groups>0)
{
	$text.="<input onclick='document.forms[0].isgroup.value=1;' type=radio name=messagespostedon value=-1 > <font style='color:red; font-weight:bold'>En los Grupos (Selecciona al menos un grupo):</font><br>";
	
	$gr_indx=0;
	
	$text.="<div style=\"width: 690px;margin:0px;margin-left:24px; padding:0px;\"><input type=checkbox name=checkall onClick=togglechecked() checked> <font style='font-weight:bold'>MARCAR / DESMARCAR TODOS LOS GRUPOS</font></div>";
	
	///scroll
	if($total_groups>15)
	{
	$text.="<div id=\"scrollgroups\" style=\"overflow: auto; width: 690px; max-height: 285px; margin:0px;margin-left:24px; padding:0px; border-top:1px #3a5896 solid; overflow-y:scroll;overflow-x:hidden;\">";
	}
	else
	{
	$text.="<div style=\"width: 690px; max-height: 285px; margin:0px;margin-left:24px; padding:0px; border-top:1px #3a5896 solid;\">";
	}
	//end scroll
	
	while($pagesdetails=mysql_fetch_array($result_gr))
	{
	$text.="<input type=checkbox name=groupid_".$gr_indx." value=".$pagesdetails['pageid']." checked> ".$pagesdetails['fbpagedescription']."<br>";
	$gr_indx++;
	}
	
	///scroll
	$text.="</div>";
	//end scroll
	
	$text.="<input name=totalnrofgroups value='".$total_groups."' type=hidden> ";
}

} //end total pages and groups



echo $text;

?>