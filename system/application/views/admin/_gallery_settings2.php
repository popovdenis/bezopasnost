<script type="text/javascript">
$(function(){
	new AjaxUpload('#gallery_main', {
		// Location of the server-side upload script
		action: '<?=base_url()?>admin/home/upload',
		// File upload name
		name: 'userfile',
		// Additional data to send
		data: {
			upload_type: 'gallery'
		},
	  responseType: false,
	  onChange: function(file, extension){},
	  onSubmit : function(file , ext){
		    if (! (ext && /^(<?=$allowed_types?>)$/.test(ext))){
		        // extension is not allowed
		        alert('Error: invalid file extension');
		        // cancel upload
		        return false;
		    } else {
		    	
		    	$("#map1_img").html('<img alt="loading..." border="0" src="<?php echo base_url() ?>images/loading-blue.gif" />');
		    }
		} ,
	  onComplete: function(file, response) {
	  	if(response) {
		  	var result = window["eval"]("(" + response + ")");
		  	var file = '<img src="<?=base_url()?>'+result.file_path+'" />';
		  	$('#map1_img').html(file);		  	
	  	}
	  }
	});
});
</script>