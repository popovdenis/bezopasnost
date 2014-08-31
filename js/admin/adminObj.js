var adminObj = {
    base_url: "http://" + window.location.hostname + "/",
    ajax_admin_path: "http://" + window.location.hostname + "/ajax_handlers/admin_handler/ajax_actions/",

    str_replace: function (haystack, needle, replacement) {
        var temp = haystack.split(needle);
        return temp.join(replacement);
    },

    change_price: function (item_id) {
        $.ajax({
            type: "POST",
            url: this.ajax_admin_path,
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
    },
    /**************** Items  *******************/

    get_new_page: function () {
        window.location = adminObj.base_url + "admin/newitem";
    },

    refreshPage: function () {
        window.location.reload();
        return false;
    },

    get_page: function(page, item_id, page_rus) {
        var pageUrl = adminObj.base_url + 'admin/' + page;
        if (item_id != undefined) {
            pageUrl += '/about/' + item_id;
        }
        window.location = pageUrl;
    },

    add_item: function (page) {
        var itemTitle = $("#new_item_title").val(),
            that = this;

        if (itemTitle == '') {
            alert('Статья не может быть сохранена, так как вы не ввели название для этой статьи.');
            return false;
        }
        if (itemTitle.replace(/\s+/g, '').length) {
            var categories = [];
            $('#chboxes').find('input:checked').each(function () {
                categories.push($(this).val());
            });
            var content = CKEDITOR.instances.hasOwnProperty('new_post_content')
                ? CKEDITOR.instances['new_post_content'].getData()
                : '';
            var charecters = CKEDITOR.instances.hasOwnProperty('new_item_charecters')
                ? CKEDITOR.instances['new_item_charecters'].getData()
                : '';

            $.ajax({
                type: "POST",
                url: this.ajax_admin_path,
                dataType: "json",
                data: {
                    'action': 'add_item',
                    'item_title': itemTitle,
                    'item_preview': $("#new_post_preview").val(),
                    'item_marks': $("#new_item_marks").val(),
                    'item_seo_title': $("#item_seo_title").val(),
                    'item_seo_keywords': $("#item_seo_keywords").val(),
                    'item_seo_description': $("#item_seo_description").val(),
                    'item_type': $("#item_type").val(),
                    'item_date_production': $("#datepicker_" + page).val(),
                    'hour': $("#hour_" + page).val(),
                    'minute': $("#minute_" + page).val(),
                    'item_mode': $("#new_item_mode option:selected").val(),
                    'content': content,
                    'charecters': charecters,
                    'categories': categories
                },
                beforeSend: function () {
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
                    $("#" + page).html('<img border="0" src="' + adminObj.base_url + 'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
                },
                success: function (response) {
                    if (response && response.hasOwnProperty('success') && response.success) {
                        that.get_page(page);
                    } else {
                        window.location.reload();
                    }
                },
                error: function (data) {
                    $("#add_item_img").hide();
                    $("#" + page).html('');
                }
            });
        } else {
            alert('Статья не может быть сохранена, так как вы не ввели название для этой статьи.');
        }
    },

    save_item: function (product_id, action) {
        var itemTitle = $("#item_title").val(),
            that = this,
            item_type_element = $('#item_type');
        var item_type = item_type_element.val() == undefined ? $('.select-list option:selected').data('item-type') : item_type_element.val();

        if (itemTitle == '') {
            alert('Статья не может быть сохранена, так как вы не ввели название для этой статьи.');
            return;
        }
        if (itemTitle.replace(/\s+/g, '').length) {
            var categories = [];
            $('#chboxes').find('input:checked').each(function () {
                categories.push($(this).val());
            });
            var content = CKEDITOR.instances.hasOwnProperty('post_content')
                ? CKEDITOR.instances['post_content'].getData()
                : '';
            var charecters = CKEDITOR.instances.hasOwnProperty('item_charecters')
                ? CKEDITOR.instances['item_charecters'].getData()
                : '';

            var params = {
                'action': 'save_item',
                'item_title': itemTitle,
                'item_preview': $("#item_preview").val(),
                'item_marks': $("#item_marks").val(),
                'item_seo_title': $("#item_seo_title").val(),
                'item_seo_keywords': $("#item_seo_keywords").val(),
                'item_seo_description': $("#item_seo_description").val(),
                'item_type': item_type,
                'item_date_production': $("#datepicker_" + product_id).val(),
                'hour': $("#hour_" + product_id).val(),
                'minute': $("#minute_" + product_id).val(),
                'item_mode': $("#item_mode_" + product_id + " option:selected").val(),
                'charecters': charecters,
                'content': content,
                'categories': categories,
                'item_id': product_id
            };

            $.ajax({
                type: "POST",
                url: action,
                dataType: "json",
                data: params,
                success: function (response) {
                    if (response && response.hasOwnProperty('success') && response.success) {
                        that.get_page(item_type, response.item_id);
                    } else {
                        window.location.reload();
                    }
                },
                error: function (data) {
                }
            });
            return true;
        } else {
            alert('Статья не может быть сохранена, так как вы не ввели название для этой статьи.');
        }
    },

    delete_items_checked: function () {
        var checkboxes = [];
        $('.item_chb_delete:checked').each(function () {
            checkboxes.push($(this).val());
        });
        if (checkboxes.length == 0) {
            alert('Вы должны выбрать хотя бы одну статью!');
            return false;
        }
        $.ajax({
            type: "POST",
            url: this.ajax_admin_path,
            dataType: "json",
            data: {
                action: "delete_items_checked",
                chb: checkboxes
            },
            success: function (response) {
                if (! response) {
                    alert('Ошибка!');
                } else {
                    for (var i = 0; i < checkboxes.length; i++) {
                        $("#item_block_" + checkboxes[i]).remove();
                    }
                }
            },
            error: function (data) {
                alert('Ошибка!');
            }
        });
    },

    delete_item: function (item_id, redirect) {
        if (item_id == undefined || item_id == '') return false;
        var that = this,
            item_type = $('#item_type' ).val();

        $.ajax({
            type: "POST",
            url: this.ajax_admin_path,
            dataType: "html",
            data: {
                'action': 'delete_item',
                'item_id': item_id
            },
            beforeSend: function () {
                $("#delete_btn_" + item_id).html('<img border="0" src="' + adminObj.base_url + 'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
            },
            success: function (data) {
                if (data == 5) window.location = adminObj.base_url + "admin/home"; else {
                    $("#item_block_" + item_id).remove();
                    if (redirect == true) that.get_page(item_type);
                }
            },
            error: function (data) {
            }
        });
    },

    paginate_items: function (page_num) {
        var item_type = $("#item_type").val();
        $.ajax({
            type: "POST",
            url: this.ajax_admin_path,
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
    },

    filter_items_category: function (item_type) {
        $.ajax({
            type: "POST", url: this.ajax_admin_path, dataType: "html",
            data: { 'action': 'filter_items_category',
                'item_type': item_type,
                'item_category': $("#item_categories option:selected").val()
            },
            beforeSend: function () {
                $('#filter_items_loading').html('<img id="loading" border="0" src="' + adminObj.base_url + 'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
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
    },

    add_category: function () {
        var cat_desc = '';
        var item_id = '';
        if ($("#new_cat_desc").val() != undefined) cat_desc = $("#new_cat_desc").val();
        if ($("#item_id").val() != undefined) item_id = $("#item_id").val();
        $.ajax({
            type: "POST",
            url: this.ajax_admin_path,
            dataType: "html",
            data: {
                'action': 'add_category',
                'item_id': item_id,
                'category_title': $("#category_title").val(),
                'category_desc': cat_desc,
                'category_parent': $("#categories_new option:selected").val()
            },
            success: function (data) {
                if (data == 5) window.location = adminObj.base_url + "admin/home"; else
                    $("#chboxes").html(data);
            },
            error: function (data) {
                $("#chboxes").html('');
            }
        });
    },

    new_category: function () {
        $.ajax({
            type: "POST",
            url: this.ajax_admin_path,
            dataType: "json",
            data: {
                'action': 'add_category',
                'category_title': $("#new_category_title").val(),
                'category_desc': $("#new_cat_desc").val(),
                'category_parent': $("#categories_new option:selected").val()
            },
            beforeSend: function () {
                $("#new_category_block").hide('slow');
                $("#set_cat_found").html('<img border="0" src="' + adminObj.base_url + 'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
            },
            success: function (data) {
                if (data == 5) window.location = adminObj.base_url + "admin/home"; else {
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
    },

    search_category: function () {
        $.ajax({
            type: "POST",
            url: this.ajax_admin_path,
            dataType: "html",
            data: {
                'action': 'search_category',
                'category_id': $("#search_category_list option:selected").val()
            },
            beforeSend: function () {
                $("#set_cat_found").html('<img border="0" src="' + adminObj.base_url + 'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
            },
            success: function (data) {
                if (data == 5) window.location = adminObj.base_url + "admin/home";
                if (data == 0) {
                    $("#set_cat_found").html('<font class="cat_not_found">Категория не найдена</font>');
                } else {
                    $("#set_cat_found").html(data);

                    var itemsSortable = $("#sortable");
                    $(function() {
                        itemsSortable.sortable({
                            placeholder: 'ui-state-highlight'
                        });
                        itemsSortable.disableSelection();
                    });
                }
            },
            error: function (data) {
                $("#set_cat_found").html('Категория не найдена');
            }
        });
    },

    update_category: function (cat_id) {
        if (cat_id == '' || cat_id == undefined) return false;
        $.ajax({
            type: "POST",
            url: this.ajax_admin_path,
            dataType: "json",
            data: {
                'action': 'update_category',
                'category_id': cat_id,
                'category_title': $("#found_category_title").val(),
                'category_desc': $("#found_cat_desc").val(),
                'category_parent': $("#found_categories_parent option:selected").val(),
                'item_seo_title': $("#item_seo_title").val(),
                'item_seo_keywords': $("#item_seo_keywords").val(),
                'item_seo_description': $("#item_seo_description").val()
            },
            beforeSend: function () {
                $("#set_cat_found").html('<img border="0" src="' + adminObj.base_url + 'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
            },
            success: function (data) {
                if (data == 5) window.location = adminObj.base_url + "admin/home";
                $("#category_header").html(data.header);
                $("#set_cat_found").html(data.content);
            },
            error: function (data) {
                $("#set_cat_found").html('');
            }
        });
    },

    delete_category: function (cat_id) {
        if (cat_id == '' || cat_id == undefined) return false;
        $.ajax({
            type: "POST",
            url: this.ajax_admin_path,
            dataType: "html",
            data: {
                'action': 'delete_category',
                'category_id': cat_id
            },
            beforeSend: function () {
                $("#set_cat_found").html('<img border="0" src="' + adminObj.base_url + 'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
            },
            success: function (data) {
                if (data == 5) window.location = adminObj.base_url + "admin/home";
                $('#search_category_list option:selected').remove();
                $("#set_cat_found").html('');
                alert('Категория успешно удалена.');
            },
            error: function (data) {
                $("#set_cat_found").html('');
            }
        });
    },

    add_category_partner: function (category_id) {
        if (category_id == '' || category_id == undefined) return false;
        var partner_id = $("#category_partner_list option:selected").val();
        if (partner_id == 0 || partner_id == undefined || partner_id == '') return false;
        $.ajax({
            type: "POST",
            url: this.ajax_admin_path,
            dataType: "html",
            data: {
                'action': 'add_category_partner',
                'category_id': category_id,
                'partner_id': partner_id
            },
            beforeSend: function () {
                $("#category_partners_img").html('<img border="0" src="' + adminObj.base_url + 'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
            },
            success: function (data) {
                if (data == 5) window.location = adminObj.base_url + "admin/home";
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
    },

    delete_category_partner: function (category_id, partner_id) {
        if (category_id == '' || category_id == undefined) return false;
        if (partner_id == '' || partner_id == undefined) return false;
        $.ajax({
            type: "POST",
            url: this.ajax_admin_path,
            dataType: "html",
            data: {
                'action': 'delete_category_partner',
                'category_id': category_id,
                'partner_id': partner_id
            },
            beforeSend: function () {
                $("#category_partners_img").html('<img border="0" src="' + adminObj.base_url + 'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
            },
            success: function (data) {
                if (data == 5) window.location = adminObj.base_url + "admin/home";
                if (data == 1) {
                    $("#category_partners_img").html('');
                    $("#partner_" + partner_id).remove();
                }
            },
            error: function (data) {
                $("#category_partners_img").html('');
            }
        });
    },

    reorder_categories: function (category_parent_id) {
        var cat_order = [];
        $('#sortable li').each(function () {
            cat_order.push($(this).attr('id'));
        });
        $.ajax({
            type: "POST",
            url: this.ajax_admin_path,
            dataType: "html",
            data: {
                'action': 'reorder_categories',
                'category_id': category_parent_id,
                'cat_order': adminObj.serialize(cat_order)
            },
            beforeSend: function () {
                $("#category_subcats_img").show();
            },
            success: function (data) {
                if (data == 5) window.location = adminObj.base_url + "admin/home";
                if (data == 1) {
                    $("#category_subcats_img").hide();
                }
            },
            complete: function () {
                $("#category_subcats_img").hide();
            }
        });
    },

    add_contact: function () {
        $.ajax({
            type: "POST",
            url: this.ajax_admin_path,
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
                if (data == 5) window.location = adminObj.base_url + "admin/home"; else
                    $('#contacts_section').html(data);
            },
            error: function (data) {
                $("#contacts_img").hide();
            }
        });
    },

    update_contacts: function () {
        var elements = [];
        $('div.contact_block').each(function () {
            var id = $(this).data('index');
            elements.push({
                item_type: $("#contact_type_" + id + " option:selected").val(),
                item_value: $("#contact_value_" + id).val()
            });
        });
        var contact_obj = {'elements': elements};
        adminObj.serialize(contact_obj);
        $.ajax({
            type: "POST",
            url: this.base_url + 'admin/contacts/update',
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
                //			$("#contacts").html('<img border="0" src="'+adminObj.base_url+'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
            },
            success: function (data) {
                if (data == 5) window.location = adminObj.base_url + "admin/home";
                $("#contacts").html(data);
            },
            error: function (data) {
                $("#contacts").html('');
            }
        });
    },

    /**** Пользователи ***/
    get_user: function () {
        $.ajax({
            type: "POST",
            url: this.ajax_admin_path,
            dataType: "html",
            data: {
                'action': 'get_user',
                'user_id': $("#search_user_list option:selected").val()
            },
            beforeSend: function () {
                $("#set_user_found").html('<img border="0" src="' + adminObj.base_url + 'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
            },
            success: function (data) {
                if (data == 5) window.location = adminObj.base_url + "admin/home";
                if (data == 0) $("#set_user_found").html('<font class="cat_not_found">Пользователь не найден</font>'); else
                    $("#set_user_found").html(data);
            },
            error: function (data) {
                $("#set_user_found").html('Пользователь не найден');
            }
        });
    },

    add_user: function () {
        $.ajax({
            type: "POST",
            url: this.ajax_admin_path,
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
                $("#set_user_found").html('<img border="0" src="' + adminObj.base_url + 'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
            },
            success: function (data) {
                if (data == 5) window.location = adminObj.base_url + "admin/home";
                $("#search_user_name").html(data.users_list);
                $("#set_user_found").html(data.user_info);
            },
            error: function (data) {
                $("#set_user_found").html('');
            }
        });
    },

    update_user: function (user_id) {
        if (user_id == '' || user_id == undefined) return false;
        $.ajax({
            type: "POST",
            url: this.ajax_admin_path,
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
                $("#set_user_found").html('<img border="0" src="' + adminObj.base_url + 'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
            },
            success: function (data) {
                if (data == 5) window.location = adminObj.base_url + "admin/home";
                $("#search_user_name").html(data.users_list);
                $("#set_user_found").html(data.user_info);
            },
            error: function (data) {
                $("#set_user_found").html('');
            }
        });
    },

    delete_user: function (user_id) {
        if (user_id == '' || user_id == undefined) return false;
        $.ajax({
            type: "POST",
            url: this.ajax_admin_path,
            dataType: "html",
            data: {
                'action': 'delete_user',
                'user_id': user_id
            },
            beforeSend: function () {
                $("#set_user_found").html('<img border="0" src="' + adminObj.base_url + 'images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
            },
            success: function (data) {
                if (data == 5) window.location = adminObj.base_url + "admin/home";
                $("#search_user_name").html(data);
                $("#set_user_found").html('');
            },
            error: function (data) {
                $("#set_user_found").html('');
            }
        });
    },

    change_password: function (user_id) {
        if (user_id == '' || user_id == undefined) return false;
        $.ajax({
            type: "POST",
            url: this.ajax_admin_path,
            dataType: "html",
            data: {
                'action': 'change_password',
                'user_id': user_id
            },
            beforeSend: function () {
                $("#user_img_loading").show();
            },
            success: function (data) {
                if (data == 5) window.location = adminObj.base_url + "admin/home";
                if (data == 1) {
                    $("#user_password").removeAttr('disabled');
                    $('#paswd_link').html('<a style="font-size:11px;" href="#" onclick="adminObj.save_password(\'' + user_id + '\'); return false;">Сохранить пароль</a>');
                }
                $("#user_img_loading").hide();
            },
            error: function (data) {
                $("#user_img_loading").hide();
            }
        });
    },

    save_password: function (user_id) {
        if (user_id == '' || user_id == undefined) return false;
        $.ajax({
            type: "POST",
            url: this.ajax_admin_path,
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
                if (data == 5) window.location = adminObj.base_url + "admin/home";
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
    },

    get_currency: function () {
        $.ajax({
            type: "POST",
            url: this.ajax_admin_path,
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
    },

    add_currency: function () {
        $.ajax({
            type: "POST",
            url: this.ajax_admin_path,
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
    },

    update_currency_rate: function (currency_id) {
        var currencies = [];
        $('input.currency').each(function () {
            currencies.push({
                'name': $(this).data('currency-name'),
                'value': $(this).val()
            });
        });
        $.ajax({
            type: "POST",
            url: this.ajax_admin_path,
            dataType: "json",
            data: {
                'action': 'update_currency_rate',
                'currency_id': currency_id,
                'currency_names': currencies
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
    },

    get_gallery: function () {
        $.ajax({
            type: "POST",
            url: this.ajax_admin_path,
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
    },

    add_gallery: function () {
        $.ajax({
            type: "POST",
            url: this.ajax_admin_path,
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
    },

    update_gallery: function (gallery_id) {
        $.ajax({
            type: "POST",
            url: this.ajax_admin_path,
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
    },

    delete_gallery: function (gallery_id) {
        $.ajax({
            type: "POST",
            url: this.ajax_admin_path,
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
    },

    reorder_attach_gallery: function (gallery_id) {
        var num = $('#sortable_gallery li').length;
        var attach_order = new Array(num);
        var i = 0;
        $('#sortable_gallery li').each(function () {
            attach_order[i] = $(this).attr('id');
            i ++;
        });
        $.ajax({
            type: "POST",
            url: this.ajax_admin_path,
            dataType: "html",
            data: {
                'action': 'reorder_attach_gallery',
                'gallery_id': gallery_id,
                'attach_order': adminObj.serialize(attach_order)
            },
            beforeSend: function () {
                $("#loader_gallery").show();
            },
            success: function (data) {
                if (data == 5) window.location = adminObj.base_url + "admin/home";
                if (data == 1) {
                    $("#loader_gallery").hide();
                }
            },
            error: function (data) {
                $("#loader_gallery").html('');
            }
        });
    },

    assign_gallery_to_item: function (item_id) {
        $.ajax({
            type: "POST",
            url: this.ajax_admin_path,
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
    },

    delete_attach_gallery: function (gallery_id, item_id) {
        $.ajax({
            type: "POST",
            url: this.ajax_admin_path,
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
    },

    add_ann_item: function () {
        var item_id = $("#search_items_list option:selected").val();
        var item_title = $("#search_items_list option:selected").text();
        $.ajax({
            type: "POST",
            url: this.ajax_admin_path,
            dataType: "html",
            data: {
                'action': 'add_ann_item',
                'item_id': item_id
            },
            beforeSend: function () {
            },
            success: function (data) {
                $('#search_items_list option:selected').remove();
                $('#items_sortable').append('<li id="item_' + item_id + '" class="ui-state-default" style="margin-bottom:3px;">' + '<div style="float: left; width: 280px;">' + '<div style="float: left; margin: 3px 0 0 5px; width: 250px;">' + item_title + '</div>' + '<div style="float: right;">' + '<img title="удалить" src="' + adminObj.base_url + 'images/icons/cancel.png" onclick="delete_ann_item(\'' + item_id + '\');" style="bottom: 3px; cursor: pointer; width: 17px; height: 17px; position: relative; "/>' + '</div>' + '</div>' + '</li>');
            },
            error: function (data) {
            }
        });
    },

    delete_ann_item: function (item_id) {
        $.ajax({
            type: "POST",
            url: this.ajax_admin_path,
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
    },

    delete_item_gallery: function (gallery_id, item_id) {
        $.ajax({
            type: "POST",
            url: this.ajax_admin_path,
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
    },

    add_form: function ( element_id ) {
        var element_display = document.getElementById( "new_" + element_id + "_block" ).style.display;

        if ( element_display == 'none' ) {
            $( "#new_" + element_id + "_block" ).show();
        } else {
            $( "#new_" + element_id + "_block" ).hide();
        }
    },

    serialize: function ( mixed_value ) {
        var _getType = function ( inp ) {
            var type = typeof inp, match;
            var key;
            if ( type == 'object' && !inp ) {
                return 'null';
            }
            if ( type == "object" ) {
                if ( !inp.constructor ) {
                    return 'object';
                }
                var cons = inp.constructor.toString();
                match = cons.match( /(\w+)\(/ );
                if ( match ) {
                    cons = match[1].toLowerCase();
                }
                var types = ["boolean", "number", "string", "array"];
                for ( key in types ) {
                    if ( cons == types[key] ) {
                        type = types[key];
                        break;
                    }
                }
            }
            return type;
        };
        var type = _getType( mixed_value );
        var val, ktype = '';

        switch( type ) {
            case "function":
                val = "";
                break;
            case "boolean":
                val = "b:" + (mixed_value ? "1" : "0");
                break;
            case "number":
                val = (Math.round( mixed_value ) == mixed_value ? "i" : "d") + ":" + mixed_value;
                break;
            case "string":
                mixed_value = adminObj.utf8_encode( mixed_value );
                val = "s:" + encodeURIComponent( mixed_value ).replace( /%../g, 'x' ).length + ":\"" + mixed_value + "\"";
                break;
            case "array":
            case "object":
                val = "a";
                var count = 0,
                    vals = "",
                    okey,
                    key;

                for ( key in mixed_value ) {
                    ktype = _getType( mixed_value[key] );
                    if ( ktype == "function" ) {
                        continue;
                    }
                    okey = (key.match( /^[0-9]+$/ ) ? parseInt( key, 10 ) : key);
                    vals += adminObj.serialize( okey ) +
                        adminObj.serialize( mixed_value[key] );
                    count++;
                }
                val += ":" + count + ":{" + vals + "}";
                break;
            case "undefined": // Fall-through
            default:
                break;
        }
        if ( type != "object" && type != "array" ) {
            val += ";";
        }
        return val;
    },

    utf8_encode: function ( argString ) {
        var string = (argString + '');

        var utftext = "";
        var start, end;
        var stringl = 0;
        start = end = 0;
        stringl = string.length;
        for ( var n = 0; n < stringl; n++ ) {
            var c1 = string.charCodeAt( n );
            var enc = null;

            if ( c1 < 128 ) {
                end++;
            } else if ( c1 > 127 && c1 < 2048 ) {
                enc = String.fromCharCode( (c1 >> 6) | 192 ) + String.fromCharCode( (c1 & 63) | 128 );
            } else {
                enc = String.fromCharCode( (c1 >> 12) | 224 ) + String.fromCharCode( ((c1 >> 6) & 63) | 128 ) + String.fromCharCode( (c1 & 63) | 128 );
            }
            if ( enc !== null ) {
                if ( end > start ) {
                    utftext += string.substring( start, end );
                }
                utftext += enc;
                start = end = n + 1;
            }
        }

        if ( end > start ) {
            utftext += string.substring( start, string.length );
        }

        return utftext;
    },

    change_price_value: function ( item_id, flag ) {
        if ( flag != undefined && flag != "" ) {
            if ( flag == 'change' ) {
                var priceType = $( '#price_select_change_' + item_id ).val();
                var uahVal = $( '#cr_val_' + item_id ).val();

                var usd = parseFloat( $( '#cr_usd' ).text() );
                var eur = parseFloat( $( '#cr_eur' ).text() );

                if ( priceType != undefined && priceType != '' ) {
                    var price_uah;
                    var price_usd;
                    var price_eur;

                    switch( priceType ) {
                        case "uah":
                            price_uah = adminObj.js_round( uahVal );
                            price_usd = adminObj.js_round( uahVal / usd );
                            price_eur = adminObj.js_round( uahVal / eur );
                            break;

                        case "usd":
                            price_uah = adminObj.js_round( uahVal * usd );
                            price_usd = adminObj.js_round( uahVal );
                            price_eur = adminObj.js_round( ( uahVal * usd ) / eur );
                            break;

                        case "eur":
                            price_uah = adminObj.js_round( uahVal * eur );
                            price_usd = adminObj.js_round( ( uahVal * eur ) / usd );
                            price_eur = adminObj.js_round( uahVal );
                            break;
                    }
                }

                $( '#cr_uah_' + item_id ).text( price_uah );
                $( '#cr_usd_' + item_id ).text( price_usd );
                $( '#cr_eur_' + item_id ).text( price_eur );

            } else if ( flag == 'display' ) {
                var price_name = $( "#price_select_" + item_id + " option:selected" ).val();
                var price = adminObj.js_round( parseInt( $( '#item_price_' + item_id ).val() ) * parseFloat( $( '#cr_' + price_name ).text() ) );
                $( '#price_item_' + item_id ).text( price );

            } else if ( flag == 'hs_set' ) {
                $( '#cr_val_' + item_id ).val( $( '#item_price_' + item_id ).val() );
                adminObj.change_price_value( item_id, 'change' );
            }
        }
    },

    js_round: function ( number ) {
        var number_arr = number.toString().split( "." );
        if ( number_arr[1] != undefined ) number = number_arr[0] + "." + number_arr[1].substr( 0, 3 );
        else number = number_arr[0];
        return number;
    },

    initGetCategoriesList: function () {
        var that = this,
            selectList = $('.select-list');
        selectList.off('change').on('change', function () {
            var $this = $(this);
            $('#chboxes').html(
                '<div style="position:relative;right:12px;">' +
                    '<input type="checkbox" value="'+ $(':selected', $this ).val() +'">' +
                    $(':selected', $this).text() +
                '</div>'
            );
            $.ajax({
                type: "POST",
                dataType: "json",
                url: adminObj.base_url + "admin/newitem/getCategories",
                data: {
                    'category_id': $this.val()
                },
                success: function (response) {
                    if (response.success != undefined && response.success) {
                        if (response.categories != undefined && response.categories.length > 0) {
                            var html =
                                '<div style="position:relative;right:12px;">' +
                                    '<input type="checkbox" value="'+ $(':selected', $this ).val() +'">' +
                                    $(':selected', $this).text() +
                                '</div>';
                            for (var i in response.categories) {
                                var shiftVal = response.categories[i].level * 10;
                                $('#chboxes' ).append(
                                    '<div style="margin-left:' + shiftVal + 'px;">' +
                                        '<input type="checkbox" value="' + response.categories[i].category_id + '">' +
                                            response.categories[i].category_title +
                                    '</div>'
                                );
                            }
                        }
                    }
                }
            });

        });
    },

    clickersCategories: null,
    clickersItems: null,
    clickersApplyBtn: null,
    chessElements: null,
    selectedCoordinate: null,
    flushMessageElement: null,

    initChessHeader: function () {
        this.initChessElements();
        this.clearItemsByCoordinatesBlock();
        this.getItemsByCategory();
    },

    initChessElements: function () {
        var that = this;
        that.clickersCategories = $('select.settings_clickers_categories');
        that.clickersItems      = $('select.settings_clickers_items');
        that.clickersApplyBtn   = $('.clickers_apply_btn');
        that.chessElements      = $('.chess_header span');
        that.flushMessageElement = $('#flashMessage');
        // init items select element
        that.clickersItems.change(function () {
            that.clickersApplyBtn.attr('disabled', false);
        });
        // init Apply button
        that.clickersApplyBtn.click(function () {
            if (that.selectedCoordinate === null) {
                alert('Вам необходимо выбрать ячейку.');
            } else {
                that.setItemByCoordinates();
            }
        });
        // init chess element
        that.chessElements.click(function () {
            that.chessElements.removeClass('selected');
            that.selectedCoordinate = $(this);
            that.getItemByCoordinates();
        });
    },

    clearItemsByCoordinatesBlock: function () {
        this.clickersCategories.prop('selectedIndex', 0);
        this.clickersItems.prop('selectedIndex', 0);
        this.clickersApplyBtn.attr('disabled', true);
    },

    getItemsByCategory: function () {
        var that = this;
        that.clickersCategories.change(function () {
            $.ajax({
                type: "POST",
                url: that.base_url + 'admin/settings/getItemByCategory',
                dataType: "json",
                data: {
                    'categoryId': $(this).val()
                },
                beforeSend: function () {
                    that.clickersItems
                        .empty()
                        .append('<option value="0">Выберите имя категории</option>')
                        .attr('disabled', true);
                    that.clickersApplyBtn.attr('disabled', true);
                },
                success: function (response) {
                    var options = '';
                    if (response != undefined && response.items != undefined && response.items.length > 0) {
                        var items = response.items;
                        for (var i = 0; i < items.length; i++) {
                            options += '<option value="' + items[i]['item_id'] + '">' + items[i]['item_title'] + '</option>'
                        }
                        that.clickersItems
                            .append(options)
                            .attr('disabled', false);
                    }
                },
                error: function (data) {}
            });
        });
    },

    getItemByCoordinates: function () {
        var that = this;
        if (that.selectedCoordinate === null) {
            return false;
        }

        that.selectedCoordinate.addClass('selected');
        $.ajax({
            type: "POST",
            url: that.base_url + 'admin/settings/getItemByCoordinates',
            dataType: "json",
            data: {
                'vOrder': that.selectedCoordinate.data('vorder'),
                'hOrder': that.selectedCoordinate.data('horder')
            },
            success: function (responses) {
                if (responses != undefined && responses.item != undefined) {
                    if ($(responses.item).size() == 0) {
                        that.clearItemsByCoordinatesBlock();
                    } else {
                        $("option[value='" + responses.item['category_id'] + "']", that.clickersCategories).prop('selected', true);
                        that.clickersCategories.change();
                        that.clickersItems.attr('disabled', true);
                        that.clickersApplyBtn.attr('disabled', true);

                        setTimeout(function(){
                            that.clickersItems
                                .attr('disabled', false)
                                .prop('selected', false)
                                .find("option[value='" + responses.item['item_id'] + "']").prop('selected', true);
                        }, 1000);

                    }
                }
            },
            error: function (data) {}
        });
        return false;
    },

    setItemByCoordinates: function () {
        var that = this;
        that.selectedItem = $('.chess_header').find('span.selected');

        $.ajax({
            type: "POST",
            url: that.base_url + 'admin/settings/setItemByCoordinate',
            dataType: "json",
            data: {
                'vOrder': that.selectedItem.data('vorder'),
                'hOrder': that.selectedItem.data('horder'),
                'itemId': that.clickersItems.val(),
                'categoryId': that.clickersCategories.val()
            },
            beforeSend: function () {
            },
            success: function (response) {
                that.flushMessageElement
                    .html('Статья успешно закреплена за ячейкой')
                    .addClass('success')
                    .fadeIn(1000);
                setTimeout(function(){that.flushMessageElement.fadeOut()}, 2000);
            },
            error: function (data) {}
        });
    }
};
