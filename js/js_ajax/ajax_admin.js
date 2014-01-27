var base_url = base_help_url;
var ajax_admin_path = base_url + "ajax_handlers/admin_handler/ajax_actions/";
var image_loading = '<img id="loading" border="0" src="' + base_url + 'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>';
function htmlspecialchars(content) {
    content = str_replace(content, '&', '');
    content = str_replace(content, '+', '002B');
    return content;
}
function str_replace(haystack, needle, replacement) {
    var temp = haystack.split(needle);
    return temp.join(replacement);
}
function change_price(item_id) {
    $.ajax({
        type: "POST",
        url: ajax_admin_path,
        dataType: "html",
        data: {
            'action': 'change_price',
            'price_uah': $('#cr_uah_' + item_id).text(),
            'item_id': item_id
        },
        beforeSend: function () {
            $('#loader_' + item_id).show();
        },
        success: function (data) {
            $('#loader_' + item_id).hide();
			var uahItem = $('#cr_uah_' + item_id);
            $('#price_item_' + item_id).text(uahItem.text());
            $('#item_price_' + item_id).val(uahItem.text());
            return hs.close($('#hs_' + item_id).attr('id'));
        },
        error: function (data) {
            $('#loader_' + item_id).hide();
            $('#price_item_' + item_id).val('');
        }
    });
}
/**************** Items  *******************/

