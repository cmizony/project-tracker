<div class="read-view">
	<div class="panel panel-primary">

		<div class="panel-heading">
			<p class="lead"><i class="fa fa-user fa-2x"></i> <?=$contact->name?></p>
		</div>

		<div class="panel-body">
			<table class="table table-bordered">
				<tbody>
					<tr class="gray">
						<td>
							<div class="text-primary text-center"><?=$contact->company?>&nbsp;</div>
							<small class="text-left text-muted"><em>Company</em></small>
						</td>
						<td>
							<div class="text-primary text-center"><?=$contact->role?>&nbsp;</div>
							<small class="text-left text-muted"><em>Role</em></small>
						</td>
						<td>
							<div class="text-primary text-center"><?=$contact->email?>&nbsp;</div>
							<small class="text-left text-muted"><em>Email</em></small>
						</td>
						<td>
							<div class="text-primary text-center"><?=format_phone($contact->phone)?>&nbsp;</div>
							<small class="text-left text-muted"><em>Phone</em></small>
						</td>
					</tr>
				</tbody>
			</table>

			<div class="panel panel-default panel-description">
				<div class="panel-body">
					<?=nl2br(markdown_replace($contact->description))?>
				</div>
			</div>
			
			<table class="table table-bordered table-condensed">
				<tbody>
					<tr class="gray">
						<td><small><a href="<?=site_url("accounts/view/$contact->id")?>"><i class="fa fa-globe"></i> Contact - <?=$contact->name?></a></small></td>
						<td>
							<div class="text-muted text-right"><?=markdown_replace("#contact-$contact->id")?> <small><em>Created: <?=date("F j, Y",strtotime($contact->date))?></small></em></div>	
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
