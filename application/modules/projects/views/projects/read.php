<div class="read-view">
	<div class="panel panel-primary">

		<div class="panel-heading">
			<p class="lead"><i class="fa fa-list-alt fa-2x"></i> <?=$project->name?></p>
		</div>

		<div class="panel-body">
			<table class="table table-bordered">
				<tbody>
					<tr class="gray">
						<td>
							<div class="text-primary text-center"><?=$project->type?>&nbsp;</div>
							<small class="text-left text-muted"><em>Type</em></small>
						</td>
						<td>
							<div class="text-primary text-center"><?=$project->status?>&nbsp;</div>
							<small class="text-left text-muted"><em>Status</em></small>
						</td>
						<td>
							<div class="text-primary text-center"><?=$project->label?>&nbsp;</div>
							<small class="text-left text-muted"><em>Label</em></small>
						</td>
						<td>
							<div class="text-primary text-center"><?=$project->beneficiary->name?>&nbsp;</div>
							<small class="text-left text-muted"><em>Beneficiary</em></small>
						</td>
					</tr>
				</tbody>
			</table>
		
			<div class="panel panel-default panel-description">
				<div class="panel-body">
					<?=nl2br(markdown_replace($project->description))?>
				</div>
			</div>
			
			<table class="table table-bordered table-condensed">
				<tbody>
					<tr class="gray">
						<td><small><a href="<?=site_url("accounts/view/$project->beneficiary_id")?>"><i class="fa fa-globe"></i> Beneficiary - <?=$project->beneficiary->name?></a></small></td>
						<td><small><a href="<?=site_url("projects/view/$project->id")?>"><i class="fa fa-globe"></i> Project - <?=$project->name?></a></small></td>
						<td class="text-right">
							<div class="text-muted text-right"><?=markdown_replace("#project-$project->id")?> <small><em>Created: <?=date("F j, Y",strtotime($project->date))?></small></em></div>	
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
