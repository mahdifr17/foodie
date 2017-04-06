$(document).ready(function() {
	
	$(".dropdown-menu li a").click(function(){
	  //$(this).parents(".dropdown").find('.btn').html($(this).text() + ' <span class="caret"></span>');
	  //$('#datebox').html($(this).text());
	  $('#datebox').val($(this).text());
	  //$(this).parents(".dropdown").find('.btn').val($(this).data('value'));
	  
	  
	  console.log("hmmm working?");
	});
	
});
console.log("is this even working");
$(".user").focusin(function(){
  $(".inputUserIcon").css("color", "#e74c3c");
}).focusout(function(){
  $(".inputUserIcon").css("color", "white");
});

$(".pass").focusin(function(){
  $(".inputPassIcon").css("color", "#e74c3c");
}).focusout(function(){
  $(".inputPassIcon").css("color", "white");
});