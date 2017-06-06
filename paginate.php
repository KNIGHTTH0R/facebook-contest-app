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

	if ($user !=0 ){ 
	$strFriends = "";
	$friends = $facebook->api("/me/friends");
	foreach ($friends['data'] as $friend)
    {
        $strFriends.= "'".$friend['id']."',";
    }
	
	$strFriends = substr($strFriends, 0, strlen($strFriends) - 1);
	
	$username="dealerlogin";
	$password="p@ssw0rd1";
	$database="facebook_nagrada";
	mysql_connect('146.255.82.16',$username,$password);
	@mysql_select_db($database) or die( "Unable to select database");
	mysql_set_charset('UTF8');
	
	$button='1';
	if(isset($_POST['buttonId']) && !empty($_POST['buttonId'])){
	   $button=$_POST['buttonId'];
	}
	
	if ($button == '1') {
		$query="SELECT id, userid, photo, likes, pcaption, pdescription, dateposted 
		FROM photos_likes_user 
		ORDER BY likes DESC";
	}
	if ($button == '2') {
		$query="SELECT id, userid, photo, likes, pcaption, pdescription, dateposted 
		FROM photos_likes_user 
		WHERE userid = '".$user."'
		ORDER BY likes DESC";
	}
	if ($button == '3') {
		$query="SELECT id, userid, photo, likes, pcaption, pdescription, dateposted 
		FROM photos_likes_user 
		WHERE userid IN (".$strFriends.")
		ORDER BY likes DESC";
	}
	
	$result=mysql_query($query);
    $count=mysql_num_rows($result);

	function getPagination($count){
		$paginationCount= floor($count / 10);

		$paginationModCount= $count % 10;
		if(!empty($paginationModCount)){
			$paginationCount++;
		}

		return $paginationCount;
	}
	
	$HTML='';
	if($count > 0){
		$paginationCount=getPagination($count);
		$HTML.='<ul class="pagess">';
		for($i=0; $i<$paginationCount; $i++) {
			$HTML.='<li id="'.$i.'_no" class="link">';
			$HTML.='<a href="#" onclick="changePagination(\''.$i.'\', \''.$button.'\');scrollWindow();">'.($i+1).'</a></li>';
		}
		$HTML.='</ul>';
	}
	else {
		if ($button == '2') {
			$HTML='<p class="refreshp">Немате ваша фотографија, кликнете на табот Пријави се и следете ги упатствата.</p>';
		}
		if ($button == '3') {
			$HTML='<p class="refreshp">Во моментов немате пријатели што учествуваат во Сетек се од техника наградениот натпревар, притиснете на копчето <b>Сите фотографии</b> за да ги погледнете активните учесници </p>';
		}
	}
	}
	else {$HTML='error';}
	echo $HTML;
?>