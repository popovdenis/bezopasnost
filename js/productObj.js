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
    },

    loadItemsByCoordinates: function () {
        var that = this;
        $.ajax({
            type: "POST",
            url: '/products/getItemsByCoordinates',
            dataType: "json",
            data: {},
            success: function (response) {
                if (response.items != undefined && response.items.length > 0) {
                    for (var i = 0; i < 2; i++) {
                        for (var j = 0; j < 7; j++) {
                            if (response.items[i][j].item_title != undefined) {
                                var item = response.items[i][j];
                                var message =
                                    '<span class="chess_message_preview">' + item.item_title + '</span>' +
                                    '<span class="tooltip-message">' + item.item_title + '</span>';
                                var row = $('#row_' + i + '_' + j);
                                row.html(message);
                                that.bindQtipToElement(row);
                                row.wrap(
                                    "<a href='"+
                                        item.item_type + '/subcat/' + item.category_id + '/about/' + item.item_id +
                                    "'></a>"
                                );
                            }
                        }
                    }
                }
            },
            error: function (data) {}
        });
    },

    bindQtipToElement: function (element) {
        element.qtip({
            content: {
                text: element.find('.tooltip-message')
            },
            position: {
                my: 'center left',
                at: 'center right',
                viewport: $(window)
            },
            /*hide: {
             event: 'click',
             inactive: 1500
             },*/
            style: {
                classes: 'qtip-light'
            }
        });
    }
};
