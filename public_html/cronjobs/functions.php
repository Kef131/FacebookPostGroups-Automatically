<?php 
/*general cron functions*/
function get_url_from_string($url)
{
$regex = '$\b(https?|ftp|file)://[-A-Z0-9+&@#/%?=~_|!:,.;]*[-A-Z0-9+&@#/%=~_|]$i';
preg_match_all($regex, $url, $result, PREG_PATTERN_ORDER);
$a = trim($result[0][0]);

return $a;
}
function get_time_difference( $start, $end )
{
    $uts['start']      =    strtotime( $start );
    $uts['end']        =    strtotime( $end );
    if( $uts['start']!==-1 && $uts['end']!==-1 )
    {
        if( $uts['end'] >= $uts['start'] )
        {
            $diff    =    $uts['end'] - $uts['start'];
            if( $days=intval((floor($diff/86400))) )
                $diff = $diff % 86400;
            if( $hours=intval((floor($diff/3600))) )
                $diff = $diff % 3600;
            if( $minutes=intval((floor($diff/60))) )
                $diff = $diff % 60;
            $diff    =    intval( $diff );            
            return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
        }
        else
        {
            return( array('days'=>'0', 'hours'=>'0', 'minutes'=>'0', 'seconds'=>'0') );
        }
    }
    else
    {
        trigger_error( "Invalid date/time data detected", E_USER_WARNING );
    }
    return( false );
}

///spintax class
class spintax
{
    public function process($text)
    {
        return preg_replace_callback(
            '/\{(((?>[^\{\}]+)|(?R))*)\}/x',
            array($this, 'replace'),
            $text
        );
    }

    public function replace($text)
    {
        $text = $this->process($text[1]);
        $parts = explode('|', $text);
        return $parts[array_rand($parts)];
    }
}
//FB class
class fb
{
    private $_api_key = NULL;
    private $_secret_key = NULL;
    private $_login = "Login";
    private $_logout = "Logout";
    private $_user = NULL;
    private $_user_data = NULL;
    private $fb_sdk = FALSE;

    private $scope = "read_stream,publish_actions,publish_stream,status_update,user_groups,manage_pages,user_about_me,friends_groups,photo_upload";
    
    public function __construct($api_k, $api_sk)
    {
        $this->_api_key = $api_k;
        $this->_secret_key = $api_sk;
        
        $this->fb_sdk = new Facebook(array(
            'appId' => $this->_api_key,
            'secret' => $this->_secret_key,
        ));
        
        //$this->_user = $this->fb_sdk->getUser();
        $this->initilize();
    }
    
    private function initilize()
    {
        if($this->_user){
            try {
                $this->_user_data = $this->fb_sdk->api('/me');
            } catch (FacebookApiException $e) {
                error_log($e);
                $this->_user = null;
            }
        }
    }
    
    public function set_button_text($login=null, $logout=null)
    {
        $this->_login = $login;
        $this->_logout = $logout;
    }
    
    public function logoutUser(){
        $this->fb_sdk->destroySession();
    }
    
    public function get_url($array=false)
    {
        if($array==true){
            $ret = array();
            if($this->_user){
                $ret['url'] = $this->fb_sdk->getLogoutUrl();
                $ret['text'] = $this->_logout;
            } else {
                $ret['url'] = $this->fb_sdk->getLoginUrl(array('scope'=>$this->scope));
                $ret['text'] = $this->_login;
            }
            return $ret;
        } else {
            if($this->_user){
                return $this->fb_sdk->getLogoutUrl();
            } else {
                return $this->fb_sdk->getLoginUrl(array('scope'=>$this->scope));
            }
        }
    }
	
	  public function get_auth_url($array=false,$app_user_id)
    {
        if($array==true){
            $ret = array();
            if($this->_user){
                $ret['url'] = $this->fb_sdk->getLogoutUrl();
                $ret['text'] = $this->_logout;
            } else {
                $ret['url'] = $this->fb_sdk->getAuthLoginUrl(array('scope'=>$this->scope),$app_user_id);
                $ret['text'] = $this->_login;
            }
            return $ret;
        } else {
            if($this->_user){
                return $this->fb_sdk->getLogoutUrl();
            } else {
                return $this->fb_sdk->getAuthLoginUrl(array('scope'=>$this->scope),$app_user_id);
            }
        }
    }
	
	 public function get_l_url()
    {
    
            $ret = array();
            if($this->_user)
			{
                $ret['url'] = $this->fb_sdk->getLogoutUrl();
                $ret['text'] = $this->_logout;
                return $ret;
        	}
    }

