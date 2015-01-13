/* * * * * * * * * * *
 * HTML CLASS USAGE  *
 * * * * * * * * * * *
 * 
 * CURRENT
 *
 *	btn-ajax 
 *	btn-ajax-unique
 *
 *	--- Tags ---
 *	data-toggle="color-tag"
 *	data-title
 *	data-id
 *
 */

/**********************/
/*  BINDING  &  INIT  */
/**********************/

function init_custom_ui()
{
	$("[data-toggle='tooltip']").tooltip(); 
	$("[data-toggle='popover']").popover(); 
	bind_all_btn_ajax();
	bind_all_remote_modal();
	bind_all_dataTables();
	bind_all_tag();
	bind_all_markdown();
}

function init_remote_ajax ()
{
	init_custom_ui();
	$(this).unbind('show');

	redirect_js($(this).text());
}

function bind_all_btn_ajax ()
{
	$(".btn-ajax").unbind('click').click(btn_ajax);
	$(".btn-ajax-unique").unbind('click').click(btn_ajax);
}

function bind_all_remote_modal()
{
	// Fix Bootstrap 3.0 load in content
	$("[data-target][data-toggle=\"modal\"]").unbind('click').click(function (ev) {
		var modal = $($(this).data("target"));
		var modal_href = ev.currentTarget.href;

		if (!modal)
			return;

		if (!ev.currentTarget.href)
			modal_href = $(this).attr("href");

		modal.on('show.bs.modal', function () {
			$(this).find('.modal-body').load(modal_href);
		}).on('shown.bs.modal', function (){
			bind_all_markdown();
			bind_all_tag();
		}).modal();

	//return false;
	});
}

function bind_all_dataTables()
{
	$('.datatable-default').dataTable( {
		"aaSorting": [],
		"sDom": "<'row'<'col-md-5'R l><'col-md-7'C f>r>t<'row'<'col-md-4'i><'col-md-4'T><'col-md-4'p>>",
		"oTableTools": {"sSwfPath": ARNY.swf_path,"aButtons": ["print","copy","csv","xls",]},
		"bStateSave": true,
		"sPaginationType": "bootstrap",
		"oLanguage": {"sSearch": "Filter Table:"},
		"fnDrawCallback": function (o) {
			$(".ColVis_MasterButton",o.nTableWrapper).addClass("btn btn-default");
		}
	});
	$('.datatable-default').removeClass("datatable-default");

	$('.mini-datatable').dataTable( {
		"aaSorting": [],
		"pagingType": "simple",
		"iDisplayLength": 5,
		"aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
		"oLanguage": {
			"sLengthMenu": "_MENU_",
			"sInfo": "Total: _TOTAL_ records"
		},
		"sDom": "<'row'<'col-md-4'l><'col-md-8'f>r>t<'row'<'col-md-4'i><'col-md-8'p>>",
	} );
	$('.mini-datatable').removeClass("mini-datatable");

	// Fix Bootstrap 3.0
	$('.dataTables_wrapper').each(function(){
		var datatable = $(this);
		// SEARCH - Add the placeholder for Search and Turn this into in-line form control
		var search_input = datatable.find('div[id$=_filter] input');
		search_input.attr('placeholder', 'Search');
		search_input.addClass('form-control input-sm');
		// LENGTH - Inline-Form control
		var length_sel = datatable.find('div[id$=_length] select');
		length_sel.addClass('form-control input-sm');
		datatable.bind('page', function(e){
			window.console && console.log('pagination event:', e) //this event must be fired whenever you paginate
		});
	});
}

function bind_all_tag (dom_src)
{
	if (!dom_src)
		dom_src = $('body');
	else
		dom_src = $(dom_src);

	dom_src.find("[data-toggle='color-tag']").each(color_tag);
}

