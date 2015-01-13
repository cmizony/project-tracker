<h2><i class="fa fa-check-square-o"></i> <?=$task->title?></h2>

<div class="pull-right alert alert-<?=convert_status($task->status)?>">
	<?=$task->status?>
</div>

<ul class="list-unstyled">
	<li><span class="badge"><?=$task->label?></span></li>
	<li><span class="text-muted">Responsible:</span> <a href="<?=site_url("accounts/view/$task->contact_id")?>"><?=$task->contact_name?></a></li>
	<li><span class="text-muted">Priority:</span> <span class="label label-<?=convert_status($task->priority)?>"><?=$task->priority?></span></li>
	<li>
		<span class="text-muted">Tag:</span> 
		<span id="box-tag-<?=$task->tag_id?>">
			<?=colored_tag($task->tag_id,$task->tag_color,$task->tag_text,$task->tag_date) ?>
		</span>
	</li>
	<li><span class="text-muted">Start:</span> <?=format_date("F j, Y",$task->start_date)?></li>
	<li><span class="text-muted">Estimated:</span> <span class="label label-info"><?=humanize_sec($task->estimated)?></span></li>
	<li>
		<span class="text-muted">Due in:</span><?=nbs(5)?> 
		<span class="label label-<?=$task->on_time(strtotime($task->iteration_start_date)+$task->iteration_time)?'danger':'success'?>">
			<?=humanize_sec(strtotime($task->iteration_start_date)+$task->iteration_time-time())?>
		</span>
	</li>
</ul>

<?php if (!empty($task->description)): ?>
<div class="panel panel-default">
	<div class="panel-body markdown-content">
		<?=nl2br($task->description)?>
	</div>
</div>
<?php endif ?>

<div class="text-muted text-right markdown-content">#task-<?=$task->id?> <small><em>Created: <?=date("F j, Y",strtotime($task->date))?></small></em></div>	

<hr>	
<?=$rendered_chat?>
