<?php include(dirname(__FILE__)."/../topcrons.php"); 
///global functions and vars
//global $appid;
//global $appsecret;
global $ch;
$cookie="/cookie.txt";
$ssl_ver=0;
$_SESSION['posted_in_this_cron_run']=0;
$_SESSION['cron_logs']="";
$_SESSION['lastphotoid']="";
unset($_SESSION['lastphotoid']);

function update_status($campaignid,$fbaccountid,$fbpageid,$fbhowtopost,$max_for_this_run,$fbcampaignrepeat,$accountid)
{
//global $appid;
//global $appsecret;
global $cookie;
global $location;
global $cookiearr;
global $ch;
global $ssl_ver;
global $cron_nr_posts;
global $cron_pause_between_posts;
global $cron_send_notifications;
global $cron_send_notifications_to;
$messagespostedthistome=0;

//change the max here
if($max_for_this_run==0) //unlimited messages to post -> will post max/cron
{
   $max_for_this_run=$cron_nr_posts;	
}

if($max_for_this_run>$cron_nr_posts) //fixed messages to post 
{
	$max_for_this_run=$cron_nr_posts;
}


///GET LOGIN DETAILS
$reslogin=mysql_query("SELECT * FROM fbshare_fbaccounts WHERE accountid='".$accountid."' AND accountstatus='1' "); //only verified accounts
$fbaccountok=mysql_num_rows($reslogin);


if($fbaccountok>0) ///acount ok
{

//login details
$fblogindetails=mysql_fetch_array($reslogin);
$email = urlencode($fblogindetails['fb_email']);
$pass = urlencode($fblogindetails['fb_password']);
$poster_user_id = urlencode($fblogindetails['fb_accountid']);

//app details
$appid= trim($fblogindetails['fb_username']);
$appsecret= trim($fblogindetails['fb_email']);

//disable campaign if access token empty
if(trim($pass)=='')
{
echo "<br>Invalid access token!<br>-----------------<br>"; 
mysql_query("INSERT INTO fbshare_logs VALUES ('','".$fbaccountid."','".$campaignid."','Invalid access token!','".date('Y-m-d H:i:s')."')");
mysql_query("UPDATE fbshare_campaigns SET campaign_enabled='0' WHERE campaignid='".$campaignid."'");
$nomessages=0;
return $nomessages;
exit();
}

	if($fbaccountok>0) //login accepted
	{
		///GET group id
		$resfbpage=mysql_query("SELECT * FROM fbshare_fbpages WHERE pageid='".$fbpageid."'");
		$respage=mysql_fetch_array($resfbpage);
		$groupid=$respage['fbpageurl'];
		
		//Check if at leats one group
		$postallgroups=false;
		$resmultimplegroups=mysql_query("SELECT * FROM fbshare_group_campaigns WHERE campaignid='".$campaignid."' AND accountid='".$accountid."' ");
	    $totalgroupstopostin=mysql_num_rows($resmultimplegroups);
		if($totalgroupstopostin>0){$postallgroups=true;}
		
		///no group - < pause campiagn
		if($postallgroups==false)
		{
		echo "<br>No groups in this campaign! Campaign disabled.<br>-----------------<br>"; 
		mysql_query("INSERT INTO fbshare_logs VALUES ('','".$fbaccountid."','".$campaignid."','No groups in this campaign! Campaign disabled.','".date('Y-m-d H:i:s')."')");
		mysql_query("UPDATE fbshare_campaigns SET campaign_enabled='0' WHERE campaignid='".$campaignid."'");
		$nomessages=0;
		return $nomessages;
		exit();
		}
		

		for($i=0;$i<$max_for_this_run;$i++)
		{		
		///get the message to post
		if($fbcampaignrepeat==0) // no loop campaign
		{ 
			if($fbhowtopost==0) //Consecutively
			{
				$res_messages=mysql_query("SELECT * FROM fbshare_campaigns_messages WHERE campaignid='".$campaignid."' AND userid='".$fbaccountid."' AND nroftimesposted='0' ORDER BY messageid ASC");
				
			}
			if($fbhowtopost==1) //Random
			{
				$res_messages_tmp=mysql_query("SELECT * FROM fbshare_campaigns_messages WHERE campaignid='".$campaignid."' AND userid='".$fbaccountid."' AND nroftimesposted='0' ");
				$maxrnd=mysql_num_rows($res_messages_tmp);
				
				$rndindex=rand(0,$maxrnd);
				$k=0;
				
				while($rand_array=mysql_fetch_array($res_messages_tmp))
				{
				 if($rndindex==$k)
				 	{$finalrndid=$rand_array['messageid']; break;}
				 $k++;
				}
				
			    $res_messages=mysql_query("SELECT * FROM fbshare_campaigns_messages WHERE campaignid='".$campaignid."' AND userid='".$fbaccountid."' AND nroftimesposted='0' AND messageid='".$finalrndid."'");
				
				$messagefoundindb_tmp1=mysql_num_rows($res_messages);
				if($messagefoundindb_tmp1==0)
				{
					mysql_query("UPDATE fbshare_campaigns SET is_campaign_finished='1' WHERE campaignid='".$campaignid."' ");
					$nomessages=0;
					return $nomessages;
					exit();
				}
			}
		}
		
		//loop campaign
		if($fbcampaignrepeat==1) ////loop campaign
		{
	
			if($fbhowtopost==0) //Post Consecutively
			{
				$res_messages=mysql_query("SELECT * FROM fbshare_campaigns_messages WHERE campaignid='".$campaignid."' AND userid='".$fbaccountid."'  ORDER BY nroftimesposted ASC, lastpostedon ASC");
			}
			if($fbhowtopost==1) //Post Random
			{
				$res_messages_tmp=mysql_query("SELECT * FROM fbshare_campaigns_messages WHERE campaignid='".$campaignid."' AND userid='".$fbaccountid."'  ORDER BY nroftimesposted ASC, lastpostedon ASC LIMIT 0,20");
				$maxrnd=mysql_num_rows($res_messages_tmp);
			
				$rndindex=rand(0,$maxrnd);
				$k=0;
					
				while($rand_array=mysql_fetch_array($res_messages_tmp))
				{
				 if($rndindex==$k)
				 	{$finalrndid=$rand_array['messageid']; break;}
				 $k++;
				}

			    $res_messages=mysql_query("SELECT * FROM fbshare_campaigns_messages WHERE campaignid='".$campaignid."' AND userid='".$fbaccountid."' AND messageid='".$finalrndid."'");
			}
		}
		
		$messagefoundindb=mysql_num_rows($res_messages);

		//end get message to post
		
		///MESSAGE
	    // %0A - new line not encoded!
		$messagedetails=mysql_fetch_array($res_messages);
		$status=$messagedetails['message'];
		$lastmessageid=$messagedetails['messageid'];
		$lastnroftimesposted=$messagedetails['nroftimesposted']+1;
		$lastpostedon=date('Y-m-d H:i:s');

		///get urls + spintax
		require_once("functions.php");
		
		//spintax
		$status_logs=$status;
		
		
		///prepare status with new lines
		$status=str_replace("<br>","||",$status);
		$status=str_replace("<br />","||",$status);
		$status=str_replace("<br/>","||",$status);
		$status=str_replace("||","\n",$status);
		

		$url_to_send=get_url_from_string($status);
		
		
		
		//status no link
		//$status_no_link=str_replace($url_to_send,"",$status);
		$status_no_link=$status;

		//CHECK if is comment for last post
		$isgroupcomment=false;
		$firstcharinmessage=substr(trim($status), 0,2);
		///if is comment 
		//replace # from message
		if($firstcharinmessage=="##")
		{
		$isgroupcomment=true;
		$status_no_diez=str_replace("##","",trim($status));
		//max comments- 10
		$cron_nr_posts=10;
		}
		//END if is comment for last post
		
		//CHECK if is comment for user's post
		$ismygroupcomment=false;
		$firstcharinmessage=substr(trim($status), 0,2);
		///if is comment 
		//replace # from message
		if($firstcharinmessage=="@@")
		{
		$ismygroupcomment=true;
		$status_no_diez=str_replace("@@","",trim($status));
		//max comments- 10
		$cron_nr_posts=10;
		}
		//END if is comment for user's post
		
		///if no comments - > exit
		if(($isgroupcomment==false) && ($ismygroupcomment==false))
		{
			$lastidquery=mysql_query("SELECT MAX(logid) FROM fbshare_logs");
			$reslogs=mysql_fetch_array($lastidquery);
					
			mysql_query("DELETE FROM fbshare_logs WHERE logid='".$reslogs[0]."' ");
			echo("<br>This is a regular post. Exit.<br>");
			exit();
		}
			
				if($messagefoundindb>0)
				{
				///POST NOW
				require_once("fbsdk/facebook.php");
				$fb = new fb($appid, $appsecret);	

				//update messages
				mysql_query("UPDATE fbshare_campaigns_messages SET lastpostedon='".$lastpostedon."', nroftimesposted='".$lastnroftimesposted."' WHERE messageid='".$lastmessageid."' ");
				
				////start loop in groups
				if($postallgroups==true) //loop groups
				{
					echo "<br>Try to put comments[multiple groups]: <br>".$status_logs."<br>";
					echo "--------------------------------------<br>";
					
					$allgroups=mysql_query("SELECT * FROM fbshare_group_campaigns WHERE campaignid='".$campaignid."' AND accountid='".$accountid."' ORDER BY rand()");
					
				    while($postinallgroupsdetails=mysql_fetch_array($allgroups))
					{
						
							//pause between posts
							sleep($cron_pause_between_posts);
							///get group details
							$resgroupname=mysql_query("SELECT * FROM fbshare_fbpages WHERE accountid='".$accountid."' AND isgroup='1' AND pageid='".$postinallgroupsdetails['groupid']."' ");
							$resgroupdetailsres=mysql_fetch_array($resgroupname);
							
						
							///if is comment to last post 
							if($isgroupcomment==true)
									{
									$groupcommentid = $fb->get_last_comment_id($resgroupdetailsres['fbpageurl'],$pass);
												
												if($groupcommentid!="Invalid comment id")
												{
													$spintax_message = new spintax();
													$status_spin=$spintax_message->process($status_no_diez);
													
													$post = $fb->commentOnGroup($groupcommentid,$pass,$status_spin,$url_to_send);
												}
												else
												{
													$post="Invalid comment id.";
												}
									}
							///if is comment to user's last post
								if($ismygroupcomment==true)
									{

									$groupcommentid = $fb->get_my_last_comment_id($resgroupdetailsres['fbpageurl'],$poster_user_id,$pass);
												
												if($groupcommentid!="Invalid comment id")
												{
													$spintax_message = new spintax();
													$status_spin=$spintax_message->process($status_no_diez);
													
													$post = $fb->commentOnGroup($groupcommentid,$pass,$status_spin,$url_to_send);
												}
												else
												{
													$post="Invalid comment id.";
												}
									}
							
						    echo "Posting comment in group: ".$resgroupdetailsres['fbpagedescription']." [Group ID: ".$resgroupdetailsres['fbpageurl']."]. FB log status: <font color=red>".$post."</font><br>";
						
							if($post=="Message posted!")
							{
							$messagespostedthistome++;
							}
						
						//logs
						mysql_query("INSERT INTO fbshare_logs VALUES ('','".$fbaccountid."','".$campaignid."','Try to add comment: ".mysql_real_escape_string($status_spin)."','".date('Y-m-d H:i:s')."')");
						mysql_query("INSERT INTO fbshare_logs VALUES ('','".$fbaccountid."','".$campaignid."','Post comment in group ".$resgroupdetailsres['fbpagedescription']." [Group ID: ".$resgroupdetailsres['fbpageurl']."]. FB log status: ".$post."','".date('Y-m-d H:i:s')."')");
						
						if($messagespostedthistome>=$cron_nr_posts)
							{
								echo("<br><font color=red>Maximum number of posts excedeed. Logout!</font><br>");
								$_SESSION['posted_in_this_cron_run']++;
								return $messagespostedthistome;	
								exit();
							}
						
						///not have rights - > delete group
						if(trim($post)=="Exception: You do not have permission to post in this group.")
						{
						echo "You do not have permission to post in this group.<br>Group deleted!<br>";
						mysql_query("INSERT INTO fbshare_logs VALUES ('','".$fbaccountid."','".$campaignid."','Group deleted!','".date('Y-m-d H:i:s')."')");
						mysql_query("DELETE FROM fbshare_fbpages WHERE fbpageurl='".$resgroupdetailsres['fbpageurl']."' AND accountid='".$accountid."' AND isgroup='1'");
						mysql_query("DELETE FROM fbshare_group_campaigns WHERE groupid='".$postinallgroupsdetails['groupid']."' AND accountid='".$accountid."' ");
						}
						
						///not have rights - > delete group
						if(trim($post)=="OAuthException: (#200) Must have permission to see group.")
						{
						echo "You do not have permission to for this group.<br>Group deleted!<br>";
						mysql_query("INSERT INTO fbshare_logs VALUES ('','".$fbaccountid."','".$campaignid."','Not enough permissions. Group deleted!','".date('Y-m-d H:i:s')."')");
						mysql_query("DELETE FROM fbshare_fbpages WHERE fbpageurl='".$resgroupdetailsres['fbpageurl']."' AND accountid='".$accountid."' AND isgroup='1'");
						mysql_query("DELETE FROM fbshare_group_campaigns WHERE groupid='".$postinallgroupsdetails['groupid']."' AND accountid='".$accountid."' ");
						}
								
					}
					
				}
				//END looping in groups
				
				
				echo "Messages posted in this cron: ".$messagespostedthistome."<br><br>";
				//end campaign
			
				$_SESSION['posted_in_this_cron_run']++;
				}
		
		
		}
sleep($cron_pause_between_posts);
unset($ch);
return $messagespostedthistome;	
}
	else
	{
	unset($ch);
	return $messagespostedthistome;	
	}
} //end accout ok

else
{
///update logs
mysql_query("INSERT INTO fbshare_logs VALUES ('','".$fbaccountid."','".$campaignid."','End posting - account not verified','".date('Y-m-d H:i:s')."')");
}

}
/////END functions




