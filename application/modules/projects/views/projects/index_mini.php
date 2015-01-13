<table class="table table-condensed table-striped table-bordered mini-datatable">
	<thead>
		<tr>
			<th>Name</th>
			<th>Role</th>
			<th>Status</th>
			<th>Creation Date</th>
		</tr>
	</thead>

	<tbody>
		<?php foreach ($projects as $project): ?>
		<tr>
			<td><a href="<?=site_url("projects/view/$project->id")?>"><?=$project->name?></a></td>
			<td><?=$project->stakeholder_role?></td>
			<td><?=$project->status?></td>
			<td><?=date("F j, Y",strtotime($project->date))?></td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>
