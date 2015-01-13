<form class="form-horizontal" method="post" action="<?=site_url("projects/tasks/create/$iteration->id")?>">
	<div class="form-group">
		<label class="col-sm-2 control-label" for="title">Title</label>
		<div class="col-sm-10">
			<input class="form-control" type="text" name="title" required>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="label">Label</label>
		<div class="col-sm-10">
			<input class="form-control" name="label">
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="responsible">Responsible</label>
		<div class="col-sm-10">
			<select class="form-control" name="responsible">
				<option></option>
				<?php foreach ($contacts as $contact): ?>
				<option value="<?=$contact->id?>" <?=$contact->id==$my_account?'selected':''?>><?=$contact->name?></option>
				<?php endforeach ?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="priority">Priority</label>
		<div class="col-sm-10">
			<select class="form-control" name="priority">
				<?php foreach (Task::$priorities as $priority): ?>
				<option value="<?=$priority?>">
					<?=$priority?>
				</option>
				<?php endforeach ?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="start_date">Start</label>
		<div class="col-sm-5">
			<input class="form-control" class="input-medium" type="date" name="start_date" value="<?=date('Y-m-d',strtotime($iteration->start_date)+$iteration->time)?>">
		</div>
		<div class="col-sm-5">
			<input class="form-control" class="input-small" type="time" name="start_time" value="09:00">
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="time">Estimated time</label>
		<div class="col-sm-2">
			<input class="form-control" type="number" min="0" step="0.1" name="estimated" class="input-mini" value=1 required>
		</div>
		<div class="col-sm-8">
			<select class="form-control" name="unit" class="input-small">
				<option value="min">Minutes</option>
				<option selected value="hour">Hours</option>
				<option value="day">Days</option>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="description">Description</label>
		<div class="col-sm-10">
			<p class="markdown-help label label-info pull-right"><i class="fa fa-info-circle"></i> Markdown</p>
			<textarea class="form-control" name="description" rows="4"></textarea>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-success"><i class="fa fa-plus"></i> Create task</button>
		</div>
	</div>
</form>