function bind_all_markdown (dom_src)
{
	if (!dom_src)
		dom_src = $('body');
	else
		dom_src = $(dom_src);

	var help_content =
		'Example (task #1): <code>#task-1</code><br>'+
		'<i class="fa fa-list-alt"></i> Project<br>'+
		'<i class="fa fa-comment-o"></i> Thread<br>'+
		'<i class="fa fa-tasks"></i> Iteration<br>'+
		'<i class="fa fa-check-square-o"></i> Task<br>'+
		'<i class="fa fa-ticket"></i> Ticket<br>'+
		'<i class="fa fa-file"></i> File<br>'+
		'<i class="fa fa-user"></i> Contact';

	dom_src.find('.markdown-help').tooltip({
		html : true,
		title : help_content,
	});

	var custom_tags =[
		{ 
			regex: /#task-(\d+)/ig,
			replace: 'projects/tasks/read/$1" title="Task id #$1"><i class="fa fa-fa fa-check-square-o"></i> Task-$1'},
		{ 
			regex: /#project-(\d+)/ig,
			replace: 'projects/read/$1" title="Project id #$1"><i class="fa fa-list-alt"></i> Project-$1'},
		{ 
			regex: /#iteration-(\d+)/ig,
			replace: 'projects/iterations/read/$1" title="Iteration id #$1"><i class="fa fa-tasks"></i> Iteration-$1'},
		{ 
			regex: /#thread-(\d+)/ig, 
			replace: 'projects/threads/read/$1" title="Thread id #$1"><i class="fa fa-comment-o"></i> Thread-$1'},
		{ 
			regex: /#ticket-(\d+)/ig,
			replace: 'projects/tickets/read/$1" title="Ticket id #$1"><i class="fa fa-ticket"></i> Ticket-$1'},
		{ 
			regex: /#file-(\d+)/ig,
			replace: 'tools/files/read/$1" title="File id #$1"><i class="fa fa-file"></i> File-$1'},
		{ 
			regex: /#contact-(\d+)/ig,
			replace: 'accounts/read/$1" title="Contact id #$1"><i class="fa fa-user"></i> Contact-$1'},
	];

	var custom_class = ' class="label label-default markdown-link" ';

	dom_src.find('.markdown-content').each(function (){
		var content = $(this).html();
		var content_id = Math.random();

		custom_tags.forEach(function (entry){

			var full_replace = '<a rel="markdown-content-' +
				content_id +'"' +
				custom_class +
				' href="'+ARNY.site_url+entry.replace +
				'</a>';

			content = content.replace(entry.regex,full_replace);

		});

		$(this).html(content);
		$(this).removeClass(".markdown-content");
	});

	var width_container = $('#main .container').width();


	$(".markdown-link").fancybox({
		type: 'ajax',
		minWidth: Math.min(700,width_container-50),
		maxWidth: 800,
		wrapCSS: 'row col-md-10',
		openEffect	: 'none',
		closeEffect	: 'none',
		afterLoad: function () {
			bind_all_markdown();
		},
		tpl : {
			error: '<div class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> The resource does not exist or you do not have the privileges to access it.<br><p class="text-center">Please contact an administrator for more details.</p></div>'
		},
	});
}

/*********************/
/*  JS & HTML CLASS  */
/*********************/

function color_tag ()
{
	var tag_toggle = $(this);
	var title = tag_toggle.attr('data-title');
	var id = tag_toggle.attr('data-id');
	var content = $('#box-tag-'+id).find('.hidden').html();

	$(this).tooltip({
		placement:'bottom',
		html:true,
	});

	var popover = $(this).popover({
		placement:'top',
		title:'',
		content:content,
		html:true,
	});
	
	popover.parent().unbind('click').delegate('button[type="submit"]', 'click', function() {
	
		var form = $('.popover #content-tag-'+id);
	
		var url = ARNY.site_url+'tools/tags/update/'+id;
		var obj = {
			color : form.find( "select option:selected" ).val(),
			text : form.find( "input[name='text']" ).val()
		};

		$.post( url, obj ).done(function( data ) {
			tag_toggle.popover('destroy');
			$('#box-tag-'+id).html(data);
			$('#box-tag-'+id).find("[data-toggle='color-tag']").each(color_tag);
			new PNotify({text: 'Tag saved', type:'success'});
		});
	});
	popover.parent().delegate('button[data-dismiss="popover"]', 'click', function() {
		tag_toggle.popover('hide');
	});
}

function btn_ajax () 
{
	var btn = $(this);

	$.ajax({
		url: btn.attr('data-href'),
		beforeSend: function () { ajax_before_send (btn);},
		success: function (data) { 
			
			if (btn.attr('class').indexOf('btn-ajax-unique') != -1)
			{
				btn.unbind('click');
				btn.removeClass('btn-ajax-unique');
				btn.removeClass('btn-ajax');
			}

			ajax_success(data,btn);

		},
		error: function (data) { ajax_error(data); }
	});
}

