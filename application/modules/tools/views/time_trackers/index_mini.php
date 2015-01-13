<div class="alert alert-info pull-right" data-toggle="tooltip" title="Non-Members can not view this page" data-placement="bottom"><i class="fa fa-key"></i> Member Area</div>

<a class="btn btn-sm btn-primary" href="<?=site_url("tools/time_trackers/index/$time_tracker->id")?>">
	<i class="fa fa-arrows-alt"></i> Details
</a><br><br>

<table class="table table-bordered table-condensed mini-datatable">
	<thead>
		<tr>
			<th>Contact</th>
			<th>Start</th>
			<th>Duration</th>
		</tr>
	</thead>

	<tbody>
		<?php foreach ($time_intervals as $time_interval): ?>
		<tr>
			<td><a href="<?=site_url("accounts/view/$time_interval->contact_id")?>"><?=$time_interval->contact_name?></a></td>
			<td><?=format_date("F j, Y, g:i a",$time_interval->start)?></td>
			<td><span class="label label-success"><?=humanize_sec(strtotime($time_interval->end)-strtotime($time_interval->start))?></span></td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>
