var base_url = base_help_url;
var ajax_gallery_path = base_url + "ajax_handlers/gallery_handler/ajax_actions";

function paginate(page, genre_id){
	$.ajax({
		type: "POST", 
		url: ajax_gallery_path,
		dataType: "json",
		data: { 'action':'paginate_items',
				'page': page
			  },
		beforeSend: function()
		{
			$(".page_container").html('<img border="0" src="'+base_url+'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;padding-left:145px;text-align:center;"/>');
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

function delete_img(attach_id, item_id, from_gallery){
	$.ajax({
		type: "POST", 
		url: ajax_gallery_path,
		dataType: "json",
		data: { 'action':'delete_img',
				'attach_id': attach_id,
				'item_id': item_id,
				'from_gallery': from_gallery
			  },
		beforeSend: function()
		{
			$("#gallery_img_id_"+attach_id).html('<img border="0" src="'+ base_url +'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;padding-left:145px;text-align:center;"/>');
		},	  
		success: function(data)
		{
			$("#gallery_img_id_"+attach_id).html('');
			if(data == 1) alert('Картинка была успешно удаленаю');							
	    },
	    error: function(data)
		{
			$("#gallery_img_id_"+attach_id).html('');
	    }
	});
	
	return true;
}
