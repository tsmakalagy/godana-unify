var $activeImage;
var cropCoordinates={};
var jcOptions = {};
var crop_api;
var jcrop_api;
$(function () {
    'use strict';  
    
    $('.btn-choose').click(function(e) {
		$('.row-media').removeClass('hide');
		$('.row-upload').addClass('hide');
		$('.btn-preview-add-image').removeClass('hide');
		
		return false;
	});
	
	$('#save-images').attr('disabled', 'disabled');
	
	$('.btn-preview-add-image').click(function(e) {
		if (jcrop_api != undefined) {			
			jcrop_api.destroy();	
		}
		$('.row-media').addClass('hide');
		$('.row-upload').removeClass('hide');
		$(this).addClass('hide');
		deselectImg();
	});
	
	$('#myModal').on('hidden.bs.modal', function(e) {
		if (jcrop_api != undefined) {			
			jcrop_api.destroy();	
		}
		$('.row-media').addClass('hide');
		$('.row-upload').removeClass('hide');
		$('.btn-preview-add-image').addClass('hide');
		deselectImg();
	});

     //Initialize the jQuery File Upload widget:   
	$('#fileupload').fileupload({   
		url: '/user/upload/ajax',
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
	
	 //Cropping
	$(document).on('click', '#btn-crop', function(e) {
		e.preventDefault();		
		$.post('/crop',cropCoordinates, function(e) {
			jcrop_api.destroy();	
			$('#btn-crop').addClass('disabled');
//			$('#save-images').removeAttr('disabled');
		});
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

function initJcrop(options)
{      
  // Invoke Jcrop in typical fashion
  
  options.onRelease = 'releaseCheck';
  $('#imgToCrop').Jcrop(options,function(){
	jcrop_api = this;
  });

}

function releaseCheck()
{
  jcrop_api.setOptions({ allowSelect: true });
}

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
	if (jcrop_api != undefined) {	
		//jcrop_api.destroy();	
	}
}
    

function previewImage(files) {
	$(document).on('click', '.img-thumbnail-preview', function(e) {
		deselectImg();
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
				detail += '<img src="'+file.mediumUrl+'" id="imgToCrop" class="img-thumbnail">'
				detail += '</div>';
				detail += "<div class='col-sm-3 col-sm-offset-4'>";
				detail += "<div class='form-group'>";
				detail += "<button class='btn btn-default disabled' id='btn-crop'>Crop</button>";
				detail += "</div>";
				detail += "</div>";
				$('.image-detail-container').html(detail);
				found = true;
				$('.my-tooltip').tooltip({placement: 'bottom'});				
				var picWidth = $('#imgToCrop').width();
				var picHeight = $('#imgToCrop').height();
				if (!picWidth) return;
				// To be set dynamically
				var cropWidth = 60;
				var cropHeight = 60;
				jcOptions.aspectRatio = cropWidth / cropHeight;
				jcOptions.trueSize = [picWidth, picHeight];
				cropCoordinates.source = {
					width:picWidth,
					height:picHeight,
					endWidth:cropWidth,
					endHeight:cropHeight,
					file:file.url
				};
				// width of cropped images
				cropCoordinates.w = {
					xs: '24',
					sm: '40',
					md: '60'
				};				
				// id of file
				cropCoordinates.id = file.id;
				jcOptions.onSelect = function(c){
					cropCoordinates.c=c;
				};	
				cx = picWidth/2;
				cy = picHeight/2;
				x1 = cx - (cropWidth/2);
				x2 = cx + (cropWidth/2);
				y1 = cy - (cropHeight/2);
				y2 = cy + (cropHeight/2);
				jcOptions.setSelect = [x1, y1, x2, y2];
				
				//$('#imgToCrop').bind('load', function() {
					initJcrop(jcOptions);
					$('#btn-crop').removeClass('disabled');
				//});
				break;
			}
		}
	});
	$(document).on('click', '.deselect', function() {
		deselectImg();
	});
	$(document).on('click', '.crop', function() {
		initJcrop(jcOptions);
		$('#btn-crop').removeClass('disabled');
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