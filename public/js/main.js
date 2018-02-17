/*
 * jQuery File Upload Plugin JS Example 8.9.0
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

/*jslint nomen: true, regexp: true */
/*global $, window, blueimp */

$(document).ready(function() {
	
});

$(function () {
	
	
	
    'use strict';

    // Initialize the jQuery File Upload widget:
    $('#fileupload').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: '/bid/ajax',
        maxFileSize: 5000000,
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        autoUpload: true,
        previewMaxWidth: 100,
        previewMaxHeight: 100,
        previewCrop: true
    }).on('fileuploadsubmit', function (e, data) {
        data.formData = data.context.find(':input').serializeArray();        
    }).on('fileuploaddone', function (e, data) {
    	var files = data.result.files;
    	$(document).on('click', '.img-thumbnail-preview', function() {
    		$(this).css('background-color', '#428BCA');
    		$(this).parent('.preview').find('.my-zoom-icon').
    			css('background-color', '#428BCA').css('display', 'inline').
    			find('a').css('color', '#FFFFFF');
    		$(this).parent('.preview').find('.fileId').addClass('file-selected');
    		$('#save-images').removeAttr('disabled');
    		var fileId = $(this).parent('.preview').find('.fileId').val();
    		for (var i=0, file; file=files[i]; i++) {
    			if (file.id == fileId) {
    				var detail = '<div class="img-medium-container col-sm-6 col-md-12"><img src="'+file.mediumUrl+'" class="img-thumbnail"></div>';
    				detail += "<div class='col-sm-6 col-md-12'>";
    				detail += "<div class='form-group'>";
    				detail += "<label class='col-sm-4 control-label'>Title</label>";
    				detail += "<div class='col-sm-10'><input class='form-control' type='text' name='img-title'></div></div>";
    				detail += "<div class='form-group'><label class='col-sm-4 control-label'>Description</label>";
    				detail += "<div class='col-sm-10'><textarea class='form-control' name='img-description'></textarea></div></div>";
    				detail += "</div>";
    				$('.image-detail-container').html(detail);
    				break;
    			}
    		}
    	});
        $('.my-tooltip').tooltip();
        $('.deselect').click(function() {
        	$(this).parents('.preview').find('.img-thumbnail-preview').css('background-color', '#FFFFFF');
        	$(this).parents('.preview').find('.my-zoom-icon').
			css('background-color', '#FFFFFF').css('display', 'none').
			find('a').css('color', '#428BCA');
        	$(this).parents('.preview').find('.fileId').removeClass('file-selected');
        	$('.image-detail-container').html("");
        });
        $('.image-preview-container').click(function (e) {
        	var container = $(".img-thumbnail-preview");
   		    if (!container.is(e.target) // if the target of the click isn't the container...
   		    	&& container.has(e.target).length === 0) // ... nor a descendant of the container
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
    }).bind('fileuploadprocessfail', function(e, data) {
    });

    // Enable iframe cross-domain access via redirect option:
    $('#fileupload').fileupload(
        'option',
        'redirect',
        window.location.href.replace(
            /\/[^\/]*$/,
            '/cors/result.html?%s'
        )
    );
    
    

    if (window.location.hostname === 'blueimp.github.io') {
        // Demo settings:
        $('#fileupload').fileupload('option', {
            url: '//jquery-file-upload.appspot.com/',
            // Enable image resizing, except for Android and Opera,
            // which actually support image resizing, but fail to
            // send Blob objects via XHR requests:
            disableImageResize: /Android(?!.*Chrome)|Opera/
                .test(window.navigator.userAgent),
            maxFileSize: 5000000,
            acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i
        });
        // Upload server status check for browsers with CORS support:
        if ($.support.cors) {
            $.ajax({
                url: '//jquery-file-upload.appspot.com/',
                type: 'HEAD'
            }).fail(function () {
                $('<div class="alert alert-danger"/>')
                    .text('Upload server currently unavailable - ' +
                            new Date())
                    .appendTo('#fileupload');
            });
        }
    } else {
        // Load existing files:
        $('#fileupload').addClass('fileupload-processing');
        $.ajax({
            // Uncomment the following to send cross-domain cookies:
            //xhrFields: {withCredentials: true},
            url: $('#fileupload').fileupload('option', 'url'),
            dataType: 'json',
            context: $('#fileupload')[0]            
        }).always(function () {
            $(this).removeClass('fileupload-processing');
        }).done(function (result) {
            $(this).fileupload('option', 'done')
                .call(this, $.Event('done'), {result: result});

            $('.image-preview-container').click(function (e) {
            	var container = $(".preview");
       		    if (!container.is(e.target) // if the target of the click isn't the container...
       		    	&& container.has(e.target).length === 0) // ... nor a descendant of the container
            	{
       		    	container.find('.img-thumbnail-preview').css('background-color', '#FFFFFF');
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
            
            var files = result.files;
            $(document).on('click', '.img-thumbnail-preview', function() {
        		$(this).css('background-color', '#428BCA');
        		$(this).parent('.preview').find('.my-zoom-icon').
        			css('background-color', '#428BCA').css('display', 'inline').
        			find('a').css('color', '#FFFFFF');
        		$(this).parent('.preview').find('.fileId').addClass('file-selected');
        		$('#save-images').removeAttr('disabled');
        		var fileId = $(this).parent('.preview').find('.fileId').val();
        		for (var i=0, file; file=files[i]; i++) {
        			if (file.id == fileId) {        				
        				var detail = '<div class="img-medium-container col-sm-6 col-md-12"><img src="'+file.mediumUrl+'" class="img-thumbnail"></div>';
        				detail += "<div class='col-sm-6 col-md-12'>";
        				detail += "<div class='form-group'>";
        				detail += "<label class='col-sm-4 control-label'>Title</label>";
        				detail += "<div class='col-sm-10'><input class='form-control' type='text' name='img-title'></div></div>";
        				detail += "<div class='form-group'><label class='col-sm-4 control-label'>Description</label>";
        				detail += "<div class='col-sm-10'><textarea class='form-control' name='img-description'></textarea></div></div>";
        				detail += "</div>";
        				$('.image-detail-container').html(detail);
        				break;
        			}
        		}
        		
        		
        		
        	});
            $('.my-tooltip').tooltip();
            $('.deselect').click(function() {
            	$(this).parents('.preview').find('.img-thumbnail-preview').css('background-color', '#FFFFFF');
            	$(this).parents('.preview').find('.my-zoom-icon').
    			css('background-color', '#FFFFFF').css('display', 'none').
    			find('a').css('color', '#428BCA');
            	$(this).parents('.preview').find('.fileId').removeClass('file-selected');
            	$('.image-detail-container').html("");
            });
            
        });
    }
    

});
