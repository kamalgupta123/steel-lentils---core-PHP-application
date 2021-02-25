function scroll_to_class(element_class, removed_height) {
	var scroll_to = $(element_class).offset().top - removed_height;
	if($(window).scrollTop() != scroll_to) {
		$('html, body').stop().animate({scrollTop: scroll_to}, 0);
	}
}
function bar_progress(progress_line_object, direction) {
	var number_of_steps = progress_line_object.data('number-of-steps');
	var now_value = progress_line_object.data('now-value');
	var new_value = 0;
	if(direction == 'right') {
		new_value = now_value + ( 100 / number_of_steps );
	}
	else if(direction == 'left') {
		new_value = now_value - ( 100 / number_of_steps );
	}
	progress_line_object.attr('style', 'width: ' + new_value + '%;').data('now-value', new_value);
}
function getDetailsWizardData(form_saved,approved_est){
	var reload = $('.ticket_wizard_form #reload').val();
	var job_id = $('.ticket_wizard_form #job_id').val();
	var change = '';
	if($('.ticket_wizard_form #back_to_est').val()==1){
		if($('.ticket_wizard_form #change_in_est').val()==1){
			change = "&change=1";
		}else{
			change = "&change=0";
		}
	}
	$.ajax({
		type:'post',
		url:SITE_URL+'/front_ajax.php',
		data:'GetDetailsWizardData=action&job_id='+job_id+'&save='+form_saved+'&approved_est='+approved_est+'&reload='+reload+change,
		success:function(data){
			if(typeof data == 'object'){
				if(data.status==1){
					$('.ticket_wizard_form #allocated_time').val(data.allocated_time);
					var start_time = new Date(PHP_CURRENT_DATETIME.replace(/-/g,"/"));
					console.log(PHP_CURRENT_DATETIME.replace(/-/g,"/"));
					if(start_time!==''){
        				var due_time = new Date(start_time.getTime() + data.allocated_time*60000 );
				    // 	console.log(start_time);
				    // 	console.log(due_time);
        				var formatted_start_time = ("0"+start_time.getDate()).slice(-2) + "-" + ("0"+(start_time.getMonth() + 1)).slice(-2) + "-" + start_time.getFullYear() + " " +  ("0"+start_time.getHours()).slice(-2) + ":" + ("0"+start_time.getMinutes()).slice(-2) + ":" + ("0"+start_time.getSeconds()).slice(-2);
        				var formatted_due_time = ("0"+due_time.getDate()).slice(-2) + "-" + ("0"+(due_time.getMonth() + 1)).slice(-2) + "-" + due_time.getFullYear() + " " +  ("0"+due_time.getHours()).slice(-2) + ":" + ("0"+due_time.getMinutes()).slice(-2) + ":" + ("0"+due_time.getSeconds()).slice(-2);
					   // console.log(formatted_due_time);
    			    	$(".f1 #start_time").val(formatted_start_time);
    			    	$(".f1 #due_date").val(formatted_due_time);
    				}
					// $('.ticket_wizard_form #deposit').val(parseFloat(data.max_deposit).toFixed(2));
					/* if(data.deposit_type!=''){
						if(data.deposit_type==1){
							$('[data-deposit-type="1"]').html('<i class="fas fa-check-circle"></i> Eftpos');
							$('[data-deposit-type="1"]').attr("data-save","1");
							$('[data-deposit-type="2"]').text("Cash");
							$('[data-deposit-type="2"]').attr("data-save","0");
						}else if(data.deposit_type==2){
							$('[data-deposit-type="2"]').html('<i class="fas fa-check-circle"></i> Cash');
							$('[data-deposit-type="2"]').attr("data-save","1");
							$('[data-deposit-type="1"]').text("Eftpos");
							$('[data-deposit-type="1"]').attr("data-save","0");
						}
					} */
					if(data.max_deposit==0){
						$('.ticket_wizard_form #deposit').val(parseFloat(89).toFixed(2));
					}else{
						$('.ticket_wizard_form #deposit').val(parseFloat(data.max_deposit).toFixed(2));
						$(".deposit_amount").attr("data-max",data.max_deposit);
						// $('[data-deposit-type="2"]').attr("data-max",data.max_deposit);
					}
				}
			}
		}
	});
}
function save_form(action_url,formData,__this,next_wizard,print){
	jQuery.ajax({
		type:'post',
		url:action_url,
		data:formData,
		processData: false,
		contentType: false,
		success: function(data){
			if(typeof data == 'object'){
				if(data.status==1){
					if($(".ticket_wizard_form #current_step").val()==1 && $(".ticket_wizard_form #job_id").val()==''){
						var _href = location.href;
						_href = _href+"/?job_id="+data.job_id;
						if(Modernizr.history){
							loadContent(_href,function(data){
								history.pushState(null, null, _href);
							});
							return false;
						}else{
							window.location.href=_href;
						}
					}else if(next_wizard=="device"){
						$('.ticket_wizard_form #reload').val("2");
						$('.ticket_wizard_form #current_step').val("2");
						$(__this).attr('data-insert',"0");
						$.ajax({
							type:'post',
							url:SITE_URL+'/front_ajax.php',
							data:'Update-Session=action&update=redirect',
								success:function(data){}
						});
					}
					if(next_wizard==""){
						var next_url = $('#SaveNewTicketForm').attr("data-next-url");
						var job_id = $(".ticket_wizard_form #job_id").val();
						// send ajax to get html to print 
						if(print==1){
							$.ajax({
								type:'post',
								url:SITE_URL+'/front_ajax.php',
								data:'OpenTermsOfServiceModal=action&job_id='+job_id+'&print=1',
								success:function(data){
									// console.log(data);
									// var WindowObject = window.open("", "PrintWindow",
									// "width=750,height=650,top=50,left=50,toolbars=no,scrollbars=yes,status=no,resizable=yes");
									// WindowObject.document.writeln(data);
									// WindowObject.document.close();
									// WindowObject.focus();
									// WindowObject.onload = function (){
										// WindowObject.print();
										// WindowObject.close();
									// }
									var iframe = document.createElement('iframe');
									html = data;
									iframe.src = 'data:text/html;charset=utf-8,' + encodeURIComponent(html);
									iframe.id = 'dynamic_iframe_for_print2';
									document.body.appendChild(iframe);
									updateSession(next_url);
								}
							});
						}else{
							updateSession(next_url);
						}
					}
				}
				else{
					var error_msg = "";
					jQuery.each(data.errors, function( index, error ) {
						error_msg += "<li>"+error+"</li>";
					});
					UIkit.notification(error_msg, "danger");
					return false;
				}
			}
			else{
				UIkit.notification(data, "danger");
			}
			return false;
		}
	});
}
function updateSession(next_url){
	// $.ajax({
		// type:'post',
		// url:SITE_URL+'/front_ajax.php',
		// data:'Update-Session=action&update=unset_session',
		// success:function(response){
			if(Modernizr.history){
				loadContent(next_url,function(response){
					history.pushState(null, null, next_url);
				});
				return false;
			}else{
				window.location.href=next_url;
			}
		// }
	// });
}
function getOnBoardWizardData(change){
	var job_id = $('.ticket_wizard_form #job_id').val();
	var approved_est_type = $('[data-next="details"]').attr("data-approved-est");
	var approved_est_type1 = $('[data-next="on_board"]').attr("data-approved-est");
	var reload = $('[data-next="on_board"]').attr("data-reload");
	var change_est = 1;
	if(approved_est_type==approved_est_type1){
		change_est = 0;
	}
	setTimeout(function(){
		$.ajax({
			type:'post',
			url:SITE_URL+'/front_ajax.php',
			data:"GetOnBoardDetails=action&job_id="+job_id+"&approved_est_type="+approved_est_type+"&change_ques="+change+"&change_est="+change_est+"&reload="+reload,
			success:function(data){
				if(data != ''){
					$('.on_board_details').html(data);
					$('[data-next="on_board"]').attr("data-reload","0");
				}
			}
		});
	},100);
		
}
function moveToDetailsWizard(__this,parent_fieldset,current_active_step,progress_line){
	var job_id = $('.ticket_wizard_form #job_id').val();
	__this.attr('data-moved','1');
	$.ajax({
		type:'post',
		url:SITE_URL+'/front_ajax.php',
		data:'NewJobPreTest=action&job_id='+job_id,
		success:function(data){
			if(data!=''){
				$(".pre_test_panel").html(data);
			}
		}
	});
	var form_saved = __this.attr("data-save");
	var approved_est = __this.attr("data-approved-est");
	getDetailsWizardData(form_saved,approved_est);
	if(approved_est==''){
		$('#allocated_time').closest('.form-group').find('label').html('Allocated Time <span class="required_star"><span title="No Estimate has been approved." data-toggle="tooltip"><i class="fas fa-exclamation-triangle"></i></span></span>');
	}else{
		$('#allocated_time').closest('.form-group').find('label').html('Allocated Time');
	}
	$('[data-next="on_board"]').attr("data-approved-est",approved_est);
	$('.ticket_wizard_form #reload').val("4");
	// console.log(2);
	$('.ticket_wizard_form #current_step').val("4");
	parent_fieldset.fadeOut(400, function() {
		current_active_step.removeClass('active').addClass('activated').next().addClass('active');
		bar_progress(progress_line, 'right');
		__this.next().fadeIn();
		scroll_to_class( $('.f1'), 20 );
		$('.details_wizard_fieldset').css("display","block");
	});
}
function changesLost(__this,parent_fieldset,current_active_step,progress_line){
	UIkit.modal.confirm("The changes made will be lost. Are you sure you want to continue?").then(function(){
		$('.ticket_wizard_form #current_step').val("4");
		$('.ticket_wizard_form #reload').val("4");
		var form_saved = __this.attr("data-save");
		var approved_est = __this.attr("data-approved-est");
		getDetailsWizardData(form_saved,approved_est);
		if(approved_est==''){
			$('#allocated_time').closest('.form-group').find('label').html('Allocated Time <span title="No user available to perform selected work" data-toggle="tooltip"><i class="fas fa-exclamation-triangle"></i></span>');
		}
		$.ajax({
			type:'post',
			url:SITE_URL+'/front_ajax.php',
			data:'NewJobPreTest=action',
			success:function(data){
				if(data!=''){
					$(".pre_test_panel").html(data);
				}
			}
		});
		__this.attr('data-save','1');
		__this.attr('data-moved','1');
		// console.log(1);
		$('.ticket_wizard_form #current_step').val("4");
		parent_fieldset.fadeOut(400, function() {
			current_active_step.removeClass('active').addClass('activated').next().addClass('active');
			bar_progress(progress_line, 'right');
			__this.next().fadeIn();
			scroll_to_class( $('.f1'), 20 );
			$('.details_wizard_fieldset').css("display","block");
		});
		$('[data-next="on_board"]').attr("data-approved-est",approved_est);
		return false;
	},function () {
		// do nothing
	});
}
function proceedFromEstimates(__this,parent_fieldset,current_active_step,progress_line){
	var validate=0;
	if(__this.attr("data-published-est")==''){
    	if($("#SaveEstimateform_ultimate").validationEngine("validate") && $("#SaveEstimateform_budget").validationEngine("validate")){
    		validate=1;
    	}
	}else{
	    validate=1;
	}
	if(validate==0){
		return false;
	}
	if(__this.attr("data-save")==0){
		success = 0;
		if(__this.attr("data-approved-est")==''){
			UIkit.modal.confirm("No work will be completed until an estimates is approved. Please approve an estimate if you want any work to be done.").then(function(){
				changesLost(__this,parent_fieldset,current_active_step,progress_line);
			},function () {
				// do nothing
			});
		}else{
			changesLost(__this,parent_fieldset,current_active_step,progress_line);
		}
	}
	else if(__this.attr("data-save")==1 && __this.attr("data-moved")!=1){
		success = 0;
		if(__this.attr("data-approved-est")==''){
			UIkit.modal.confirm("No work will be completed until an estimates is approved. Please approve an estimate if you want any work to be done.").then(function(){
				moveToDetailsWizard(__this,parent_fieldset,current_active_step,progress_line)
			},function () {
				// do nothing
			});
		}else{
			moveToDetailsWizard(__this,parent_fieldset,current_active_step,progress_line)
		}
	}else if(__this.attr("data-save")==1 && __this.attr("data-moved")==1){
		$('.ticket_wizard_form #current_step').val("4");
		parent_fieldset.fadeOut(400, function() {
			current_active_step.removeClass('active').addClass('activated').next().addClass('active');
			bar_progress(progress_line, 'right');
			__this.next().fadeIn();
			scroll_to_class( $('.f1'), 20 );
			$('.details_wizard_fieldset').css("display","block");
		});
	}
}

