
<div class="col-sm-offset-2 col-sm-10">
	<ul class="list-unstyled">
		<li>Timer related to <span class="markdown-content">#<?=$time_interval->related_resource()?></span></li>
		<?php if (is_null($time_interval->end)): ?>
		<li>Status <span class="label label-success">Open</span></li>
		<li class="text-muted"><i class="fa fa-info-circle"></i> Duration can be updated once the timer is close</li>
		<?php else: ?>
		<li>Status <span class="label label-primary">Closed</span></li>
		<?php endif ?>
	</ul>
</div>

<form class="form-horizontal" method="post" action="<?=site_url("tools/time_trackers/update/$time_interval->id")?>">

	<?php if (!is_null($time_interval->end)): ?>
	<div class="form-group">
		<label class="col-sm-2 control-label" for="start_date">Start</label>
		<div class="col-sm-5">
			<input class="form-control" class="input-medium" type="date" name="start_date" value="<?=format_date('Y-m-d',$time_interval->start)?>">
		</div>
		<div class="col-sm-5">
			<input class="form-control" class="input-small" type="time" name="start_time" value="<?=format_date('H:i',$time_interval->start)?>">
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="duration">Duration</label>
		<div class="col-sm-5">
			<input class="form-control" type="number" min="0" step="1" name="duration" value=<?=ceil((strtotime($time_interval->end)-strtotime($time_interval->start))/60)?>>
		</div>
		<div class="col-sm-5">Minutes</div>
	</div>
	<?php endif ?>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="note">Note</label>
		<div class="col-sm-10">
			<p class="markdown-help label label-info pull-right"><i class="fa fa-info-circle"></i> Markdown</p>
			<textarea class="form-control" name="note" rows="2"><?=$time_interval->note?></textarea>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-info"><i class="fa fa-pencil"></i> <?=is_null($time_interval->end)?'Save &amp; Stop Timer':'Update'?></button>
		</div>
	</div>
</form>
