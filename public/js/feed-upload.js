var $activeImage;

$(function () {
    'use strict';  
    
    $('.btn-choose').click(function(e) {
		$('.row-media').removeClass('hide');
		$('.row-upload').addClass('hide');
		$('.btn-preview-add-image').removeClass('hide');
//		$('.img-thumbnail-preview').popover({
//			placement: 'right',
//			trigger: 'hover',
//			content: 'Press ctrl+click to select multiple'
//		});
		return false;
	});
	
	$('#save-images').attr('disabled', 'disabled');
	
	$('.btn-preview-add-image').click(function(e) {		
		$('.row-media').addClass('hide');
		$('.row-upload').removeClass('hide');
		$(this).addClass('hide');
		deselectImg();
	});
	
	$('#myModal').on('hidden.bs.modal', function(e) {		
		$('.row-media').addClass('hide');
		$('.row-upload').removeClass('hide');
		$('.btn-preview-add-image').addClass('hide');
		deselectImg();
	});

     //Initialize the jQuery File Upload widget:   
	$('#fileupload').fileupload({   
		url: '/feed/upload/ajax',
        maxFileSize: 5000000,
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
		autoUpload: true,
		previewMaxWidth: 100,
        previewMaxHeight: 100,
        previewCrop: true
	}).on('fileuploadadd', function() {
		$('.row-media').removeClass('hide');
		$('.row-upload').addClass('hide');
		$('.btn-preview-add-image').removeClass('hide');
	}).on('fileuploaddone', function (e, data) {
		var files = data.result.files;
		previewImage(files);
	});	
	 
     //Enable iframe cross-domain access via redirect option:
    $('#fileupload').fileupload(
        'option',
        'redirect',
        window.location.href.replace(
            /\/[^\/]*$/,
            '/cors/result.html?%s'
        )
    );
   
	loadExistingFiles();	
    
});



function deselectImg() {
	$(".img-thumbnail-preview").css('background-color', '#FFFFFF');
	$('.my-zoom-icon').
	css('background-color', '#FFFFFF').css('display', 'none').
	find('a').css('color', '#428BCA');
	$('.fileId').each(function() {
		$(this).removeClass('file-selected');
	});
	$('#save-images').attr('disabled', 'disabled');
	$('.image-detail-container').html("");	
	
}
    

function previewImage(files) {
	$(document).on('click', '.img-thumbnail-preview', function(e) {
		//deselectImg();
		
		mouseDown(e);
		$('#save-images').removeAttr('disabled');
		$(this).css('background-color', '#428BCA');
		$(this).parent('.preview').find('.my-zoom-icon').
			css('background-color', '#428BCA').css('display', 'inline').
			find('a').css('color', '#FFFFFF');
		$(this).parent('.preview').find('.fileId').addClass('file-selected');
		
		var fileId = $(this).parent('.preview').find('.fileId').val();
		var found = false;
		for (var i=0, file; file=files[i]; i++) {
			if (file.id == fileId) {
				var detail = '<div class="img-medium-container col-sm-6 col-md-12">';
				detail += '<img src="'+file.mediumUrl+'" id="imgToCrop" class="img-thumbnail">';
				detail += '</div>';
				$('.image-detail-container').html(detail);
				$('.my-tooltip').tooltip({placement: 'bottom'});				
				
				break;
			}
		}
	});
	$(document).on('click', '.deselect', function() {
		deselectImg();
	});
	
	$(document).on('click', '.image-preview-container', function(e) {
		var container = $(".img-thumbnail-preview");
		var myZoomIcon = $(".my-zoom-icon");
		if ((!container.is(e.target) // if the target of the click isn't the container...
			&& container.has(e.target).length === 0) // ... nor a descendant of the container
			&& (!myZoomIcon.is(e.target) && myZoomIcon.has(e.target).length === 0)) 
		{
			container.css('background-color', '#FFFFFF');
			$('.my-zoom-icon').
			css('background-color', '#FFFFFF').css('display', 'none').
			find('a').css('color', '#428BCA');
			$('.fileId').each(function() {
				$(this).removeClass('file-selected');
			});
			$('#save-images').attr('disabled', 'disabled');
			$('.image-detail-container').html("");
			//$('.img-thumbnail-preview').popover("destroy");
		}
	});	
}

function loadExistingFiles(){
    result=null;
    $('#fileupload').each(function () {
        var that = this;
        var url = $('#fileupload').fileupload('option', 'url');
        $.getJSON(url, function (result) {
        	var files = result.files;
            if (files && files.length) {
				previewImage(files);
                $(that).fileupload('option', 'done')
                    .call(that, $.Event('done'), {result: result});
            }
        });
    });
}

function mouseDown(e) {
	var ctrlPressed=0;
	var altPressed=0;
	var shiftPressed=0;

	if (parseInt(navigator.appVersion)>3) {
		var evt = e ? e:window.event;

		if (document.layers && navigator.appName=="Netscape"
			&& parseInt(navigator.appVersion)==4) {
			// NETSCAPE 4 CODE
			var mString =(e.modifiers+32).toString(2).substring(3,6);
			shiftPressed=(mString.charAt(0)=="1");
			ctrlPressed =(mString.charAt(1)=="1");
			altPressed  =(mString.charAt(2)=="1");
			self.status="modifiers="+e.modifiers+" ("+mString+")";
		} else {
			// NEWER BROWSERS [CROSS-PLATFORM]
			shiftPressed = evt.shiftKey;
			altPressed  = evt.altKey;
			ctrlPressed = evt.ctrlKey;
			self.status = ""
				+  "shiftKey="+shiftPressed 
				+", altKey="  +altPressed 
				+", ctrlKey=" +ctrlPressed;
		}
		if (shiftPressed || altPressed || ctrlPressed) 
			alert ("Mouse clicked with the following keys:\n"
					+ (shiftPressed ? "Shift ":"")
					+ (altPressed   ? "Alt "  :"")
					+ (ctrlPressed  ? "Ctrl " :"")
			);
	}
	return true;
}

function mouseOver(e) {
	var ctrlPressed=0;
	var altPressed=0;
	var shiftPressed=0;

	if (parseInt(navigator.appVersion)>3) {
		var evt = e ? e:window.event;

		if (document.layers && navigator.appName=="Netscape"
			&& parseInt(navigator.appVersion)==4) {
			// NETSCAPE 4 CODE
			var mString =(e.modifiers+32).toString(2).substring(3,6);
			shiftPressed=(mString.charAt(0)=="1");
			ctrlPressed =(mString.charAt(1)=="1");
			altPressed  =(mString.charAt(2)=="1");
			self.status="modifiers="+e.modifiers+" ("+mString+")";
		} else {
			// NEWER BROWSERS [CROSS-PLATFORM]
			shiftPressed = evt.shiftKey;
			altPressed  = evt.altKey;
			ctrlPressed = evt.ctrlKey;
			self.status = ""
				+  "shiftKey="+shiftPressed 
				+", altKey="  +altPressed 
				+", ctrlKey=" +ctrlPressed;
		}
		if (shiftPressed || altPressed || ctrlPressed) 
			alert ("Mouse clicked with the following keys:\n"
					+ (shiftPressed ? "Shift ":"")
					+ (altPressed   ? "Alt "  :"")
					+ (ctrlPressed  ? "Ctrl " :"")
			);
	}
	return true;
}