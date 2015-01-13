<?php if ($internal_access): ?>
<a class="btn btn-success btn-sm" href="<?=site_url("accounts/link/$project->id")?>" data-target=".modal-link-contact" data-toggle="modal">
	<i class="fa fa-link"></i> Link
</a>
<?=empty_modal('modal-link-contact','Link Contact')?>
<a class="btn btn-primary btn-sm pull-right" href="<?=site_url("accounts/index/project/$project->id")?>">
	<i class="fa fa-arrows-alt"></i> Details
</a>
<?php endif ?>

<table class="table table-bordered table-striped table-condensed mini-datatable">
	<thead>
		<tr>
			<th>Name</th>
			<th>Role</th>
			<th>Email</th>
			<th>Phone</th>
			<th>Company</th>
		</tr>
	</thead>

	<tbody>
		<?php foreach ($stakeholders as $stakeholder): ?>
		<tr>
			<?php if ($internal_access): ?>
			<td><a href="<?=site_url("accounts/view/$stakeholder->contact_id")?>"><?=$stakeholder->contact_name?></a></td>
			<?php else: ?>
			<td><?=$stakeholders->contact_name?></td>
			<?php endif ?>
			<td><?=humanize($stakeholder->role)?></td>
			<td><a href="mailto:<?=$stakeholder->contact_email?>"><?=$stakeholder->contact_email?></a></td>
			<td><?=format_phone($stakeholder->contact_phone)?></td>
			<td><?=$stakeholder->contact_company?></td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>
