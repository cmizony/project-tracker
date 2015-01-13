<div class="row">
	<div class="col-md-3">
		<a class="btn btn-success" data-target=".modal-add-timer" data-toggle="modal"  href="<?=site_url("tools/time_trackers/add/$time_tracker->id")?>">
			<i class="fa fa-plus"></i> Create Time Slip
		</a>
	</div>
	<div class="alert alert-success col-md-6 text-center"><i class="fa fa-clock-o"></i> 
Time slips related to resource <span class="markdown-content">#<?=$time_tracker->related_resource()?></span></div>
	<div class="alert alert-info pull-right" data-toggle="tooltip" title="Non-Members can not view this page" data-placement="bottom"><i class="fa fa-key"></i> Member Area</div>
</div>
<?=empty_modal('modal-add-timer','Add Timer')?>

<table class="table table-striped table-bordered table-condensed datatable-default">
	<thead>
		<tr>
			<th>Contact</th>
			<th>Start</th>
			<th>Duration</th>
			<th>Note</th>
			<th>Action</th>
		</tr>
	</thead>

	<tbody>
		<?php foreach ($time_intervals as $time_interval): ?>
		<tr>
			<td><a href="<?=site_url("accounts/view/$time_interval->contact_id")?>"><?=$time_interval->contact_name?></a></td>
			<td><?=format_date("F j, Y, g:i a",$time_interval->start)?></td>
			<td><span class="label label-success"><?=humanize_sec(strtotime($time_interval->end)-strtotime($time_interval->start))?></span></td>
			<td><span class="markdown-content"><?=nl2br($time_interval->note)?></span></td>
			<td>
				<div class="btn-group">
					<a data-toggle="modal" href="<?=site_url("tools/time_trackers/edit/$time_interval->id")?>" data-target=".modal-edit-timer-<?=$time_interval->id?>" class="btn btn-sm btn-info"><i class="fa fa-pencil"></i></a>
					<?php if ($my_account_id == $time_interval->contact_id): ?>
					<button data-text="Time record" class="delete-time-interval btn btn-danger btn-sm " data-id="<?=$time_interval->id?>" title="Delete"><i class="fa fa-trash-o"></i></button>
					<?php endif ?>
				</div>
			</td>
		</tr>
		<?=empty_modal("modal-edit-timer-$time_interval->id","Edit Timer")?>
		<?php endforeach ?>
	</tbody>
</table>

<script defer>
	$(".delete-time-interval").click(delete_time_interval);

	function delete_time_interval ()
	{
		var id = $(this).data("id");
		var text = $(this).data("text");
		var url = ARNY.site_url+"tools/time_trackers/delete/"+id;
		confirm_delete(url,text);
		return false;
	}
</script>
