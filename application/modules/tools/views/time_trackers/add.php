<form class="form-horizontal" method="post" action="<?=site_url("tools/time_trackers/create/$time_tracker->id")?>">

	<div class="form-group">
		<label class="col-sm-2 control-label" for="start_date">Start</label>
		<div class="col-sm-5">
			<input class="form-control" class="input-medium" type="date" name="start_date" value="<?=date('Y-m-d')?>">
		</div>
		<div class="col-sm-5">
			<input class="form-control" class="input-small" type="time" name="start_time" value="<?=date('H:i')?>">
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="duration">Duration</label>
		<div class="col-sm-5">
			<input class="form-control" type="number" min="0" step="1" name="duration">
		</div>
		<div class="col-sm-5">Minutes</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="note">Note</label>
		<div class="col-sm-10">
			<p class="markdown-help label label-info pull-right"><i class="fa fa-info-circle"></i> Markdown</p>
			<textarea class="form-control" name="note" rows="2"></textarea>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-success"><i class="fa fa-pencil"></i> Create Time Slip</button>
		</div>
	</div>
</form>
