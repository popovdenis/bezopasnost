var base_url = "http://" + window.location.hostname + "/";
var ajax_admin_path = base_url + "ajax_handlers/admin_handler/ajax_actions";

function dump(obj, step) {
	if (typeof step == 'undefined') {
		step = -1;
	}
	step++;
	var pad = new Array(2*step).join('   ');
	var str = typeof(obj)+":\n";
	for(var p in obj){
		if (typeof obj[p] == 'object') {
			str += pad+'   ['+p+'] = '+dump(obj[p], step);
		} else {
			str += pad+'   ['+p+'] = '+obj[p]+"\n";
		}
	}
	return str;
}

function htmlspecialchars(content){
	content = str_replace(content, '&', '');
	content = str_replace(content, '+', '002B');
	
	return content;
}

function str_replace(haystack, needle, replacement) { 
	var temp = haystack.split(needle); 
	return temp.join(replacement); 
}

/**************** Items  *******************/

function get_new_page(page){
	$.ajax({
		type: "POST",
		url: ajax_admin_path,
		dataType: "html",
		data: {
			'action':'get_new_page',
			'page':page,
			'flag': 'new'
		},
		beforeSend: function()
		{
			FCKeditorAPI = null;
			__FCKeditorNS = null;
			$("#add_item_img").show();
		},
		success: function(data)
		{
			$("#add_item_img").hide();
			$.prompt(data,{ 
				buttons:{'Применить':true},
				submit:function(v,m, f){
					an = m.children('#new_item_title');
  					if(f.new_item_title == ""){
  						alert('Статья не может быть сохранена, так как вы не ввели название для этой статьи.');
  						return false;
  					} else {
  						add_item(page);
  						return true;
  					}
				}
			});
			
		},
		error: function(data)
		{
			$("#add_item_img").hide();
			$("#"+page).html('');
		}
	});
}

