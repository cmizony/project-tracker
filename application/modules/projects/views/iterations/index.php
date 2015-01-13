<div class="alert alert-info pull-right" data-toggle="tooltip" title="Non-Members can not view this page" data-placement="bottom"><i class="fa fa-key"></i> Member Area</div>

<div class="row">
	<?php if ($manage_iterations): ?>
	<div class="col-md-3">
		<a class="btn btn-success" href="<?=site_url("projects/iterations/add/$project_id")?>" data-target=".modal-add-iteration" data-toggle="modal">
			<i class="fa fa-plus"></i> Create Iteration
		</a>
	</div>
	<?=empty_modal('modal-add-iteration','Add Iteration')?>
	<?php endif ?>
	<div class="col-md-6 text-center alert alert-success"><i class="fa fa-tasks"></i> <?=$page_title?></div>
</div>

<ul class="nav nav-tabs">
	<li class="active"><a href="#iterations-tab-calendar" data-toggle="tab"><i class="fa fa-calendar"></i> Calendar Overview</a></li>
	<li><a href="#iterations-tab-grid" data-toggle="tab"><i class="fa fa-th"></i> Grid Overview</a></li>
</ul>
<div class="tab-content">
	<div class="tab-pane active" id="iterations-tab-calendar">
		<br><div id="iterations-calendar"></div>
	</div>
	<div class="tab-pane" id="iterations-tab-grid">
		<br><div id="iterations-box-vignettes"</div>
	</div>
</div>

<div class="block-loading hidden">
	<table class="table table-bordered table-condensed iterations-datatable">
		<thead>
			<tr>
				<th colspan=6>Iteration</th>
				<th colspan=12>Task</th>
			</tr>
			<tr>
				<th>Iteration #</th>
				<th>Iteration Title</th>
				<th>Iteration Status</th>
				<th>Iteration Duration</th>
				<th>Iteration Label</th>
				<th>Project</th>
				<th>#</th>
				<th>Tag</th>
				<th>Title</th>
				<th>Status</th>
				<th>Priority</th>
				<th>Creation</th>
				<th>Start Time</th>
				<th>Start</th>
				<th>Estimated Second</th>
				<th>Estimated</th>
				<th>Due in</th>
				<th>Responsible</th>
			</tr>
		</thead>

		<tbody>
			<?php foreach ($iterations as $iteration): ?>
			<tr class="<?=convert_status($iteration->task_status)?>" id="tr-task-<?=$iteration->task_id?>">
				<td><?=$iteration->id?></td>
				<td><a href="<?=site_url("projects/iterations/view/$iteration->id")?>"><?=$iteration->title?></a></td>
				<td><?=$iteration->status?></td>
				<td><?=humanize_sec($iteration->time)?></td>
				<td><span class="badge"><?=$iteration->label?></span></td>
				<td><a href="<?=site_url("projects/view/$iteration->project_id")?>"><?=$iteration->project_name?></a></td>
				<td><?=$iteration->task_id?></td>
				<td>
					<?php if (!is_null($iteration->task_id)): ?>
					<span id="box-tag-<?=$iteration->task_tag_id?>">
						<?=colored_tag($iteration->task_tag_id,$iteration->task_tag_color,$iteration->task_tag_text,$iteration->task_tag_date) ?>
					</span>
					<?php endif ?>
				</td>
				<td>
					<a data-toggle="modal" href="<?=site_url("projects/tasks/view/$iteration->task_id")?>" data-target=".modal-task-view-<?=$iteration->task_id?>"><?=$iteration->task_title?></a>
				</td>
				<td><span class="label label-<?=convert_status($iteration->task_status)?>"><?=$iteration->task_status?></span></td>
				<td><span class="label label-<?=convert_status($iteration->task_priority)?>"><?=$iteration->task_priority?></span></td>
				<td><?=format_date("F j Y",$iteration->task_date)?></td>
				<td><?=strtotime($iteration->task_start_date)?></td>
				<td><?=format_date("F j Y",$iteration->task_start_date)?></td>
				<td><?=$iteration->task_estimated?></td>
				<td><?=humanize_sec(convert_null($iteration->task_estimated))?></td>
				<td><?=humanize_sec(strtotime($iteration->start_date)+$iteration->time-time(),1)?></td>
				<td><?=$iteration->task_contact_name?></td>
			</tr>
			<?php endforeach ?>
		</tbody>
	</table>
</div>

<?php foreach ($iterations as $iteration): ?>
<?=empty_modal("modal-task-view-$iteration->task_id",'','modal-lg')?>
<?php endforeach ?>

<script defer>
var global_iteration_dtt;
var global_iteration_timeout;

