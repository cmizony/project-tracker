<script>
var ARNY = (function(){
	var _baseUrl = "<?=base_url()?>/";
	var _siteUrl = "<?=site_url()?>/";
	return{
		"language": "<?php echo $this->config->item('language'); ?>",
		"base_url": _baseUrl,
		"site_url": _siteUrl,
		"swf_path":  _baseUrl+"resources/img/dataTables/dtt/copy_csv_xls_pdf.swf",
		"uri_segment_1":"<?php echo $uri_segment_1;?>",
		"uri_segment_2":"<?php echo $uri_segment_2;?>"
	}
})();
</script>
