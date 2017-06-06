<?php
	
	$username="";
	$password="";
	$database="";
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
	mysql_connect('',$username,$password);
	@mysql_select_db($database) or die( "Unable to select database");
	mysql_set_charset('UTF8');
	
	if(isset($_POST['itemId']) && !empty($_POST['itemId'])){
		$id=$_POST['itemId'];
	}else{
		$id='0';
	}
	
	if ($id != '0') {
		$HTML='';
		$voting_query="SELECT *
		FROM votingusers
		WHERE item=$id AND user='$user' AND DATE_ADD(votingdate, INTERVAL 1 DAY) > NOW()";
		$voting_result=mysql_query($voting_query);
		$voting_count=mysql_num_rows($voting_result);
		if ($voting_count == 0) {
			$update_query="UPDATE photos_likes_user SET likes = likes + 1
			WHERE id=$id";
			mysql_query($update_query);
			
			$vote_query="INSERT INTO votingusers
			VALUES (NOW(), '$user', $id)";
			mysql_query($vote_query);
		}
		
		$query="SELECT likes
		FROM photos_likes_user
		WHERE id=$id";
		$result=mysql_query($query);
		$count=mysql_num_rows($result);
		
		if($count > 0){
			$row=mysql_fetch_array($result);
			$likes=$row["likes"];
			$HTML.=$likes;
		}
		
		echo $HTML;
	}
?>