///START CRON POSTING - campaigns enabled, not ended, posts on groups
$sendemailtoadmin=false;

$rescampaigns=mysql_query("SELECT * FROM fbshare_campaigns WHERE isgroup=1 AND campaign_enabled=1 AND is_campaign_finished=0 AND messagespostedon!=0 ");
$total_pending_campaigns=mysql_num_rows($rescampaigns);

//at least one campaign
if($total_pending_campaigns>0)
{
	
//priority 1: 1 messages in the list, every Y minutes
$rescampaigns=mysql_query("SELECT * FROM fbshare_campaigns WHERE isgroup=1 AND campaign_enabled=1 AND is_campaign_finished=0 AND messagespostedon!=0 AND campaign_run=9 AND ((TIMESTAMPDIFF(MINUTE, campaign_last_time_run, now()) > campaign_run_minutes_post_x_messages) || campaign_last_time_run='0000-00-00 00:00:00') ORDER BY campaign_last_time_run ASC");
	$total_pending_campaigns=mysql_num_rows($rescampaigns);
		
		if(($total_pending_campaigns>0) && ($_SESSION['posted_in_this_cron_run']<$cron_nr_posts)) //if this type of campaign exists and limit/cron not exceeded
		{ 
		$campaigndetails=mysql_fetch_array($rescampaigns);
		$campaignid=$campaigndetails['campaignid'];
		$topostfbaccountid=$campaigndetails['accountid'];

///cron log
echo("Run campaign: ".$campaigndetails['campaignname'].": ".$campaigndetails['campaign_run_messages_to_post_minutes']." messages in the list, every ".$campaigndetails['campaign_run_minutes_post_x_messages']." minutes");

///update logs
mysql_query("INSERT INTO fbshare_logs VALUES ('','".$campaigndetails['userid']."','".$campaigndetails['campaignid']."','Start posting in ".$campaigndetails['campaignname']."','".date('Y-m-d H:i:s')."')");

$newtotalrun=$campaigndetails['howmanytimesthecampaignrun']+1;
mysql_query("UPDATE fbshare_campaigns SET howmanytimesthecampaignrun='".$newtotalrun."' WHERE campaignid='".$campaigndetails['campaignid']."' ");
	
//post messages
$max_posts_per_cron_run=$campaigndetails['campaign_run_messages_to_post_minutes']-$campaigndetails['posted_temp'];

///set campiagn started before updating
mysql_query("UPDATE fbshare_campaigns SET is_campaign_started='1' WHERE campaignid='".$campaigndetails['campaignid']."' ");

$messagespostedthistime=update_status($campaignid, $campaigndetails['userid'], $campaigndetails['messagespostedon'],$campaigndetails['howtopostmessages'],$max_posts_per_cron_run,$campaigndetails['campaign_repeat_type'],$topostfbaccountid);
//end post messages
		
///update logs
mysql_query("INSERT INTO fbshare_logs VALUES ('','".$campaigndetails['userid']."','".$campaigndetails['campaignid']."','Posted ".$messagespostedthistime." message(s) in ".$campaigndetails['campaignname']." campaign. Log out.','".date('Y-m-d H:i:s')."')");
		
	
		//update total messages messages posted in this campaign 
		$newtotalposted=$campaigndetails['totalmessagesposted']+$messagespostedthistime;
		mysql_query("UPDATE fbshare_campaigns SET totalmessagesposted='".$newtotalposted."' WHERE campaignid='".$campaigndetails['campaignid']."' ");
		

		//update campaign finished - send email is_campaign_finished 	
		//if no loop and if total posted=total to post - OK
		if(($campaigndetails['campaign_repeat_type']==0) && ($newtotalrun>=$campaigndetails['totalmessagespostedinthiscampaign']))
		{
			mysql_query("UPDATE fbshare_campaigns SET is_campaign_finished='1' WHERE campaignid='".$campaigndetails['campaignid']."' ");
			$sendemailtoadmin=true;
			$email_campaign_name=$campaigndetails['campaignname'];
		}
		
		
		
		//update last time run and total numer of runs
		//posted temp
		$newpostedtemp=$campaigndetails['posted_temp']+$messagespostedthistime;
		
		//update last time run and nr of times run
		if($newpostedtemp>=$campaigndetails['campaign_run_messages_to_post_minutes'])
		{
		mysql_query("UPDATE fbshare_campaigns SET posted_temp='0' WHERE campaignid='".$campaigndetails['campaignid']."' ");
		mysql_query("UPDATE fbshare_campaigns SET campaign_last_time_run=NOW() WHERE campaignid='".$campaigndetails['campaignid']."' ");
		

		}
		else
		{
		mysql_query("UPDATE fbshare_campaigns SET posted_temp='".$newpostedtemp."' WHERE campaignid='".$campaigndetails['campaignid']."' ");
		}
		

		}
//end priority 1
	
//priority 2 : 1 messages in the list, every hour
$rescampaigns=mysql_query("SELECT * FROM fbshare_campaigns WHERE isgroup=1 AND campaign_enabled=1 AND is_campaign_finished=0 AND messagespostedon!=0 AND campaign_run=1 AND ((TIMESTAMPDIFF(MINUTE, campaign_last_time_run, now()) > 60) || campaign_last_time_run='0000-00-00 00:00:00') ORDER BY campaign_last_time_run ASC");
	$total_pending_campaigns=mysql_num_rows($rescampaigns);
		
		if(($total_pending_campaigns>0) && ($_SESSION['posted_in_this_cron_run']<$cron_nr_posts)) //if this type of campaign exists and limit/cron not exceeded
		{ 
		$campaigndetails=mysql_fetch_array($rescampaigns);
		$campaignid=$campaigndetails['campaignid'];
		$topostfbaccountid=$campaigndetails['accountid'];
		
///cron log
echo("Run campaign: ".$campaigndetails['campaignname'].": ".$campaigndetails['campaign_run_messages_to_post_every_hour']." messages in the list, every hour");		

///update logs
mysql_query("INSERT INTO fbshare_logs VALUES ('','".$campaigndetails['userid']."','".$campaigndetails['campaignid']."','Start posting in ".$campaigndetails['campaignname']."','".date('Y-m-d H:i:s')."')");

$newtotalrun=$campaigndetails['howmanytimesthecampaignrun']+1;
mysql_query("UPDATE fbshare_campaigns SET howmanytimesthecampaignrun='".$newtotalrun."' WHERE campaignid='".$campaigndetails['campaignid']."' ");
	
//post messages
$max_posts_per_cron_run=$campaigndetails['campaign_run_messages_to_post_every_hour']-$campaigndetails['posted_temp'];

//update started
mysql_query("UPDATE fbshare_campaigns SET is_campaign_started='1' WHERE campaignid='".$campaigndetails['campaignid']."' ");

$messagespostedthistime=update_status($campaignid, $campaigndetails['userid'], $campaigndetails['messagespostedon'],$campaigndetails['howtopostmessages'],$max_posts_per_cron_run,$campaigndetails['campaign_repeat_type'],$topostfbaccountid);
		//end post messages
		
		///update logs
		mysql_query("INSERT INTO fbshare_logs VALUES ('','".$campaigndetails['userid']."','".$campaigndetails['campaignid']."','Posted ".$messagespostedthistime." message(s) in ".$campaigndetails['campaignname']." campaign. Log out.','".date('Y-m-d H:i:s')."')");
		
		//update total messages messages posted in this campaign - OK
		$newtotalposted=$campaigndetails['totalmessagesposted']+$messagespostedthistime;
		mysql_query("UPDATE fbshare_campaigns SET totalmessagesposted='".$newtotalposted."' WHERE campaignid='".$campaigndetails['campaignid']."' ");
		
		
		//update campaign finished - send email  is_campaign_finished 	
		//if no loop and if total posted=total to post - OK
		if(($campaigndetails['campaign_repeat_type']==0) && ($newtotalrun>=$campaigndetails['totalmessagespostedinthiscampaign']))
		{
			mysql_query("UPDATE fbshare_campaigns SET is_campaign_finished='1' WHERE campaignid='".$campaigndetails['campaignid']."' ");
			$sendemailtoadmin=true;
			$email_campaign_name=$campaigndetails['campaignname'];
		}
		
		
		
		//update last time run and total numer of runs
		//posted temp
		$newpostedtemp=$campaigndetails['posted_temp']+$messagespostedthistime;
		
		//update last time run and nr of times run
		if($newpostedtemp>=$campaigndetails['campaign_run_messages_to_post_every_hour'])
		{
		mysql_query("UPDATE fbshare_campaigns SET posted_temp='0' WHERE campaignid='".$campaigndetails['campaignid']."' ");
		mysql_query("UPDATE fbshare_campaigns SET campaign_last_time_run=NOW() WHERE campaignid='".$campaigndetails['campaignid']."' ");
		}
		else
		{
		mysql_query("UPDATE fbshare_campaigns SET posted_temp='".$newpostedtemp."' WHERE campaignid='".$campaigndetails['campaignid']."' ");
		}
		

		}
	//end priority 2
	
	
//priority 3: 1 messages in the list, every day
$rescampaigns=mysql_query("SELECT * FROM fbshare_campaigns WHERE isgroup=1 AND campaign_enabled=1 AND is_campaign_finished=0 AND messagespostedon!=0 AND campaign_run=7 AND ((TIMESTAMPDIFF(HOUR, campaign_last_time_run, now()) > 24) || campaign_last_time_run='0000-00-00 00:00:00') ORDER BY campaign_last_time_run ASC");
	$total_pending_campaigns=mysql_num_rows($rescampaigns);
		
		if(($total_pending_campaigns>0) && ($_SESSION['posted_in_this_cron_run']<$cron_nr_posts)) //if this type of campaign exists and limit/cron not exceeded
		{ 
		$campaigndetails=mysql_fetch_array($rescampaigns);
		$campaignid=$campaigndetails['campaignid'];
		$topostfbaccountid=$campaigndetails['accountid'];
	    
		///cron log
		echo("Run campaign: ".$campaigndetails['campaignname'].": ".$campaigndetails['campaign_run_messages_to_post_every_day']." messages in the list, every day");
		///update logs
		mysql_query("INSERT INTO fbshare_logs VALUES ('','".$campaigndetails['userid']."','".$campaigndetails['campaignid']."','Start posting in ".$campaigndetails['campaignname']."','".date('Y-m-d H:i:s')."')");
		
		$newtotalrun=$campaigndetails['howmanytimesthecampaignrun']+1;
		mysql_query("UPDATE fbshare_campaigns SET howmanytimesthecampaignrun='".$newtotalrun."' WHERE campaignid='".$campaigndetails['campaignid']."' ");
	
		//post messages
$max_posts_per_cron_run=$campaigndetails['campaign_run_messages_to_post_every_day']-$campaigndetails['posted_temp'];

mysql_query("UPDATE fbshare_campaigns SET is_campaign_started='1' WHERE campaignid='".$campaigndetails['campaignid']."' ");

$messagespostedthistime=update_status($campaignid, $campaigndetails['userid'], $campaigndetails['messagespostedon'],$campaigndetails['howtopostmessages'],$max_posts_per_cron_run,$campaigndetails['campaign_repeat_type'],$topostfbaccountid);
		//end post messages
		
		///update logs
		mysql_query("INSERT INTO fbshare_logs VALUES ('','".$campaigndetails['userid']."','".$campaigndetails['campaignid']."','Posted ".$messagespostedthistime." message(s) in ".$campaigndetails['campaignname']." campaign. Log out.','".date('Y-m-d H:i:s')."')");
		
	
		//update total messages messages posted in this campaign - OK
		$newtotalposted=$campaigndetails['totalmessagesposted']+$messagespostedthistime;
		mysql_query("UPDATE fbshare_campaigns SET totalmessagesposted='".$newtotalposted."' WHERE campaignid='".$campaigndetails['campaignid']."' ");
		
		
		//update campaign finished - send email is_campaign_finished 	
		//if no loop and if total posted=total to post - OK
		if(($campaigndetails['campaign_repeat_type']==0) && ($newtotalrun>=$campaigndetails['totalmessagespostedinthiscampaign']))
		{
			mysql_query("UPDATE fbshare_campaigns SET is_campaign_finished='1' WHERE campaignid='".$campaigndetails['campaignid']."' ");
			$sendemailtoadmin=true;
			$email_campaign_name=$campaigndetails['campaignname'];
		}
		
		
		
		//update last time run and total numer of runs
		//posted temp
		$newpostedtemp=$campaigndetails['posted_temp']+$messagespostedthistime;
		
		//update last time run and nr of times run
		if($newpostedtemp>=$campaigndetails['campaign_run_messages_to_post_every_day'])
		{
		mysql_query("UPDATE fbshare_campaigns SET posted_temp='0' WHERE campaignid='".$campaigndetails['campaignid']."' ");
		mysql_query("UPDATE fbshare_campaigns SET campaign_last_time_run=NOW() WHERE campaignid='".$campaigndetails['campaignid']."' ");
		
		}
		else
		{
		mysql_query("UPDATE fbshare_campaigns SET posted_temp='".$newpostedtemp."' WHERE campaignid='".$campaigndetails['campaignid']."' ");
		}
		

		}
	//end priority 3
	
	
//priority 4: 1 messages in the list, X DAY
$todaydate=date("Y-m-d");
$todaydate_daynr=date("N");
$rescampaigns=mysql_query("SELECT * FROM fbshare_campaigns WHERE isgroup=1 AND campaign_enabled=1 AND is_campaign_finished=0 AND messagespostedon!=0 AND campaign_run=8  AND campaign_run_day_post_x_messages='".$todaydate_daynr."' AND DATE(campaign_last_time_run)!='".$todaydate."' ORDER BY campaign_last_time_run ASC");

	$total_pending_campaigns=mysql_num_rows($rescampaigns);
		
		if(($total_pending_campaigns>0) && ($_SESSION['posted_in_this_cron_run']<$cron_nr_posts)) //if this type of campaign exists and limit/cron not exceeded
		{ 
		$campaigndetails=mysql_fetch_array($rescampaigns);
		$campaignid=$campaigndetails['campaignid'];
		$topostfbaccountid=$campaigndetails['accountid'];
		
        ///cron log
		echo("Run campaign: ".$campaigndetails['campaignname'].": ".$campaigndetails['campaign_run_messages_to_post_every_week']." messages in the list, every week.");

		///update logs
		mysql_query("INSERT INTO fbshare_logs VALUES ('','".$campaigndetails['userid']."','".$campaigndetails['campaignid']."','Start posting in ".$campaigndetails['campaignname']."','".date('Y-m-d H:i:s')."')");
		
		$newtotalrun=$campaigndetails['howmanytimesthecampaignrun']+1;
		mysql_query("UPDATE fbshare_campaigns SET howmanytimesthecampaignrun='".$newtotalrun."' WHERE campaignid='".$campaigndetails['campaignid']."' ");
	
		//post messages
$max_posts_per_cron_run=$campaigndetails['campaign_run_messages_to_post_every_week']-$campaigndetails['posted_temp'];

mysql_query("UPDATE fbshare_campaigns SET is_campaign_started='1' WHERE campaignid='".$campaigndetails['campaignid']."' ");

$messagespostedthistime=update_status($campaignid, $campaigndetails['userid'], $campaigndetails['messagespostedon'],$campaigndetails['howtopostmessages'],$max_posts_per_cron_run,$campaigndetails['campaign_repeat_type'],$topostfbaccountid);
		//end post messages
		
		///update logs
		mysql_query("INSERT INTO fbshare_logs VALUES ('','".$campaigndetails['userid']."','".$campaigndetails['campaignid']."','Posted ".$messagespostedthistime." message(s) in ".$campaigndetails['campaignname']." campaign. Log out.','".date('Y-m-d H:i:s')."')");
		
	
		//update total messages messages posted in this campaign - OK
		$newtotalposted=$campaigndetails['totalmessagesposted']+$messagespostedthistime;
		mysql_query("UPDATE fbshare_campaigns SET totalmessagesposted='".$newtotalposted."' WHERE campaignid='".$campaigndetails['campaignid']."' ");
		
		
		//update campaign finished - send email is_campaign_finished 	
		//if no loop and if total posted=total to post - OK
		if(($campaigndetails['campaign_repeat_type']==0) && ($newtotalrun>=$campaigndetails['totalmessagespostedinthiscampaign']))
		{
			mysql_query("UPDATE fbshare_campaigns SET is_campaign_finished='1' WHERE campaignid='".$campaigndetails['campaignid']."' ");
			$sendemailtoadmin=true;
			$email_campaign_name=$campaigndetails['campaignname'];
		}
		
		
		
		//update last time run and total numer of runs
		//posted temp
		$newpostedtemp=$campaigndetails['posted_temp']+$messagespostedthistime;
		
		//update last time run and nr of times run
		if($newpostedtemp>=$campaigndetails['campaign_run_messages_to_post_every_week'])
		{
		mysql_query("UPDATE fbshare_campaigns SET posted_temp='0' WHERE campaignid='".$campaigndetails['campaignid']."' ");
		mysql_query("UPDATE fbshare_campaigns SET campaign_last_time_run=NOW() WHERE campaignid='".$campaigndetails['campaignid']."' ");
		
		}
		else
		{
		mysql_query("UPDATE fbshare_campaigns SET posted_temp='".$newpostedtemp."' WHERE campaignid='".$campaigndetails['campaignid']."' ");
		}
		

		}
	//end priority 4
	
	
//priority 5: ALL messages in the list, on exact date
$todaydate=date("Y-m-d");
$rescampaigns=mysql_query("SELECT * FROM fbshare_campaigns WHERE isgroup=1 AND campaign_enabled=1 AND is_campaign_finished=0 AND messagespostedon!=0 AND campaign_run=3  AND campaign_run_specific_day='".$todaydate."' AND DATE(campaign_last_time_run)!='".$todaydate."' ORDER BY campaign_last_time_run ASC");

	$total_pending_campaigns=mysql_num_rows($rescampaigns);
		
		if(($total_pending_campaigns>0) && ($_SESSION['posted_in_this_cron_run']<$cron_nr_posts)) //if this type of campaign exists and limit/cron not exceeded
		{ 
		$campaigndetails=mysql_fetch_array($rescampaigns);
		$campaignid=$campaigndetails['campaignid'];
		$topostfbaccountid=$campaigndetails['accountid'];
		
		///cron log
		echo("Run campaign: ".$campaigndetails['campaignname'].": All messages in the list, on ".$campaigndetails['campaign_run_specific_day ']);
		
		///update logs
		mysql_query("INSERT INTO fbshare_logs VALUES ('','".$campaigndetails['userid']."','".$campaigndetails['campaignid']."','Start posting in ".$campaigndetails['campaignname']."','".date('Y-m-d H:i:s')."')");
		
		$newtotalrun=$campaigndetails['howmanytimesthecampaignrun']+1;
		mysql_query("UPDATE fbshare_campaigns SET howmanytimesthecampaignrun='".$newtotalrun."' WHERE campaignid='".$campaigndetails['campaignid']."' ");
	
		//post messages
$max_posts_per_cron_run=$campaigndetails['totalmessagespostedinthiscampaign']-$campaigndetails['posted_temp'];

mysql_query("UPDATE fbshare_campaigns SET is_campaign_started='1' WHERE campaignid='".$campaigndetails['campaignid']."' ");

//$messagespostedthistime=update_status($campaignid, $campaigndetails['userid'], $campaigndetails['messagespostedon'],$campaigndetails['howtopostmessages'],$max_posts_per_cron_run,$campaigndetails['campaign_repeat_type'],$topostfbaccountid);
$messagespostedthistime=update_status($campaignid, $campaigndetails['userid'], $campaigndetails['messagespostedon'],$campaigndetails['howtopostmessages'],1,$campaigndetails['campaign_repeat_type'],$topostfbaccountid);
		//end post messages
		
		///update logs
		mysql_query("INSERT INTO fbshare_logs VALUES ('','".$campaigndetails['userid']."','".$campaigndetails['campaignid']."','Posted ".$messagespostedthistime." message(s) in ".$campaigndetails['campaignname']." campaign. Log out.','".date('Y-m-d H:i:s')."')");
		
	
		//update total messages messages posted in this campaign - OK
		$newtotalposted=$campaigndetails['totalmessagesposted']+$messagespostedthistime;
		mysql_query("UPDATE fbshare_campaigns SET totalmessagesposted='".$newtotalposted."' WHERE campaignid='".$campaigndetails['campaignid']."' ");
		
		
		//update campaign finished - send email is_campaign_finished 	
		//if no loop and if total posted=total to post - OK
		if($messagespostedthistime>0)
		{
			mysql_query("UPDATE fbshare_campaigns SET is_campaign_finished='1' WHERE campaignid='".$campaigndetails['campaignid']."' ");
			$sendemailtoadmin=true;
			$email_campaign_name=$campaigndetails['campaignname'];
		}
		
		//update last time run and total numer of runs
		//posted temp
		$newpostedtemp=$campaigndetails['posted_temp']+$messagespostedthistime;
		
		//update last time run and nr of times run
		if($messagespostedthistime>0)
		{
		mysql_query("UPDATE fbshare_campaigns SET posted_temp='0' WHERE campaignid='".$campaigndetails['campaignid']."' ");
		mysql_query("UPDATE fbshare_campaigns SET campaign_last_time_run=NOW() WHERE campaignid='".$campaigndetails['campaignid']."' ");
		}
		//else
		//{
		//mysql_query("UPDATE fbshare_campaigns SET posted_temp='".$newpostedtemp."' WHERE campaignid='".$campaigndetails['campaignid']."' ");
		//}
		

		}
	//end priority 5

//priority 6: 1 messages in the list, on exact date(X day)
$todaydate=date("Y-m-d");
$todaydate_monthnr=date("j");

$rescampaigns=mysql_query("SELECT * FROM fbshare_campaigns WHERE isgroup=1 AND campaign_enabled=1 AND is_campaign_finished=0 AND messagespostedon!=0 AND campaign_run=5  AND campaign_run_month_day='".$todaydate_monthnr."' AND DATE(campaign_last_time_run)!='".$todaydate."' ORDER BY campaign_last_time_run ASC");

	$total_pending_campaigns=mysql_num_rows($rescampaigns);
		
		if(($total_pending_campaigns>0) && ($_SESSION['posted_in_this_cron_run']<$cron_nr_posts)) //if this type of campaign exists and limit/cron not exceeded
		{ 
		$campaigndetails=mysql_fetch_array($rescampaigns);
		$campaignid=$campaigndetails['campaignid'];
		$topostfbaccountid=$campaigndetails['accountid'];
		
		///cron log
		echo("Run campaign: ".$campaigndetails['campaignname'].": 1 message in the list, this day of the month.");
		
		///update logs
		mysql_query("INSERT INTO fbshare_logs VALUES ('','".$campaigndetails['userid']."','".$campaigndetails['campaignid']."','Start posting in ".$campaigndetails['campaignname']."','".date('Y-m-d H:i:s')."')");
		
		$newtotalrun=$campaigndetails['howmanytimesthecampaignrun']+1;
		mysql_query("UPDATE fbshare_campaigns SET howmanytimesthecampaignrun='".$newtotalrun."' WHERE campaignid='".$campaigndetails['campaignid']."' ");
	
		//post messages
$max_posts_per_cron_run=$campaigndetails['totalmessagespostedinthiscampaign']-$campaigndetails['posted_temp'];

mysql_query("UPDATE fbshare_campaigns SET is_campaign_started='1' WHERE campaignid='".$campaigndetails['campaignid']."' ");

//$messagespostedthistime=update_status($campaignid, $campaigndetails['userid'], $campaigndetails['messagespostedon'],$campaigndetails['howtopostmessages'],$max_posts_per_cron_run,$campaigndetails['campaign_repeat_type'],$topostfbaccountid);
$messagespostedthistime=update_status($campaignid, $campaigndetails['userid'], $campaigndetails['messagespostedon'],$campaigndetails['howtopostmessages'],1,$campaigndetails['campaign_repeat_type'],$topostfbaccountid);
		//end post messages
		
		///update logs
		mysql_query("INSERT INTO fbshare_logs VALUES ('','".$campaigndetails['userid']."','".$campaigndetails['campaignid']."','Posted ".$messagespostedthistime." message(s) in ".$campaigndetails['campaignname']." campaign. Log out.','".date('Y-m-d H:i:s')."')");
		
	
		//update total messages messages posted in this campaign - OK
		$newtotalposted=$campaigndetails['totalmessagesposted']+$messagespostedthistime;
		mysql_query("UPDATE fbshare_campaigns SET totalmessagesposted='".$newtotalposted."' WHERE campaignid='".$campaigndetails['campaignid']."' ");
		
		
		//update campaign finished - send email is_campaign_finished 	
		//if no loop and if total posted=total to post - OK
		if(($campaigndetails['campaign_repeat_type']==0) && ($newtotalrun>=$campaigndetails['totalmessagespostedinthiscampaign']))
		{
			mysql_query("UPDATE fbshare_campaigns SET is_campaign_finished='1' WHERE campaignid='".$campaigndetails['campaignid']."' ");
			$sendemailtoadmin=true;
			$email_campaign_name=$campaigndetails['campaignname'];
		}
		
		
		
		//update last time run and total numer of runs
		//posted temp
		$newpostedtemp=$campaigndetails['posted_temp']+$messagespostedthistime;
		
		//update last time run and nr of times run
		if($messagespostedthistime==1)
		{
		mysql_query("UPDATE fbshare_campaigns SET posted_temp='0' WHERE campaignid='".$campaigndetails['campaignid']."' ");
		mysql_query("UPDATE fbshare_campaigns SET campaign_last_time_run=NOW() WHERE campaignid='".$campaigndetails['campaignid']."' ");
		}
	//	else
		//{
		//mysql_query("UPDATE fbshare_campaigns SET posted_temp='".$newpostedtemp."' WHERE campaignid='".$campaigndetails['campaignid']."' ");
	//	}
  
		}
	//end priority 6
	
//priority 7: ALL messages in the list.

$rescampaigns=mysql_query("SELECT * FROM fbshare_campaigns WHERE isgroup=1 AND campaign_enabled=1 AND is_campaign_finished=0 AND messagespostedon!=0 AND campaign_run=0 ORDER BY campaign_last_time_run ASC");

	$total_pending_campaigns=mysql_num_rows($rescampaigns);
		
		if(($total_pending_campaigns>0) && ($_SESSION['posted_in_this_cron_run']<$cron_nr_posts)) //if this type of campaign exists and limit/cron not exceeded
		{ 
		$campaigndetails=mysql_fetch_array($rescampaigns);
		$campaignid=$campaigndetails['campaignid'];
		$topostfbaccountid=$campaigndetails['accountid'];
				
        ///cron log
		echo("Run campaign: ".$campaigndetails['campaignname'].": All messages in the list.");
		
		///update logs
		mysql_query("INSERT INTO fbshare_logs VALUES ('','".$campaigndetails['userid']."','".$campaigndetails['campaignid']."','Start posting in ".$campaigndetails['campaignname']."','".date('Y-m-d H:i:s')."')");
				
		$newtotalrun=$campaigndetails['howmanytimesthecampaignrun']+1;
		mysql_query("UPDATE fbshare_campaigns SET howmanytimesthecampaignrun='".$newtotalrun."' WHERE campaignid='".$campaigndetails['campaignid']."' ");
	
		//post messages
$max_posts_per_cron_run=$campaigndetails['totalmessagespostedinthiscampaign']-$campaigndetails['posted_temp'];

mysql_query("UPDATE fbshare_campaigns SET is_campaign_started='1' WHERE campaignid='".$campaigndetails['campaignid']."' ");

$messagespostedthistime=update_status($campaignid, $campaigndetails['userid'], $campaigndetails['messagespostedon'],$campaigndetails['howtopostmessages'],$max_posts_per_cron_run,$campaigndetails['campaign_repeat_type'],$topostfbaccountid);
		//end post messages
		
		///update logs
		mysql_query("INSERT INTO fbshare_logs VALUES ('','".$campaigndetails['userid']."','".$campaigndetails['campaignid']."','Posted ".$messagespostedthistime." message(s) in ".$campaigndetails['campaignname']." campaign. Log out.','".date('Y-m-d H:i:s')."')");
		
	
		//update total messages messages posted in this campaign - OK
		$newtotalposted=$campaigndetails['totalmessagesposted']+$messagespostedthistime;
		mysql_query("UPDATE fbshare_campaigns SET totalmessagesposted='".$newtotalposted."' WHERE campaignid='".$campaigndetails['campaignid']."' ");
		
		
		//update campaign finished - send email is_campaign_finished 	
		//if no loop and if total posted=total to post - OK
		if(($campaigndetails['campaign_repeat_type']==0) && ($newtotalrun>=$campaigndetails['totalmessagespostedinthiscampaign']))
		{
			mysql_query("UPDATE fbshare_campaigns SET is_campaign_finished='1' WHERE campaignid='".$campaigndetails['campaignid']."' ");
			$sendemailtoadmin=true;
			$email_campaign_name=$campaigndetails['campaignname'];
		}
		
		
		
		//update last time run and total numer of runs
		//posted temp
		$newpostedtemp=$campaigndetails['posted_temp']+$messagespostedthistime;
		
		//update last time run and nr of times run
		if($newpostedtemp>=$campaigndetails['totalmessagespostedinthiscampaign'])
		{
		mysql_query("UPDATE fbshare_campaigns SET posted_temp='0' WHERE campaignid='".$campaigndetails['campaignid']."' ");
		mysql_query("UPDATE fbshare_campaigns SET campaign_last_time_run=NOW() WHERE campaignid='".$campaigndetails['campaignid']."' ");

		}
		else
		{
		mysql_query("UPDATE fbshare_campaigns SET posted_temp='".$newpostedtemp."' WHERE campaignid='".$campaigndetails['campaignid']."' ");
		}

		}
	//end priority 7
	
///send email to admin
if($sendemailtoadmin==true && $cron_send_notifications==1)
{
	//email details
	include(dirname(__FILE__)."/../includes/phpmailer/class.phpmailer.php"); 
	$Mail = new PHPMailer();
	$Mail->IsHTML(true);
    $Mail->AddReplyTo($cron_send_notifications_to,"PGF Admin");
	
	$emailcontent2="<b>The campaign </b>".$email_campaign_name." has ended.<br><br>Please login into admin panel to see all details.<br><br>----------------------------------------------------------<br>";
	
		$emailbody='
		<html>
		<body>'.$emailcontent2.'<br><br>
		</body>
		</html>
		';
		 $Mail->MsgHTML($emailbody);
		 $Mail->Subject  = "Your campaign is completed!";
		 $Mail->AddAddress($cron_send_notifications_to, "PGF Campaigns");
		 $Mail->From = trim($cron_send_notifications_to);
	     $Mail->FromName = trim("PGF Campaigns");
		 $Mail->Send();
		 $Mail->ClearAddresses();
		 echo("<br>Campaign ended. Email sent to admin.");
	
	
}

} //end if at least one campaign in DB


?>
