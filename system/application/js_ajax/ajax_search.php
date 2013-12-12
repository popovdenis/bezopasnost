<script language="JavaScript" type="text/javascript">
	var ajax_search_path;
	var ajax_search_path = "<?php echo base_url(); ?>ajax_handlers/search_handler/ajax_actions";	
	
	function paginate(page, genre_id){
		var category_id = parseInt(window.location.hash.substr(1));
		$.ajax({
			type: "POST", 
			url: ajax_search_path,
			dataType: "json",
			data: { 'action':'paginate_items',
				'page': page,
				'category_id': category_id
			},
			beforeSend: function()
			{
				$(".page_container").html('<div style="float:left;"><img border="0" src="<?php echo base_url(); ?>images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;padding-left:145px;text-align:center;"/></div>');
			},
			success: function(data)
			{
				var num_old = parseInt($("#results_"+ category_id +"_from").text());
				
				$("#items_block").html(data.template);
				$(".page_container").html(data.paginate_args);
				$("#count_result").html(data.count);
				
				var li_counts = $("#results-"+ category_id+' ul').find('li').size();
				
				var num1 = data.page*data.per_page; // 25*3=75
				var num2 = data.per_page-li_counts; // 25-18=7
				var num3 = num1-num2; // 75-7=68;				

				$("#results_"+ category_id +"_from").text(num3);
		    },
		    error: function(data)
			{	
				$("#items_block").html('');
				$(".page_container").html('');
		    }
		});
		
		return true;
	}
	
	function search_by_tag(tag){
		$.ajax({
			type: "POST", 
			url: ajax_search_path,
			dataType: "json",
			data: { 'action':'search_by_tag',
					'tag': tag
				  },
			beforeSend: function()
			{
				$(".page_container").html('<div style="float:left;"><img border="0" src="<?php echo base_url(); ?>images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;padding-left:145px;text-align:center;"/></div>');
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