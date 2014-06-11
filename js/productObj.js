/**
 * User: Denis
 * Date: 10.03.14
 * Time: 13:30
 */
var productObj = {

    paginate: function (page) {
        $.ajax({
            type: "POST",
            url: '/ajax_handlers/products_handler/ajax_actions',
            dataType: "json",
            data: { 'action': 'paginate_items',
                'page': page,
                'category_id': $('#category_id').val()
            },
            beforeSend: function () {
                $(".page_container").html('<div style="float:left;"><img border="0" src="<?php echo base_url(); ?>images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;padding-left:145px;text-align:center;"/></div>');
            },
            success: function (data) {
                $("#products_block").html(data.item_main);
                $(".page_container").html(data.page_container);
            },
            error: function (data) {
                $("#products_block").html('');
                $(".page_container").html('');
            }
        });
        return true;
    },

    paginate_items: function(page_num) {
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
    },

    quick_search: function() {
        $.ajax({
            type: "POST",
            url: '/ajax_handlers/products_handler/ajax_actions',
            dataType: "json",
            data: { 'action': 'quick_search',
                'keywords': $('#quick_search_field').val(),
                'category_id': $('#category_id').val()
            },
            beforeSend: function () {
                $("#filter_img").show();
            },
            success: function (data) {
                $("#filter_img").hide();
                $("#products_block").html(data.items_block);
                $(".page_container").html(data.page_container);
            },
            error: function (data) {
                $("#filter_img").html('');
                $("#products_block").html('');
                $(".page_container").html('');
            }
        });
        return true;
    },

    open_compare: function(current_catid, item_id) {
        $.ajax({
            type: "POST",
            url: '/ajax_handlers/products_handler/ajax_actions',
            dataType: "html",
            data: {
                'action': 'compare',
                'item_id': item_id
            },
            success: function (data) {
                var url = '<?=base_url()?>comparison';
                var win2 = window.open(url, '_compare');
            }
        });
    }
};
