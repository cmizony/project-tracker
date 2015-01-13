<div id="box-breadcrumb">
</div>

<script defer>
$(function() {
	$.ajax({
		url: ARNY.site_url + "tools/breadcrumb",
		type: 'POST',
		data: { url: <?=$current?> },
		success: function (data) { 
			$("#box-breadcrumb").html(data);
		}
	});
});
</script>