function get_page(page, item_id){
	$.ajax({
		type: "POST", 
		url: ajax_admin_path,
		dataType: "html",
		data: { 
				'action':'get_page',
				'page':page,
				'item_id': item_id,
				'flag': 'exist'
			  },
		beforeSend: function()
		{
			FCKeditorAPI = null;
			__FCKeditorNS = null;
			$("li").removeClass("active");
			$("#li_"+page).addClass("active");
			
			$("#about").html('');
			$("#information").html('');
			$("#partners").html('');
			$("#products").html('');				
			$("#sertificates").html('');
			$("#contacts").html('');
			$("#settings").html('');				
			
			$("#"+page).html('<img border="0" src="'+base_url+'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
		},	  
		success: function(data)
		{
			if(data == 5) window.location = base_url+"admin/home";
			else
				$("#"+page).html(data);
	    },
	    error: function(data)
		{
			alert('error');
			$("#"+page).html('');
	    }
	});
	
	return true;
}

function add_item(page){
	if($("#new_item_title").val() == '') {alert('Статья не может быть сохранена, так как вы не ввели название для этой статьи.');return false;}
	if($("#new_item_title").val().replace(/\s+/g, '').length) {
		// categories
		var num = $('input[id^="ch_"]').length;
		var product = new Object();
		var checkboxes = new Array(num);
		var j=0;
		if(num > 0) {
		$('input[id^="ch_"]').each(function () {
			var ch_id = $(this).attr('id');
			var productobj = new Object();

			productobj.id = ch_id;
			productobj.is_checked = $(this).attr('checked')?1:0;
			productobj.val = $(this).val();
			
			checkboxes[j] = productobj;
			j++;
		});
		product.chb = checkboxes;
		} else {
			product.chb = 0;
		}
		
		var content = FCKeditorAPI.GetInstance('new_post_content').GetXHTML();
		content = htmlspecialchars(content);
		
		$.ajax({
			type: "POST",
			url: ajax_admin_path,
			dataType: "html",
			data:{ 
				'action': 'add_item',
				'item_title': $("#new_item_title").val(),
				'post_preview': $("#new_post_preview").val(),
				'item_marks': $("#new_item_marks").val(),
				'item_tags': $("#new_item_tags").val(),
				'item_type': $("#item_type").val(),
				'item_date_production': $("#datepicker_"+page).val(),
				'hour': $("#hour_"+page).val(),
				'minute': $("#minute_"+page).val(),
				'item_mode': $("#new_item_mode option:selected").val(),
				'content': serialize(content),
				'item': serialize(product)
			},
			beforeSend: function()
			{
				FCKeditorAPI = null;
				__FCKeditorNS = null;
				$("#add_item_img").show();
			},
			success: function(data)
			{
				FCKeditorAPI = null;
				__FCKeditorNS = null;
				
				if(data == 5) window.location = base_url+"admin/home";
				else {
					$("#add_item_img").hide();
					$("#items_"+page).html(data);
				}
			},
		    error: function(data)
			{	
				$("#add_item_img").hide();
				$("#"+page).html('');
		    }
		});
		return true;
	} else {
        alert('Статья не может быть сохранена, так как вы не ввели название для этой статьи.');
    }
}

function save_item(product_id, item_type){
	if($("#item_title").val() == '') {alert('Статья не может быть сохранена, так как вы не ввели название для этой статьи.');return;}
	if($("#item_title").val().replace(/\s+/g, '').length) {
       
		// categories
		var num = $('input[id^="ch_"]').length;
		var item = new Object();
		var checkboxes = new Array(num);
		var j=0;
		
		if(product_id != undefined)
			item.product_id = product_id;
		if(item_type != undefined)
			item.item_type = item_type;
		
		if(num > 0) {
			$('input[id^="ch_"]').each(function () {
				var ch_id = $(this).attr('id');
				var itemobj = new Object();
	
				itemobj.id = ch_id;
				itemobj.is_checked = $(this).attr('checked')?1:0;
				itemobj.val = $(this).val();
				
				checkboxes[j] = itemobj;
				j++;
			});
			item.chb = checkboxes;
		} else {
			item.chb = 0;
		}
		
		var content = FCKeditorAPI.GetInstance('post_content').GetXHTML();
		content = htmlspecialchars(content);
		
		$.ajax({
			type: "POST",
			url: ajax_admin_path,
			dataType: "html",
			data:{ 
				'action': 'save_item',
				'item_title': $("#item_title").val(),
				'post_preview': $("#post_preview").val(),
				'item_marks': $("#item_marks").val(),
				'item_tags': $("#item_tags").val(),
				'item_date_production': $("#datepicker_"+product_id).val(),
				'hour': $("#hour_"+product_id).val(),
				'minute': $("#minute_"+product_id).val(),
				'item_mode': $("#item_mode_"+product_id+" option:selected").val(),
				'content': serialize(content),
				'item': serialize(item)
			},
			beforeSend: function()
			{
				$("#"+item_type).html('<img border="0" src="'+base_url+'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
			},
			success: function(data)
			{
				if(data == 5) window.location = base_url+"admin/home";
				else
					$("#"+item_type).html(data);	
			},
			error: function(data)
			{
				alert('error');
			}
		});
		return true;
	} else {
        alert('Статья не может быть сохранена, так как вы не ввели название для этой статьи.');
    }
}

function delete_items_checked(){
	var num = $('input[id^="item_chb_delete_"]').length;
	if(num == 0) { alert('Вы должны выбрать хотя бы одну статью!');return false;}
	
	var item_obj = new Object();
	var checkboxes = new Array(num);
	var j=0;
	
	$('input[id^="item_chb_delete_"]').each(function () {
		var ch_id = $(this).attr('id');
		var item = ch_id.split("_");
		var item_id = item[3];
		var itemobj = new Object();

		var is_checked = $(this).attr('checked');
		if(is_checked) {
			itemobj.item_id = item_id;
						
			checkboxes[j] = itemobj;
			j++;
		}
	});
	item_obj.chb = checkboxes;		
	
	$.ajax({
		type: "POST",
		url: ajax_admin_path,
		dataType: "html",
		data: 'action=delete_items_checked&jsonData=' + serialize(item_obj),
		success: function(data)
		{
			if(data == 5) window.location = base_url+"admin/home";
			else{
				$('input[id^="item_chb_delete_"]').each(function () {
					var is_checked = $(this).attr('checked');
					if(is_checked) {
						var item = $(this).attr('id').split("_");
						var item_id = item[3];
						$("#item_block_"+item_id).remove();
					}
				});
			}	
		},
		error: function(data)
		{
		}
	});
}

function delete_item(item_id, redirect, item_type) {
	if(item_id == undefined || item_id == '') return false;
	
	$.ajax({
		type: "POST",
		url: ajax_admin_path,
		dataType: "html",
		data: {
			'action': 'delete_item',
			'item_id': item_id
		},
		beforeSend: function()
		{
			$("#delete_btn_"+item_id).html('<img border="0" src="'+base_url+'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
		},
		success: function(data)
		{
			if(data == 5) window.location = base_url+"admin/home";
			else {
				$("#item_block_"+item_id).remove();
				if(redirect == true) get_page(item_type);
			}
		},
		error: function(data)
		{
//				$("#chboxes").html('');
		}
	});
}

function paginate_items(page_num){
	
	var item_type = $("#item_type").val();
	
	$.ajax({
			type: "POST", 
			url: ajax_admin_path,
			dataType: "html",
			data: { 'action':'paginate_items',
					'page_num': page_num,
					'item_type': item_type
				  },
			beforeSend: function()
			{				
				$("#paginate_img").show();
			},	  
			success: function(data)
			{	
				$("#paginate_img").hide();
				$("#items_"+item_type).html(data);
		    },
		    error: function(data)
			{	
				$("#paginate_img").hide();
				$("#items_"+item_type).html('');
		    }
		});
	return true;
}


/********** Категории *************/

function add_category(){
	var cat_desc = '';
	var item_id = '';
	if($("#new_cat_desc").val() != undefined) cat_desc = $("#new_cat_desc").val();
	if($("#item_id").val() != undefined) item_id = $("#item_id").val();
	
	$.ajax({
		type: "POST",
		url: ajax_admin_path,
		dataType: "html",
		data: {
			'action': 'add_category',
			'item_id': item_id,
			'category_title': $("#category_title").val(),
			'category_desc': cat_desc,
			'category_parent': $("#categories_new option:selected").val()
		},
		success: function(data)
		{
			if(data == 5) window.location = base_url+"admin/home";
			else
				$("#chboxes").html(data);	
		},
		error: function(data)
		{
			$("#chboxes").html('');
		}
	});
}

function new_category(){		
	$.ajax({
		type: "POST",
		url: ajax_admin_path,
		dataType: "json",
		data: {
			'action': 'add_category',
			'category_title': $("#new_category_title").val(),
			'category_desc': $("#new_cat_desc").val(),
			'category_parent': $("#categories_new option:selected").val()
		},
		beforeSend: function()
		{
			$("#new_category_block").hide('slow');
			$("#set_cat_found").html('<img border="0" src="'+base_url+'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
		},
		success: function(data)
		{
			if(data == 5) window.location = base_url+"admin/home";
			else {
				$("#category_header").html(data.header);
				$("#set_cat_found").html(data.content);
			}
		},
		error: function(data)
		{
			$("#set_cat_found").html('');
		}
	});
}

function search_category(){
	$.ajax({
		type: "POST",
		url: ajax_admin_path,
		dataType: "html",
		data: {
			'action': 'search_category',
			'category_id': $("#search_category_list option:selected").val()
		},
		beforeSend: function()
		{
			$("#set_cat_found").html('<img border="0" src="'+base_url+'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
		},
		success: function(data)
		{
			if(data == 5) window.location = base_url+"admin/home";
			if(data == 0) $("#set_cat_found").html('<font class="cat_not_found">Категория не найдена</font>');
			else
				$("#set_cat_found").html(data);	
		},
		error: function(data)
		{
			$("#set_cat_found").html('Категория не найдена');
		}
	});
}

function update_category(cat_id){
	if(cat_id == '' || cat_id == undefined) return false;
	
	$.ajax({
		type: "POST",
		url: ajax_admin_path,
		dataType: "json",
		data: {
			'action': 'update_category',
			'category_id': cat_id,
			'category_title': $("#found_category_title").val(),
			'category_desc': $("#found_cat_desc").val(),
			'category_parent': $("#found_categories_parent option:selected").val()
		},
		beforeSend: function()
		{
			$("#set_cat_found").html('<img border="0" src="'+base_url+'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
		},
		success: function(data)
		{
			if(data == 5) window.location = base_url+"admin/home";
			$("#category_header").html(data.header);
			$("#set_cat_found").html(data.content);	
		},
		error: function(data)
		{
			$("#set_cat_found").html('');
		}
	});
}

function delete_category(cat_id){
	if(cat_id == '' || cat_id == undefined) return false;
	
	$.ajax({
		type: "POST",
		url: ajax_admin_path,
		dataType: "html",
		data: {
			'action': 'delete_category',
			'category_id': cat_id
		},
		beforeSend: function()
		{
			$("#set_cat_found").html('<img border="0" src="'+base_url+'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
		},
		success: function(data)
		{
			if(data == 5) window.location = base_url+"admin/home";
			$("#category_header").html(data);
			$("#set_cat_found").html('');
		},
		error: function(data)
		{
			$("#set_cat_found").html('');
		}
	});
}

function add_category_partner(category_id){
	if(category_id == '' || category_id == undefined) return false;
	
	var partner_id = $("#category_partner_list option:selected").val();
	if(partner_id == 0 || partner_id == undefined || partner_id == '') return false;
	
	$.ajax({
		type: "POST",
		url: ajax_admin_path,
		dataType: "html",
		data: {
			'action': 'add_category_partner',
			'category_id': category_id,
			'partner_id': partner_id
		},
		beforeSend: function()
		{
			$("#category_partners_img").html('<img border="0" src="'+base_url+'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
		},
		success: function(data)
		{
			if(data == 5) window.location = base_url+"admin/home";
			var result = '';
			var partner_name = $("#category_partner_list option:selected").text();
			if(data == 1) result = '<div id="partner_'+partner_id+'"><span>'+partner_name+'</span><a href="#" onclick="javascript:delete_category_partner('+partner_id+');return false;">Удалить</a></div>';
			else result = '';
			$("#category_partners_img").html('');
			$("#category_partners").append(result);
		},
		error: function(data)
		{
			$("#category_partners_img").html('');
		}
	});
}

function delete_category_partner(category_id, partner_id){
	if(category_id == '' || category_id == undefined) return false;
	if(partner_id == '' || partner_id == undefined) return false;
	
	$.ajax({
		type: "POST",
		url: ajax_admin_path,
		dataType: "html",
		data: {
			'action': 'delete_category_partner',
			'category_id': category_id,
			'partner_id': partner_id
		},
		beforeSend: function()
		{
			$("#category_partners_img").html('<img border="0" src="'+base_url+'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
		},
		success: function(data)
		{
			if(data == 5) window.location = base_url+"admin/home";
			if(data == 1) {
				$("#category_partners_img").html('');
				$("#partner_"+partner_id).remove();
			}
		},
		error: function(data)
		{
			$("#category_partners_img").html('');
		}
	});
}

function reorder_categories(category_parent_id){
	var num = $('#sortable li').length;
	var cat_order = new Array(num);
	var i=0;
	
	$('#sortable li').each(function () {
		cat_order[i] = $(this).attr('id');
		i++;
	});
	$.ajax({
		type: "POST",
		url: ajax_admin_path,
		dataType: "html",
		data: {
			'action': 'reorder_categories',
			'category_id': category_parent_id,
			'cat_order':serialize(cat_order)
		},
		beforeSend: function()
		{
			$("#category_subcats_img").show();
		},
		success: function(data)
		{
			if(data == 5) window.location = base_url+"admin/home";
			if(data == 1) {
				$("#category_subcats_img").hide();
			}
		},
		error: function(data)
		{
			$("#category_partners_img").html('');
		}
	});
}

/**** Контакты  ****/
function update_contacts(){
	$.ajax({
		type: "POST",
		url: ajax_admin_path,
		dataType: "html",
		data: {
			'action': 'update_contacts',
			'contact_phone1': $("#contact_phone1").val(),
			'contact_phone2': $("#contact_phone2").val(),
			'contact_phone3': $("#contact_phone3").val(),
			'contact_phone4': $("#contact_phone4").val(),
			'contact_phone5': $("#contact_phone5").val(),
			'contact_address1': $("#contact_address1").val(),
			'contact_address2': $("#contact_address2").val(),
			'contact_email1': $("#contact_email1").val(),
			'contact_email2': $("#contact_email2").val(),
			'contact_fax': $("#contact_fax").val(),
			'time1': $("#time1").val(),
			'time2': $("#time2").val()
		},
		beforeSend: function()
		{
			$("#contacts").html('<img border="0" src="'+base_url+'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
		},
		success: function(data)
		{
			if(data == 5) window.location = base_url+"admin/home";
			$("#contacts").html(data);
		},
		error: function(data)
		{
			$("#contacts").html('');
		}
	});
}

/**** Пользователи ***/
function get_user(){
	$.ajax({
		type: "POST",
		url: ajax_admin_path,
		dataType: "html",
		data: {
			'action': 'get_user',
			'user_id': $("#search_user_list option:selected").val()
		},
		beforeSend: function()
		{
			$("#set_user_found").html('<img border="0" src="'+base_url+'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
		},
		success: function(data)
		{
			if(data == 5) window.location = base_url+"admin/home";
			if(data == 0) $("#set_user_found").html('<font class="cat_not_found">Пользователь не найден</font>');
			else
				$("#set_user_found").html(data);	
		},
		error: function(data)
		{
			$("#set_user_found").html('Пользователь не найден');
		}
	});
}

function add_user(){
	$.ajax({
		type: "POST",
		url: ajax_admin_path,
		dataType: "json",
		data: {
			'action': 'add_user',
			'user_login': $("#new_user_login").val(),
			'user_password': $("#new_user_password").val(),
			'first_name': $("#new_user_first_name").val(),
			'last_name': $("#new_user_last_name").val(),
			'user_email': $("#new_user_email").val(),
			'user_role': $("#new_user_role option:selected").val()
		},
		beforeSend: function()
		{
			$("#new_user_block").hide('slow');
			$("#set_user_found").html('<img border="0" src="'+base_url+'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
		},
		success: function(data)
		{
			if(data == 5) window.location = base_url+"admin/home";
			$("#search_user_name").html(data.users_list);
			$("#set_user_found").html(data.user_info);
		},
		error: function(data)
		{
			$("#set_user_found").html('');
		}
	});
}

function update_user(user_id){
	if(user_id == '' || user_id == undefined) return false;
	
	$.ajax({
		type: "POST",
		url: ajax_admin_path,
		dataType: "json",
		data: {
			'action': 'update_user',
			'user_id': user_id,
			'user_login': $("#user_login").val(),
			'first_name': $("#first_name").val(),
			'last_name': $("#last_name").val(),
			'user_email': $("#user_email").val(),
			'user_role': $("#user_role option:selected").val()
		},
		beforeSend: function()
		{
			$("#set_user_found").html('<img border="0" src="'+base_url+'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
		},
		success: function(data)
		{
			if(data == 5) window.location = base_url+"admin/home";
			$("#search_user_name").html(data.users_list);
			$("#set_user_found").html(data.user_info);	
		},
		error: function(data)
		{
			$("#set_user_found").html('');
		}
	});
}

function delete_user(user_id){
	if(user_id == '' || user_id == undefined) return false;
	
	$.ajax({
		type: "POST",
		url: ajax_admin_path,
		dataType: "html",
		data: {
			'action': 'delete_user',
			'user_id': user_id
		},
		beforeSend: function()
		{
			$("#set_user_found").html('<img border="0" src="'+base_url+'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
		},
		success: function(data)
		{
			if(data == 5) window.location = base_url+"admin/home";
			$("#search_user_name").html(data);
			$("#set_user_found").html('');
		},
		error: function(data)
		{
			$("#set_user_found").html('');
		}
	});
}

function change_password(user_id){
	if(user_id == '' || user_id == undefined) return false;
	
	$.ajax({
		type: "POST",
		url: ajax_admin_path,
		dataType: "html",
		data: {
			'action': 'change_password',
			'user_id': user_id
		},
		beforeSend: function()
		{
			$("#user_img_loading").show();
		},
		success: function(data)
		{
			if(data == 5) window.location = base_url+"admin/home";
			if(data == 1) {
				$("#user_password").removeAttr('disabled');
				$('#paswd_link').html('<a style="font-size:11px;" href="#" onclick="javascript:save_password(\''+user_id+'\'); return false;">Сохранить пароль</a>');
			}
			$("#user_img_loading").hide();				
		},
		error: function(data)
		{
			$("#user_img_loading").hide();
		}
	});
}

function save_password(user_id){		
	if(user_id == '' || user_id == undefined) return false;
	
	$.ajax({
		type: "POST",
		url: ajax_admin_path,
		dataType: "html",
		data: {
			'action': 'save_password',
			'user_id': user_id,
			'user_password': $("#user_password").val()
		},
		beforeSend: function()
		{
			$("#user_img_loading").show();
		},
		success: function(data)
		{
			if(data == 5) window.location = base_url+"admin/home";
			$("#user_password").val('');
			$("#user_img_loading").hide();
			if(data == 1) {
				$("#user_password").attr('disabled', 'disabled');
				$('#paswd_link').html('<a style="font-size:11px;" href="#" onclick="javascript:change_password(\''+user_id+'\'); return false;">Изменить пароль</a>');
			}				
		},
		error: function(data)
		{
			$("#user_img_loading").hide();
		}
	});
}
