<?php 
require 'fb/facebook.php';
$fbconfig['appUrl'] = "https://apps.facebook.com//";
// Create An instance of our Facebook Application.
$facebook = new Facebook(array(
  'appId'  => '',
  'secret' => '',
  'cookies' => '',
));
// Get the app User ID
$user = $facebook->getUser();
if ($user) {
     try {
      // If the user has been authenticated then proceed
      $user_profile = $facebook->api('/me');
     } catch (FacebookApiException $e) {
      error_log($e);
      $user = null;
     }
}

// If the user is authenticated then generate the variable for the logout URL
if ($user) {
  $logoutUrl = $facebook->getLogoutUrl();
?>
<!DOCTYPE HTML>
<html lang="mk">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Сетек се за техника Награден натпревар</title>
<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/functions.js" type="text/javascript"></script>
<script src="js/geturl.js" type="text/javascript"></script>
<script src="js/myjava.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="css/style.css">

</head>
<body>
<div id="fb-root"></div>
<script src="https://connect.facebook.net/en_US/all.js" type="text/javascript"></script>
<script>
window.fbAsyncInit=function(){


  	FB.init({
	appId : '150783835081280',
	status : true, // check login status
	cookie : true, // enable cookies to allow the server to access the session
	xfbml : true // parse XFBML
	});


     FB.Canvas.setAutoGrow(7);
};

function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=150783835081280";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

function vote(item) {
	var votes = $(item).parent().siblings(".rating").children("p").html();
	$itemId = $(item).attr("id").substring(5);
	var dataString = 'itemId='+$itemId;
	$.ajax({
		type: "POST",
		url: "voting.php",
		data: dataString,
		cache: false,
		success: function(result){
			if (votes == result) {
				alert("Веќе имате гласано, можете да гласате повторно по истекување на 24часа од првиот глас !");
			}
			else {
				$(item).parent().siblings(".rating").children("p").html(result);
			}
		}
	});
}
</script>

<script language="javascript" type="text/javascript">
function publishStream(item){
		var b = 'www.set.com.mk/setnagradnaigra/user_photos/';
		var photo = $(item).attr("id").substring(8);
		 
        // calling the API ...
        var obj = {
          method: 'feed',
          redirect_uri: 'https://apps.facebook.com/setnagrada/',
          link:'https://apps.facebook.com/setnagrada/index.php',
          picture: b + photo,
          name: 'Сетек се од техника наградува!',
          caption: 'Гласајте за вашиот фаворит.',
          description: 'Посетете го нашиот награден натпревар и вие можете да влезете во трка за вредна награда.'
        };
		
        function callback(response) {
          document.getElementById('msg').innerHTML = "Post ID: " + response['post_id'];
        }
		
        FB.ui(obj, callback);
      }
		function sendRequestViaMultiFriendSelector(item) {
		var b = 'www.set.com.mk/setnagradnaigra/user_photos/';
		var photo = $(item).attr("id").substring(9);
        FB.ui({method: 'apprequests',
		  message: 'Пријави се,гласај и освои една од многуте вредни награди',
          data: b + photo
        }, requestCallback);
	}
	function requestCallback(response) {
        // Handle callback here
        console.log(response);
      }
</script>

<?php 
		try {
			$likes = $facebook->api("/me/likes/297337543627963");
			if(!empty($likes['data'])) { ?>
				<div class="maincontent">
					<div class="logo"><a href="https://set.com.mk/setnagradnaigra"><img src="images/header.png" alt="set.com.mk"></a></div>
					<ul class="tabs">
						<li><a href="#tab1">Гласај</a></li>
						<li><a href="#tab2">Пријави се</a></li>
						<li><a href="#tab3">Правила</a></li>
						<li><a href="#tab4">За Сетек</a></li>
					</ul>
					<div class="tab_container">
						
						<div id="tab1" class="tab_content">
							<div id="topp">
								<div class="tabHeader" id="thtxt"> <?php echo $user_profile['name']; ?>, притисни на копчето гласај и направи го твојот избор</div>
								<img class="profileimage" name="" src="//graph.facebook.com/<?php echo $user; ?>/picture" width="50" height="50" alt="">
								<div class="button" id="button1" onclick="changeButton('1'); return false;">
								Сите фотографии
								</div>
								<div class="button" id="button2" onclick="changeButton('2'); return false;">
										Мои фотографии
								</div>
								<div class="button" id="button3" onclick="changeButton('3'); return false;">
										Фотографии од пријатели
								</div>
							</div>
							
							<div id="tab2pictures"></div>
							<div id="tab2pages"></div>
						
						<div id="pageData"></div>
						</div><!--End Tab 1 -->
						<div id="tab2" class="tab_content">
							<div class="wellcome">
								<h1>
									Добредојдовте на наградниот натпревар на Сетек се од техника
								</h1>
								
									<p>Првите 3 фотографии со најмногу гласа добиваат подароци!

									Гласајте за своите фаворити на секој 24 часа.
									
									<a href="http://www.set.mk" target="_blank">Сетек се од техника</a> ви посакува многу среќа!
									</p>
									<div class="linkset"><a href="http://www.set.mk" target="_blank">www.setek.mk</a></div>
								
							</div>
							
							<div class="tabHeader" id="zdr">Здраво <?php echo $user_profile['name'];?></div>
							<img class="profileimage" name="" src="//graph.facebook.com/<?php echo $user; ?>/picture" width="75" height="75" alt="setec,facebook,user">
							<p id="wp"><strong>Внеси ја твојата омилена фотографија превземена од интернет страницата на <a href="http://www.set.mk" target="_blank">Сетек се од техника</a> и учевствувај во нашиот награден натпревар.</strong></p>
							
							
							
							<div class="tabHeader" >Формулар за учество:</div>
							<div class="form">
								<div id="showimg"> </div>
								
								<form id="uploadform" action="upload_img.php" method="post" enctype="multipart/form-data" target="uploadframe" onsubmit="uploadimg(this); return false">
								
								  <h4 id="text1">Наслов на слика:</h4><input type="text" name="pcaption" id="inputtext1"><br>
								  <h4 id="text2">Опис на слика:</h4>
								  <textarea rows="10" cols="35" name="pdescription"  id="inputtextopis"></textarea></br>
								  <label for="myfile">Одбери слика:</label>
								  <input type="file" id="myfile" name="myfile" /></br>
								  <input type="submit" value="Качи на сервер" id="inputtext5" /></br>
								  <iframe id="uploadframe" name="uploadframe" src="upload_img.php" width="8" height="8" scrolling="no" frameborder="0"></iframe>
								</form>

								
								<span class="line"></span>
								<ul class="ulall">
									<li>Одете на официјалната интернет страна на <a href="http://www.set.mk" target="_blank">Сетек се од техника</a></li>
									<li>Превземете било која фотографија од наш производ</li>	
									<li>Прикачете ја за наградниот натпревар и споделете ја со пријателите за да добиете повеќе гласови</li>
								</ul>
							</div>
							
						</div><!--End Tab 2-->
						<div id="tab3" class="tab_content">
							<h3>Правила на натпреварот:</h3>
							<h2>Натпреварот ќе започне на 03.04.2013 во 14 часот и ќе заврши на 17.04.2013 во 14:00 часот.</h2>
							<p>За учество во наградниот натпревар на Сетек се од техника</p>
							<ul class="ulall">
							<li>Одете на официјалната интернет страна на <a href="http://www.set.mk" target="_blank">Сетек се од техника</a></li>
							<li>Превземете било која фотографија од наш производ</li>	
							<li>Прикачете ја за наградниот натпревар и споделете ја со пријателите за да добиете повеќе гласови</li>
							
							</ul>
							<p>Препорачуваме: При пријава за учество во натпреварот Сетек се од техника, веднаш гласајте и споделете ја вашата фотографија како би можеле подоцна полесно да ја најдете помеѓу многуте објавени фотографии во табот "Гласај".</p> 
							
							<h3>Награден фонд:</h3>
							<ul class="ulall">
								<li>Прво место: <b>Sony HD Камера</b></li>
								<li>Второ место:<b>Canon Принтер</b></li>
								<li>Трето место: <b>Bosh Сецко</b></li>
								<li>Маички</li>
							</ul>
								<h3>Правила за учество
							</h3>
							<ul class="ulall">
								<li>Право на учество имаат сите кои притиснале like на фејсбук страната на Сетек се од техника и се Македонски државјани.</li>
								<li>Учесник може да добие само една награда. Објавувајте една фотографија како би ги зголемиле шансите за победа. </li>
								<li>Можете да гласате за својот фаворит на секој 24 часа.</li>

								<li>Сите фотографии и коментари кои не се прилагодени на настанот, навреди и слично ќе бидат избришани! </li>

								<li>Прифатливи типови на слика се JPG, GIF и PNG. Слики поголеми од 1000 пиксели широки или високи ќе бидат намалени за да ги собере во полето, оригиналниот сооднос ќе биде сочуван.</li>

								<li>Редоследот на покажување на фотографиите на учесниците по бројот на гласови, почнувајќи од фотографијата со најголем број.</li>

								<li>Наградата може да ја подигне само facebook корисникот лично со документ за идентификација.   </li>

								<li>Наградите не се испраќаат по пошта, ќе мора да се подигнат во некој од салоните на Сетек се од техника.</li>

							</ul>
							
						</div><!--End Tab 3 -->
						<div id="tab4" class="tab_content">
						
							<h2>Профил за компанијата
							</h2>
							<img src="images/set.jpg"/>
							<p>
							Сетек е лидер во областа на ИТ, Аудио видео и бела техника на македонскиот пазар. Сетек е формиран во 1993 година, и во тоа време главна дејност беше само дистрибуција во ИТ сегментот. Сетек се од техника ја поседува најголемата дистрибутивна мрежа во Македонија која ја сочинуваат повеќе од 500 активни партнерски фирми – соработници и кооператори. Истовремено Сетек има и свој малопродажен ланец кој континуирано се развива.<br>
							Нашето седиште е близу централното градско подрачје, на улицата Рузвелтова бр.19,т ука е сместена целокупната администрација, големопродажбата,с ервисниот центар како и најголемиот продажен салон на Сетек се од техника од 450м2, кој се наоѓа на приземјето во деловната зграда на Сетек. 
							</p>
							<img src="images/set_s.jpg"/>
							<p>
							Уште од почетокот определбата на Сетек се од техника е да биде најуспешен во своето својата област, и тоа е така во изминатите 18 години. Но тоа не значи дека мируваме, ние секој ден упорно и упорно работиме на постојано усовршување во сите сегменти на нашата работа, а се со цел да останеме на лидерската позиција, која што ја заслуживме благодарение на постојаното функционорање, динамичност и усовршување. Ние сме репрезенти за тоа како успешно треба да се води еден бизнис. Горди сме на она што го имаме постигнато, но секогаш се стремиме кон тоа да оствариме уште повеќе.
							</p>
							<h2>
							Контакт
							</h2>
							<ul id="about">
								<li>
								<strong>СЕТЕК 1</strong><br>
								Рузвелтова 21, 1000 Скопје,<br>
								Македонија<br>
								Тел.: +389 (0)2 30 80 100<br>
								Факс: +389 (0)2 30 80 390<br>
								E: setpccentar@set.com.mk
								</li>
								<li>
								<strong>СЕТЕК 2</strong><br>
								Kej 13ти Ноември - ГТЦ <br>
								Приземје-Скопје <br>
								(спроти клуб КАРТЕЛ)<br>
								Тел.: +389 (0)2 32 33 493<br>
								Факс: +389 (0)2 30 80 390<br>
								E: setgtc@set.com.mk
								</li>
								<li>
								<strong>СЕТЕК 3 -<br> Мултимедија ЦЕНТАР</strong> <br>
								ГТЦ Приземје<br>
								Тел.: +389 (0)2 3 214 025<br>
								Факс: +389 (0)2 30 80 390<br>
								E: setgtc@set.com.mk
								</li>
								<li>
								<strong>СЕТEK БИТОЛА</strong> <br>
								ул. Никола Тесла 55 Битола,<br> Македонија<br>	
								Тел: 047 222 201<br>
								Факс: +389 (0)2 30 80 390<br>
								Е-маил: sks5@set.com.mk
								</li>
								<li>
								<strong>СЕТEK ОХРИД</strong> <br>
								Ул. Партизанска бр.1/100,<br> Македонија	<br>
								Тел: 075 405 751<br>
								Факс: +389 (0)2 30 80 390<br>
								Е-маил: sks7@set.com.mk
								</li>
								<li>
								<strong>СЕТEK КУМАНОВО</strong> <br>
								ул. 11 Октомври, <br>Македонија	<br>
								Тел: 31 413 947<br>
								Факс: +389 (0)2 30 80 390<br>
								Е-маил: sks6@set.com.mk
								</li>
								<li>
								<strong>СЕТEK ТЕТОВО</strong> <br>
								ул. Јна 10 , Македонија	<br>
								Тел: 044 33 66 35<br>
								Факс: +389 (0)2 30 80 390<br>
								Е-маил: sks4@set.com.mk
								</li>
								<li>
								<strong>СЕТEK СТРУМИЦА</strong> <br>
								ул. Ленинова бр. 103 лок 2,
								<br>Македонија	<br>
								тел: 043 33 03 41<br>
								Факс: +389 (0)2 30 80 390<br>
								Е-маил: sks8@set.com.mk
								</li>
								<li>
								<strong>Сетек големопродажба</strong><br>
								Рузвелтова 19, 1000 Скопје,
								<br>Македонија<br>
								Тел.: +389 (0)2 30 80 877<br>
								Факс: +389 (0)2 30 80 390<br>
								E: sales@set.com.mk
								</li>
								<li>
								<strong>Сетек Сервис</strong> <br>
								Рузвелтова 21, 1000 Скопје,
								<br>Македонија<br>
								Тел.: +389 (0)2 30 84 890<br>
								Факс: +389 (0)2 30 80 390<br>
								E: servis@set.com.mk
								</li>
							</ul>
						
						</div><!--End Tab 4 -->
					</div><!--End Tab Container -->
				</div><!--End Main Content-->
			<?php }
			else { ?>
				<div class="maincontent">
					<div class="logo"><img src="images/header.png" alt="set.com.mk"></div>
					<div>
						<div>
						<h3>
									Кликни <strong>LIKE</strong> за да бидеш дел од натпреварот за најдобра фотографија!
						</h3>
						<h3>Правила на натпреварот:</h3>
							<h2>Натпреварот ќе започне на 03.04.2013 во 14 часот и ќе заврши на 17.04.2013 во 14:00 часот.</h2>
							<p>За учество во наградниот натпревар на Сетек се од техника</p>
							<ul class="ulall">
							<li>Одете на официјалната интернет страна на <a href="http://www.set.mk" target="_blank">Сетек се од техника</a></li>
							<li>Превземете било која фотографија од наш производ</li>	
							<li>Прикачете ја за наградниот натпревар и споделете ја со пријателите за да добиете повеќе гласови</li>
							
						<p>Препорачуваме: При пријава за учество во натпреварот Сетек се од техника за најдобра фотографија, веднаш гласајте и споделете ја вашата фотографија како би можеле подоцна полесно да ја најдете помеѓу многуте објавени фотографии во табот "Гласај".</p>
						<h3>Награден фонд:</h3>
						<ul class="ulall">
						<li>Прво место:Sony HD Камера</li>
						<li>Второ место:Canon Принтер</li>
						<li>Трето место:Bosh Сецко</li>
						<li>Маички</li>
						</ul>
						<h3>
								Правила за учество
						</h3>
							<ul class="ulall">
								<li>Право на учество имаат сите кои притиснале like на фејсбук страната на Сетек се од техника и се Македонски државјани.</li>
								<li>Учесник може да добие само една награда. Објавувајте една фотографија како би ги зголемиле шансите за победа. </li>
								<li>Можете да гласате за својот фаворит на секој 24 часа.</li>

								<li>Сите фотографии и коментари кои не се прилагодени на настанот, навреди и слично ќе бидат избришани! </li>

								<li>Прифатливи типови на слика се JPG, GIF и PNG. Слики поголеми од 1000 пиксели широки или високи ќе бидат намалени за да ги собере во полето, оригиналниот сооднос ќе биде сочуван.</li>

								<li>Редоследот на покажување на фотографиите на учесниците по бројот на гласови, почнувајќи од фотографијата со најголем број.</li>

								<li>Наградата може да ја подигне само facebook корисникот лично со документ за идентификација.   </li>

								<li>Наградите не се испраќаат по пошта, ќе мора да се подигнат во некој од салоните на Сетек се од техника.</li>

							</ul>
						<a href="<?php echo $logoutUrl; ?>">Logout</a>
						</div>
					</div><!--End Tab Container -->
				</div><!--End Main Content-->
			<?php }
		} catch (FacebookApiException $e) {
			error_log($e);
			$user = null;
		} ?>
		<div class="sidebar">
			<div class="fb-like-box" data-href="http://www.facebook.com/set.com.mk" data-width="250" data-show-faces="true" data-stream="false" data-border-color="white" data-header="false"></div>
		</div><!--End Sidebar-->
<script type="text/javascript">

$('#10_no,#11_no,#12_no,#13_no').hide();

function tise(){
	$('#0_no,#1_no,#2_no,#3_no,#4_no,#5_no,#6_no,#7_no,#8_no,#9_no').hide();
	$('#10_no,#11_no,#12_no,#13_no').show();
};

function tise1(){
	$('#0_no,#1_no,#2_no,#3_no,#4_no,#5_no,#6_no,#7_no,#8_no,#9_no').show();
	$('#10_no,#11_no,#12_no,#13_no').hide();
};

function changeButton(button){
	$("#tab2pictures").html('Loading <img src="images/ajax-loader.gif" />');
	$("div[id^='button']").removeClass("active");
	$("#button" + button).addClass("active");
	
	var dataString = 'buttonId=' + button;
	$.ajax({
		type: "POST",
		url: "paginate.php",
		data: dataString,
		cache: false,
		success: function(result){
			if (result!='error'){
				$("#tab2pages").html(result);
				changePagination('0', button);
			}
			else {
				$("#tab2pictures").html('');
				$("#tab2pages").html('<p class="refreshp">Ве молиме освежете ја страницата!</p>');
			}
		}
	});
};

function changePagination(pageId,button){
	$("#tab2pictures").html('Loading <img src="images/ajax-loader.gif" />');

	FB.Canvas.scrollTo(0,0);

	var dataString = 'pageId='+pageId + '&buttonId='+button;
	$.ajax({
		type: "POST",
		url: "display.php",
		data: dataString,
		cache: false,
		success: function(result){
			$("#tab2pictures").html(result);
		}
	});
		
};
FB.Event.subscribe('edge.create', function(response){
	top.location.href = 'https://apps.facebook.com//';
});
</script>

</body>
</html>
<?php  
} else {
  $loginUrl = $facebook->getLoginUrl(array('redirect_uri' => $fbconfig['appUrl'], 'scope' => 'user_likes'));
  print "<script type='text/javascript'>top.location.href = '$loginUrl';</script>";
}

?>
