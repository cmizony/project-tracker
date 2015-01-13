<div class="read-view">
	<div class="panel panel-primary">

		<div class="panel-heading">
			<p class="lead"><i class="fa fa-tasks fa-2x"></i> <?=$iteration->title?></p>
		</div>

		<div class="panel-body">
			<table class="table table-bordered">
				<tbody>
					<tr class="gray">
						<td>
							<div class="text-primary text-center"><?=format_date('F j, Y',$iteration->start_date)?>&nbsp;</div>
							<small class="text-left text-muted"><em>Start</em></small>
						</td>
						<td>
							<div class="text-primary text-center"><?=$iteration->status?>&nbsp;</div>
							<small class="text-left text-muted"><em>Status</em></small>
						</td>
						<td>
							<div class="text-primary text-center"><?=humanize_sec($iteration->time)?>&nbsp;</div>
							<small class="text-left text-muted"><em>Duration</em></small>
						</td>
						<td>
							<div class="text-primary text-center"><?=$iteration->label?>&nbsp;</div>
							<small class="text-left text-muted"><em>Label</em></small>
						</td>
						<td>
							<div class="text-primary text-center"><?=$iteration->count_tasks?> Tasks</div>
							<small class="text-left text-muted"><em>Total</em></small>
						</td>
					</tr>
				</tbody>
			</table>

			<div class="panel panel-default panel-description">
				<div class="panel-body">
					<?=nl2br(markdown_replace($iteration->description))?>
				</div>
			</div>
			
			<table class="table table-bordered table-condensed">
				<tbody>
					<tr class="gray">
						<td><small><a href="<?=site_url("projects/view/$iteration->project_id")?>"><i class="fa fa-globe"></i> Project - <?=$iteration->project->name?></a></small></td>
						<td><small><a href="<?=site_url("projects/iterations/view/$iteration->id")?>"><i class="fa fa-globe"></i> Iteration - <?=$iteration->title?></a></small></td>
						<td>
							<div class="text-muted text-right"><?=markdown_replace("#iteration-$iteration->id")?> <small><em>Created: <?=date("F j, Y",strtotime($iteration->date))?></small></em></div>	
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