function get_new_page(page) {
    $.ajax({
        type: "POST",
        url: ajax_admin_path,
        dataType: "html",
        data: {
            'action': 'get_new_page',
            'page': page,
            'flag': 'new'
        },
        beforeSend: function () {
            __FCKeditorNS = null;
            $('iframe').remove();
            $('div[class^="uploadfile"]').remove();
            $("li").removeClass("active");
            $("#li_" + page).addClass("active");
            $("#about").html('');
            $("#information").html('');
            $("#partners").html('');
            $("#products").html('');
            $("#sertificates").html('');
            $("#contacts").html('');
            $("#gallery").html('');
            $("#settings").html('');
            //$('div[class^="highslide-container"]').remove();
            $("#" + page).html('<img border="0" src="' + base_url + 'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
        },
        success: function (data) {
            if (data == 5) window.location = base_url + "admin/home"; else
                $("#" + page).html(data);
        },
        error: function (data) {
            $("#add_item_img").hide();
            $("#" + page).html('');
        }
    });
}
function get_page(page, item_id, page_rus) {
    $.ajax({
        type: "POST",
        url: ajax_admin_path,
        dataType: "html",
        data: {
            'action': 'get_page',
            'page': page,
            'page_rus': page_rus,
            'item_id': item_id,
            'flag': 'exist'
        },
        beforeSend: function () {
            __FCKeditorNS = null;
            $('iframe').remove();
            $('div[class^="uploadfile"]').remove();
            $("li").removeClass("active");
            $("#li_" + page).addClass("active");
            $("#about").html('');
            $("#information").html('');
            $("#partners").html('');
            $("#products").html('');
            $("#sertificates").html('');
            $("#contacts").html('');
            $("#gallery").html('');
            $("#settings").html('');
            //$('div[class^="highslide-container"]').remove();
            $("#" + page).html('<img border="0" src="' + base_url + 'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
        },
        success: function (data) {
            if (data == 5) {
                window.location = base_url + "admin/home";
            } else {
                $("#" + page).html(data);
            }

            productsObj.initCKeditors();
            productsObj.initDatePicker();

            switch (page) {
                case 'about':
                case 'information':
                case 'partners':
                    productsObj.initImageTitleUploader(item_id);
                break;
                case 'products':
                    productsObj.init(item_id);
                break;
                default:
                break;
            }
        },
        error: function (data) {
            $("#" + page).html('');
        }
    });
    return true;
}

function add_item(page) {
    if ($("#new_item_title").val() == '') {
        alert('Статья не может быть сохранена, так как вы не ввели название для этой статьи.');
        return false;
    }
    if ($("#new_item_title").val().replace(/\s+/g, '').length) {
        // categories
        var product = {
            chb: []
        };
        $('input[id^="ch_"]:checked').each(function () {
            var ch_id = $(this).attr('id');
            product.chb.push({
                id: ch_id,
                is_checked: $(this).attr('checked') ? 1 : 0,
                val: $(this).val()
            });
        });
        var content = htmlspecialchars($('#new_post_content').val());
        var charecters = "";
        if (document.getElementById('new_item_charecters') != null) {
            charecters = htmlspecialchars($('#new_item_charecters').val());
        }
        $.ajax({
            type: "POST",
            url: ajax_admin_path,
            dataType: "html",
            data: {
                'action': 'add_item',
                'item_title': $("#new_item_title").val(),
                'post_preview': $("#new_post_preview").val(),
                'item_marks': $("#new_item_marks").val(),
                'item_seo_title': $("#item_seo_title").val(),
                'item_seo_keywords': $("#item_seo_keywords").val(),
                'item_seo_description': $("#item_seo_description").val(),
                'item_type': $("#item_type").val(),
                'item_date_production': $("#datepicker_" + page).val(),
                'hour': $("#hour_" + page).val(),
                'minute': $("#minute_" + page).val(),
                'item_mode': $("#new_item_mode option:selected").val(),
                'content': serialize(content),
                'charecters': serialize(charecters),
                'item': serialize(product)
            },
            beforeSend: function () {
                __FCKeditorNS = null;
                $('iframe').remove();
                $('div[class^="uploadfile"]').remove();
                $("li").removeClass("active");
                $("#li_" + page).addClass("active");
                $("#about").html('');
                $("#information").html('');
                $("#partners").html('');
                $("#products").html('');
                $("#sertificates").html('');
                $("#contacts").html('');
                $("#gallery").html('');
                $("#settings").html('');
                $('div[class^="highslide-container"]').remove();
                $("#" + page).html('<img border="0" src="' + base_url + 'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
            },
            success: function (data) {
                if (data == 5) window.location = base_url + "admin/home"; else {
                    get_page(page);
                }
            },
            error: function (data) {
                $("#add_item_img").hide();
                $("#" + page).html('');
            }
        });
        return true;
    } else {
        alert('Статья не может быть сохранена, так как вы не ввели название для этой статьи.');
    }
}
function save_item(product_id, item_type) {
    if ($("#item_title").val() == '') {
        alert('Статья не может быть сохранена, так как вы не ввели название для этой статьи.');
        return;
    }
    if ($("#item_title").val().replace(/\s+/g, '').length) {
        // categories
        var item = {
            chb: []
        };
        if (product_id != undefined)
            item.product_id = product_id;
        if (item_type != undefined)
            item.item_type = item_type;

        $('input[id^="ch_"]:checked').each(function () {
            var ch_id = $(this).attr('id');
            item.chb.push({
                id: ch_id,
                is_checked: 1,
                val: $(this).val()
            });
        });
        var content = htmlspecialchars($('#post_content').val());
        var charecters = "";
        if ($('#item_charecters').val() != undefined)
            charecters = htmlspecialchars($('#item_charecters').val());
        $.ajax({
            type: "POST",
            url: ajax_admin_path,
            dataType: "html",
            data: {
                'action': 'save_item',
                'item_title': $("#item_title").val(),
                'item_preview': $("#item_preview").val(),
                'item_marks': $("#item_marks").val(),
                'item_seo_title': $("#item_seo_title").val(),
                'item_seo_keywords': $("#item_seo_keywords").val(),
                'item_seo_description': $("#item_seo_description").val(),
                'item_date_production': $("#datepicker_" + product_id).val(),
                'hour': $("#hour_" + product_id).val(),
                'minute': $("#minute_" + product_id).val(),
                'item_mode': $("#item_mode_" + product_id + " option:selected").val(),
                'charecters': charecters,
                'content': content,
                'item': item
            },
            beforeSend: function () {
                $("#" + item_type).html('<img border="0" src="' + base_url + 'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
            },
            success: function (data) {
                if (data == 5) window.location = base_url + "admin/home"; else
                    $("#" + item_type).html(data);
            },
            error: function (data) {
            }
        });
        return true;
    } else {
        alert('Статья не может быть сохранена, так как вы не ввели название для этой статьи.');
    }
}
function delete_items_checked() {
    var num = $('input[id^="item_chb_delete_"]').length;
    if (num == 0) {
        alert('Вы должны выбрать хотя бы одну статью!');
        return false;
    }
    var item_obj = new Object();
    var checkboxes = new Array(num);
    var j = 0;
    $('input[id^="item_chb_delete_"]').each(function () {
        var ch_id = $(this).attr('id');
        var item = ch_id.split("_");
        var item_id = item[3];
        var itemobj = new Object();
        var is_checked = $(this).attr('checked');
        if (is_checked) {
            itemobj.item_id = item_id;
            checkboxes[j] = itemobj;
            j ++;
        }
    });
    item_obj.chb = checkboxes;
    $.ajax({
        type: "POST",
        url: ajax_admin_path,
        dataType: "html",
        data: 'action=delete_items_checked&jsonData=' + serialize(item_obj),
        success: function (data) {
            if (data == 5) window.location = base_url + "admin/home"; else {
                $('input[id^="item_chb_delete_"]').each(function () {
                    var is_checked = $(this).attr('checked');
                    if (is_checked) {
                        var item = $(this).attr('id').split("_");
                        var item_id = item[3];
                        $("#item_block_" + item_id).remove();
                    }
                });
            }
        },
        error: function (data) {
        }
    });
}
function delete_item(item_id, redirect, item_type) {
    if (item_id == undefined || item_id == '') return false;
    $.ajax({
        type: "POST",
        url: ajax_admin_path,
        dataType: "html",
        data: {
            'action': 'delete_item',
            'item_id': item_id
        },
        beforeSend: function () {
            $("#delete_btn_" + item_id).html('<img border="0" src="' + base_url + 'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
        },
        success: function (data) {
            if (data == 5) window.location = base_url + "admin/home"; else {
                $("#item_block_" + item_id).remove();
                if (redirect == true) get_page(item_type);
            }
        },
        error: function (data) {
            //				$("#chboxes").html('');
        }
    });
}
function paginate_items(page_num) {
    var item_type = $("#item_type").val();
    $.ajax({
        type: "POST",
        url: ajax_admin_path,
        dataType: "html",
        data: { 'action': 'paginate_items',
            'page_num': page_num,
            'item_type': item_type
        },
        beforeSend: function () {
            $("#paginate_img").show();
        },
        success: function (data) {
            $("#paginate_img").hide();
            $("#items_" + item_type).html(data);
        },
        error: function (data) {
            $("#paginate_img").hide();
            $("#items_" + item_type).html('');
        }
    });
    return true;
}
function filter_items_category(item_type) {
    $.ajax({
        type: "POST", url: ajax_admin_path, dataType: "html",
        data: { 'action': 'filter_items_category',
            'item_type': item_type,
            'item_category': $("#item_categories option:selected").val()
        },
        beforeSend: function () {
            $('#filter_items_loading').html(image_loading);
        },
        success: function (data) {
            $('#loading').remove();
            $("#items_" + item_type).html(data);
        },
        error: function (data) {
            $('#loading').remove();
        }
    });
    return true;
}
/********** Настройки *************/
/**** Категории ***/

function add_category() {
    var cat_desc = '';
    var item_id = '';
    if ($("#new_cat_desc").val() != undefined) cat_desc = $("#new_cat_desc").val();
    if ($("#item_id").val() != undefined) item_id = $("#item_id").val();
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
        success: function (data) {
            if (data == 5) window.location = base_url + "admin/home"; else
                $("#chboxes").html(data);
        },
        error: function (data) {
            $("#chboxes").html('');
        }
    });
}
function new_category() {
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
        beforeSend: function () {
            $("#new_category_block").hide('slow');
            $("#set_cat_found").html('<img border="0" src="' + base_url + 'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
        },
        success: function (data) {
            if (data == 5) window.location = base_url + "admin/home"; else {
                $("#category_header").html(data.header);
                $("#set_cat_found").html(data.content);
                $("#search_category_list option:selected").removeAttr('selected');
                $('#search_category_list option:[value="' + data.category_id + '"]').attr('selected', 'selected');
            }
        },
        error: function (data) {
            $("#set_cat_found").html('');
        }
    });
}
function search_category() {
    $.ajax({
        type: "POST",
        url: ajax_admin_path,
        dataType: "html",
        data: {
            'action': 'search_category',
            'category_id': $("#search_category_list option:selected").val()
        },
        beforeSend: function () {
            $("#set_cat_found").html('<img border="0" src="' + base_url + 'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
        },
        success: function (data) {
            if (data == 5) window.location = base_url + "admin/home";
            if (data == 0) $("#set_cat_found").html('<font class="cat_not_found">Категория не найдена</font>'); else
                $("#set_cat_found").html(data);
        },
        error: function (data) {
            $("#set_cat_found").html('Категория не найдена');
        }
    });
}
function update_category(cat_id) {
    if (cat_id == '' || cat_id == undefined) return false;
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
        beforeSend: function () {
            $("#set_cat_found").html('<img border="0" src="' + base_url + 'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
        },
        success: function (data) {
            if (data == 5) window.location = base_url + "admin/home";
            $("#category_header").html(data.header);
            $("#set_cat_found").html(data.content);
        },
        error: function (data) {
            $("#set_cat_found").html('');
        }
    });
}
function delete_category(cat_id) {
    if (cat_id == '' || cat_id == undefined) return false;
    $.ajax({
        type: "POST",
        url: ajax_admin_path,
        dataType: "html",
        data: {
            'action': 'delete_category',
            'category_id': cat_id
        },
        beforeSend: function () {
            $("#set_cat_found").html('<img border="0" src="' + base_url + 'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
        },
        success: function (data) {
            if (data == 5) window.location = base_url + "admin/home";
            $('#search_category_list option:selected').remove();
            $("#set_cat_found").html('');
            alert('Категория успешно удалена.');
        },
        error: function (data) {
            $("#set_cat_found").html('');
        }
    });
}
function add_category_partner(category_id) {
    if (category_id == '' || category_id == undefined) return false;
    var partner_id = $("#category_partner_list option:selected").val();
    if (partner_id == 0 || partner_id == undefined || partner_id == '') return false;
    $.ajax({
        type: "POST",
        url: ajax_admin_path,
        dataType: "html",
        data: {
            'action': 'add_category_partner',
            'category_id': category_id,
            'partner_id': partner_id
        },
        beforeSend: function () {
            $("#category_partners_img").html('<img border="0" src="' + base_url + 'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
        },
        success: function (data) {
            if (data == 5) window.location = base_url + "admin/home";
            var result = '';
            var partner_name = $("#category_partner_list option:selected").text();
            if (data == 1) result = '<div id="partner_' + partner_id + '"><span>' + partner_name + '</span><a href="#" onclick="javascript:delete_category_partner(' + partner_id + ');return false;">Удалить</a></div>'; else result = '';
            $("#category_partners_img").html('');
            $("#category_partners").append(result);
        },
        error: function (data) {
            $("#category_partners_img").html('');
        }
    });
}
function delete_category_partner(category_id, partner_id) {
    if (category_id == '' || category_id == undefined) return false;
    if (partner_id == '' || partner_id == undefined) return false;
    $.ajax({
        type: "POST",
        url: ajax_admin_path,
        dataType: "html",
        data: {
            'action': 'delete_category_partner',
            'category_id': category_id,
            'partner_id': partner_id
        },
        beforeSend: function () {
            $("#category_partners_img").html('<img border="0" src="' + base_url + 'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
        },
        success: function (data) {
            if (data == 5) window.location = base_url + "admin/home";
            if (data == 1) {
                $("#category_partners_img").html('');
                $("#partner_" + partner_id).remove();
            }
        },
        error: function (data) {
            $("#category_partners_img").html('');
        }
    });
}
function reorder_categories(category_parent_id) {
    var num = $('#sortable li').length;
    var cat_order = new Array(num);
    var i = 0;
    $('#sortable li').each(function () {
        cat_order[i] = $(this).attr('id');
        i ++;
    });
    $.ajax({
        type: "POST",
        url: ajax_admin_path,
        dataType: "html",
        data: {
            'action': 'reorder_categories',
            'category_id': category_parent_id,
            'cat_order': serialize(cat_order)
        },
        beforeSend: function () {
            $("#category_subcats_img").show();
        },
        success: function (data) {
            if (data == 5) window.location = base_url + "admin/home";
            if (data == 1) {
                $("#category_subcats_img").hide();
            }
        },
        error: function (data) {
            $("#category_partners_img").html('');
        }
    });
}
/**** Контакты  ****/
function add_contact() {
    $.ajax({
        type: "POST",
        url: ajax_admin_path,
        dataType: "html",
        data: {
            'action': 'add_contact',
            'contact_type': $("#contact_type option:selected").val(),
            'contact_value': $("#contact_value").val()
        },
        beforeSend: function () {
            $("#contacts_img").show();
            $("#contact_value").val('');
        },
        success: function (data) {
            $("#contacts_img").hide();
            $("#new_contacts_block").hide();
            if (data == 5) window.location = base_url + "admin/home"; else
                $('#contacts_section').html(data);
        },
        error: function (data) {
            $("#contacts_img").hide();
        }
    });
}
function update_contacts() {
    var num = $('div[id^="contact_block_"]').length;
    var contact_obj = new Object();
    var contact_elements = new Array(num);
    var j = 0;
    $('div[id^="contact_block_"]').each(function () {
        var ch_id = $(this).attr('id');
        var item = ch_id.split("_");
        var id = item[2];
        var itemobj = new Object();
        itemobj.item_type = $("#contact_type_" + id + " option:selected").val();
        itemobj.item_value = $("#contact_value_" + id).val();
        contact_elements[j] = itemobj;
        j ++;
    });
    contact_obj.elements = contact_elements;
    serialize(contact_obj);
    $.ajax({
        type: "POST",
        url: ajax_admin_path,
        dataType: "html",
        data: {
            'action': 'update_contacts',
            'contact_address_1': $("#contact_address_1").val(),
            'contact_time_1_f_h': $("#contact_time_1_f_h").val(),
            'contact_time_1_f_m': $("#contact_time_1_f_m").val(),
            'contact_time_1_t_h': $("#contact_time_1_t_h").val(),
            'contact_time_1_t_m': $("#contact_time_1_t_m").val(),
            'contact_time_1_tm_f_h': $("#contact_time_1_tm_f_h").val(),
            'contact_time_1_tm_f_m': $("#contact_time_1_tm_f_m").val(),
            'contact_time_1_tm_t_h': $("#contact_time_1_tm_t_h").val(),
            'contact_time_1_tm_t_m': $("#contact_time_1_tm_t_m").val(),
            'contact_address_2': $("#contact_address_2").val(),
            'contact_time_2_f_h': $("#contact_time_2_f_h").val(),
            'contact_time_2_f_m': $("#contact_time_2_f_m").val(),
            'contact_time_2_t_h': $("#contact_time_2_t_h").val(),
            'contact_time_2_t_m': $("#contact_time_2_t_m").val(),
            'contact_time_2_tm_f_h': $("#contact_time_2_tm_f_h").val(),
            'contact_time_2_tm_f_m': $("#contact_time_2_tm_f_m").val(),
            'contact_time_2_tm_t_h': $("#contact_time_2_tm_t_h").val(),
            'contact_time_2_tm_t_m': $("#contact_time_2_tm_t_m").val(),
            'contact_obj': contact_obj
        },
        beforeSend: function () {
            //			$("#contacts").html('<img border="0" src="'+base_url+'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
        },
        success: function (data) {
            if (data == 5) window.location = base_url + "admin/home";
            $("#contacts").html(data);
        },
        error: function (data) {
            $("#contacts").html('');
        }
    });
}
/**** Пользователи ***/
function get_user() {
    $.ajax({
        type: "POST",
        url: ajax_admin_path,
        dataType: "html",
        data: {
            'action': 'get_user',
            'user_id': $("#search_user_list option:selected").val()
        },
        beforeSend: function () {
            $("#set_user_found").html('<img border="0" src="' + base_url + 'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
        },
        success: function (data) {
            if (data == 5) window.location = base_url + "admin/home";
            if (data == 0) $("#set_user_found").html('<font class="cat_not_found">Пользователь не найден</font>'); else
                $("#set_user_found").html(data);
        },
        error: function (data) {
            $("#set_user_found").html('Пользователь не найден');
        }
    });
}
function add_user() {
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
        beforeSend: function () {
            $("#new_user_block").hide('slow');
            $("#set_user_found").html('<img border="0" src="' + base_url + 'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
        },
        success: function (data) {
            if (data == 5) window.location = base_url + "admin/home";
            $("#search_user_name").html(data.users_list);
            $("#set_user_found").html(data.user_info);
        },
        error: function (data) {
            $("#set_user_found").html('');
        }
    });
}
function update_user(user_id) {
    if (user_id == '' || user_id == undefined) return false;
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
        beforeSend: function () {
            $("#set_user_found").html('<img border="0" src="' + base_url + 'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
        },
        success: function (data) {
            if (data == 5) window.location = base_url + "admin/home";
            $("#search_user_name").html(data.users_list);
            $("#set_user_found").html(data.user_info);
        },
        error: function (data) {
            $("#set_user_found").html('');
        }
    });
}
function delete_user(user_id) {
    if (user_id == '' || user_id == undefined) return false;
    $.ajax({
        type: "POST",
        url: ajax_admin_path,
        dataType: "html",
        data: {
            'action': 'delete_user',
            'user_id': user_id
        },
        beforeSend: function () {
            $("#set_user_found").html('<img border="0" src="' + base_url + 'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
        },
        success: function (data) {
            if (data == 5) window.location = base_url + "admin/home";
            $("#search_user_name").html(data);
            $("#set_user_found").html('');
        },
        error: function (data) {
            $("#set_user_found").html('');
        }
    });
}
function change_password(user_id) {
    if (user_id == '' || user_id == undefined) return false;
    $.ajax({
        type: "POST",
        url: ajax_admin_path,
        dataType: "html",
        data: {
            'action': 'change_password',
            'user_id': user_id
        },
        beforeSend: function () {
            $("#user_img_loading").show();
        },
        success: function (data) {
            if (data == 5) window.location = base_url + "admin/home";
            if (data == 1) {
                $("#user_password").removeAttr('disabled');
                $('#paswd_link').html('<a style="font-size:11px;" href="#" onclick="javascript:save_password(\'' + user_id + '\'); return false;">Сохранить пароль</a>');
            }
            $("#user_img_loading").hide();
        },
        error: function (data) {
            $("#user_img_loading").hide();
        }
    });
}
function save_password(user_id) {
    if (user_id == '' || user_id == undefined) return false;
    $.ajax({
        type: "POST",
        url: ajax_admin_path,
        dataType: "html",
        data: {
            'action': 'save_password',
            'user_id': user_id,
            'user_password': $("#user_password").val()
        },
        beforeSend: function () {
            $("#user_img_loading").show();
        },
        success: function (data) {
            if (data == 5) window.location = base_url + "admin/home";
            $("#user_password").val('');
            $("#user_img_loading").hide();
            if (data == 1) {
                $("#user_password").attr('disabled', 'disabled');
                $('#paswd_link').html('<a style="font-size:11px;" href="#" onclick="javascript:change_password(\'' + user_id + '\'); return false;">Изменить пароль</a>');
            }
        },
        error: function (data) {
            $("#user_img_loading").hide();
        }
    });
}
function get_currency() {
    $.ajax({
        type: "POST",
        url: ajax_admin_path,
        dataType: "html",
        data: {'action': 'get_currency', 'currency_id': $("#search_currency_list option:selected").val()},
        beforeSend: function () {
            $("#currency_img").show();
        },
        success: function (data) {
            $("#currency_img").hide();
            $("#set_currency_found").html(data);
        },
        error: function (data) {
            $("#currency_img").hide();
            $("#set_currency_found").html('');
        }
    });
}
function add_currency() {
    $.ajax({
        type: "POST",
        url: ajax_admin_path,
        dataType: "json",
        data: {
            'action': 'add_currency', 'currency_value': $("#currency_value").val()
        },
        beforeSend: function () {
            $("#currency_value").val('');
            $("#new_currency_block").hide();
            $("#currency_img").show();
        },
        success: function (data) {
            $("#currency_img").hide();
            $("#search_currency_list").append('<option value="' + data.currency_id + '">' + data.currency_value + '</option>');
        },
        error: function (data) {
            $("#currency_img").hide();
            $("#set_currency_found").html();
        }
    });
}
function update_currency_rate(currency_id) {
    var num = $('input[id^="currencyname_"]').length;
    var currency_names = new Array(num);
    var i = 0;
    if (num > 0) {
        $('input[id^="currencyname_"]').each(function () {
            var currency = new Object();
            var c_id = $(this).attr('id').split("_");
            currency.name = c_id[1];
            currency.value = $(this).val();
            currency_names[i] = currency;
            i ++;
        });
    }
    $.ajax({
        type: "POST",
        url: ajax_admin_path,
        dataType: "json",
        data: {
            'action': 'update_currency_rate',
            'currency_id': currency_id,
            'currency_names': serialize(currency_names)
        },
        beforeSend: function () {
            $("#currency_img").show();
        },
        success: function (data) {
            $("#currency_img").hide();
        },
        error: function (data) {
            $("#currency_img").hide();
        }
    });
}
function delete_currency() {
}
function get_gallery() {
    $.ajax({
        type: "POST",
        url: ajax_admin_path,
        dataType: "json",
        data: {
            'action': 'get_gallery',
            'gallery_id': $("#galleries option:selected").val()
        },
        beforeSend: function () {
            $("#gallery_img").show();
        },
        success: function (data) {
            $("#gallery_img").hide();
            $("#gallery_info").html(data.gallery_info);
            $("#gallery_images").html(data.gallery_images);
        },
        error: function (data) {
            $("#gallery_img").hide();
        }
    });
}
function add_gallery() {
    $.ajax({
        type: "POST",
        url: ajax_admin_path,
        dataType: "json",
        data: {
            'action': 'add_gallery',
            'gallery_title': $("#new_gal_title").val()
        },
        beforeSend: function () {
            $("#gallery_img").show();
        },
        success: function (data) {
            $("#new_gallery_block").hide();
            $("#galleries").append('<option value="' + data.gallery_id + '">' + $("#new_gal_title").val() + '</option>');
            $('#galleries option:last').attr('selected', 'yes');
            $("#new_gal_title").val('');
            $("#gallery_img").hide();
            $("#gallery_info").html(data.gallery_info);
            $("#gallery_images").html(data.gallery_images);
        },
        error: function (data) {
            $("#gallery_img").hide();
        }
    });
}
function update_gallery(gallery_id) {
    $.ajax({
        type: "POST",
        url: ajax_admin_path,
        dataType: "html",
        data: {
            'action': 'update_gallery',
            'gallery_id': gallery_id,
            'gallery_title': $("#gal_title").val()
        },
        beforeSend: function () {
            $("#loader_gallery").show();
        },
        success: function (data) {
            $("#loader_gallery").hide();
        },
        error: function (data) {
            $("#loader_gallery").hide();
        }
    });
}
function delete_gallery(gallery_id) {
    $.ajax({
        type: "POST",
        url: ajax_admin_path,
        dataType: "html",
        data: {
            'action': 'delete_gallery',
            'gallery_id': gallery_id
        },
        beforeSend: function () {
            $("#gallery_img").show();
        },
        success: function (data) {
            $("#gallery_img").hide();
            $("#gallery_info").html('');
            $("#gallery_images").html('');
            $('#galleries option:selected').remove();
            alert('Галерея успешно удалена');
        },
        error: function (data) {
            $("#gallery_img").hide();
        }
    });
}
function reorder_attach_gallery(gallery_id) {
    var num = $('#sortable_gallery li').length;
    var attach_order = new Array(num);
    var i = 0;
    $('#sortable_gallery li').each(function () {
        attach_order[i] = $(this).attr('id');
        i ++;
    });
    $.ajax({
        type: "POST",
        url: ajax_admin_path,
        dataType: "html",
        data: {
            'action': 'reorder_attach_gallery',
            'gallery_id': gallery_id,
            'attach_order': serialize(attach_order)
        },
        beforeSend: function () {
            $("#loader_gallery").show();
        },
        success: function (data) {
            if (data == 5) window.location = base_url + "admin/home";
            if (data == 1) {
                $("#loader_gallery").hide();
            }
        },
        error: function (data) {
            $("#loader_gallery").html('');
        }
    });
}
function assign_gallery_to_item(item_id) {
    $.ajax({
        type: "POST",
        url: ajax_admin_path,
        dataType: "html",
        data: {
            'action': 'assign_gallery_to_item',
            'gallery_id': $("#galleries option:selected").val(),
            'item_id': item_id
        },
        beforeSend: function () {
        },
        success: function (data) {
            $('#new_gallery_block').hide();
            $('#imggallery_img').html(data);
        },
        error: function (data) {
        }
    });
}
function delete_attach_gallery(gallery_id, item_id) {
    $.ajax({
        type: "POST",
        url: ajax_admin_path,
        dataType: "html",
        data: {
            'action': 'delete_attach_gallery',
            'gallery_id': gallery_id,
            'item_id': item_id
        },
        beforeSend: function () {
            $("#loader_gallery").show();
        },
        success: function (data) {
            $("#loader_gallery").hide();
            $('li#' + item_id).remove();
            if ($('#sortable_gallery').length == 0) $('#gallery_reorder_apply').hide();
        },
        error: function (data) {
            $("#loader_gallery").hide();
        }
    });
}
function add_ann_item() {
    var item_id = $("#search_items_list option:selected").val();
    var item_title = $("#search_items_list option:selected").text();
    $.ajax({
        type: "POST",
        url: ajax_admin_path,
        dataType: "html",
        data: {
            'action': 'add_ann_item',
            'item_id': item_id
        },
        beforeSend: function () {
        },
        success: function (data) {
            $('#search_items_list option:selected').remove();
            $('#items_sortable').append('<li id="item_' + item_id + '" class="ui-state-default" style="margin-bottom:3px;">' + '<div style="float: left; width: 280px;">' + '<div style="float: left; margin: 3px 0 0 5px; width: 250px;">' + item_title + '</div>' + '<div style="float: right;">' + '<img title="удалить" src="' + base_url + 'images/icons/cancel.png" onclick="delete_ann_item(\'' + item_id + '\');" style="bottom: 3px; cursor: pointer; width: 17px; height: 17px; position: relative; "/>' + '</div>' + '</div>' + '</li>');
        },
        error: function (data) {
        }
    });
}
function delete_ann_item(item_id) {
    $.ajax({
        type: "POST",
        url: ajax_admin_path,
        dataType: "html",
        data: {
            'action': 'delete_ann_item',
            'item_id': item_id
        },
        beforeSend: function () {
        },
        success: function (data) {
            $('#item_' + item_id).remove();
        },
        error: function (data) {
        }
    });
}
function delete_item_gallery(gallery_id, item_id) {
    $.ajax({
        type: "POST",
        url: ajax_admin_path,
        dataType: "html",
        data: {
            'action': 'delete_item_gallery',
            'gallery_id': gallery_id,
            'item_id': item_id
        },
        beforeSend: function () {
            $('#loader_gallery').remove();
        },
        success: function (data) {
            $('#sortable_gallery').remove();
        },
        error: function (data) {
            $('#loader_gallery').remove();
        }
    });
}

