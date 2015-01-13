<?php if ($manage_iterations): ?>
<a class="btn btn-success btn-sm" href="<?=site_url("projects/iterations/add/$project->id")?>" data-target=".modal-add-iteration" data-toggle="modal">
	<i class="fa fa-plus"></i> New
</a>
<?=empty_modal('modal-add-iteration','Add Iteration')?>
<?php endif ?>
<?php if ($internal_access): ?>
<a class="btn btn-sm btn-primary pull-right" href="<?=site_url("projects/iterations/index/project/$project->id")?>">
	<i class="fa fa-arrows-alt"></i> Details
</a>
<?php endif ?>

<table class="table table-bordered table-condensed mini-datatable">
	<thead>
		<tr>
			<th class="col-md-1">#</th>
			<th>Title</th>
			<th>Status</th>
			<th>Duration</th>
			<th>Label</th>
		</tr>
	</thead>

	<tbody>
		<?php foreach ($iterations as $iteration): ?>
		<tr class="<?=convert_status($iteration->status)?>">
			<td><?=$iteration->id?></td>
			<?php if ($internal_access): ?>
			<td><a href="<?=site_url("projects/iterations/view/$iteration->id")?>"><?=$iteration->title?></a></td>
			<?php else: ?>
			<td><?=$iteration->title?></td>
			<?php endif ?>

			<td><?=$iteration->status?></td>
			<td><?=humanize_sec($iteration->time)?></td>
			<td><span class="badge"><?=$iteration->label?></span></td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>
