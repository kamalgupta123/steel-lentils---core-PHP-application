$(document).on('show.bs.dropdown','.dropdown', function(e){
  $(this).find('.dropdown-menu').first().stop(true, true).slideDown(300);
});

$(document).on('hide.bs.dropdown','.dropdown', function(e){
  $(this).find('.dropdown-menu').first().stop(true, true).slideUp(200);
});

 

$(document).ready(function() {
	$(document).on('click','.sidebartoggle',function(){
		if(!$('.left-sidebar').hasClass('sidebar-closed')){
			$('.left-sidebar').addClass('sidebar-closed');
// 			$('.left-sidebar').width(250);
		}
		else{
			$('.left-sidebar').removeClass('sidebar-closed');
// 			$('.left-sidebar').width(250);
		}
		if(!$('.page-wrapper').hasClass('wrapper-closed')){
			$('.page-wrapper').addClass('wrapper-closed');
// 			$('.page-wrapper').css("margin-left","250px");
		}
		else{
			$('.page-wrapper').removeClass('wrapper-closed');
// 			$('.page-wrapper').css("margin-left","250px");
		}
	});
	$(document).on("click",".nav-item > .nav-toggle", function() {
		if ($(this).hasClass("active")) {
			$(this).removeClass("active");
			$(this)
				.siblings(".sub-menu")
				.slideUp(200);
		} else {
			if($(this).parents('.sub-menu').length>0){
				$(this).parent().siblings().find("a").removeClass("active");
				$(this).parent().siblings().find(".sub-menu").slideUp(200);
			}else{
				$(".nav-item > a").removeClass("active");
				$(".sub-menu").slideUp(200);
			}
			$(this).addClass("active");
			$(this)
			.siblings(".sub-menu")
			.slideDown(200);
		}
	});
	
	$(document).on("click",'.nav-toggle',function(e){   
		e.preventDefault();
	});
	
	$(document).on('click', '.sidebar-toggle', function(){
		$('body').toggleClass('active-menu');
	});
	

});



jQuery('.mode').modal('show', {backdrop: 'static', keyboard: false});