$(document).ready( function () {
	jQuery.extend( jQuery.fn.dataTableExt.oSort, {
		"date-humanized-pre": function ( a ) {
			var moment_date = moment(a,"MMMM DD YYYY");
			return moment_date.isValid()?moment_date.unix():0;
		},
		"date-humanized-asc": function ( a, b ) { return a - b },
		"date-humanized-desc": function ( a, b ) { return b - a }
	} );

	global_iteration_dtt = $('.iterations-datatable').dataTable( {
		"aaSorting": [],
		"aoColumnDefs": [
			{ "bVisible": false, "aTargets": [0,3,6,11,12,14,16] },
			{ "sType": "date-humanized", "aTargets": [13,16]},
		],
		"sDom": "<'row'<'col-md-5'R l><'col-md-7'C f>r>t<'row'<'col-md-4'i><'col-md-4'T><'col-md-4'p>>",
		"oTableTools": {"sSwfPath": ARNY.swf_path,"aButtons": ["print","copy","csv","xls",]},
		"oColVis": { "aiExclude": [ 12,14 ] },
		"bStateSave": true,
		"sPaginationType": "bootstrap",
		"oLanguage": {"sSearch": "Filter:"},
		"fnDrawCallback": function (o) {
			$(".ColVis_MasterButton",o.nTableWrapper).addClass("btn btn-default");
			window.clearTimeout(global_iteration_timeout);
			global_iteration_timeout = window.setTimeout(function (){
				render_calendar_events("#iterations-calendar");
				render_iterations_vignettes("#iterations-box-vignettes");
			},500);
		},
		"fnInitComplete": function(oSettings, json) {

			init_iterarions_calendar("#iterations-calendar");
			$(this).removeClass("iterations-datatable");
			$(this).parents(".block-loading").removeClass("hidden");
		}
	});

	function render_iterations_vignettes(dom_src)
	{
		var o = global_iteration_dtt.fnSettings();
		var vignettes = [];
		var current_row_vignette;
		var ITERATION_NAME=1, ITERATION_STATUS=2, ITERATION_ID=0,TASK_NAME=8,TASK_ID=6,PROJECT_NAME=5,TASK_STATUS=9;
		$(dom_src).empty();

		o.oInstance._('tr', {"filter": "applied"}).forEach(function(entry){
			var iteration_name = $('<p>'+entry[ITERATION_NAME]+'</p>').text();
			var task_name = entry[TASK_NAME];
			var project_name = $('<p>'+entry[PROJECT_NAME]+'</p>').text();
			var iteration_id = parseInt(entry[ITERATION_ID]);
			var task_id = entry[TASK_ID];
			var task_status = entry[TASK_STATUS];
			var iteration_status = entry[ITERATION_STATUS];
			var vignette = $('#iteration-vignette-'+iteration_id);

			// Create Vignette
			if (vignettes.indexOf(iteration_id) == -1)
			{
				if ((vignettes.length % 4) == 0)
				{
					current_row_vignette = $('<div class="row"></div>');
					$(dom_src).append(current_row_vignette);
				}

				vignette = $('<div '+ 
					'id="iteration-vignette-'+iteration_id+'" '+
					'class="col-md-3">'+
					'<div class="panel panel-'+convert_status(iteration_status)+'">'+
					'<div class="panel-heading">'+
					'<i data-toggle="tooltip" title="Project '+project_name+'" class="fa fa-list-alt pull-right"></i>'+
					'<a href="'+ARNY.site_url+'projects/iterations/view/'+iteration_id+'">'+
					'<i class="fa fa-tasks"></i> '+iteration_name +
					'</a>'+
					'</div>'+
					'<div class="panel-body">'+
					'<ul class="list-group vignette-tasks" data-id="'+iteration_id+'" data-iteration="'+encodeURI(entry[ITERATION_NAME])+'" data-project="'+encodeURI(entry[PROJECT_NAME])+'"></ul>'+
					'</div>'+
					'</div>'+
					'</div>'
				).appendTo(current_row_vignette);

				vignette.find("[data-toggle='tooltip']").tooltip(); 
				vignettes.push(iteration_id);
			}

			// Create Task if existing
			if (!task_id)
				return;
			
			var list_color = $(task_status).attr('class').match(/label-\w+/g)[0].replace('label','list-group-item');
			var tasks_list = vignette.find('ul');
			tasks_list.append(
				'<li class="vignette-task list-group-item '+list_color+'" data-id="'+task_id+'">'+
				'<i class="fa fa-check-square-o"></i> '+task_name+
				'</li>');


		});

		bind_all_remote_modal();


		$(".vignette-tasks").sortable({
			connectWith: ".vignette-tasks",
			opacity: 0.5,
			receive: function( event, ui ) {
				var new_iteration = ui.item.closest(".vignette-tasks");
				var task_id = ui.item.data('id');
				var project_name = decodeURI(new_iteration.data('project'));
				var iteration_name = decodeURI(new_iteration.data('iteration'));
				var iteration_id = new_iteration.data('id');
				var url = ARNY.site_url+"projects/tasks/update_inline/"+task_id;
				var obj = {	field : "iteration_id",	val : iteration_id};

				$.post(url,obj).done(function(){
					new PNotify({text: 'Task: Moved to '+$('<p>'+iteration_name+'</p>').text(), type:'success'});
					global_iteration_dtt.fnUpdate(iteration_id,$("#tr-task-"+task_id)[0],ITERATION_ID);
					global_iteration_dtt.fnUpdate(iteration_name,$("#tr-task-"+task_id)[0],ITERATION_NAME);
					global_iteration_dtt.fnUpdate(project_name,$("#tr-task-"+task_id)[0],PROJECT_NAME );
				});
			},
		});
	}

	function init_iterarions_calendar(dom_src)
	{
		$(dom_src).fullCalendar({
			aspectRatio: 2,
			firstDay:1,
			handleWindowResize: true,
			editable: true,
			header: {
				left: 'prev,today,next',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			eventDrop: function(event, delta) {

				var start_date = new moment(event.start);
				var id = event.db_id;
				var url = ARNY.site_url+"projects/tasks/update_inline/"+id;
				var obj = {	field : "start_date",	val : start_date.format('YYYY-MM-DD hh:mm:ss')};

				$.post(url,obj).done(function(){
					new PNotify({text: 'Task: Start date saved', type:'success'});

					global_iteration_dtt.fnUpdate(start_date.format('X'),$("#tr-task-"+id)[0],11 );
					global_iteration_dtt.fnUpdate(start_date.format('MMMM DD YYYY'),$("#tr-task-"+id)[0],12 );
				});
			},
			eventRender: function(event, element) {
				var start = new moment(event.start);
				var year = start.format('YYYY');
				var month = start.format('MM');
				var first_day_week = new Date(year, month - 1, 1).getDay();
				var first_sunday = new Date(year, month - 1, (7 - first_day_week)%7 +1);
				var title = "Due in "+event.due_in+"<br>Responsible: "+event.responsible;
				var displayed_date = $(dom_src).fullCalendar('getDate');
				var placement = "top";

				if ((first_sunday.getTime()/1000 >= start.unix() ||
					event.start.getMonth() < displayed_date.getMonth()) || event.allDay)
					placement = "bottom";	
				if (start.format('dddd') == 'Sunday') 		 placement = "left";
				if (start.format('dddd') == 'Monday')		 placement = "right";

				element.tooltip({title:title,placement:placement,html:true,});
			},
			eventResize: function(event,dayDelta,minuteDelta) {

				if (!event.end)
					event.end = moment(event.start).endOf("day").toDate();

				var estimated = (event.end.getTime() - event.start.getTime()) / 1000;
				var id = event.db_id;
				var url = ARNY.site_url+"projects/tasks/update_inline/"+id;
				var obj = {	field : "estimated",	val : estimated};

				$.post(url,obj).done(function(){
					new PNotify({text: 'Task: Duration saved', type:'success'});

					global_iteration_dtt.fnUpdate(estimated,$("#tr-task-"+id)[0],14);
					global_iteration_dtt.fnUpdate(humanize_sec(estimated),$("#tr-task-"+id)[0],15);
				});
			},
			eventAfterRender: function(event,element) {
				$(element).attr('data-target',".modal-task-view-"+event.db_id);
				$(element).attr('href',ARNY.site_url+"projects/tasks/view/"+event.db_id);

				$(element).unbind('click').click(function (ev){
					var modal_href = $(this).attr('href');

					$($(this).data("target")).on('show.bs.modal', function () {
						$(this).find('.modal-body').load(modal_href);
					}).on('shown.bs.modal', function (){
						bind_all_markdown();
						bind_all_tag();
					}).modal();

					return false;
				});	
			},
		});
	}

	function render_calendar_events (dom_src)
	{
		var o = global_iteration_dtt.fnSettings();
		var header = new Array();
		var data = new Array();

		o.aoColumns.forEach(function(entry){header[entry.sTitle.toLowerCase()] = entry.mData;});
		o.oInstance._('tr', {"filter":"applied"}).forEach(function(entry){
			var obj = new Object();
			for (var index in header)
				obj[index] = $('<p>'+entry[header[index]]+'</p>').text();
			data.push(obj);
		});

		var colors = {
			"New":"#f89406",
			"Assigned":"#3a87ad",
			"Stopped":"#b94a48",
			"Finished":"#468847"
		};

		$(dom_src).fullCalendar( 'removeEvents');

		data.forEach(function(entry){
			if (!entry['#'] || !entry['start time'])
				return;

			var event_start = moment(entry['start time'],"X").toDate();
			var event_end = moment(entry['start time'],"X").add('seconds', entry['estimated second']).toDate();
			var all_day = entry['estimated second'] >= (3600*24);

			var new_event = {
				title: entry.title,
				start: event_start,
				end: event_end,
				backgroundColor : colors[entry.status],
				borderColor: colors[entry.status],
				editable: true,
				allDay: all_day,
				// Custom fields
				db_id: entry['#'],
				created : entry.creation,
				responsible : entry.responsible,
				due_in: entry['due in'],
				estimated: entry['estimated second'],
			};

			$(dom_src).fullCalendar( 'renderEvent', new_event, true);
		});
	}

});
</script>
