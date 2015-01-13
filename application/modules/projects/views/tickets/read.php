<div class="read-view">
	<div class="panel panel-primary">

		<div class="panel-heading">
			<p class="lead"><i class="fa fa-check-square-o fa-2x"></i> <?=$ticket->title?></p>
		</div>

		<div class="panel-body">
			<table class="table table-bordered">
				<tbody>
					<tr class="gray">
						<td>
							<div class="text-primary text-center"><?=$ticket->type?>&nbsp;</div>
							<small class="text-left text-muted"><em>Type</em></small>
						</td>
						<td>
							<div class="text-primary text-center"><?=$ticket->status?>&nbsp;</div>
							<small class="text-left text-muted"><em>Status</em></small>
						</td>
						<td>
							<div class="text-primary text-center"><?=$ticket->priority?>&nbsp;</div>
							<small class="text-left text-muted"><em>Priority</em></small>
						</td>
						<td>
							<div class="text-primary text-center"><?=strtotime($ticket->min_date)?$ticket->total:0?> Messages</div>
							<small class="text-left text-muted"><em>Total</em></small>
						</td>
					</tr>
				</tbody>
			</table>

			<div class="panel panel-default panel-description">
				<div class="panel-body">
					<?=nl2br(markdown_replace($ticket->description))?>
				</div>
			</div>
			
			<table class="table table-bordered table-condensed">
				<tbody>
					<tr class="gray">
						<td><small><a href="<?=site_url("projects/view/$ticket->project_id")?>"><i class="fa fa-globe"></i> Project - <?=$ticket->project->name?></a></small></td>
						<td><small><a href="<?=site_url("projects/tickets/view/$ticket->id")?>"><i class="fa fa-globe"></i> Ticket - <?=$ticket->title?></a></small></td>
						<td>
							<div class="text-muted text-right"><?=markdown_replace("#ticket-$ticket->id")?> <small><em>Created: <?=date("F j, Y",strtotime($ticket->date))?></small></em></div>	
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
