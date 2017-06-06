$(document).ready(function() {
	$(".tab_content").hide(); 
	$("ul.tabs li:first").addClass("active").show();
	$(".tab_content:first").show();
	changeButton('3');
	$('#10_no,#11_no,#12_no,#13_no').hide();
	function one(){
		$('#0_no,#1_no,#2_no,#3_no,#4_no,#5_no,#6_no,#7_no,#8_no,#9_no').hide();
		$('#10_no,#11_no,#12_no,#13_no').show();
	};
	function two(){
		$('#0_no,#1_no,#2_no,#3_no,#4_no,#5_no,#6_no,#7_no,#8_no,#9_no').show();
		$('#10_no,#11_no,#12_no,#13_no').hide();
	};
	
	$("a#show-panel").click(function(){  
	    $("#lightbox, #lightbox-panel").fadeIn(300);  
	});  
	$("a#close-panel").click(function(){  
	    $("#lightbox, #lightbox-panel").fadeOut(300);  
	});
	$("ul.tabs li").click(function() {
		$("ul.tabs li").removeClass("active");
		$(this).addClass("active"); 
		$(".tab_content").hide();
		var activeTab = $(this).find("a").attr("href"); 
		$(activeTab).fadeIn(); 
		if (activeTab == "#tab1"){
			changeButton('3');
		}
		return false;
	});
});