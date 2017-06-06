<?php
$savefolder = 'user_photos';
$max_size = 10000;
$name=$_POST['pcaption'];
$myfile=$_FILES['myfile']['name'];
$pdescription=$_POST['pdescription'];
$allowtype = array('bmp', 'gif', 'jpg', 'jpeg', 'gif', 'png', 'Jpg', 'Jpeg');
$rezultat = '';

require 'fb/facebook.php';
$facebook = new Facebook(array(
  'appId'  => '150783835081280',
  'secret' => '9ee5e541b947795cdb039b4da1e8bc8c',
));
$user = $facebook->getUser();
if ( $user != 0 ){
$setzirro = 0;
$timedb=date("Y-m-d H:i:s");

if (isset ($_FILES['myfile'])) {
  // checks to have the allowed extension
  $type = end(explode(".", strtolower($_FILES['myfile']['name'])));
  if (in_array($type, $allowtype)) {
    // check its size
	if ($_FILES['myfile']['size']<=$max_size*1000) {
      // if no errors
      if ($_FILES['myfile']['error'] == 0) {
        $thefile = $savefolder . "/" . $_FILES['myfile']['name'];
        // if the file can`t be uploaded, return a message
        if (!move_uploaded_file ($_FILES['myfile']['tmp_name'], $thefile)) {
          $rezultat = 'Фотографијата не можеше да биде качена, ве молиме пробајте повторно(прочитајте ги правилата за качување на фајлови)';
        }
        else { 
          // Return the img tag with uploaded image.
          $rezultat = '<p class="sucess">Фотографијата е успешно качена, притиснете на табот Гласај за да го дадете вашиот прв глас.</p><img src="'.$thefile.'" width="400"/>';
		  echo $user;
          
		  //connect to mysql and write data
		  mysql_connect("146.255.82.16", "dealerlogin", "p@ssw0rd1") or die(mysql_error()) ;
		  mysql_query("SET NAMES UTF8");
		  mysql_select_db("facebook_nagrada") or die(mysql_error()) ;

		  //Writes the information to the database
		  mysql_query("INSERT INTO photos_likes_user (userid,pcaption,photo,pdescription,dateposted,likes)
		  VALUES ('$user','$name', '$myfile', '$pdescription','$timedb','$setzirro')") ;

        }
      }
    }
	else { $rezultat = 'The file <b>'. $_FILES['myfile']['name']. '</b> Фотографијата ја надминува границата за максимална големина. <i>'. $max_size. 'KB</i>'; }
  }
  else { $rezultat = 'Фотографијата <b>'. $_FILES['myfile']['name']. '</b> не е во дозволен формат.'; }
}
}
else {$rezultat = '<p class="refreshp">Фотографијата не може да биде качена, ве молиме освежете ја страницата!</p>'; }

// encode with 'urlencode()' the $rezultat and return it in 'onload', inside a BODY tag
$rezultat = urlencode($rezultat);
echo '<body onload="parent.doneloading(\''.$rezultat.'\')"></body>';
?>