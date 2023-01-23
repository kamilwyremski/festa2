$(document).ready(function(){

	$('[data-toggle="collapse"]').click(function(){
		$(this).blur();
	})
	
	$('.select_state').change(function(){
		$('.substates').hide().find('select').prop("disabled", true);
		$('.substate_'+$(this).val()).show().find('select').prop("disabled", false);
	})

	$(".ajax_confirm").click(function(){
		const $this = $(this);
		if (confirm($this.data('title'))) {
			let mydata = $this.data();
			$.ajax({
				url: "php/ajax.php",
				type: "POST",
				data:mydata,
				async: false,
				success: function(data) {
					window.location.href = window.location;
				}
			});
		}
    return false;
  });

	$('.form-search').submit(function(){
		const $form = $(this);
		const $address = $form.find(':input[name="address"]');
		let flag = true;
		if(!$address.length || $address.val()==''){
			$form.find(':input[name="distance"]').prop( "disabled",true);
		}
		$form.find(':input:enabled').not(':input[type=submit], [name=search]').each(function(){
			const $this = $(this);
			if($this.val()=='' && $this.prop("defaultValue")==''){
				$this.prop( "disabled",true);
			}else{
				flag = false;
			}
		})
		if(flag){
			$form.find(':input[name="search"]').prop( "disabled",true);
		}
	})

	const $back_to_top = $('#back_to_top');
	function scroll() {
		if($(window).scrollTop() > 150){
			$back_to_top.removeClass('back_to_top_hidden');
		}else{
			$back_to_top.addClass('back_to_top_hidden');
		}
	}
	scroll();
	document.onscroll = scroll;

	$('.return_false a').click(function(){
		$(this).blur();
		return false;
	})

	$back_to_top.on("click", function(){
		$('html, body').stop().animate({scrollTop: 0}, 300);
		$(this).blur();
		return false;
	})

	$('.show_hidden_data').on("click", function(){
		const $this = $(this);
		const $parent = $this.parents('a');
		const type = $this.data('type');
		let data = '';
		let href = '';
		if(type == 'phone'){
			data = $this.data('data')
			href = 'tel:'+data;
		}else if(type == 'email'){
			data = $this.data('data_0')+'@'+$this.data('data_1');
			href = 'mailto:'+data;
		}else{
			href = data = $this.data('data');
		}
		$this.text(data).contents().unwrap();
		$parent.attr("href", href);
		return false;
	})

	$("#facebook_side").hover(function(){
			$(this).stop(true,false).animate({right: "0px"}, 500 );},
		function(){
			$(this).stop(true,false).animate({right: "-300px"}, 500 );
		}
	);

	$('.reset_form').click(function(){
		const $form = $(this).parents('form');
		$form.find('input').each(function(){
			const $this = $(this);
			switch ($this.attr('type')) {
				case 'text':
				case 'number':
					$this.val('');
					break;
				case 'radio':
				case 'checkbox':
					$this.prop('checked',false);
			}
		});
		$form.find('select').each(function(){
			$(this).prop("selectedIndex", 0);
		})
		$(this).blur();
		return false;
	})

	$('.index_show_subcategories').click(function(){
		const $this = $(this);
		const active = $this.hasClass('active');
		const $subcategories = $('#index_subcategory_'+$this.data('id'));
		let eq = 0;
		$('.index_subcategories').hide();
		$('.index_show_subcategories').removeClass('active');
		if(!active){
			const index = $this.data('index');
			eq = index;
			const window_width = $(window).width();
			if(window_width < 540){
				eq = index-1;
			}else if(window_width < 768){
				if(index%2 == 0){
					eq = index-1;
				}
			}else if(window_width < 992){
				mod = index%3;
				if(mod == 0){
					eq = index-1;
				}else if(mod == 1){
					eq = index+1;
				}
			}else{
				mod = index%4;
				if(mod == 0){
					eq = index-1;
				}else if(mod == 1){
					eq = index+2;
				}else if(mod == 2){
					eq = index+1;
				}
			}
			$subcategories.insertAfter('.index_categories:eq('+eq+')');
			$subcategories.show();
			$this.addClass('active');
		}
		$this.blur();
		return false;
	})

	if(!localStorage.rodo_accepted) {
		$("#rodo-message").modal('show');
	}
});

function closeRodoWindow(){
	localStorage.rodo_accepted = true;
	$("#rodo-message").modal('hide');
}

if (window.location.href.indexOf('#_=_') > 0) {
	window.location = window.location.href.replace(/#.*/, '');
}

$(function(){
	const options = {
		url: function(phrase) {
			return "php/ajax.php?action=classifieds_sugested_keywords&keywords=" + phrase;
		},
		list: {
			match: {
				enabled: true
			}
		},
		theme: "square"
	};
	$("#search_keywords").easyAutocomplete(options);
})

$(window).on("load", function (){
	const $js_scroll_page = $('#js_scroll_page')
  if($js_scroll_page.length > 0){
		const position = $js_scroll_page.offset().top;
		if($(window).scrollTop()+$(window).height() < position){
			$('html, body').stop().animate({scrollTop: (position-110)}, 300);
		}
	}
});

function initGoogleMap() {
	if(typeof displayMap == 'function') {
		displayMap();
	}else{
		const input = document.getElementById('search_main_address');
		if(input){
			autocomplete = new google.maps.places.Autocomplete(input, {types: ['geocode']});
		}
	}
}

function checkCookies(){
	if(!localStorage.cookies_accepted) {
		cookies_message = document.getElementById("cookies-message").style.display="block"
	}
}

function closeCookiesWindow(){
	localStorage.cookies_accepted = true;
	const cookie_window = document.getElementById("cookies-message");
  cookie_window.parentNode.removeChild(cookie_window);
}

window.onload = checkCookies;