jQuery(document).ready(function() {
    /*Fullscreen background*/
    // $.backstretch("assets/img/backgrounds/1.jpg");
    $('#top-navbar-1').on('shown.bs.collapse', function(){
    	$.backstretch("resize");
    });
    $('#top-navbar-1').on('hidden.bs.collapse', function(){
    	$.backstretch("resize");
    });
    /*Form*/
    $('.f1 fieldset:first').fadeIn('slow');
    $('.f1 input[type="text"], .f1 input[type="password"], .f1 textarea').on('focus', function() {
    	$(this).removeClass('input-error');
    });
    // next step
    $(document).on('click','.f1 .btn-next', function() {
		if($('.select2').is(":visible")){
			// console.log('fdf');
			$('.select2').select2();
		}
		var __this = $(this);
    	var next_wizard = $(this).attr('data-next');
    	var insert = $(this).attr('data-insert');
		var $form = jQuery("#"+'SaveNewTicketForm');
		var action_url = $form.attr("data-action-url");
    	var parent_fieldset = $(this).parents('fieldset');
    	var next_step = true;
    	// navigation steps / progress steps
    	var current_active_step = $(this).parents('.f1').find('.f1-step.active');
    	var progress_line = $(this).parents('.f1').find('.f1-progress-line');
		if(__this.attr("data-published-est")=='' || typeof __this.attr("data-published-est") == typeof undefined){
			if($form.validationEngine("validate")){
				next_step = true;
			}else{
				next_step = false;
			}
    	}
    	// fields validation
    	if( next_step ) {
			var formData = new FormData($form[0]);
			var success = 1;
			var confirm_save=0;
			if(next_wizard=="on_board"){
				/* if($('.finalize_test').attr("data-finalize")!=1){
					UIkit.notification("The Pre test has not been Finalized. Please finalize continue.","danger");
					return false;
				} */
				if($('.ticket_wizard_form #deposit').val()!='' || $('.ticket_wizard_form #deposit').val()!=0){
					if($(__this).attr("data-save")==0 && $("[data-deposit-type='1']").attr("data-save")==0 && $("[data-deposit-type='2']").attr("data-save")==0){
						success = 0;
						// console.log(next_wizard+'|1|'+success+'||'+confirm_save);
						UIkit.modal.confirm("The Deposit amount is not saved. Are you sure you want to continue?").then(function(){
							confirm_save = 1;
							success = 1;
							save_form(action_url,formData,__this,next_wizard);
							var change = $(__this).attr("data-change");
							getOnBoardWizardData(change);
							parent_fieldset.fadeOut(400, function() {
								current_active_step.removeClass('active').addClass('activated').next().addClass('active');
								bar_progress(progress_line, 'right');
								$(this).next().fadeIn();
								scroll_to_class( $('.f1'), 20 );
							});
							$('.ticket_wizard_form #current_step').val("5");
							$(__this).attr("data-save","1");
							$(__this).attr("data-change","0");
							$('.ticket_wizard_form #reload').val("5");
							return false;
						},function () {
							// do nothing
						});
					}
					else{
						confirm_save = 1;
						var change = $(__this).attr("data-change");
						getOnBoardWizardData(change);
						$('.ticket_wizard_form #current_step').val("5");
						$(__this).attr("data-save","1");
						$(__this).attr("data-change","0");
						$('.ticket_wizard_form #reload').val("5");
						// $(__this).attr("data-change","0");
					}
				}else{
					confirm_save = 1;
				}
			}
			else{
				confirm_save = 1;
				// console.log(next_wizard+'|2|'+success+'||'+confirm_save);
			}
			if(next_wizard!="details" && confirm_save==1){
				save_form(action_url,formData,__this,next_wizard);
			}
			if((typeof insert == typeof undefined && insert == false) && (next_wizard=="device")){
				$('.ticket_wizard_form #current_step').val("2");
			}
			else if(next_wizard=='estimates'){
				var val_changed = $('[data-next="estimates"]').attr("data-val-change");
				var change = '';
				var reload = $('.ticket_wizard_form #reload').val();
				if($('.ticket_wizard_form #backward').val()==1){
					if($('.ticket_wizard_form #change_in_fields').val()==1){
						change = "&change=1";
					}else{
						change = "&change=0";
					}
				}
				$('.ticket_wizard_form #backward').val('0');
				$('.ticket_wizard_form #change_in_fields').val('0');
				var job_id = $('.ticket_wizard_form #job_id').val();
				var created_estimate = $('.ticket_wizard_form #created_estimate').val();
				var customer_id = $('.ticket_wizard_form #customer_id').val();
				var model_id = $('.ticket_wizard_form #model_id').val();
				var checked = [];
				$("input[name='work_to_complete[]']:checked").each(function(){
					checked.push($(this).val());
				});
				$.ajax({
					type:'post',
					url:SITE_URL+'/front_ajax.php',
					data:'GetEstimateWizardData=action&created_estimate='+created_estimate+'&work_to_complete_id='+checked+'&job_id='+job_id+'&customer_id='+customer_id+'&model_id='+model_id+'&reload='+reload+'&val_changed='+val_changed+''+change,
					success:function(data){
						if(data!=''){
							$('.estimate_wizard').html(data);
							if($('.ticket_wizard_form #reload').val()==2){
								$('.ticket_wizard_form #reload').val("3");
							}
							$('.ticket_wizard_form #created_estimate').val("1");
							/* $.ajax({
								type:'post',
								url:SITE_URL+'/front_ajax.php',
								data:'Update-Session=action&update=created_estimate',
								success:function(data){}
							}); */
						}
					}
				});
				$('.ticket_wizard_form #current_step').val("3");
				$('[data-next="estimates"]').attr("data-val-change","0");
				$('[data-next="estimates"]').attr("data-moved","1");
			}
			else if(next_wizard=="details"){
				success = 0;
				if($(this).attr("data-published-est")==''){
					UIkit.modal.confirm("Estimates are about to be lost, do you want to publish the estimates now?").then(function(){
						$('[data-action="publish"]').attr("data-triggered","1");
						// $('[data-action="publish"]').trigger("click");
						$('[data-action="publish"]').each(function(){
							if($(this).attr("data-save")==0){
								$(this).trigger("click");
							}
						});
						// setTimeout(function(){
							// proceedFromEstimates(__this,parent_fieldset,current_active_step,progress_line);
						// },3000);
					},function () {
						// do nothing
					});
				}else{
					proceedFromEstimates(__this,parent_fieldset,current_active_step,progress_line);
				}
				
			}
			if(success==1){
				if(next_wizard=="details"){
					$(this).attr('data-save','1');
					$('.ticket_wizard_form #current_step').val("4");
				}
				// console.log(next_wizard+'|4|'+success+'||'+confirm_save);
				// console.log(3);
				parent_fieldset.fadeOut(400, function() {
					current_active_step.removeClass('active').addClass('activated').next().addClass('active');
					bar_progress(progress_line, 'right');
					$(this).next().fadeIn();
					scroll_to_class( $('.f1'), 20 );
				});
			}
    	}
    });
    
    // previous step
    $(document).on('click', '.f1 .btn-previous', function() {
		if($('.select2').is(":visible")){
			// console.log('fdf');
			$('.select2').select2();
		}
    	var current_active_step = $(this).parents('.f1').find('.f1-step.active');
    	var progress_line = $(this).parents('.f1').find('.f1-progress-line');
    	var prev_wizard = $(this).attr('data-previous');
		if(prev_wizard=='customer'){
			$('.ticket_wizard_form #current_step').val("1");
		}else if(prev_wizard=="device"){
			$('.ticket_wizard_form #backward').val("1");
			$('.ticket_wizard_form #current_step').val("2");
		}else if(prev_wizard=="estimates"){
			$('.ticket_wizard_form #back_to_est').val("1");
			$('.ticket_wizard_form #current_step').val("3");
		}else if(prev_wizard=="details"){
			$('.ticket_wizard_form #current_step').val("4");
		}
    	$(this).parents('fieldset').fadeOut(400, function() {
    		current_active_step.removeClass('active').prev().removeClass('activated').addClass('active');
    		bar_progress(progress_line, 'left');
    		$(this).prev().fadeIn();
			scroll_to_class( $('.f1'), 20 );
    	});
    });

	$(document).on('click','.f1 .btn-confirm',function(){
		var __this = $(this);
		var action = __this.attr("data-type");
		var $form = jQuery("#"+'SaveNewTicketForm');
		var action_url = $form.attr("data-action-url");
		var msg;
		var formData = new FormData($form[0]);
		var print=0;
		var show_prompt=0;
		if(action==1){
			//msg = "This action will create the ticket for your customer and email the terms of service on registered email address. Are you are you want to perform this action?";
			formData.append('email_to_customer',1);
		}else if(action==2){
			msg = "Mother nature is turning in her grave! Please think of the environment before printing this! Are you sure you want to proceed?";
			formData.append('print_pdf',1);
			print=1;
			show_prompt=1;
		}
		if($form.validationEngine("validate")){
			var proceed = 1;
			$(".terms_of_service").each(function(){
				if(!$(this).is(':checked')){
					proceed = 0;
					return false;
				}
			});
			if(proceed==1){
				if(show_prompt==1){
					UIkit.modal.confirm(msg).then(function(){
						save_form(action_url,formData,__this,'',print);
						
					},function () {
						// do nothing
						print=0;
						save_form(action_url,formData,__this,'',print);
					});
				}else{
					save_form(action_url,formData,__this,'',print);
				}
			}else{
				UIkit.notification("Please agree to all the terms to continue.", "danger");
			}
			
			setTimeout(function(){ $("body").removeAttr("style"); }, 1);
		}
	});
	
	
});
