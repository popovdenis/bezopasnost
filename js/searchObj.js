/**
 * User: Denis
 * Date: 10.03.14
 * Time: 13:48
 */
var searchObj = {
    search_by_tag: function(tag) {
        $.ajax({
            type: "POST",
            url: ajax_search_path,
            dataType: "json",
            data: {
                'action': 'search_by_tag',
                'tag': tag
            },
            beforeSend: function () {
                /*$(".page_container").html('<div style="float:left;"><img border="0" src="<?php echo base_url(); ?>images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;padding-left:145px;text-align:center;"/></div>');*/
            },
            success: function (data) {
                $("#items_block").html(data.items_block);
            },
            error: function (data) {
                $("#items_block").html('');
                $(".page_container").html('');
            }
        });

        return true;
    }
};