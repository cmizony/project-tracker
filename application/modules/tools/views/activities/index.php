<div class="well well-small">
	<p>
		<i class="fa fa-bar-chart"></i> 
		Chart representing all the activities per day shaded by 
		<select class="input-small" id="select-chart-activities">
			<option value="account">Account</option>
			<option value="type">Type</option>
			<option value="action">Action</option>
		</select>
	</p>
	<div id="svg-chart-activities" style="min-height:350px"></div>
</div>

<div class="block-loading hidden">
	<table class="table table-condensed table-bordered activities-datatable">
		<thead>
			<tr>
				<th>Account</th>
				<th>Action</th>
				<th>Type</th>
				<th>Date</th>
				<th>Description</th>
			</tr>
		</thead>

		<tbody>
			<?php foreach ($activities as $activity): ?>
			<tr class="<?=convert_status($activity->action)?>">
				<?php if ($activity->contact_id == -1): ?>
				<td>Admin</td>
				<?php else: ?>
				<td><a href="<?=site_url("accounts/view/$activity->contact_id")?>" title="<?=$activity->contact_name?>"><?=$activity->contact_login?></a></td>
				<?php endif ?>
				<td><?=$activity->action?></td>
				<td><a href="<?=site_url($activity->uri_link)?>"><?=humanize($activity->type)?></a></td>
				<td><?=format_date("F j Y g:i a",$activity->date)?></td>
				<td><?=$activity->title?></td>
			</tr>
			<?php endforeach ?>
		</tbody>
	</table>
</div>

<script defer>
var global_log_datatable;
var global_log_timeout;
var refresh_chart;

$(document).ready( function () {
	jQuery.extend( jQuery.fn.dataTableExt.oSort, {
		"date-humanized-pre": function ( a ) {
			var moment_date = moment(a,"MMMM DD YYYY HH:mm a");
			return moment_date.isValid()?moment_date.unix():0;
		},
		"date-humanized-asc": function ( a, b ) { return a - b },
		"date-humanized-desc": function ( a, b ) { return b - a }
	} );

	global_log_datatable = $('.activities-datatable').dataTable( {
		"aaSorting": [],
		"aoColumnDefs": [{ "sType": "date-humanized", "aTargets": [3]}],
		"sDom": "<'row'<'col-md-5'R l><'col-md-7'C f>r>t<'row'<'col-md-4'i><'col-md-4'T><'col-md-4'p>>",
		"oTableTools": {"sSwfPath": ARNY.swf_path,"aButtons": ["print","copy","csv","xls",]},
		"bStateSave": true,
		"sPaginationType": "bootstrap",
		"oLanguage": {"sSearch": "Filter:"},
		"fnDrawCallback": function (o) {
			$(".ColVis_MasterButton",o.nTableWrapper).addClass("btn btn-default");
			window.clearTimeout(global_log_timeout);
			global_log_timeout = window.setTimeout(refresh_chart,800);
		},
		"fnInitComplete": function(oSettings, json) {
				$(this).removeClass("activities-datatable");
				$(this).parents(".block-loading").removeClass("hidden");
		}
	});

	refresh_chart = function ()
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

		var dom_selector = {svg:"#svg-chart-activities",select:"#select-chart-activities"};
		$(dom_selector.select).unbind("change").change(function(){
			plot_chart_activities(dom_selector,data);
		});
		plot_chart_activities(dom_selector,data);
	}
});

</script>
