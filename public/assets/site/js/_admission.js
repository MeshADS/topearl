var admissionJS = new function(){

	function loadTeamGallery(){
	    // Init inline Fancybox
	  $(".fancyboxBtn").fancybox({ 
	  	overlay : { locked : false },
	  	autoDimensions:false,
	  	width:560
	  });
	}

	function validateForm(){
	    // Init inline Fancybox
	    if ($("form").hasClass("application-form")) {

	    	$("#application-form").validate({
	    		rules:{
	    			childs_name:{
	    				required:true
	    			},
	    			childs_surname:{
	    				required:true
	    			},
	    			childs_age:{
	    				required:true,
	    				number:true,
	    				min:0
	    			},
	    			childs_sex:{
	    				required:true
	    			},
	    			childs_birthday:{
	    				required:true,
	    				date:true
	    			},
	    			address:{
	    				required:true
	    			},
	    			starting_on:{
	    				required:true,
	    				date:true
	    			},
	    			mothers_name:{
	    				required:true
	    			},
	    			mothers_workphone:{
	    				required:true
	    			},
	    			mothers_homephone:{
	    				required:true
	    			},
	    			mothers_mobilephone:{
	    				required:true
	    			},
	    			mothers_email:{
	    				required:true,
	    				email:true
	    			},
	    			fathers_name:{
	    				required:function(element) {
							        if ($(".application-form #mothers_name").val().length < 1) {
							        	return true;
							        }
							        else{
							        	return false;
							        }
							      }
	    			},
	    			fathers_workphone:{
	    				required:function(element) {
							        if ($(".application-form #mothers_workphone").val().length < 1) {
							        	return true;
							        }
							        else{
							        	return false;
							        }
							      }
	    			},
	    			fathers_homephone:{
	    				required:function(element) {
							        if ($(".application-form #mothers_homephone").val().length < 1) {
							        	return true;
							        }
							        else{
							        	return false;
							        }
							      }
	    			},
	    			fathers_mobilephone:{
	    				required:function(element) {
							        if ($(".application-form #mothers_mobilephone").val().length < 1) {
							        	return true;
							        }
							        else{
							        	return false;
							        }
							      }
	    			},
	    			fathers_email:{
	    				required:function(element) {
							        if ($(".application-form #mothers_email").val().length < 1) {
							        	return true;
							        }
							        else{
							        	return false;
							        }
							      },
	    				email:true
	    			},
	    		},
	    		messages:{
	    			childs_age:{
	    				required:"Age must be a number.",
	    				number:"Age must be a number.",
	    				min:"Child's age must 0 or above."
	    			},
	    			childs_birthday:{
	    				date:"Birthday must be in the following format mm/dd/yyyy."
	    			},
	    			starting_on:{
	    				date:"Starting date must be in the following format mm/dd/yyyy."
	    			},
	    		}
	    	});

	    };
	    // Init inline Fancybox
	    if ($("form").hasClass("afterschool-application-form")) {

	    	$("#afterschool-application-form").validate({
	    		rules:{
	    			childs_name:{
	    				required:true
	    			},
	    			childs_surname:{
	    				required:true
	    			},
	    			childs_sex:{
	    				required:true
	    			},
	    			dob:{
	    				required:true,
	    				date:true
	    			},
	    			address:{
	    				required:true
	    			},
	    			starting_on:{
	    				required:true,
	    				date:true
	    			},
	    			parents_name:{
	    				required:true
	    			},
	    			parents_phone:{
	    				required:true
	    			},
	    			parents_email:{
	    				required:true,
	    				email:true
	    			},
	    			work_address:{
	    				required:true
	    			},
	    			checkboxes:{
	    				required:true,
	    				min:1
	    			}
	    		},
	    		messages:{
	    			dob:{
	    				date:"Birthday must be in the following format mm/dd/yyyy."
	    			},
	    			starting_on:{
	    				date:"Starting date must be in the following format mm/dd/yyyy."
	    			},
	    			checkboxes:{
	    				required:"Please select at least one club to continue.",
	    				min:"Please select at least one club to continue."
	    			},
	    		}
	    	});

	    	var checkedClubs = 0;
	    		checkcheckboxes(checkedClubs);
	    	// Clubs check box
	    	$(".club_checks").click(function(){
				checkedClubs = 0;
	    		checkcheckboxes(checkedClubs);
	    	});

	    };
	}
	function checkcheckboxes(checkedClubs){
		// Update count of checkboxes
		$(".club_checks").each(function(){
			elm = $(this);
			if (elm.is(":checked")) {
				checkedClubs++;
			};
		});
		// Update checkedbox count
		$("#afterschool-application-form .checkboxes").val(checkedClubs);
		// Disable/Enable checkboxes
		$(".club_checks").each(function(){
			elm = $(this);
			if (checkedClubs == 2 && !elm.is(":checked")) {
				elm.prop("disabled", true);
			}
			else{
				elm.prop("disabled", false);
			}
		});
	}
	/*
		Init function for page JS
	*/
	this.init = function(){
			
		loadTeamGallery();
		validateForm();
	};
};