    public function getMyLoginURL($redirect){
        return $this->fb_sdk->getLoginUrl(array('scope'=>$this->scope),$redirect);
    }
    
    public function getMyData(){
        
        try{
            $ret = $this->fb_sdk->api('/me');
            return $ret;
        } catch (FacebookApiException $e) {
            return null;
        }
        
    }
    
    public function getUserData($what=null)
    {
        if($what!=null && !is_array($what))
        {
            $d = array();
            foreach ($this->_user_data as $key => $value) {
                if($key == $what){
                    return $value;
                }
            }
        } elseif($what!=null && is_array($what)) {
            $data = array();
            foreach($what as $val){
                if($this->_user_data[$val]){
                    if($val=="location") {
                        $data[$val] = $this->_user_data[$val]["name"];
                    } else {
                        $data[$val] = $this->_user_data[$val];
                    }
                }
            }
            return $data;
        } else {
            return $this->_user_data;
        }
    }
    
    public function LoginStatus()
    {
        return $this->fb_sdk->getAccessToken();
    }
	
	 public function uploadOnPage_old($pID, $at, $data, $link)
    {
        if( !empty($at) ) 
		{
            $args = array(
                'access_token'  => $at, 
				'url'  => $link,
				'message'=> $data
            );
            try{
                $post_id = $this->fb_sdk->api("/".$pID."/photos","post",$args);
				return "Message posted!";
            } 
			catch (FacebookApiException $e) 
			{
				if( trim($e)=='OAuthException: It looks like you were misusing this feature by going too fast. You’ve been blocked from using it.  Learn more about blocks in the Help Center.')
				{
					return 0;
					exit();
				}
           		//post only image and add comment
				$args = array(
                'access_token'  => $at, 
				'url'  => $link);
				try
				{
				$post_id = $this->fb_sdk->api("/".$pID."/photos","post",$args);
				
					if($post_id['id']!='' && $link!='')
					{
						///get post id and comment on photo 'message'=> $data
						$args_comment = array
						(
						'access_token'  => $at,
						'message'=> $data."\n".$link
						);
					
					$commentstatus=$this->fb_sdk->api("/".$post_id['id']."/comments","post",$args_comment);
					}
				
				return "Message posted!";
				}
				catch (FacebookApiException $e)
				{
				return $e;
				}
            }
        } 
		
		else 
		{
			return "Message or access token are empty!";
        }
    }

	
 public function uploadOnPage($pID, $at, $data, $link)
    { 
        if(!empty($at)) 
		{
            //if posted once
			if(!isset($_SESSION['lastphotoid']))
			{
				$args = array
				(
					'access_token'  => $at, 
					'url'  => $link,
					'message'=> $data
				);
				try{
						$post_id = $this->fb_sdk->api("/".$pID."/photos","post",$args);
						if($post_id['id']!="")
						{
						$_SESSION['lastphotoid']=$post_id['id'];
						}
					return "Message posted!";
					
				} 
				catch (FacebookApiException $e) 
				{
					return $e;
				}
		}///end posted once
        
		if(isset($_SESSION['lastphotoid']))//start posting links to posted photo
		{
			//post with link
			$new_status=str_replace($link,"",$data);
			
			$args = array(
			'access_token'  => $at, 
			'link'  => "https://www.facebook.com/photo.php?fbid=".$_SESSION['lastphotoid'],
			'message'=> $new_status);	
           	try 
		    {
                $post_id = $this->fb_sdk->api("/".$pID."/feed","post",$args);
				return "Message posted!";
            } 
			catch (FacebookApiException $e)
			{
                return $e;
            }
		}//end posting link
		
		} 
		else 
		{
			return "Message or access token are empty!";
        }
    }

  public function postOnPage($pID, $at,$data, $link)
    {
        if( !empty($at) ) {
            $args = array(
                'access_token'  => $at, 
				'link'  => $link, 
				'name' =>'',
				'caption'=>'',
				'description'=>'',
                'message'=> $data
            );
            try{
                $post_id = $this->fb_sdk->api("/".$pID."/feed","post",$args);
				return "Message posted!";
            } catch (FacebookApiException $e) {
                return $e;
            }
        } else {
			return "Message or access token are empty!";
        }
    }
	
