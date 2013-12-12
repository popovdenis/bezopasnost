<script language="JavaScript" type="text/javascript">
	var ajax_info_path;
	var ajax_info_path = "<?php echo base_url(); ?>ajax_handlers/info_handler/ajax_actions";	
	
	function paginate(page){
		$.ajax({
			type: "POST", 
			url: ajax_info_path,
			dataType: "json",
			data: { 'action':'paginate_items',
					'page': page
				  },
			beforeSend: function()
			{
				$(".page_container").html('<img border="0" src="<?php echo base_url(); ?>images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;padding-left:145px;text-align:center;"/>');
			},	  
			success: function(data)
			{
				$("#items_block").html(data.items_block);
				$(".page_container").html(data.page_container);
		    },
		    error: function(data)
			{	
				$("#items_block").html('');
				$(".page_container").html('');
		    }
		});
		
		return true;
	}
	
</script>