<?php header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
include("topadmin.php");
$messageid = '';
$listid='';
if (isset($_GET['messageid'])) 
	{$messageid = $_GET['messageid'];}

if (isset($_GET['listid'])) 
	{$listid = $_GET['listid'];}

$result = mysql_query('DELETE FROM fbshare_messages WHERE messageid="'.$messageid.'" AND userid="'.$_SESSION['fbs_admin'].'" ');

$rescampiagns=mysql_query("SELECT * FROM fbshare_campaigns_messages WHERE originallistid='".$listid."' GROUP BY campaignid");

$usedincampiagn=mysql_num_rows($rescampiagns);
if($usedincampiagn>0)
{
	while($campaigndetails=mysql_fetch_array($rescampiagns))
	{
		//update campaign + increase nr of messages
		$resoldcampiagn=mysql_query("SELECT * FROM fbshare_campaigns WHERE campaignid='".$campaigndetails['campaignid']."' ");
		$oldcampiagnmes=mysql_fetch_array($resoldcampiagn);
		
		$new_totalmessagespostedinthiscampaign=$oldcampiagnmes['totalmessagespostedinthiscampaign']-1;
		
		mysql_query("UPDATE fbshare_campaigns SET 
			totalmessagespostedinthiscampaign='".$new_totalmessagespostedinthiscampaign."' WHERE userid='".$_SESSION['fbs_admin']."' AND campaignid='".$campaigndetails['campaignid']."'");
	}

}

$result = mysql_query('DELETE FROM fbshare_campaigns_messages WHERE originalmessageid="'.$messageid.'" AND userid="'.$_SESSION['fbs_admin'].'" ');


?>