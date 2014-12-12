<?php include(dirname(__FILE__)."/../topcrons.php"); 
require_once("functions.php");

///global functions and vars
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
		echo "<br>No groups in this campaign! Campaign paused.<br>-----------------<br>"; 
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
		
		echo "<br>---------------------------------<br>Original message:<br><br>".$messagedetails['message']."<br>--------------------------------------<br>" ;
		
		///prepare status with new lines
		$status=str_replace("<br>","||",$status);
		$status=str_replace("<br />","||",$status);
		$status=str_replace("<br/>","||",$status);
		$status=str_replace("||","\n",$status);
		

		$url_to_send=get_url_from_string($status);
		
		//status no link
		$status_no_link=str_replace($url_to_send,"",$status);
		//$status_no_link=$status;
		
		//check if the message is image
		$isimage=false;
		if($url_to_send!="")
		{
			if (preg_match('/(\.jpg|\.png|\.jpeg|\.gif|\.bmp)$/', $url_to_send)) 
			{
				$isimage=true;
			}
   		}
		
		//end is image
		
		//check if message is a FB video
		$isfbvideo=false;
		///video format : https://www.facebook.com/photo.php?v=726977603986546 
		if($url_to_send!="")
		{
			$fbvideoidarr=explode("photo.php?v=",$url_to_send);
			if (trim($fbvideoidarr[1])!="") 
			{
				$isfbvideo=true;
				$fbvideoid=$fbvideoidarr[1];
			}
   		}
		//end if video
	
				////CHECK if is comment for last post
				$isgroupcomment=false;
				$firstcharinmessage=substr(trim($status), 0,2);
				
				///if is comment 
				if($firstcharinmessage=="##")
				{
					$isgroupcomment=true;
					$lastidquery=mysql_query("SELECT MAX(logid) FROM fbshare_logs");
					$reslogs=mysql_fetch_array($lastidquery);
					echo("<br>This is a comment to a previous post. Exit.<br>");
					mysql_query("DELETE FROM fbshare_logs WHERE logid='".$reslogs[0]."' ");
					exit();
				}
				////End CHECK if is comment for last post
				
				////CHECK if is comment for user's post
				$ismygroupcomment=false;
				$firstcharinmessage=substr(trim($status), 0,2);
				
				///if is comment 
				if($firstcharinmessage=="@@")
				{
					$ismygroupcomment=true;
					$lastidquery=mysql_query("SELECT MAX(logid) FROM fbshare_logs");
					$reslogs=mysql_fetch_array($lastidquery);
					echo("<br>This is a comment to a previous post. Exit.<br>");
					mysql_query("DELETE FROM fbshare_logs WHERE logid='".$reslogs[0]."' ");
					exit();
				}
				////End CHECK if is comment for user's post
				
				//IF RSS
				$isrss=false;
				$firstcharsinmessage=substr(trim($status),0,5);
				if($firstcharsinmessage=="#rss#")
				{
					$spintax_message = new spintax();
					$status=$spintax_message->process($status);
					
					
					$isrss=true;
					$isimage=false;
					$rssurl=str_replace("#rss#","",trim($status));
					
					
					$rss = simplexml_load_file(trim($rssurl));
					$items = $rss->channel->item;
					$status_rss=trim($items[0]->title);
					$url_to_send_rss=trim($items[0]->link);
					if($url_to_send_rss==""){$url_to_send_rss=$rssurl;}
					
					$status_logs=$status_rss;
				}
			///POST NOW
				
				
				
			
				if($messagefoundindb>0)
				{
				///POST NOW
				require_once("fbsdk/facebook.php");
				$fb = new fb($appid, $appsecret);
				
				///is fb video - get video image and video owner
				if($isfbvideo==true)
				{
					$video_image = $fb->get_video_image_url($pass, $fbvideoid);
					$video_userid = $fb->get_video_image_userid($pass, $fbvideoid);
					$new_status_no_video=str_replace($url_to_send,"",$status);
				}
				

				//update messages
				mysql_query("UPDATE fbshare_campaigns_messages SET lastpostedon='".$lastpostedon."', nroftimesposted='".$lastnroftimesposted."' WHERE messageid='".$lastmessageid."' ");
				
				////start loop in groups
				if($postallgroups==true) //loop groups
				{
					echo "<br>Try to post message in groups[batch message]";
					echo "<br>----------------------------------------------------------<br>";
					
					$allgroups=mysql_query("SELECT * FROM fbshare_group_campaigns WHERE campaignid='".$campaignid."' AND accountid='".$accountid."' ORDER BY rand()");
					$totalgroupstopostin=mysql_num_rows($allgroups);
					
					echo "<br>Total groups to post in this campaign: ".$totalgroupstopostin."<br>";
					
					$k=1;
					$batcharray=array();
					$logs_batch_groups="";
					$flush_array=false;
					$status_to_spin=$status;
					
					//logs
					sleep(1);
					mysql_query("INSERT INTO fbshare_logs VALUES ('','".$fbaccountid."','".$campaignid."','<font color=#FF3700>Publicando mensaje [Lotes de mensajes]</font><br>','".date('Y-m-d H:i:s')."')");
					sleep(1);
				    
					while($postinallgroupsdetails=mysql_fetch_array($allgroups))
					{

							///get group details
							$resgroupname=mysql_query("SELECT * FROM fbshare_fbpages WHERE accountid='".$accountid."' AND isgroup='1' AND pageid='".$postinallgroupsdetails['groupid']."' ");
							$resgroupdetailsres=mysql_fetch_array($resgroupname);
							
							//SPINTAX only if no rss
							if($isrss==false)
							{
							$spintax_message = new spintax();
							$status=$spintax_message->process($status_to_spin);
							$status_logs=$status;
							
							///this is regular post - > get link again
							$url_to_send=get_url_from_string($status);
							
							//check if previous url != url to send => unset session -> upload again
							//if($previous_url_to_send!=$url_to_send) $messagespostedthistome%2==0
							//if(($previous_url_to_send!=$url_to_send)) 
							//{
								//unset($_SESSION['lastphotoid']);
							//}
							
							//previous url
							$previous_url_to_send=$url_to_send;
							
							//status no link
							$status_no_link=str_replace($url_to_send,"",$status);
							//end get link again
							}
							////END SPINTAX
										
						
							//////////////start batch posting
							//IMAGE
							if($isimage==true)
							{
							$new_status=str_replace("\n".trim($url_to_send),"",$status);
							$new_status=str_replace(trim($url_to_send),"",$status);
						
							//$post = $fb->uploadOnPage($resgroupdetailsres['fbpageurl'],$pass,$new_status,$url_to_send);
							///batch body for regular message
								    unset($body);
									if(!isset($_SESSION['lastphotoid'])) //session not set -> upload image
									{
								     $post = $fb->uploadOnPage_first_Batch($resgroupdetailsres['fbpageurl'],$pass,$new_status,$url_to_send);
									 echo "<br>-----------------------<br>Message to post: ".$new_status."<br>Session image not set. Image to upload: ".$url_to_send."<br>";
									 echo "Upload image to group: [".$resgroupdetailsres['fbpageurl']."]<br>";
									 	if($post!="Message posted!") ///FIRST UPLOAD NOT OK
										{
										echo "<br>-----------------------<br>Error while uploading the image<br>-----------------------<br>";
										mysql_query("INSERT INTO fbshare_logs VALUES ('','".$fbaccountid."','".$campaignid."','<font color=#FF3700>FB error: <br>".$post."</font>','".date('Y-m-d H:i:s')."')");
										return 0;	//not ok
										exit();
										}
									 $messagespostedthistome++;
									}
									else
									{
									
										//spin upload with links
										if($messagespostedthistome%2==0) //upload
										{	
										
										///replace URL from message
										$url_new_from_message="";
										$url_new_from_message=get_url_from_string($new_status);
										$new_status_nolinks="";
										$new_status_nolinks=str_replace("\n".trim($url_new_from_message),"",$new_status);
										$new_status_nolinks=str_replace(trim($url_new_from_message),"",$new_status);
										
										$body = array(
										'url'  => $url_to_send,
										//'link'  => 'https://www.facebook.com/photo.php?fbid='.$_SESSION['lastphotoid'],
										'message'=> $new_status
										//'message'=> $new_status_nolinks
										);
										
										} 
										else  /// link
										{
										//get link from the remaining message
										$url_new_from_message="";
										$url_new_from_message=get_url_from_string($new_status);
								
											if(trim($url_new_from_message)!="")
											{
												///replace URL from message
												$new_status_nolinks="";
												$new_status_nolinks=str_replace("\n".trim($url_new_from_message),"",$new_status);
												$new_status_nolinks=str_replace(trim($url_new_from_message),"",$new_status);
												
												$body = array(
												//'link'  => $url_new_from_message,
												'link'  => 'https://www.facebook.com/photo.php?fbid='.$_SESSION['lastphotoid'],
												//'image'  => $url_to_send,
												'message'=> $new_status_nolinks
												);

											}
											else
											{
												$body = array(
												//'link'  => $url_to_send,
												'link'  => 'https://www.facebook.com/photo.php?fbid='.$_SESSION['lastphotoid'],
												//'image'  => $url_to_send,
												'message'=> $new_status);
					
											}
										
										}
									
									echo "<br>-----------------------<br>Message to post: ".$new_status."<br>Session image is set - Image to post: ".$url_to_send." [Last posted:https://www.facebook.com/photo.php?fbid=".$_SESSION['lastphotoid']."]<br>";
									}
									
								    
									///loop through groups and build array
									if(((($totalgroupstopostin-$k)%20)<20) && $k>1)
									{
										//put messages
										if($messagespostedthistome<$cron_nr_posts)
											{		
												//spin upload with links
												if($messagespostedthistome%2==0) // upload
												{	
												$batcharray[] = array(
												'method' => 'POST',
												'relative_url' => "/".$resgroupdetailsres['fbpageurl']."/photos",
												//'relative_url' => "/".$resgroupdetailsres['fbpageurl']."/feed",
												'body' => http_build_query($body)
												);	
								
												}
												else  //link
												{
												$batcharray[] = array(
												'method' => 'POST',
												'relative_url' => "/".$resgroupdetailsres['fbpageurl']."/feed",
												'body' => http_build_query($body)
												);
													
												}
												
							///LOGS
							mysql_query("INSERT INTO fbshare_logs 
							VALUES ('','".$fbaccountid."','".$campaignid."','Publicado en el grupo ID: [".$resgroupdetailsres['fbpageurl']."] en cola...Mensaje colocado en este grupo:<br>".mysql_real_escape_string($status_logs)."','".date('Y-m-d H:i:s')."')");
												
												echo "Put group in queue image posting: [".$resgroupdetailsres['fbpageurl']."]<br>";
												///array for logs
												$logs_batch_groups=$logs_batch_groups."[".$resgroupdetailsres['fbpageurl']."] ";
												$flush_array=false;
											}
											if($messagespostedthistome>=$cron_nr_posts)
											{
											$flush_array=true;
											}

										$messagespostedthistome++;	
									}
									
									if((((($totalgroupstopostin-$k)%20)==0) && $k>1) || ($flush_array==true))
									{
										echo ("<br>Posting now to ".sizeof($batcharray)." groups. Pause ".$cron_pause_between_posts." seconds...<br>--------------------------------------<br>");
									
										///POST here
										$post = $fb->uploadOnPage_Batch($pass,$batcharray);
										
										if($post!="Message posted!") ///BATCH POST NOT OK
										{
										mysql_query("INSERT INTO fbshare_logs VALUES ('','".$fbaccountid."','".$campaignid."','<font color=#FF3700>FB error: <br>".$post."</font>','".date('Y-m-d H:i:s')."')");
										echo "<br>-----------------------<br>Error while uploading batch images<br>-----------------------<br>";
										return 1;	//only first message was ok
										exit();
										}
										else ///POST OK
										{
										unset($batcharray);
										$batcharray=array();								
										mysql_query("INSERT INTO fbshare_logs VALUES ('','".$fbaccountid."','".$campaignid."','<font color=#FF3700>Exitosamente colocado en los grupos IDs: <br>".$logs_batch_groups."</font>','".date('Y-m-d H:i:s')."')");
										$logs_batch_groups="";
										}

										//pause between posts
										sleep($cron_pause_between_posts);
										//new api 
										require_once("fbsdk/facebook.php");
										$fb = new fb($appid, $appsecret);
									}
									$k++;
									
									//check if maximum post -> exit
									if($messagespostedthistome>$cron_nr_posts)
									{
									echo("<br><font color=red>Maximum number of ".$cron_nr_posts." posts excedeed. Logout!</font><br>");
									$_SESSION['posted_in_this_cron_run']++;
									$messagespostedthistome++;
									return $messagespostedthistome;	
									exit();
									}
						
							
							} //END IMAGE
							
							else //IF REGULAR POST
							{
									///batch body for regular message
								    unset($body);
				
									///no rss
									if($isrss==false)
									{
										if($messagespostedthistome%2==0) //if odd
										{
											
											 $body = array(
											'message'    => $status_no_link."\n".$url_to_send
											);
										}
										else
										{
											$body = array
											(
											'message'        => $status_no_link,
											//'image'          => $url_to_send,
											'link'           => $url_to_send
											);
										}
									}
									//rss
									if($isrss==true)
									{
										
										if($messagespostedthistome%2==0) //if odd
										{
											
											$body = array('message'    => $status_rss."\n".$url_to_send_rss);
										}
										else
										{
											$body = array
											(
											'message'        => $status_rss,
											//'image'          => $url_to_send_rss,
											'link'           => $url_to_send_rss
											);
										}
										
									$status_no_link=$status_rss;
									$url_to_send=$url_to_send_rss;
									}
									//end rss
									
									
								    echo "<br>-----------------------<br>Message to post: ".$status_no_link."<br>Link to post: ".$url_to_send."<br>";
									///loop through groups and build array
									if((($totalgroupstopostin-$k)%20)<20)
									{
										//put messages
										if($messagespostedthistome<$cron_nr_posts)
											{
												$batcharray[] = array(
												'method' => 'POST',
												'relative_url' => "/".$resgroupdetailsres['fbpageurl']."/feed",
												'body' => http_build_query($body)
												);
												
							///LOGS
							mysql_query("INSERT INTO fbshare_logs 
							VALUES ('','".$fbaccountid."','".$campaignid."','Publicando en el grupo ID: [".$resgroupdetailsres['fbpageurl']."] en cola...Mensaje colocado en este grupo:<br>".mysql_real_escape_string($status_logs)."','".date('Y-m-d H:i:s')."')");
												
												echo "Put group in queue regular post: [".$resgroupdetailsres['fbpageurl']."]<br>";
												///array for logs
												$logs_batch_groups=$logs_batch_groups."[".$resgroupdetailsres['fbpageurl']."] ";
												$flush_array=false;
											}
											if($messagespostedthistome>=$cron_nr_posts)
											{
											$flush_array=true;
											}

										$messagespostedthistome++;	
									}
									
									if(((($totalgroupstopostin-$k)%20)==0) || ($flush_array==true))
									{
										echo ("<br>Posting now to ".sizeof($batcharray)." groups. Pause ".$cron_pause_between_posts." seconds...<br>--------------------------------------<br>");
										
										///POST here
										$post = $fb->postOnGroup_Batch($pass,$batcharray);
										
										if($post!="Message posted!") ///POST NOT OK
										{
										mysql_query("INSERT INTO fbshare_logs VALUES ('','".$fbaccountid."','".$campaignid."','<font color=#FF3700>FB error: <br>".$post."</font>','".date('Y-m-d H:i:s')."')");
										echo "<br>-----------------------<br>Error posting batch messages: ".$post."<br>-----------------------<br>";
										}
										else ///POST OK
										{
										unset($batcharray);
										$batcharray=array();								
										mysql_query("INSERT INTO fbshare_logs VALUES ('','".$fbaccountid."','".$campaignid."','<font color=#FF3700>Exitosamente colocado en los grupos IDs: <br>".$logs_batch_groups."</font>','".date('Y-m-d H:i:s')."')");
										$logs_batch_groups="";
										}

										//pause between posts
										sleep($cron_pause_between_posts);
										//new api 
										require_once("fbsdk/facebook.php");
										$fb = new fb($appid, $appsecret);
									}
									$k++;
									
									//check if maximum post -> exit
									if($messagespostedthistome>$cron_nr_posts)
									{
									echo("<br><font color=red>Maximum number of ".$cron_nr_posts." posts excedeed. Logout!</font><br>");
									$_SESSION['posted_in_this_cron_run']++;
									$messagespostedthistome--;
									return $messagespostedthistome;	
									exit();
									}
																
							} //END IF REGULAR POST
							
							//////////////END BATCH POSTING
						
					} //END looping in groups 
					
					
				}//end posting in all groups
				
				
				echo "Messages posted by this cron: ".$messagespostedthistome."<br><br>";
				
				//end campaign
				$_SESSION['posted_in_this_cron_run']++;
				}
		}
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
mysql_query("INSERT INTO fbshare_logs VALUES ('','".$campaigndetails['userid']."','".$campaigndetails['campaignid']."','Comienza a publicar la campa&ntilde;a ".$campaigndetails['campaignname']."','".date('Y-m-d H:i:s')."')");

$newtotalrun=$campaigndetails['howmanytimesthecampaignrun']+1;
mysql_query("UPDATE fbshare_campaigns SET howmanytimesthecampaignrun='".$newtotalrun."' WHERE campaignid='".$campaigndetails['campaignid']."' ");
	
//post messages
$max_posts_per_cron_run=$campaigndetails['campaign_run_messages_to_post_minutes']-$campaigndetails['posted_temp'];

///set campiagn started before updating
mysql_query("UPDATE fbshare_campaigns SET is_campaign_started='1' WHERE campaignid='".$campaigndetails['campaignid']."' ");


$messagespostedthistime=update_status($campaignid, $campaigndetails['userid'], $campaigndetails['messagespostedon'],$campaigndetails['howtopostmessages'],$max_posts_per_cron_run,$campaigndetails['campaign_repeat_type'],$topostfbaccountid);
//end post messages
		
///update logs
mysql_query("INSERT INTO fbshare_logs VALUES ('','".$campaigndetails['userid']."','".$campaigndetails['campaignid']."','".$messagespostedthistime." mensaje(s) publicados de la campa&ntilde;a ".$campaigndetails['campaignname'].". Terminado.','".date('Y-m-d H:i:s')."')");
		
	
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
mysql_query("INSERT INTO fbshare_logs VALUES ('','".$campaigndetails['userid']."','".$campaigndetails['campaignid']."','Comienza a publicar la campa&ntilde;a ".$campaigndetails['campaignname']."','".date('Y-m-d H:i:s')."')");

$newtotalrun=$campaigndetails['howmanytimesthecampaignrun']+1;
mysql_query("UPDATE fbshare_campaigns SET howmanytimesthecampaignrun='".$newtotalrun."' WHERE campaignid='".$campaigndetails['campaignid']."' ");
	
//post messages
$max_posts_per_cron_run=$campaigndetails['campaign_run_messages_to_post_every_hour']-$campaigndetails['posted_temp'];

//update started
mysql_query("UPDATE fbshare_campaigns SET is_campaign_started='1' WHERE campaignid='".$campaigndetails['campaignid']."' ");


$messagespostedthistime=update_status($campaignid, $campaigndetails['userid'], $campaigndetails['messagespostedon'],$campaigndetails['howtopostmessages'],$max_posts_per_cron_run,$campaigndetails['campaign_repeat_type'],$topostfbaccountid);
		//end post messages
		
		///update logs
		mysql_query("INSERT INTO fbshare_logs VALUES ('','".$campaigndetails['userid']."','".$campaigndetails['campaignid']."','".$messagespostedthistime." mensaje(s) publicados de la campa&ntilde;a ".$campaigndetails['campaignname'].". Terminado.','".date('Y-m-d H:i:s')."')");
		
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
		mysql_query("INSERT INTO fbshare_logs VALUES ('','".$campaigndetails['userid']."','".$campaigndetails['campaignid']."','Comienza a publicar la campa&ntilde;a ".$campaigndetails['campaignname']."','".date('Y-m-d H:i:s')."')");
		
		$newtotalrun=$campaigndetails['howmanytimesthecampaignrun']+1;
		mysql_query("UPDATE fbshare_campaigns SET howmanytimesthecampaignrun='".$newtotalrun."' WHERE campaignid='".$campaigndetails['campaignid']."' ");
	
		//post messages
$max_posts_per_cron_run=$campaigndetails['campaign_run_messages_to_post_every_day']-$campaigndetails['posted_temp'];

mysql_query("UPDATE fbshare_campaigns SET is_campaign_started='1' WHERE campaignid='".$campaigndetails['campaignid']."' ");


$messagespostedthistime=update_status($campaignid, $campaigndetails['userid'], $campaigndetails['messagespostedon'],$campaigndetails['howtopostmessages'],$max_posts_per_cron_run,$campaigndetails['campaign_repeat_type'],$topostfbaccountid);
		//end post messages
		
		///update logs
		mysql_query("INSERT INTO fbshare_logs VALUES ('','".$campaigndetails['userid']."','".$campaigndetails['campaignid']."','".$messagespostedthistime." mensaje(s) publicados de la campa&ntilde;a ".$campaigndetails['campaignname'].". Teminado.','".date('Y-m-d H:i:s')."')");
		
	
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
		mysql_query("INSERT INTO fbshare_logs VALUES ('','".$campaigndetails['userid']."','".$campaigndetails['campaignid']."','Comienza a publicar la campa&ntilde;a ".$campaigndetails['campaignname']."','".date('Y-m-d H:i:s')."')");
		
		$newtotalrun=$campaigndetails['howmanytimesthecampaignrun']+1;
		mysql_query("UPDATE fbshare_campaigns SET howmanytimesthecampaignrun='".$newtotalrun."' WHERE campaignid='".$campaigndetails['campaignid']."' ");
	
		//post messages
$max_posts_per_cron_run=$campaigndetails['campaign_run_messages_to_post_every_week']-$campaigndetails['posted_temp'];

mysql_query("UPDATE fbshare_campaigns SET is_campaign_started='1' WHERE campaignid='".$campaigndetails['campaignid']."' ");


$messagespostedthistime=update_status($campaignid, $campaigndetails['userid'], $campaigndetails['messagespostedon'],$campaigndetails['howtopostmessages'],$max_posts_per_cron_run,$campaigndetails['campaign_repeat_type'],$topostfbaccountid);
		//end post messages
		
		///update logs
		mysql_query("INSERT INTO fbshare_logs VALUES ('','".$campaigndetails['userid']."','".$campaigndetails['campaignid']."','".$messagespostedthistime." mensaje(s) publicados de la campa&ntilde;a ".$campaigndetails['campaignname'].". Terminado.','".date('Y-m-d H:i:s')."')");
		
	
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
		mysql_query("INSERT INTO fbshare_logs VALUES ('','".$campaigndetails['userid']."','".$campaigndetails['campaignid']."','Comienza a publicar la campa&ntilde;a ".$campaigndetails['campaignname']."','".date('Y-m-d H:i:s')."')");
		
		$newtotalrun=$campaigndetails['howmanytimesthecampaignrun']+1;
		mysql_query("UPDATE fbshare_campaigns SET howmanytimesthecampaignrun='".$newtotalrun."' WHERE campaignid='".$campaigndetails['campaignid']."' ");
	
		//post messages
$max_posts_per_cron_run=$campaigndetails['totalmessagespostedinthiscampaign']-$campaigndetails['posted_temp'];

mysql_query("UPDATE fbshare_campaigns SET is_campaign_started='1' WHERE campaignid='".$campaigndetails['campaignid']."' ");

//$messagespostedthistime=update_status($campaignid, $campaigndetails['userid'], $campaigndetails['messagespostedon'],$campaigndetails['howtopostmessages'],$max_posts_per_cron_run,$campaigndetails['campaign_repeat_type'],$topostfbaccountid);

$messagespostedthistime=update_status($campaignid, $campaigndetails['userid'], $campaigndetails['messagespostedon'],$campaigndetails['howtopostmessages'],1,$campaigndetails['campaign_repeat_type'],$topostfbaccountid);
		//end post messages
		
		///update logs
		mysql_query("INSERT INTO fbshare_logs VALUES ('','".$campaigndetails['userid']."','".$campaigndetails['campaignid']."','".$messagespostedthistime." mensaje(s) publicados de la campa&ntilde;a ".$campaigndetails['campaignname'].". Terminado.','".date('Y-m-d H:i:s')."')");
		
	
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

$rescampaigns=mysql_query("SELECT * FROM fbshare_campaigns WHERE isgroup=1 AND campaign_enabled=1 AND is_campaign_finished=0 AND messagespostedon!=0 AND campaign_run=5 AND campaign_run_month_day='".$todaydate_monthnr."' AND DATE(campaign_last_time_run)!='".$todaydate."' ORDER BY campaign_last_time_run ASC");

	$total_pending_campaigns=mysql_num_rows($rescampaigns);
		
		if(($total_pending_campaigns>0) && ($_SESSION['posted_in_this_cron_run']<$cron_nr_posts)) //if this type of campaign exists and limit/cron not exceeded
		{ 
		$campaigndetails=mysql_fetch_array($rescampaigns);
		$campaignid=$campaigndetails['campaignid'];
		$topostfbaccountid=$campaigndetails['accountid'];
		
		///cron log
		echo("Run campaign: ".$campaigndetails['campaignname'].": 1 message in the list, this day of the month.");
		
		///update logs
		mysql_query("INSERT INTO fbshare_logs VALUES ('','".$campaigndetails['userid']."','".$campaigndetails['campaignid']."','Comienza a publicar la campa&ntilde;a ".$campaigndetails['campaignname']."','".date('Y-m-d H:i:s')."')");
		
		$newtotalrun=$campaigndetails['howmanytimesthecampaignrun']+1;
		mysql_query("UPDATE fbshare_campaigns SET howmanytimesthecampaignrun='".$newtotalrun."' WHERE campaignid='".$campaigndetails['campaignid']."' ");
	
		//post messages
$max_posts_per_cron_run=$campaigndetails['totalmessagespostedinthiscampaign']-$campaigndetails['posted_temp'];

mysql_query("UPDATE fbshare_campaigns SET is_campaign_started='1' WHERE campaignid='".$campaigndetails['campaignid']."' ");

//$messagespostedthistime=update_status($campaignid, $campaigndetails['userid'], $campaigndetails['messagespostedon'],$campaigndetails['howtopostmessages'],$max_posts_per_cron_run,$campaigndetails['campaign_repeat_type'],$topostfbaccountid);

$messagespostedthistime=update_status($campaignid, $campaigndetails['userid'], $campaigndetails['messagespostedon'],$campaigndetails['howtopostmessages'],1,$campaigndetails['campaign_repeat_type'],$topostfbaccountid);
		//end post messages
		
		///update logs
		mysql_query("INSERT INTO fbshare_logs VALUES ('','".$campaigndetails['userid']."','".$campaigndetails['campaignid']."','".$messagespostedthistime." mensaje(s) publicados de la campa&ntilde;a ".$campaigndetails['campaignname'].". Teminado.','".date('Y-m-d H:i:s')."')");
		
	
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
		mysql_query("INSERT INTO fbshare_logs VALUES ('','".$campaigndetails['userid']."','".$campaigndetails['campaignid']."','Comienza a publicar la campa&ntilde;a ".$campaigndetails['campaignname']."','".date('Y-m-d H:i:s')."')");
				
		$newtotalrun=$campaigndetails['howmanytimesthecampaignrun']+1;
		mysql_query("UPDATE fbshare_campaigns SET howmanytimesthecampaignrun='".$newtotalrun."' WHERE campaignid='".$campaigndetails['campaignid']."' ");
	
		//post messages
$max_posts_per_cron_run=$campaigndetails['totalmessagespostedinthiscampaign']-$campaigndetails['posted_temp'];

mysql_query("UPDATE fbshare_campaigns SET is_campaign_started='1' WHERE campaignid='".$campaigndetails['campaignid']."' ");


$messagespostedthistime=update_status($campaignid, $campaigndetails['userid'], $campaigndetails['messagespostedon'],$campaigndetails['howtopostmessages'],$max_posts_per_cron_run,$campaigndetails['campaign_repeat_type'],$topostfbaccountid);
		//end post messages
		
		///update logs
		mysql_query("INSERT INTO fbshare_logs VALUES ('','".$campaigndetails['userid']."','".$campaigndetails['campaignid']."','".$messagespostedthistime." mensaje(s) publicados de la campa&ntilde;a ".$campaigndetails['campaignname'].". Terminado.','".date('Y-m-d H:i:s')."')");
		
	
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
    $Mail->AddReplyTo($cron_send_notifications_to,"Administrador PGF");
	
	$emailcontent2="<b>La Campa&ntilde;a </b>".$email_campaign_name." ha terminado.<br><br>Para detalles, Ingresa a tu panel de administrador.<br><br>----------------------------------------------------------<br>";
	
		$emailbody='
		<html>
		<body>'.$emailcontent2.'<br><br>
		</body>
		</html>
		';
		 $Mail->MsgHTML($emailbody);
		 $Mail->Subject  = "Campana de PGF completada!";
		 $Mail->AddAddress($cron_send_notifications_to, "Publicador PGF");
		 $Mail->From = trim($cron_send_notifications_to);
	     $Mail->FromName = trim("Publicador PGF");
		 $Mail->Send();
		 $Mail->ClearAddresses();
		 echo("<br>Campa&ntilde;a terminada. Email enviado al administrador.");
	
	
}

} //end if at least one campaign in DB


?>
