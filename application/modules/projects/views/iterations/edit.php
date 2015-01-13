<form class="form-horizontal" method="post" action="<?=site_url("projects/iterations/update/$iteration->id")?>">
	<div class="form-group">
		<label class="col-sm-2 control-label" for="title">Title</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" name="title" required value="<?=$iteration->title?>">
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="start_date">Start Date</label>
		<div class="col-sm-10">
			<input class="form-control" type="date" name="start_date" value="<?=format_date('Y-m-d',$iteration->start_date)?>">
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="status">Status</label>
		<div class="col-sm-10">
			<select class="form-control" name="status" required>
				<?php foreach (Iteration::$statuses as $status): ?>
				<option value="<?=$status?>" <?=$iteration->status==$status?'selected':''?>><?=$status?></option>
				<?php endforeach ?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="time">Duration</label>
		<div class="col-sm-2">
			<input class="form-control" class="col-sm-2" type="number" min="0" step="0.1" name="time" class="input-mini" value=<?=array_shift(explode_sec($iteration->time))?>>
		</div>
		<div class="col-sm-8">
			<select name="unit" class="form-control" class="cols-sm-8" class="input-small">
				<option value="day" <?=end(explode_sec($iteration->time))=='day'?'selected':''?>>Days</option>
				<option value="week" <?=end(explode_sec($iteration->time))=='week'?'selected':''?>>Weeks</option>
				<option value="month" <?=end(explode_sec($iteration->time))=='month'?'selected':''?>>Months</option>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="label">Label</label>
		<div class="col-sm-10">
			<input class="form-control" type="text" name="label" value="<?=$iteration->label?>">
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="description">Description</label>
		<div class="col-sm-10">
			<p class="markdown-help label label-info pull-right"><i class="fa fa-info-circle"></i> Markdown</p>
			<textarea class="form-control" name="description" rows="6" required><?=$iteration->description?></textarea>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-info"><i class="fa fa-pencil"></i> Update iteration</button>
		</div>
	</div>
</form>
