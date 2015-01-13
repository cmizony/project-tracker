<table class="table table-bordered table-condensed mini-datatable">
	<thead>
		<tr>
			<th>User</th>
			<th>Action</th>
			<th>Date</th>
		</tr>
	</thead>

	<tbody>
		<?php foreach ($activities as $activity): ?>
		<tr class="<?=convert_status($activity->action)?>">
			<?php if ($activity->contact_id == -1): ?>
			<td><?=$label_company?></td>
			<?php else: ?>
			<td><?=$activity->contact_name?></td>
			<?php endif ?>
			<td><?=$activity->title?></td>
			<td><?=format_date("F j, Y g:i a",$activity->date)?></td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>
