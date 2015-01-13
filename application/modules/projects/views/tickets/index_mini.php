<a class="btn btn-sm btn-success" href="<?=site_url("projects/tickets/add/$project->id")?>" data-target=".modal-add-ticket" data-toggle="modal">
	<i class="fa fa-plus"></i> New
</a>
<?=empty_modal('modal-add-ticket','Add Ticket')?>
<?php if ($internal_access): ?>
<a class="btn btn-sm btn-primary pull-right" href="<?=site_url("projects/tickets/index/project/$project->id")?>">
	<i class="fa fa-arrows-alt"></i> Details
</a>
<?php endif ?>

<table class="table table-bordered mini-datatable table-condensed">
	<thead>
		<tr>
			<th class="col-md-1">#</th>
			<th>Title</th>
			<th>Type</th>
			<th>Priority</th>
			<th>Status</th>
		</tr>
	</thead>

	<tbody>
		<?php foreach ($tickets as $ticket): ?>
		<tr class="<?=convert_status($ticket->status)?>">
			<td><?=$ticket->number?></td>
			<td><a href="<?=site_url("projects/tickets/view/$ticket->id")?>"><?=$ticket->title?></a></td>
			<td><?=$ticket->type?></td>
			<td><span class="label label-<?=convert_status($ticket->priority)?>"><?=$ticket->priority?></span></td>
			<td><?=$ticket->status?></td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>
