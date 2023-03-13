// JavaScript Document

function displaySubadminmenu(subtr){
		$('.submenulist').css('display',"none");
		$('.sub_trno_'+subtr).show('slow');
		//alert(subtr);

}

function redirect_to(url){
	window.location.href=url;
}

$(function() {
$( "#start_date" ).datepicker();
});

$(function() {
$( "#end_date" ).datepicker();
});
 
$('#srch').keypress(function (e) {
  if (e.which == 13) {
    $('form#validate').submit();
    return false;    //<---- Add this line
  }
});
