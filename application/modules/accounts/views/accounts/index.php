<div class="alert alert-info pull-right" data-toggle="tooltip" title="Non-Members can not view this page" data-placement="bottom"><i class="fa fa-key"></i> Member Area</div>

<div class="row">
	<?php if ($manage_contacts): ?>
	<div class="col-md-3">
		<a class="btn btn-success" data-target=".modal-add-contact" data-toggle="modal" href="<?=site_url('accounts/add')?>">
			<i class="fa fa-plus"></i> Create Contact
		</a>
	</div>
	<?=empty_modal('modal-add-contact','Add Contact')?>
	<?php endif ?>

	<div class="col-md-6 text-center alert alert-success"><i class="fa fa-group"></i> <?=$page_title?></div>
</div>

<ul class="nav nav-tabs">
	<li class="active"><a href="#contacts-tab-map" data-toggle="tab"><i class="fa fa-users"></i> Profiles</a></li>
	<li><a href="#contacts-tab-logs" data-toggle="tab"><i class="fa fa-search"></i> Logs</a></li>
</ul>

<div class="tab-content">
	<div class="tab-pane active" id="contacts-tab-map">
		<br>
		<div class="google-map" id="google-map-canvas"></div>

		<table class="table table-striped table-condensed table-bordered contacts-datatable">
			<thead>
				<tr>
					<th>Id</th>
					<th>Latitude</th>
					<th>Longitude</th>
					<th>Login</th>
					<th>Name</th>
					<th>Email</th>
					<th>Company</th>
					<th>Note</th>
					<th>Address</th>
				</tr>
			</thead>

			<tbody>
				<?php foreach ($contacts as $contact): ?>
				<tr>
					<td><?=$contact->id?></td>
					<td><?=$contact->latitude?></td>
					<td><?=$contact->longitude?></td>
					<td><a href="<?=site_url("accounts/view/$contact->id")?>"><?=$contact->login?></a></td>
					<td><?=$contact->name?></td>
					<td><?=$contact->email?></td>
					<td><?=$contact->company?></td>
					<td><?=$contact->note?></td>
					<td class="<?=empty($contact->latitude)?'warning':''?>">
						<?=empty($contact->latitude)?'<i class="fa fa-warning-sign" title="Address seems to be wrong, please edit it"></i>':''?>
						<?=$contact->address?>
					</td>
				</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
	<div class="tab-pane" id="contacts-tab-logs">
		<br>
		<?=$rendered_logs?>
	</div>
</div>


<script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script defer>
$(function() {

	var global_contacts_timeout;
	var contacts_dtt = $('.contacts-datatable').dataTable( {
		"aaSorting": [],
		"aoColumnDefs": [{ "bVisible": false, "aTargets": [0,1,2] }],
		"sDom": "<'row'<'col-md-5'R l><'col-md-7'C f>r>t<'row'<'col-md-4'i><'col-md-4'T><'col-md-4'p>>",
		"oTableTools": {"sSwfPath": ARNY.swf_path,"aButtons": ["print","copy","csv","xls",]},
		"bStateSave": true,
		"sPaginationType": "bootstrap",
		"oLanguage": {"sSearch": "Filter:"},
		"oColVis": {"aiExclude": [ 0,1,2]},
		"fnDrawCallback": function (o) {
			$(".ColVis_MasterButton",o.nTableWrapper).addClass("btn btn-default");
			window.clearTimeout(global_contacts_timeout);
			global_contacts_timeout = window.setTimeout(refresh_map,800);
		},
		"fnInitComplete": function(oSettings, json) {
				$(this).removeClass("contacts-datatable");
		}
	});

	function refresh_map ()
	{
		var o = contacts_dtt.fnSettings();
		var header = new Array();
		var data = new Array();

		o.aoColumns.forEach(function(entry){header[entry.sTitle.toLowerCase()] = entry.mData;});
		o.oInstance._('tr', {"filter":"applied"}).forEach(function(entry){
			var obj = new Object();
			for (var index in header)
				obj[index] = $('<p>'+entry[header[index]]+'</p>').text();
			data.push(obj);
		});

		var mapOptions = {};

		var map = new google.maps.Map(document.getElementById('google-map-canvas'), mapOptions);
		var markers = new Array();
		var bounds = new google.maps.LatLngBounds();

		for (var key in data)
		{
			var contact = data[key];
			var id = contact.id;
			var link = ARNY.site_url+"/accounts/view/"+id;
			var div_address = '<a target="_blank" href="https://maps.google.com/maps?q='+
				encodeURI(contact.address)+'"><small>'+nl2br(contact.address)+'</small></a>';
			
			var contentString =	'<div style="white-space:nowrap">'+
				'<a href="'+link+'"><b>'+contact.name+'</b></a><br>'+
				'<span class="text-muted">'+contact.company+'</span><br>'+
				div_address+'</div>';

			markers[id] = new google.maps.Marker({
				position: new google.maps.LatLng(contact.latitude,contact.longitude),
				map: map,
				title: contact.name,
				infowindow: new google.maps.InfoWindow({content: contentString}),
				icon: ARNY.base_url+"resources/img/marker-building.png"
			});

			bounds.extend(markers[id].position);

			google.maps.event.addListener(markers[id], 'click', function() {
				this.infowindow.open(map,this);
			});

			google.maps.event.trigger(map, 'resize');
			map.fitBounds(bounds);

		}

		if (data.length==0)
		{
			new PNotify({text: "No Contacts selected",type:"info"});
			map.setZoom(2);
		}
	}
	$('a[href="#contacts-tab-map"]').on('shown.bs.tab', refresh_map);
	$('a[href="#contacts-tab-logs"]').on('shown.bs.tab', refresh_chart);
});
</script>
