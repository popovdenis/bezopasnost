var general_url = "http://" + window.location.hostname + "/";
var ajax_general_path = "ajax_handlers/products_handler/ajax_actions/";

function set_general_url(url){
   general_url = url;
   ajax_general_path = general_url + "ajax_handlers/search_handler/ajax_actions/";
}

function trim(string) {
	return string.replace(/(^\s+)|(\s+$)/g, "");
}
$(document).ready(function() {
	$('input[id="quick_search_field"]').addClass("idleField");
    $('input[id="main_keywords"]').addClass("idleField");
	$('input[id="quick_search_field"]').focus(function() {
		$(this).removeClass("idleField").addClass("focusField");
		if (this.value == this.defaultValue){
			this.value = '';
		}
		if(this.value != this.defaultValue){
			this.select();
		}
		this.value = '';
	});
    $('input[id="main_keywords"]').focus(function() {
		$(this).removeClass("idleField").addClass("focusField");
		if (this.value == this.defaultValue){
			this.value = '';
		}
		if(this.value != this.defaultValue){
			this.select();
		}
		this.value = '';
	});
	$('input[id="quick_search_field"]').blur(function() {
		$(this).removeClass("focusField").addClass("idleField");
		if (this.value == ''){
			this.value = 'Быстрый поиск';
		}		
	});
	$('input[id="main_keywords"]').blur(function() {
		$(this).removeClass("focusField").addClass("idleField");
		if (this.value == ''){
			this.value = 'Быстрый поиск';
		}
	});
});

function go_to_search(){
	window.location = general_url+"search";
}

function sort_search_result(trigger){	
	$('div[id^="results"]').each(function () {
				
		if($(this).attr('id') != 'results-'+trigger) {
			if(trigger != 'all') $(this).hide('fast');
			else $(this).show('fast');
		} else $(this).show('fast');
	});
	$('li').removeClass('selected');
	$('a').removeClass('selected');
	$('#'+trigger+'-categories-trigger').addClass('selected');
	$('#'+trigger+'-categories-trigger:parent').addClass('selected');
}

function search_other(category_id){
	$.ajax({
            type: "POST", 
            url: ajax_general_path,
            dataType: "json",
            data: { 'action':'quick_search',
                    'category_id':category_id
                  },
            beforeSend: function()
            {
                //$("#filter_img").html('<img border="0" src="<?php echo general_url(); ?>images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;padding-left:145px;text-align:center;"/>');
            },
            success: function(data)
            {
                $("#items_block").html(data.template);
                $(".page_container").html(data.paginate_args);
                $("#count_result").html(data.count);
            },
            error: function(data)
            {    
                //$("#filter_img").html('');
                $("#items_block").html('');
            }
        });
        
        return true;
}

function main_quick_search(){
//    alert(ajax_general_path + 'main_quick_search');
        $.ajax({
            type: "POST", 
            url: ajax_general_path,
            dataType: "html",
            data: { 'action':'main_quick_search',
                    'keywords':$('#main_keywords').val()
                  },
            beforeSend: function()
            {
                //$("#filter_img").html('<img border="0" src="<?php echo general_url(); ?>images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;padding-left:145px;text-align:center;"/>');
            },
            success: function(data)
            {
                $("#sub_search_menuitems").html(data);
            },
            error: function(data)
            {
                //$("#filter_img").html('');
                $("#sub_search_menuitems").html('');
            }
        });
        
        return true;
    }
