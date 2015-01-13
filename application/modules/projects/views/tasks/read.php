<div class="read-view">
	<div class="panel panel-primary">

		<div class="panel-heading">
			<p class="lead"><i class="fa fa-check-square-o fa-2x"></i> <?=$task->title?></p>
		</div>

		<div class="panel-body">
			<table class="table table-bordered">
				<tbody>
					<tr class="gray">
						<td>
							<div class="text-primary text-center"><?=format_date('F j, g:i a',$task->start_date)?>&nbsp;</div>
							<small class="text-left text-muted"><em>Start</em></small>
						</td>
						<td>
							<div class="text-primary text-center"><?=humanize_sec($task->estimated)?>&nbsp;</div>
							<small class="text-left text-muted"><em>Estimated</em></small>
						</td>
						<td>
							<div class="text-primary text-center"><?=$task->label?>&nbsp;</div>
							<small class="text-left text-muted"><em>Label</em></small>
						</td>
						<td>
							<div class="text-primary text-center"><?=$task->status?>&nbsp;</div>
							<small class="text-left text-muted"><em>Status</em></small>
						</td>
						<td>
							<div class="text-primary text-center"><?=$task->priority?>&nbsp;</div>
							<small class="text-left text-muted"><em>Priority</em></small>
						</td>
						<td>
							<div class="text-primary text-center"><?=$task->contact->name?>&nbsp;</div>
							<small class="text-left text-muted"><em>Responsible</em></small>
						</td>
					</tr>
				</tbody>
			</table>

			<div class="panel panel-default panel-description">
				<div class="panel-body">
					<?=nl2br(markdown_replace($task->description))?>
				</div>
			</div>
			
			<table class="table table-bordered table-condensed">
				<tbody>
					<tr class="gray">
						<td><small><a href="<?=site_url("projects/iterations/view/$task->iteration_id")?>"><i class="fa fa-globe"></i> Iteration - <?=$task->iteration->title?></a></small></td>
						<td><small><a href="<?=site_url("projects/tasks/view/$task->id")?>"><i class="fa fa-globe"></i> Task - <?=$task->title?></a></small></td>
						<td>
							<div class="text-muted text-right"><?=markdown_replace("#task-$task->id")?> <small><em>Created: <?=date("F j, Y",strtotime($task->date))?></small></em></div>	
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
