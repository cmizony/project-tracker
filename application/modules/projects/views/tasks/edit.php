<form class="form-horizontal" method="post" action="<?=site_url("projects/tasks/update/$task->id")?>">
	<div class="form-group">
		<label class="col-sm-2 control-label" for="title">Title</label>
		<div class="col-sm-10">
		<input class="form-control" type="text" name="title" required value="<?=$task->title?>">
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="iteration">Iteration</label>
		<div class="col-sm-10">
			<select class="form-control" name="iteration">
				<?php foreach ($iterations as $iteration): ?>
				<option value="<?=$iteration->id?>" <?=$task->iteration_id==$iteration->id?'selected':''?>>
					<?=$iteration->title?>
				</option>
				<?php endforeach ?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="label">Label</label>
		<div class="col-sm-10">
			<input class="form-control" name="label" value="<?=$task->label?>">
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="status">Status</label>
		<div class="col-sm-10">
			<select class="form-control" name="status">
				<?php foreach (Task::$statuses as $status): ?>
				<option value="<?=$status?>" <?=$task->status==$status?'selected':''?>>
					<?=$status?>
				</option>
				<?php endforeach ?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="priority">Priority</label>
		<div class="col-sm-10">
			<select class="form-control" name="priority">
				<?php foreach (Task::$priorities as $priority): ?>
				<option value="<?=$priority?>" <?=$task->priority==$priority?'selected':''?>>
					<?=$priority?>
				</option>
				<?php endforeach ?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="responsible">Responsible</label>
		<div class="col-sm-10">
			<select class="form-control" name="responsible">
				<option></option>
				<?php foreach ($contacts as $contact): ?>
				<option value="<?=$contact->id?>" <?=$contact->id==$task->contact_id?'selected':''?>><?=$contact->name?></option>
				<?php endforeach ?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="start_date">Start</label>
		<div class="col-sm-5">
			<input class="form-control" class="input-medium" type="date" name="start_date" value="<?=format_date('Y-m-d',$task->start_date)?>">
		</div>
		<div class="col-sm-5">
			<input class="form-control" class="input-small" type="time" name="start_time" value="<?=format_date('H:i',$task->start_date)?>">
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="time">Estimated time</label>
		<div class="col-sm-2">
			<input class="form-control" type="number" min="0" step="0.1" name="estimated" class="input-mini" value=<?=array_shift(explode_sec($task->estimated))?>>
		</div>
		<div class="col-sm-8">
			<select class="form-control" name="unit" class="input-small">
				<option value="min" <?=end(explode_sec($task->estimated))=='min'?'selected':''?>>Minutes</option>
				<option value="hour" <?=end(explode_sec($task->estimated))=='hour'?'selected':''?>>Hours</option>
				<option value="day" <?=end(explode_sec($task->estimated))=='day'?'selected':''?>>Days</option>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="description">Description</label>
		<div class="col-sm-10">
			<p class="markdown-help label label-info pull-right"><i class="fa fa-info-circle"></i> Markdown</p>
			<textarea class="form-control" name="description" rows="4"><?=$task->description?></textarea>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-info"><i class="fa fa-pencil"></i> Update task</button>
		</div>
	</div>
</form>
