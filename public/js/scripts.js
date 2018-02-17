$(function() {
    // Side Bar Toggle
    $('.hide-sidebar').click(function() {
	  $('#sidebar').hide('fast', function() {
	  	$('#content').removeClass('span9');
	  	$('#content').addClass('span12');
	  	$('.hide-sidebar').hide();
	  	$('.show-sidebar').show();
	  });
	});

	$('.show-sidebar').click(function() {
		$('#content').removeClass('span12');
	   	$('#content').addClass('span9');
	   	$('.show-sidebar').hide();
	   	$('.hide-sidebar').show();
	  	$('#sidebar').show('fast');
	});
	
//	$(".datepicker").datepicker().on('changeDate', function(){
//        $(this).blur();
//    });
	
	//$(".chzn-select").chosen({placeholder_text_multiple:'Select a category...'});
	$(".chzn-select").chosen().change(function(event){
		 if(event.target == this){
			    //alert($(this).val());
			 }
			});
	
});