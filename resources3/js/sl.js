// Created By Kamal 
$(document).ready(function(){
    var i = 0;
    //$('.delete_selected_btn').addClass('disabled');
     $(document).on("click", ".add-to-cart", function() {
        if($(this).parents().eq(3).validationEngine('validate')){
            var item_id = $(this).attr("data-item-id");
            var $form = jQuery(this).parents().eq(3);
            var formData = new FormData($form[0]);
            formData.append("action","AddToCart");
            formData.append("item_id",item_id);
            $.ajax({
                url: SITE_URL+'/front-ajax.php',
                type: 'post',
                data:formData,
                processData: false,
                contentType: false,
                success: function(data){
                    if(typeof data == 'object'){
                        if(data.status==1){
                            UIkit.notification(data.msg, "success");
							loadContent(location.href);
                        }else{
                            var error_msg = "";
                            $.each(data.errors, function( index, error ) {
                                error_msg += "<li>"+error+"</li>";
                            });
                            UIkit.notification(error_msg, "danger");
                        }
                    }else{
                        UIkit.notification(data, "danger");
                    }
                }
            });
        }
        return false;
    });
    $(document).on("click", ".add-irregular-to-cart", function() {
        if($(this).parents().eq(2).validationEngine('validate')){
            var section_id = $(this).attr("data-section-id");
            var $form = jQuery(this).parents().eq(2);
            var formData = new FormData($form[0]);
            formData.append("action","AddToCart");
            formData.append("irregular","1");
            formData.append("item_id",section_id);
            $.ajax({
                url: SITE_URL+'/front-ajax.php',
                type: 'post',
                data:formData,
                processData: false,
                contentType: false,
                success: function(data){
                    if(typeof data == 'object'){
                        if(data.status==1){
                            UIkit.notification(data.msg, "success");
							loadContent(location.href);
                        }else{
                            var error_msg = "";
                            $.each(data.errors, function( index, error ) {
                                error_msg += "<li>"+error+"</li>";
                            });
                            UIkit.notification(error_msg, "danger");
                        }
                    }else{
                        UIkit.notification(data, "danger");
                    }
                }
            });
        }
        return false;
    });
    $(document).on("submit", "#reset_pass", function(e){
		if($("#reset_pass").validationEngine("validate")){
			$.ajax({
				type: "POST",
				url: SITE_URL+"/front-ajax.php",
				data: $('#reset_pass').serialize()+"&reset_password=action",
				success: function(response){ 
					if(typeof response == 'object'){
						if(response.status==1){
							UIkit.notification('Password has been reset successfully.', "success");
							$(".reset_form_hide").hide();
						    $(".reset_pwd_container").show();
						}else{
							var error_msg = "";
							jQuery.each(response.errors, function( index, error ) {
								error_msg += "<li>"+error+"</li>";
							});
							UIkit.notification(error_msg, "danger");
						}
					}else{
						UIkit.notification(response, "danger");
					}
				}
			});
		}
		return false;        
	});
	$(document).on('submit','#ForgotPasswordForm',function(){
		if($("#ForgotPasswordForm").validationEngine("validate")){
			var form_values = $("#ForgotPasswordForm").serialize();
			$.ajax({
				type: "POST",
				url: "front-ajax.php",
				data: "forgot_pwd=action&"+form_values,
				success: function(response){
					if(typeof response == 'object'){
						if(response.status==1){
							UIkit.notification('Password reset link has been sent to your email address successfully.', "success");
							$('#forgot_pwd_modal').modal('hide');
						}else{
							var error_msg = "";
							jQuery.each(response.errors, function( index, error ) {
								error_msg += "<li>"+error+"</li>";
							});
							UIkit.notification(error_msg, "danger");
						}
					}else{
						UIkit.notification(response, "danger");
					}
				}
				
			});
		}
		return false;
    });
    
    $(document).on("click", "#single_checkbox_event", function() {
        $('.checkboxes-del').not(this).prop('checked', this.checked);
        if(this.checked) {
			$('.delete_selected_btn').prop("disabled", false);
        }else{
			$('.delete_selected_btn').prop("disabled", true);
		}
    });

    $(document).on("click", ".checkboxes-del", function() {
        if(this.checked) {
			$('.delete_selected_btn').prop("disabled", false);
        }else{
            var enable_delete_button = 0;
            $('.checkboxes-del').each(function() {
                if(this.checked) {
                    enable_delete_button = 1;
                }
            });
            if(enable_delete_button==0) {
                $('.delete_selected_btn').prop("disabled", true);
            }
		}
    });

    $(document).on('click','.delete_selected_btn',function(){
            var item_ids = [];
            jQuery('.checkboxes-del').each(function() {
                if(this.checked) {
                    item_ids.push($(this).attr("data-item-id"));
                }
            });
            UIkit.modal.confirm("Are you sure you want to delete these items?",{
            labels: {
                cancel: 'No',
                ok: 'Yes'
			}}).then(function(){
				$.ajax({
					type:'post',
					url:ADMIN_URL+'/items/items-ajax.php',
					data: "action=DeleteItems&item_ids="+item_ids,
					success:function(data){
						if(typeof data == 'object'){
							if(data.status==1){
							    $('.uk-modal').remove();
		                    	$('html').removeClass('uk-modal-page');
								UIkit.notification("Deleted Successfully.", "success");
								var __href = ADMIN_URL+'/items/list-items.php';
                                loadContent(__href);
							}else{
								var error_msg = "";
								$.each(data.errors, function( index, error ) {
									error_msg += "<li>"+error+"</li>";
								});
								UIkit.notification(error_msg, "danger");
							}
						}else{
							UIkit.notification(data, "danger");
						}
					}
				});
			}, function () {
				// nothing to do
				$('.uk-modal').remove();
			    $('html').removeClass('uk-modal-page');
			});
			
	});
    $(document).on('change','#product_range_filter',function(){
        var search_col = $('#search_col').val();
        var product_range_type = $('#product_range_filter').val();
        var onlyUrl = $('#reload_url').val();
        // console.log(onlyUrl);
        onlyUrl = onlyUrl+'&search_col='+encodeURIComponent(search_col)+'&product_range_type='+encodeURIComponent(product_range_type);
        if(Modernizr.history){
            loadContent(onlyUrl,function(data){
                history.pushState({ myTag: true }, null, onlyUrl);
            });
        }else{
            window.location.href=onlyUrl;
        }
        return false;
    });
    $(document).on('submit','#SearchSections',function(){
		var search_col = $('#search_col').val();
        var product_range = $('#product_range_filter').val();
		var onlyUrl = $('#reload_url').val();
		// console.log(onlyUrl);
		onlyUrl = onlyUrl+'&search_col='+encodeURIComponent(search_col)+'&product_range='+encodeURIComponent(product_range);
		if(Modernizr.history){
			loadContent(onlyUrl,function(data){
				history.pushState({ myTag: true }, null, onlyUrl);
			});
		}else{
			window.location.href=onlyUrl;
		}
        return false;
	});
	$(document).on('change','#product_range_filter_in_sections,#product_range_type_in_sections',function(){
		var search_col = $('#search_col').val();
        var product_range = $('#product_range_filter_in_sections').val();
        var product_range_type = $('#product_range_type_in_sections').val();
		var onlyUrl = $('#reload_url').val();
		// console.log(onlyUrl);
		onlyUrl = onlyUrl+'&search_col='+encodeURIComponent(search_col)+'&product_range='+encodeURIComponent(product_range)+'&product_range_type='+encodeURIComponent(product_range_type);
		if(Modernizr.history){
			loadContent(onlyUrl,function(data){
				history.pushState({ myTag: true }, null, onlyUrl);
			});
		}else{
			window.location.href=onlyUrl;
		}
        return false;
	});
    $(document).on('change','select#product_range',function(){
       var val = $(this).val();
       if(val!=''){
           $.ajax({
                url: ADMIN_URL+'/sections/sections-ajax.php',
    			type: 'post',
    			data: 'CheckIfCustomLengthAllowed=action&product_range_id='+val,
                success: function(data){
                    if(typeof data == 'object'){
                        if(data.status==1){
                            $('#price_per_cut').attr('disabled',false);
                        }else if(data.status==2){
                            $('#price_per_cut').attr('disabled',true);
                        }
                        else{
                            var error_msg = "";
                            jQuery.each(data.errors, function( index, error ) {
                                error_msg += "<li>"+error+"</li>";
                            });
                            UIkit.notification(error_msg, "danger");
                        }
                    }
                    else{
                        UIkit.notification(data, "danger");
                    }
                }
            });
        }
        return false;
    });
    $(document).on('submit','#SearchProducts',function(){
		var search_col = $('#search_col').val();
		var onlyUrl = $('#reload_url').val();
		// console.log(onlyUrl);
		onlyUrl = onlyUrl+'&search_col='+encodeURIComponent(search_col);
		if(Modernizr.history){
			loadContent(onlyUrl,function(data){
				history.pushState({ myTag: true }, null, onlyUrl);
			});
		}else{
			window.location.href=onlyUrl;
		}
		return false;
	});
    $(document).on('submit','#adminloginform',function(){
        if($("#adminloginform").validationEngine('validate')){
            var $form = jQuery("#adminloginform");
            var __href= ADMIN_URL+'/profile.php';
            var formData = new FormData($form[0]);
            formData.append("admin_login","action");
            $.ajax({
                url: SITE_URL+'/front-ajax.php',
				type: 'post',
				data: formData,
				processData: false,
                contentType: false,
                success: function(data){
                    if(typeof data == 'object'){
                        if(data.status==1){
                            UIkit.notification(data.msg, "success");
                            window.location.href = __href;
                        }
                        else{
                            var error_msg = "";
                            jQuery.each(data.errors, function( index, error ) {
                                error_msg += "<li>"+error+"</li>";
                            });
                            UIkit.notification(error_msg, "danger");
                        }
                    }
                    else{
                        UIkit.notification(data, "danger");
                    }
                }
            });
            return false;
        }
    });

    $(document).on('click','#add_postcode',function(){
        var add_row = true;
        //console.log($('.post_inputs .append_to:last-child input').val());
        if($('.post_inputs .append_to:last-child input').val()==""){
            $('.post_inputs .append_to:last-child input').focus();
            add_row = false;
            return false;
        }
        if(add_row = true){
            //console.log($('.post_input:last-child').val());
            i++;
            $('.post_inputs .append_to:last-child').after("<div class='row append_to appended_row'><div class='col-lg-4'><input type='text' class='form-control post_input validate[required,custom[postcode]] minSize[4]' name='postcodes[]' value=''></div><div class='col-lg-2'><button type='button' class='btn btn-danger remove_postcode'><i class='fa fa-minus'></i></button></div></div>");
        }
        else{
            // nothing to do
        }
    });

    $(document).on('click','.remove_postcode',function(){
        $(this).parent().parent().remove();
    });

    $(document).on('submit','#PostCodeSaveForm',function(){
        if($("#PostCodeSaveForm").validationEngine('validate')){
            var $form = jQuery("#PostCodeSaveForm");
            var formData = new FormData($form[0]);
            formData.append("postcodes_save","action");
            $.ajax({
                url: ADMIN_URL+'/settings/settings-ajax.php',
				type: 'post',
				data: formData,
				processData: false,
                contentType: false,
                success: function(data){
                    if(typeof data == 'object'){
                        if(data.status==1){
                            UIkit.notification(data.msg, "success");
                        }
                        else{
                            var error_msg = "";
                            jQuery.each(data.errors, function( index, error ) {
                                error_msg += "<li>"+error+"</li>";
                            });
                            UIkit.notification(error_msg, "danger");
                        }
                    }
                    else{
                        UIkit.notification(data, "danger");
                    }
                }
            });
            return false;
        }
    });
    
    $(document).on('change','#suggested_stocked_section',function(){
        var suggested_stocked_section = $('#suggested_stocked_section').val();
        var data_pid = $(this).attr("data-pid");
        var pid = $("input[name='product_recommendation_id']").val();
		$.ajax({
			url: ADMIN_URL+'/product-recommendations/product-recommendations-ajax.php',
			type: 'post',
			data: "sid="+suggested_stocked_section+"&suggested_items=action"+"&pid="+pid,
			success: function(result){
				$('.length_'+data_pid).html(result['html']);
			}
		});
        return false;
    });
    
    $(document).on('change','#product_range_type',function(){
         if($(this).val()==1){
            $('#is_custom_length_allowed').prop("disabled", false);
            $('#is_custom_length_allowed').prop("checked", false);
        }
        else if($(this).val()==2){
            $('#is_custom_length_allowed').prop("disabled", true);
            $('#is_custom_length_allowed').prop("checked", true);
        }
    });
    
    $(document).on('submit','#SignUpForm',function(){
        if($("#SignUpForm").validationEngine('validate')){
            var $form = jQuery("#SignUpForm");
            var formData = new FormData($form[0]);
            formData.append("action","SaveSignUpForm");
            $.ajax({
                url: SITE_URL+'/front-ajax.php',
				type: 'post',
				data: formData,
				processData: false,
                contentType: false,
                success: function(data){
                    if(typeof data == 'object'){
                        if(data.status==1){ 
                            $('#SignUpForm').hide();
                            $('.toggle_menu2').show();
                            $('.signup-head').hide();
                        }
                        else{
                            var error_msg = "";
                            jQuery.each(data.errors, function( index, error ) {
                                error_msg += "<li>"+error+"</li>";
                            });
                            UIkit.notification(error_msg, "danger");
                        }
                    }
                    else{
                        UIkit.notification(data, "danger");
                    }
                }
            });
            return false;
        }
    });
    
     $(document).on('submit','#login-form',function(){
        if($("#login-form").validationEngine('validate')){
            var $form = jQuery("#login-form");
            var __href= SITE_URL;
            var formData = new FormData($form[0]);
            formData.append("login","action");
            $.ajax({
                url: SITE_URL+'/front-ajax.php',
				type: 'post',
				data: formData,
				processData: false,
                contentType: false,
                success: function(data){
                    if(typeof data == 'object'){
                        if(data.status==1){
                            UIkit.notification(data.msg, "success");
                            window.location.href = __href;
                        }
                        else{
                            var error_msg = "";
                            jQuery.each(data.errors, function( index, error ) {
                                error_msg += "<li>"+error+"</li>";
                            });
                            UIkit.notification(error_msg, "danger");
                        }
                    }
                    else{
                        UIkit.notification(data, "danger");
                    }
                }
            });
            return false;
        }
    });
    
});