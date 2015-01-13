<form class="form-horizontal" method="post" action="<?=site_url("projects/tickets/update/$ticket->id")?>">

	<div class="form-group">
		<label class="col-sm-2 control-label" for="title">Title</label>
		<div class="col-sm-10">
			<input class="form-control" type="text" name="title" required value="<?=$ticket->title?>">
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="type">Type</label>
		<div class="col-sm-10">
			<select class="form-control" name="type">
				<?php foreach (Ticket::$types as $type): ?>
				<option value="<?=$type?>" <?=$ticket->type==$type?'selected':''?>>
					<?=humanize($type)?>
				</option>
				<?php endforeach ?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="status">Status</label>
		<div class="col-sm-10">
			<select class="form-control" name="status">
				<?php foreach (Ticket::$statuses as $status): ?>
				<option value="<?=$status?>" <?=$ticket->status==$status?'selected':''?>>
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
				<?php foreach (Ticket::$priorities as $priority): ?>
				<option value="<?=$priority?>" <?=$ticket->priority==$priority?'selected':''?>>
					<?=$priority?>
				</option>
				<?php endforeach ?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="description">Description</label>
		<div class="col-sm-10">
			<p class="markdown-help label label-info pull-right"><i class="fa fa-info-circle"></i> Markdown</p>
			<textarea class="form-control" rows=6 name="description"><?=$ticket->description?></textarea>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-info"><i class="fa fa-pencil"></i> Update ticket</button>
		</div>
	</div>
</form>
