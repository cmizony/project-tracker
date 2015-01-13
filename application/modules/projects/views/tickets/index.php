<div class="alert alert-info pull-right" data-toggle="tooltip" title="Non-Members can not view this page" data-placement="bottom"><i class="fa fa-key"></i> Member Area</div>

<div class="row">
	<?php if ($manage_tickets): ?>
	<div class="col-md-3">
		<a class="btn btn-success" href="<?=site_url("projects/tickets/add/$project_id")?>" data-target=".modal-add-ticket" data-toggle="modal">
			<i class="fa fa-plus"></i> Create Ticket
		</a>
	</div>
	<?=empty_modal('modal-add-ticket','Add Ticket')?>
	<?php endif ?>
	<div class="col-md-6 text-center alert alert-success"><i class="fa fa-ticket"></i> <?=$page_title?></div>
</div>

<div class="well well-white well-small">
	<p>
		<i class="fa fa-bar-chart"></i> 
		Chart representing the tickets per projects shaded by 
		<select class="input-small" id="select-chart-tickets">
			<option value="status">Status</option>
			<option value="type">Type</option>
			<option value="priority">Priority</option>
		</select>
	</p>
	
	<div id="svg-chart-tickets" style="min-height:350px"></div>
</div>

<div class="block-loading hidden">
	<table class="table table-bordered table-condensed tickets-datatable">
		<thead>
			<tr>
				<th class="col-md-1">#</th>
				<th class="col-md-1">Tag</th>
				<th class="col-md-3">Title</th>
				<th class="col-md-1">Type</th>
				<th class="col-md-1">Priority</th>
				<th class="col-md-1">Status</th>
				<th class="col-md-2">Project</th>
				<th class="col-md-2">Creation date</th>
			</tr>
		</thead>

		<tbody>
			<?php foreach ($tickets as $ticket): ?>
			<tr class="<?=convert_status($ticket->status)?>">
				<td><?=$ticket->number?></td>
				<td>
					<span id="box-tag-<?=$ticket->tag_id?>">
						<?=colored_tag($ticket->tag_id,$ticket->tag_color,$ticket->tag_text,$ticket->tag_date) ?>
					</span>
				</td>
				<td><a href="<?=site_url("projects/tickets/view/$ticket->id")?>"><?=$ticket->title?></a></td>
				<td><?=$ticket->type?></td>
				<td><span class="label label-<?=convert_status($ticket->priority)?>"><?=$ticket->priority?></span></td>
				<td><?=$ticket->status?></td>
				<td><a href="<?=site_url("projects/view/$ticket->project_id")?>"><?=$ticket->project->name?></a></td>
				<td><?=date("F j, Y",strtotime($ticket->date))?></td>
			</tr>
			<?php endforeach ?>
		</tbody>
	</table>
</div>

<script defer>
var global_log_datatable;
var global_log_timeout;

$(document).ready( function () {
	jQuery.extend( jQuery.fn.dataTableExt.oSort, {
		"date-humanized-pre": function ( a ) {
			var moment_date = moment(a,"MMMM DD YYYY");
			return moment_date.isValid()?moment_date.unix():0;
		},
		"date-humanized-asc": function ( a, b ) { return a - b },
		"date-humanized-desc": function ( a, b ) { return b - a }
	} );

	global_log_datatable = $('.tickets-datatable').dataTable( {
		"aaSorting": [],
		"aoColumnDefs": [{ "sType": "date-humanized", "aTargets": [6]}],
		"sDom": "<'row'<'col-md-5'R l><'col-md-7'C f>r>t<'row'<'col-md-4'i><'col-md-4'T><'col-md-4'p>>",
		"oTableTools": {"sSwfPath": ARNY.swf_path,"aButtons": ["print","copy","csv","xls",]},
		"bStateSave": true,
		"sPaginationType": "bootstrap",
		"oLanguage": {"sSearch": "Filter Table:"},
		"fnDrawCallback": function (o) {
			$(".ColVis_MasterButton",o.nTableWrapper).addClass("btn btn-default");
			window.clearTimeout(global_log_timeout);
			global_log_timeout = window.setTimeout(refresh_chart,800);
		},
		"fnInitComplete": function(oSettings, json) {
				$(this).removeClass("tickets-datatable");
				$(this).parents(".block-loading").removeClass("hidden");
		}
	});

	function refresh_chart ()
	{
		var o = global_log_datatable.fnSettings();
		var header = new Array();
		var data = new Array();

		o.aoColumns.forEach(function(entry){header[entry.sTitle.toLowerCase()] = entry.mData;});
		o.oInstance._('tr', {"filter":"applied"}).forEach(function(entry){
			var obj = new Object();
			for (var index in header)
				obj[index] = $('<p>'+entry[header[index]]+'</p>').text();
			data.push(obj);
		});

		var dom_selector = {svg:"#svg-chart-tickets",select:"#select-chart-tickets"};
		$(dom_selector.select).unbind("change").change(function(){
			plot_chart_tickets(dom_selector,data);
		});
		plot_chart_tickets(dom_selector,data);
	}
});

</script>
