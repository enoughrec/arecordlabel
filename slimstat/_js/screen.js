function positionSidebar() {
	if ($(window).scrollTop() > 120) {
		$('#side, #ajaxindicator').css({position: 'fixed', top: '-1.6666em'});
	} else {
		$('#side, #ajaxindicator').css({position: 'absolute', top: '8.3333em'});
	}
}

function handleHashChange() {
	if ($('body').attr('id') != 'detailspage') {
		return;
	}
	
	var href = location.href.substr(0, location.href.indexOf('#'));
	href += (href.indexOf('?') > -1) ? '&' : '?';
	href += 'ajax=1&';
	href += location.hash.substr(1);

	$.get(href, function(data) {
		$('#title').html($(data).find('#title').html());
		$('#main').html($(data).find('#main').html());
		positionSidebar();
	});
}

function createAjaxCookie() {
	var date = new Date();
	date.setTime(date.getTime() + (365*24*60*60*1000));
	document.cookie = 'slimstatajax=1; expires=' + date.toGMTString() + '; path=/';
}

function activateFilter(field, value) {
	var sel = $('select[name="'+field+'"]');
	sel.closest('p').addClass('activefilter');
	sel.val(value).trigger('change');
}

$(function() {
	// focus username field on login page
	$('#loginpage .input[name="username"]').focus();
	
	// let the server know that we support ajax
	createAjaxCookie();
	
	// handle clicks on toggle links
	$('a.toggle').live('click', function() {
		var toggle = $(this);
		if (toggle.css('background-position').indexOf('-15') > -1) {
			toggle.css('background-position', '0 0');
		} else {
			toggle.css('background-position', '-15px 0');
		}
		var id = toggle.attr('id');
		if (id != '') {
			$('tr.detail_'+id).toggle();
		}
		return false;
	});
	
	// handle filters changing
	$('#filters select').live('change', function() {
		var currentDate = new Date();
		var isCurrentYr = ($('#filters select[name="filter_yr"]').val() == currentDate.getFullYear());
		var isCurrentMo = isCurrentYr && ($('#filters select[name="filter_mo"]').val() == currentDate.getMonth() + 1);
		
		var hash = '';
		var separator = '#';
		$('#filters select').each(function() {
			var sel = $(this);
			if (sel.attr('name') == 'filter_yr' && isCurrentYr) {
				return;
			} else if (sel.attr('name') == 'filter_mo' && isCurrentMo) {
				return;
			} else if (sel.val() != '' && sel.val() != null) {
				hash += separator;
				hash += sel.attr('name') + '=' + encodeURIComponent(sel.val());
				separator = '&';
			}
		});
		
		location.hash = hash;
	});
	
	// handle window.onhashchange
	$(window).bind('hashchange', function() {
		handleHashChange();
	});
	
	// handle details page links being clicked
	$('#detailspage #content a[href^="./?filter_"]').live('click', function() {
		var field = $(this).closest('div.table').attr('class');
		field = field.substring(field.indexOf('filter_'));
		field = field.substring(0, field.indexOf(' '));
		
		if (field == 'filter_prev_resource' || field == 'filter_next_resource') {
			field = 'filter_resource';
		}
		
		var value = $(this).attr('href');
		value = value.substring(value.indexOf(field) + field.length + 1);
		value = decodeURIComponent(value);
		
		activateFilter(field, value);
		
		return false;
	});
	
	// handle filters being reset
	$('#filtersform a').live('click', function() {
		var a = $(this);
		a.closest('p').removeClass('activefilter');
		a.parent().children().filter('select').attr('selectedIndex',0).trigger('change');
		
		return false;
	});
	
	// handle calendar month links being clicked
	$('#detailspage table.calendar thead a').live('click', function() {
		var value = $(this).attr('href');
		value = (value.indexOf('?') > -1) ? value.substr(value.indexOf('?') + 1) : '';
		
		var changedYr = false;
		var changedMo = false;
		var vars = value.split('&');
		for (var i=0; i<vars.length; i++) {
			var param = vars[i].split('=');
			if (param[0] == 'filter_yr') {
				$('select[name="filter_yr"]').val(param[1]);
				changedYr = true;
			} else if (param[0] == 'filter_mo') {
				$('select[name="filter_mo"]').val(param[1]);
				changedMo = true;
			}
		}
		
		var currentDate = new Date();
		if (!changedYr) {
			$('#filters select[name="filter_yr"]').val(currentDate.getFullYear());
		}
		if (!changedMo) {
			$('#filters select[name="filter_mo"]').val(currentDate.getMonth() + 1);
		}
		
		$('select[name="filter_dy"] option').removeAttr('selected');
		$('select[name="filter_dy"]').trigger('change');
		return false;
	});
	
	// handle calendar day links being clicked
	$('#detailspage table.calendar tbody a').live('click', function() {
		var value = $(this).html();
		$('select[name="filter_dy"]').val(value).trigger('change');
		return false;
	});
	
	// ajax activity indicator
	$('body').append('<div id="ajaxindicator"><img src="./_img/loading.gif" width="16" height="16" alt="Activity indicator" /></div>');
	$('#ajaxindicator').css({
		display: 'none',
		margin: '0 0 0 460px',
		padding: '3.5em 0 0 0',
		position: 'absolute',
		left: '50%',
		top: '0',
		width: '16px',
		'z-index': '20'
	});
	
	// handle scrolling
	$(window).scroll(function() {
		positionSidebar();
	});
	positionSidebar();

	// show/hide ajax activity indicator
	$(document).ajaxStart(function() { 
		$('#ajaxindicator').show(); 
	}).ajaxStop(function() { 
		$('#ajaxindicator').hide();
	});
	
	$(window).load(function() {
		handleHashChange();
	});
});
