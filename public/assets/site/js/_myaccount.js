var myaccount = new function(){

	/**
	* Upload and crop user avatar with cropic js
	*/
	function imageUpload(){
		var url = Site.Config.url;
		var user = Site.data.userdata;
		var cropperOptions = {
			modal:true,
			zoomFactor:20,
			rotateControls:false,
			customUploadButtonId:'newAvatarTriger',
			uploadUrl:url+'/api/user/uploadAvatar',
			cropUrl:url+'/api/user/cropAvatar',
			uploadData:{
				"user": user.id,
				"_token": Site.Config.token
			},
			cropData:{
				"user": user.id,
				"_token": Site.Config.token
			},
			onAfterImgCrop: function(data){
				$("#account_avatar").prop("src", url+"/"+data.url);
				resetCroper();
			},
			onError: function(errormsg){
			}
		};
		var cropper = new Croppic('newAvatarCrop', cropperOptions);

		function resetCroper(){
			cropper.reset();
		}
	}
	/**
	* Messages table checkbox actions
	*/
	function checkbox_action () {
		$("#master_checkbox").click(function(event){

			var checked = 0;

			if ($(this).is(":checked")) {

				$(".table_checkbox").prop("checked", true);
				$(".bulk_checked_list").html("");
				$(".table_checkbox").each(function(){
					var item_id = $(this).val();
					$("<input type='hidden' name='list[]' value='"+item_id+"' id='item"+item_id+"'>").prependTo(".bulk_checked_list");
				});
			}
			else{

				$(".table_checkbox").prop("checked", false);
				$(".bulk_checked_list").html("");
			}

			$(".table_checkbox").each(function(){

				if ($(this).is(":checked")) {
					checked ++;
				};

			});

			if (checked > 0) {
				// There are checked
				$(".bulkActionElement").removeClass("hide");
			}
			else{
				// No checked
				if (!$(".bulkActionElement").hasClass("hide")) {
					$(".bulkActionElement").addClass("hide");
				};
			}
			
		});

		$(".table_checkbox").click(function(){

			var item_id = $(this).val(),
				checked = 0;

			if (!$(this).is(":checked")) {

				$("#master_checkbox").prop("checked", false);

				$(".bulk_checked_list #item"+item_id+"").remove();
			}
			else{

				$("<input type='hidden' name='list[]' value='"+item_id+"' id='item"+item_id+"'>").prependTo(".bulk_checked_list");
			}

			$(".table_checkbox").each(function(){

				if ($(this).is(":checked")) {
					checked ++;
				};

			});

			if (checked > 0) {
				// There are checked
				$(".bulkActionElement").removeClass("hide");
			}
			else{
				// No checked
				if (!$(".bulkActionElement").hasClass("hide")) {
					$(".bulkActionElement").addClass("hide");
				};
			}
		});
	}

	/*
	* Init function for page JS
	*/
	this.init = function(){
		imageUpload();
		checkbox_action();		
	};
};