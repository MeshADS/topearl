var form_builder = new function(){

	var listvalues = 0, radiobuttons = 0, selectoptions = 0,
		listValueTpl = '<div class="row p-r-35 m-t-10 listvalues" id="elistvalue|ID|">'+
							'<div class="col-md-6">'+
								'<div class="form-group form-group-default">'+
									'<label for="list">'+
										'Name'+
									'</label>'+
									'<input type="text" name="list[]" class="form-control" placeholder="List item name">'+
								'</div>'+
							'</div>'+
							'<div class="col-md-6">'+
								'<div class="form-group form-group-default">'+
									'<label for="value">'+
										'Value'+
									'</label>'+
									'<input type="text" name="value[]" class="form-control" placeholder="List item value">'+
								'</div>'+
							'</div>'+
							'<a href="javascript:;" class="new-element-dl-btn remove-listvalue" data-id="|ITEM|" data-helper="|HELPER|" data-target="#elistvalue|REMOVE-ID|">'+
								'<i class="fa fa-times"></i>'+
							'</a>'+
						'</div>';

	var addListvalue = function(){
		var nwTpl;
		$(".addListvalue").click(function(event){
			// Diable click event
			$(".addListvalue").off("click");
			// Get target element
			var target = $(this).attr("data-target");
			var helper = $(this).attr("data-helper");
			var id = $(this).attr("data-id");
			// Update list value counter
			console.log(target);
			console.log(helper);
			console.log(id);
			listvalues++;
			// Create new list value template
			nwTpl = listValueTpl;
			nwTpl = nwTpl.replace("|ID|", listvalues);
			nwTpl = nwTpl.replace("|REMOVE-ID|", listvalues);
			nwTpl = nwTpl.replace("|HELPER|", helper);
			nwTpl = nwTpl.replace("|ITEM|", id);
			// Append new list value to container
			$(nwTpl).appendTo(target);
			// Hide modal helper
			$(helper).hide();
			// Re-init  this function
			addListvalue();
			// Init remove method
			removeListvalue();
			// Cancel default action
			event.preventDefault();
		});
	};

	var removeListvalue = function(){
		var target, helper, id;
		$(".listvalues .remove-listvalue").off("click");
		$(".listvalues .remove-listvalue").click(function(event){
			// grab target element
			target = $(this).attr("data-target");
			helper = $(this).attr("data-helper");
			id = $(this).attr("data-id");
			// Remove checkbox from view
			$(target).remove();
			// Turn click event off
			$(this).off("click");
			// Show modal helper
			if ($("#listvalues"+id+" .row").size() < 1) {
				$(helper).show();
			};
			// Cancel default action
			event.preventDefault();
		});
	};

	var deleteListValue = function(){
		// Add click event listener
		$(".remove-olistvalue").click(function(event){
			// Get target id
			var target = $(this).attr("data-target");
			// hide target
			$(target+" input[type='text']").prop("disabled", true);
			$(target+" .delMessage").fadeIn('slow');
			// Toggle delete state
			$(target+" #deleteThis").val(1);
			// Cancel default action
			event.preventDefault();
		});
	};

	var cancelDeleteListValue = function(){
		// Add click event listener
		$(".cancel-remove-olistvalue").click(function(event){
			// Get target id
			var target = $(this).attr("data-target");
			// hide target
			$(target+" input[type='text']").prop("disabled", false);
			$(target+" .delMessage").fadeOut('slow');
			// Toggle delete state
			$(target+" #deleteThis").val(0);
			// Cancel default action
			event.preventDefault();
		});
	};

	var formWizard = function(){

		$('.myFormWizard').each(function(){
			var self = $(this);
			self.bootstrapWizard({
		        onTabShow: function(tab, navigation, index) {
		            var $total = navigation.find('li').length;
		            var $current = index + 1;

		            // If it's the last tab then hide the last button and show the finish instead
		            if ($current >= $total) {
		                self.find('.pager .next').hide();
		                self.find('.pager .finish').show();
		                self.find('.pager .finish').removeClass('disabled');
		            } else {
		                self.find('.pager .next').show();
		                self.find('.pager .finish').hide();
		            }

		            var li = navigation.find('li.active');

		            var btnNext = self.find('.pager .next').find('button');
		            var btnPrev = self.find('.pager .previous').find('button');

		            // remove fontAwesome icon classes
		            function removeIcons(btn) {
		                btn.removeClass(function(index, css) {
		                    return (css.match(/(^|\s)fa-\S+/g) || []).join(' ');
		                });
		            }
		        }
		    });
		});
	};

	var formValidation = function(){

		
	};

	this.init = function(){
		addListvalue();
		formWizard();
		deleteListValue();
		cancelDeleteListValue();
		formValidation();
	}
}