  public function postOnGroup_Batch($at,$batcharray)
    {
        if( !empty($at) ) {
            $args = array(
                'access_token'  => $at, 
				'batch'  => $batcharray
            );
            try{
                $post_id = $this->fb_sdk->api("https://graph.facebook.com/","POST",$args);
				return "Message posted!";
            } catch (FacebookApiException $e) {
                return $e;
            }
        } else {
			return "Message or access token are empty!";
        }
    }
 public function uploadOnPage_first_Batch($pID, $at, $data, $link)
    {
        if(!empty($at)) 
		{
            //if posted once
			if(!isset($_SESSION['lastphotoid']))
			{
				$args = array
				(
					'access_token'  => $at, 
					'url'  => $link,
					'message'=> $data
					
				);
				try{
						$post_id = $this->fb_sdk->api("/".$pID."/photos","post",$args);
						if($post_id['id']!="")
						{
						$_SESSION['lastphotoid']=$post_id['id'];
						}
					return "Message posted!";
					
				} 
				catch (FacebookApiException $e) 
				{
					return $e;
				}
		}///end posted once
        
		
		} 
		else 
		{
			return "Message or access token are empty!";
        }
    }
	
 public function uploadOnPage_Batch($at,$batcharray)
    {
           if( !empty($at) ) {
            $args = array(
                'access_token'  => $at, 
				'batch'  => $batcharray
            );
            try{
                $post_id = $this->fb_sdk->api("https://graph.facebook.com/","POST",$args);
				return "Message posted!";
				
            } catch (FacebookApiException $e) {
                return $e;
            }
        } else {
			return "Message or access token are empty!";
        }
    }	
	
	
  public function postOnGroup($pID, $at, $data, $link, $mod)
    { 
        
		if( !empty($at) ) //if empty AT
		{
			$postnr=$mod%2;
			
				if($postnr==0)
				{      
						//post with link
						$args = array(
						'access_token'  => $at, 
						'link'  => $link,
						'message'=> $data);
				}
				else
				{      
						 ///post without link
						 $args = array(
						'access_token'  => $at, 
						'message'=> $data);
				}
				
           	try 
		    {
                $post_id = $this->fb_sdk->api("/".$pID."/feed","post",$args);
				return "Message posted!";
            } 
			catch (FacebookApiException $e)
			{
                return $e;
            }
      
	  } //end empty AT
		else 
		{
			return "Message or access token are empty!";
        }
    }

	public function get_last_comment_id($pID, $at)
	{
		if(!empty($at)) 
		{
                $urlfound=false;
				//$pagesarray=get_remote_file('https://graph.facebook.com/'.$pID.'/feed?access_token='.$at);
			    $pagesarray=file_get_contents('https://graph.facebook.com/'.$pID.'/feed?access_token='.$at);
				$pagesarray=json_decode($pagesarray,1);
				
				$commentid=trim($pagesarray['data'][0]['id']);
				
				if(trim($commentid)!="") {$urlfound=true;}
				
				if($urlfound==false)
				{
				return "Invalid comment id";	
				}
				if($urlfound==true)
				{
				return $commentid;	
				}	
        } 
		else 
		{
			return "No token";
        }
	}
	
	public function get_my_last_comment_id($pID,$userid,$at)
	{
		if(!empty($at)) 
		{
                $urlfound=false;
				//$pagesarray=get_remote_file('https://graph.facebook.com/'.$pID.'/feed?access_token='.$at);
			    $pagesarray=file_get_contents('https://graph.facebook.com/'.$pID.'/feed?access_token='.$at);
				$pagesarray=json_decode($pagesarray,1);
					//
						foreach ($pagesarray['data'] as $group) 
						{
						$poster_id = $group['from']['id'];
							if(trim($poster_id)==trim($userid)) //ID OK
							{
								if(trim($group['id'])!="")
								{
								$commentid = trim($group['id']);
								$urlfound=true;
								}
							}
						
						}
					//
				
				if(trim($commentid)!="") {$urlfound=true;}
				
				if($urlfound==false)
				{
				return "Invalid comment id";	
				}
				if($urlfound==true)
				{
				return $commentid;	
				}	
        } 
		else 
		{
			return "No token";
        }
	}

 public function commentOnGroup($pID, $at, $data, $link)
    { 
        if( !empty($at) ) {

                $args_comment = array(
					'access_token'  => $at,
					'message'=> $data
				);
			
            try{
				if($pID!="")
				{
                $post_id = $this->fb_sdk->api("/".$pID."/comments","post",$args_comment);
				return "Message posted!";
				}
				else
				{
				return "Invalid post ID.";
				}
            } catch (FacebookApiException $e) {
                return $e;
            }
        } else {
			return "Message or access token are empty!";
        }
    }

