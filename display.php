<?php
	require 'fb/facebook.php';
	$fbconfig['appUrl'] = "https://apps.facebook.com//";
	// Create An instance of our Facebook Application.
	$facebook = new Facebook(array(
		'appId'  => '',
		'secret' => '',
		'cookies' => 'true',
	));
	// Get the app User ID
	$user = $facebook->getUser();
	
	if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}

// Login or logout url will be needed depending on current user state.
if ($user) {
  $logoutUrl = $facebook->getLogoutUrl();
} else {
  $loginUrl = $facebook->getLoginUrl();
}

	$strFriends = "";
	$friends = $facebook->api("/me/friends");
	foreach ($friends['data'] as $friend)
    {
        $strFriends.= "'".$friend['id']."',";
    }
	
	$strFriends = substr($strFriends, 0, strlen($strFriends) - 1);
	
	$t='user_photos/';
	$username="dealerlogin";
	$password="p@ssw0rd1";
	$database="facebook_nagrada";
	$base_url = 'https://set.com.mk';
	$path = substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], '/'));
	$filedir = $base_url.$path.'/'.$t;

	mysql_connect('146.255.82.16',$username,$password);
	@mysql_select_db($database) or die( "Unable to select database");
	mysql_set_charset('UTF8');
	
	if(isset($_POST['pageId']) && !empty($_POST['pageId'])){
		   $id=$_POST['pageId'];
		}else{
		   $id='0';
	}
	
	$button='1';
	if(isset($_POST['buttonId']) && !empty($_POST['buttonId'])){
	   $button=$_POST['buttonId'];
	}
	
	$pageLimit=10*$id;
	
	if ($button == '1') {
		$query="SELECT id, userid, photo, likes, pcaption, pdescription, dateposted 
		FROM photos_likes_user 
		ORDER BY likes DESC 
		LIMIT $pageLimit,10";
	}
	if ($button == '2') {
		$query="SELECT id, userid, photo, likes, pcaption, pdescription, dateposted 
		FROM photos_likes_user 
		WHERE userid = '".$user."'
		ORDER BY likes DESC 
		LIMIT $pageLimit,10";
	}
	if ($button == '3') {
		$query="SELECT id, userid, photo, likes, pcaption, pdescription, dateposted 
		FROM photos_likes_user 
		WHERE userid IN (".$strFriends.")
		ORDER BY likes DESC 
		LIMIT $pageLimit,10";
	}
	
	$result=mysql_query($query);
	$count=mysql_num_rows($result);
	$HTML='';
	if($count > 0){
		while($row=mysql_fetch_array($result)){
			$id=$row["id"];
			$user=$row["userid"];
			$username = $facebook->api("/$user");
			$current_user=$username['name'];
			$current_link=$username['link'];
			$photo=$row["photo"];
			$likes=$row["likes"];
			$caption=$row["pcaption"];
			$description=$row["pdescription"];
			$date=$row["dateposted"];
			$HTML.='<div class="contestant clearfix">';
			$HTML.='<div class="rating"><a href="'.$current_link.'" target="blank"><img class="profileimage" src="//graph.facebook.com/'.$user.'/picture" alt="setec,facebook,user"/><p id="currentuser">'.$current_user.'</p></a><h3>Гласови</h3><p>'.$likes.'</p></div>';
			$HTML.='<div class="pic"><figure>
				<figcaption class="firstcaption">'.$caption.'</figcaption>
				<a id="show-panel" href="#"><img src="'.$t.$photo.'"/></a>
				<figcaption class="secondcaption">'.$description.'</figcaption>
			</figure></div>';
			
			$HTML.='<div class="cbutton">
				<div class="button" onclick="vote(this)" id="vote_'.$id.'"><a>Гласај</a></div>
				<div class="button" onclick="publishStream(this)" id="picture_'.$photo.'"><a>Сподели на ѕид</a></div>
				<div class="button" onclick="sendRequestViaMultiFriendSelector(this)" id="picture2_'.$photo.'"><a>Сподели со пријатели</a></div>
			</div>';
			$HTML.='</div><span class="line"></span>';
		}
	}
	echo $HTML;
?>