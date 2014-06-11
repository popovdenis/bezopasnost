/**
 * User: Denis
 * Date: 19.04.14
 * Time: 9:21
 */
var productObj = {
    
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
    }
}