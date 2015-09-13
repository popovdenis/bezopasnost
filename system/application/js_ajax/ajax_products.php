<script language="JavaScript" type="text/javascript">
	var ajax_product_path;
	var ajax_product_path = "<?php echo base_url(); ?>ajax_handlers/products_handler/ajax_actions";

	function paginate(page){
		$.ajax({
			type: "POST",
			url: ajax_product_path,
			dataType: "json",
			data: { 'action':'paginate_items',
					'page': page,
					'category_id': $('#category_id').val()
				  },
			beforeSend: function()
			{
				$(".page_container").html('<div style="float:left;"><img border="0" src="<?php echo base_url(); ?>images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;padding-left:145px;text-align:center;"/></div>');
			},
			success: function(data)
			{
				$("#products_block").html(data.item_main);
				$(".page_container").html(data.page_container);
		    },
		    error: function(data)
			{
				$("#products_block").html('');
				$(".page_container").html('');
		    }
		});

		return true;
	}

	function filter_products(){
		var filter_name = $("#bynames option:selected").val();
		var filter_size = $("#bysize option:selected").val();

		$.ajax({
			type: "POST",
			url: ajax_product_path,
			dataType: "json",
			data: { 'action':'filter_products',
					'filter_name':filter_name,
					'filter_size': filter_size,
					'category_id': $('#category_id').val()
				  },
			beforeSend: function()
			{
//				$("#filter_img").html('<img border="0" src="<?php echo base_url(); ?>images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;padding-left:145px;text-align:center;"/>');
			},
			success: function(data)
			{
				$("#filter_img").html('');
				$("#products_block").html(data.items_block);
				$(".page_container").html(data.page_container);
		    },
		    error: function(data)
			{
		    	$("#filter_img").html('');
				$("#products_block").html('');
				$(".page_container").html('');
		    }
		});

		return true;
	}

	function quick_search(){
		$.ajax({
			type: "POST",
			url: ajax_product_path,
			dataType: "json",
			data: { 'action':'quick_search',
					'keywords':$('#quick_search_field').val(),
					'category_id': $('#category_id').val()
				  },
			beforeSend: function()
			{
				$("#filter_img").show();
			},
			success: function(data)
			{
				$("#filter_img").hide();
				$("#products_block").html(data.items_block);
				$(".page_container").html(data.page_container);
		    },
		    error: function(data)
			{
		    	$("#filter_img").html('');
				$("#products_block").html('');
				$(".page_container").html('');
		    }
		});

		return true;
	}



	function open_compare(current_catid, item_id){
		$.ajax({
			type: "POST",
			url:  ajax_product_path,
			dataType: "html",
			data: {
				'action':'compare',
				'item_id': item_id
			},
			beforeSend: function()
			{


			},
			success: function(data)
			{
				var url = '<?=base_url()?>comparison';
				/*wnd = window.open( url,  '_compare' );
				alert(document.getElementById("compare_products").value);

				wnd.focus();*/


				var win2=window.open(url, '_compare');

			}
		});
	}

</script>
