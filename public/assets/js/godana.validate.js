(function($) {
	var privateFunction = function() {
		// code here
	}
 
	var methods = {
		init: function(options) {
 
			// Repeat over each element in selector
			return this.each(function() {
				var $this = $(this);
 
				// Attempt to grab saved settings, if they don't exist we'll get "undefined".
				var settings = $this.data('validateInput');

				var timeout;
 
				// If we could't grab settings, create them from defaults and passed options
				if(typeof(settings) == 'undefined') {
 
					var defaults = {
					    btnRegister: '.btn-register',
					    query: '',
						onSomeEvent: function() {}
					}
 
					settings = $.extend({}, defaults, options);
 
					// Save our newly created settings
					$this.data('validateInput', settings);
				} else {
					// We got settings, merge our passed options in with them (optional)
					settings = $.extend({}, settings, options);
 
					// If you wish to save options passed each time, add:
					// $this.data('pluginName', settings);
				}
 
				$this.on("keypress", function() {
					if (timeout) clearTimeout(timeout);
					timeout = setTimeout(function() {
			            if ($this.val() != '') {
			            	$.ajax({
			                	type: "POST",
			                    url: settings.url,
			                    data: "name="+settings.name+"&"+settings.name+"="+$this.val()+settings.query,
			                    dataType: "json",
			    				beforeSend: function() {    
			            			$(settings.loadingClass).html("<i class='icon-spinner icon-spin'></i>"); 
			            			$this.parents('.control-group').removeClass('error');
			                    	$this.parents('.control-group').find('.help-block').remove();			
			            		},
			                    success: function(res) {
			                        if (!res.success) {
			                        	$(settings.loadingClass).html("<i class='icon-remove text-error'></i>");                        	
			                        	$this.parents('.control-group').addClass('error');
			                        	$this.parents('.control-group').append(res.error_msg);
//			                        	$(settings.btnRegister).addClass('disabled');
			                        } else {
			                        	$(settings.loadingClass).html("<i class='icon-ok text-success'></i>");
//			                        	$(settings.btnRegister).removeClass('disabled');
			                        }
			            			
			            		}
			                });
			            } else {
			            	$(settings.loadingClass).html("");
//			            	$this.parents('.form-group').removeClass('has-error');
//			            	$this.parents('.form-group').find('.help-block').remove();
			            }
			            
			        }, 1000);
				});			
				$this.on("blur", function() {
					if ($this.val() == '') {
						$(settings.loadingClass).html("");
						$this.parents('.control-group').removeClass('error');
						$this.parents('.control-group').find('.help-block').remove();
					} 
				});	
 
			});
		},
		destroy: function(options) {
			// Repeat over each element in selector
			return $(this).each(function() {
				var $this = $(this);
 				$this.off("keypress");
 				$this.off("blur");
				// run code here
 
				// Remove settings data when deallocating our plugin
				$this.removeData('pluginName');
			});
		},
		val: function(options) {
			// code here, use .eq(0) to grab first element in selector
			// we'll just grab the HTML of that element for our value
			var someValue = this.val();
 
			// return one value
			return someValue;
		}
	};
 
	$.fn.validateInput = function() {
		var method = arguments[0];
 
		if(methods[method]) {
			method = methods[method];
			arguments = Array.prototype.slice.call(arguments, 1);
		} else if( typeof(method) == 'object' || !method ) {
			method = methods.init;
		} else {
			$.error( 'Method ' +  method + ' does not exist on jQuery.pluginName' );
			return this;
		}
 
		return method.apply(this, arguments);
 
	}
 
})(jQuery);