function ajax_before_send (dom_src)
{
//	dom_src.button('loading');
//	dom_src.addClass("disabled");
}

function ajax_success(data,dom_src)
{
	var selector = dom_src.attr('data-target');
	if (!redirect_js(data))
	{
		var anchor = $(selector);
		anchor.html(data);
		init_custom_ui();
	}
//	dom_src.button('reset');
//	dom_src.removeClass("disabled");
}

function ajax_error (data)
{
	new PNotify({
		title: 'Error',
		text: 'Network error on loading content',
		type: 'error',
	});
}

function redirect_js (content)
{
	if (content.match(/^Location:/)) // Redirection
	{
		window.location.replace(content.split(' ')[1]);
		return true;
	}
	return false;
}

/*********************/
/*     UTILS JS      */
/*********************/

function create_cookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*3600));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function read_cookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function confirm_delete (url,custom)
{
	if (!custom)
		custom = 'confirmation';

	html ='Do you really want to perform this action ?';
	html += '<a href="'+url+'" class="btn btn-primary btn-sm"><i class="fa fa-check"></i> Confirm</a>';

	var notify = new PNotify({
		title: 'Delete ' + custom,
		text: html,
		closer: true,
		sticker: false,
		text_escape: false
	});
}

function exec_js (anchor)
{
	anchor.find("script").each(function() {
		eval($(this).text());
	});
}

function convert_status (status)
{
	var lower_status = status.toLowerCase();

	switch (lower_status)
	{
		case 'new':
			return 'warning';
		case 'in progress':
			return 'info';
		case 'stopped':
			return 'danger';
		case 'finished':
			return 'success';
		default:
			return  'default';
	}
}

function nl2br (str)
{
	return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + '<br>' + '$2');
}

function humanize_sec (seconds)
{
	var units = {
		"week"	: 7*24*3600,
		"day"	: 24*3600,
		"hour"	: 3600,
		"minute": 60,
	};

	var string = "";

	for (var name in units)
	{
		var quot = parseInt(seconds / units[name]);
		if (quot && quot > 0)
		{
			string += quot+" "+name;
			string += quot>1?'s, ':', ';
			seconds -= quot * units[name];
		}
	}
	return string.substring(0,string.length-2);
}

function tab_state_save ()
{
	try {
		var state_tabs = JSON.parse(read_cookie(crc32('StateTabs')));
	} catch (e) {
		var state_tabs = {};
	}
	if (!state_tabs)
		var state_tabs = {};

	$('.nav').each(function (){
		// Get Name of tab	
		var tabs_name = [];
		$(this).find('a[data-toggle="tab"]').each(function (){
			tabs_name.push($(this).attr('href'));
		});
		var nav_name = crc32(tabs_name.toString());

		// Bind shown state save & Set active element
		$(this).find('a[data-toggle="tab"]').each(function (){
			var tab_name = crc32($(this).attr('href'));

			if (state_tabs[nav_name] == tab_name)
				$(this).tab('show');

			$(this).on('shown.bs.tab',function(e) {
				state_tabs[nav_name] = tab_name;
				create_cookie(crc32('StateTabs'),JSON.stringify(state_tabs),1);
			});
		});
	});
}

function collapse_state_save ()
{
	try {
		var state_collapses = JSON.parse(read_cookie(crc32('StateCollapses')));
	} catch (e) {
		var state_collapses = {};
	}
	if (!state_collapses)
		var state_collapses = {};

	$('.saved-collapse').each(function (){
		// Get Name of collapse	
		var collapses_name = [];
		$(this).find('a[data-toggle="collapse"]').each(function (){
			collapses_name.push($(this).attr('href'));
		});
		
		var nav_name = crc32(collapses_name.toString());

		$(this).find('.collapse').each (function (){
			var collapse_name = crc32($(this).attr('id'));

			if (state_collapses[nav_name] == collapse_name)
				$(this).collapse('show');

			$(this).on('shown.bs.collapse',function(e){
				state_collapses[nav_name] = collapse_name;
				create_cookie(crc32('StateCollapses'),JSON.stringify(state_collapses),1);
			});
		});
	});
}