 public function postfbvideoonpage($pID, $at, $userid, $videoid, $videoimage, $message)
    {
        if( !empty($at) ) {
			
            $args = array(
                'access_token'  => $at, 
				'link'  => 'https://www.facebook.com/'.$userid.'/posts/'.$videoid, 
				'name' =>'',
				'caption'=>'',
				'description'=>$message,
                'message'=> $message
            );
            try{
                $post_id = $this->fb_sdk->api("/".$pID."/feed","post",$args);
				return "Message posted!";
            } catch (FacebookApiException $e) {
                return $e;
            }
        } else {
			return "Message or access token are empty!";
        }
    }
	
public function get_video_image_url($at,$pid)
    {   
        if(!empty($at)) 
		{
                $urlfound=false;
				$videopicture="";
				//$pagesarray=get_remote_file('https://graph.facebook.com/'.$pid.'?access_token='.$at);
			    $pagesarray=file_get_contents('https://graph.facebook.com/'.$pid.'?access_token='.$at);
				$pagesarray=json_decode($pagesarray,1);
				
				$videopicture=trim($pagesarray['picture']);
				
				if($videopicture=="") $videopicture=trim($pagesarray['format'][2]['picture']);
				if($videopicture=="") {$videopicture=trim($pagesarray['format'][1]['picture']);} 
				if($videopicture=="") {$videopicture=trim($pagesarray['format'][0]['picture']);}
				
				if(trim($videopicture)!="") {$urlfound=true;}
				
				if($urlfound==false)
				{
				return "Wrong video URL";	
				}
				if($urlfound==true)
				{
				return $videopicture;	
				}	
        } 
		else 
		{
			return "No token";
        }
    }
	
public function get_video_image_userid($at,$pid)
    {   
        if(!empty($at)) 
		{
                $useridfound=false;
				//$pagesarray=get_remote_file('https://graph.facebook.com/'.$pid.'?access_token='.$at);
				$pagesarray=file_get_contents('https://graph.facebook.com/'.$pid.'?access_token='.$at);
				$pagesarray=json_decode($pagesarray,1);
				$videouserid=$pagesarray['from']['id'];
				if(trim($videouserid)!="") {$useridfound=true;}
				
				if($useridfound==false)
				{
				return "Wrong user id";	
				}
				if($useridfound==true)
				{
				return $videouserid;	
				}	
        } 
		else 
		{
			return "No token";
        }
    }
	
	public function getusergroups($at)
    {
        if( !empty($at) ) 
		{
         
            try{
                $groupsarray = $this->fb_sdk->api('/me/groups', 'GET', array( 'access_token=' => $at ));
				return $groupsarray;
            } catch (FacebookApiException $e) {
                return 0;
            }
        } else {
			return 0;
        }
    }
	
     public function get_page_token($at,$pid)
    {   
        if(!empty($at)) 
		{
                $pagenotfound=true;
				//$pagesarray = $this->fb_sdk->api('/me/accounts', 'GET', array('access_token=' => $at ));
				//$pagesarray=get_remote_file('https://graph.facebook.com/me/accounts?access_token='.$at);
				$pagesarray=file_get_contents('https://graph.facebook.com/me/accounts?access_token='.$at);
                $pagesarray=json_decode($pagesarray,1);
				
				foreach ($pagesarray['data'] as $page) 
				{
				$page_id = $page['id'];
					if(trim($page_id)==trim($pid)) //ID OK
					{
						$page_access_token = trim($page['access_token']);
						$pagenotfound=false;
					}
				
				}
				
				if($pagenotfound==true)
				{
				return "Wrong page id";	
				}
				if($pagenotfound==false)
				{
				return $page_access_token;	
				}
			
			
        } 
		else 
		{
			return "No token";
        }
    }
	

    public function renewAccessToken($at){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/oauth/access_token?client_id=".$this->_api_key."&client_secret=".$this->_secret_key."&grant_type=fb_exchange_token&fb_exchange_token=".$at);
		
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $page=curl_exec($ch);
        curl_close($ch);
    }
	
public function postOnPage_test2($pID, $at,$data, $link)
    {
        if( !empty($at) ) {
            $args = array(
            'access_token'  => $at, 
			'link'  => 'http://webrenta.me', 
			'name' =>'Name here',
			'caption'=>'Caption here',
			'description'=>'Description here',
			'object_attachment'=>'http://webrenta.me/images/testfb.png',
			'message'=> 'Test message to go FBS'
            );
            try{
                $post_id = $this->fb_sdk->api("/".$pID."/feed","post",$args);
				return "Message posted!";
            } catch (FacebookApiException $e) {
                return $e;
            }
        } else {
			return "Message or access token are empty!";
        }
    }
	
}


?>