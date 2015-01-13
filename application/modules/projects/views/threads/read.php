<div class="read-view">
	<div class="panel panel-primary">

		<div class="panel-heading">
			<p class="lead"><i class="fa fa-comment-o fa-2x"></i> <?=$thread->title?></p>
		</div>

		<div class="panel-body">
			<table class="table table-bordered">
				<tbody>
					<tr class="gray">
						<td>
							<div class="text-primary text-center"><?=strtotime($thread->min_date)?$thread->total:0?> Messages</div>
							<small class="text-left text-muted"><em>Total</em></small>
						</td>
						<td>
							<div class="text-primary text-center"><?=format_date('F j, Y',$thread->min_date)?>&nbsp;</div>
							<small class="text-left text-muted"><em>First</em></small>
						</td>
						<td>
							<div class="text-primary text-center"><?=format_date('F j, Y',$thread->max_date)?>&nbsp;</div>
							<small class="text-left text-muted"><em>Last</em></small>
						</td>
					</tr>
				</tbody>
			</table>
			<br>
			<div class="container-fluid">
				<div class="row">
					<?php if ($thread->thumbnail_exists()): ?>
					<img src="<?=site_url("projects/threads/thumbnail/$thread->id")?>" class="img-rounded" height="100px" align=left style="margin-right:10px;">
					<?php endif ?>
					<p class="text-muted"><?=nl2br($thread->outline)?></p>
				</div>
			</div>

			<div class="panel panel-default panel-description">
				<div class="panel-body">
					<?=nl2br(markdown_replace($thread->description))?>
				</div>
			</div>
			
			<table class="table table-bordered table-condensed">
				<tbody>
					<tr class="gray">
						<td><small><a href="<?=site_url("projects/view/$thread->project_id")?>"><i class="fa fa-globe"></i> Project - <?=$thread->project->name?></a></small></td>
						<td><small><a href="<?=site_url("projects/threads/view/$thread->id")?>"><i class="fa fa-globe"></i> Thread - <?=$thread->title?></a></small></td>
						<td>
							<div class="text-muted text-right"><?=markdown_replace("#thread-$thread->id")?> <small><em>Created: <?=date("F j, Y",strtotime($thread->date))?></small></em></div>	
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
