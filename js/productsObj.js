/**
 * Created with PhpStorm.
 * User: Denis Popov
 * Email: pod@ciklum.com
 * Date: 1/22/14
 * Time: 10:25 AM
 */
var productsObj = {

    init: function(itemId)
    {
        this.initImageGalleryUploader(itemId);
        this.initImageTitleUploader(itemId);
//        this.initHighslide();
        this.initCKeditors();
    },

    initImageGalleryUploader: function (itemId) {
        if ($('#imggallery_' + itemId).length > 0) {
            new AjaxUpload('#imggallery_' + itemId, {
                action: '/admin/home/upload',
                name: 'userfile',
                data: {
                    upload_type: 'item_gallery'
                },
                responseType: false,
                onChange: function (file, extension) {
                },
                onSubmit: function (file, ext) {
                },
                onComplete: function (file, response) {
                    var result = '';
                    if (response) {
                        result = window["eval"]("(" + response + ")");
                        $.post("/admin/home/upload", { new_gal_title: $('#new_gal_title').val(), new_gal_desc: $('#new_gal_desc').val(), attach_id: result.attach_id, item_id: itemId, upload_type: 'item_gallery', file_type: $("#gallery_file_tyles option:selected").val() }, function (data) {
                                var result = window["eval"]("(" + data + ")");
                                $('#new_gal_title').val('');
                                $('#new_gal_desc').val('');
                                if ($("#gallery_file_tyles option:selected").val() == 'price') {
                                    var img_del_gal = '<img title="Удалить картинку из текущей галереи" style="cursor:pointer;width:15px;height:15px;" src="/images/icons/cancel.png" onclick="javascript:if(confirm(\'Картинка будет удалена из текущей галереи. Вы уверены, что хотите удалить этот файл?\')) delete_img(\'' + result.attach_id + '\', \'' + result.item_id + '\', \'false\');return false;" /><img title="Удалить картинку из всех галерей" style="cursor:pointer;width:21px;height:21px;" src="/images/icons/trash.png" onclick="javascript:if(confirm(\'Картинка будет удалена из всех галерей. Вы уверены, что хотите удалить этот файл?\')) delete_img(\'' + result.attach_id + '\', \'null\', \'true\');return false;" />';
                                    var file = '<div id="gallery_file_id_' + result.attach_id + '" class="gallery_image_block price_gal"><div class="heading">' + result.attach_title + '</div><span><img src="/images/icons/excel_48.png" /></span><br /><div>' + result.attach_desc + '</div><br />' + img_del_gal + '</div>';
                                } else
                                    if ($("#gallery_file_tyles option:selected").val() == 'image') {
                                        var img_del_gal = '<img title="Удалить картинку из текущей галереи" style="cursor:pointer;width:15px;height:15px;" src="/images/icons/cancel.png" onclick="javascript:if(confirm(\'Картинка будет удалена из текущей галереи. Вы уверены, что хотите удалить этот файл?\')) delete_img(\'' + result.attach_id + '\', \'' + result.item_id + '\', \'false\');return false;" /><img title="Удалить картинку из всех галерей" style="cursor:pointer;width:21px;height:21px;" src="/images/icons/trash.png" onclick="javascript:if(confirm(\'Картинка будет удалена из всех галерей. Вы уверены, что хотите удалить этот файл?\')) delete_img(\'' + result.attach_id + '\', \'null\', \'true\');return false;" />';
                                        var file = '<div id="gallery_img_id_' + result.attach_id + '" class="gallery_image_block image_gal"><div class="heading">' + result.attach_title + '</div><a href="/' + result.file_full_path + '" class="highslide" onclick="return hs.expand(this)"><img src="/' + result.file_path + '" title="Click to enlarge" /></a><div class="highslide-caption">' + result.attach_desc + '</div>' + img_del_gal + '</div>';
                                    }
                                $("#loader").hide();
                                $("#new_gallery_block").hide();
                                $('#imggallery_img').append(file);
                            })
                    } else {
                        alert('Ошибка! Файл не был загружен или загружен с ошибкой!');
                    }
                }
            });
        }
    },

    initImageTitleUploader: function (itemId) {
        var imgTitleElement = $("a[id^='imgtitle_']");
        if (imgTitleElement.length > 0) {
            itemId = (typeof itemId != "undefined") ? itemId : (imgTitleElement.attr('id').split('_')[1]);
            new AjaxUpload(imgTitleElement.attr('id'), {
                // Location of the server-side upload script
                action: '/admin/home/upload',
                // File upload name
                name: 'userfile',
                data: {
                    item_id: itemId,
                    upload_type: 'product_title'
                },
                responseType: false,
                onChange: function (file, extension) {
                },
                onSubmit: function (file, ext) {
                },
                onComplete: function (file, response) {
                    if (response) {
                        var result = window["eval"]("(" + response + ")");
                        $.post("/admin/home/upload", { attach_id: result.attach_id, item_id: itemId, upload_type: 'product_title'}, function (data) {
                                result = window["eval"]("(" + data + ")");
                                var file = '<img width="235" src="/' + result.file_path + '" />';
                                $('#item_title_img').html(file);
                            })
                    } else {
                        alert('Ошибка! Файл не был загружен или загружен с ошибкой!');
                    }
                }
            });
        }
    },

    initHighslide: function()
    {
        hs.graphicsDir = '/js/highslide/graphics/';
        hs.align = 'center';
        hs.transitions = ['expand', 'crossfade'];
        hs.outlineType = 'rounded-white';
        hs.fadeInOut = true;
        hs.addSlideshow({
            interval: 3000,
            repeat: false,
            useControls: true,
            fixedControls: 'fit',
            overlayOptions: {
                opacity: .75,
                position: 'bottom center',
                hideOnMouseOut: true
            }
        });
    },

    initCKeditors: function()
    {
        var config = {
            toolbar: 'Basic',
            uiColor: '#CB6615'
        };

        if ($('#post_content').length > 0) {
            CKEDITOR.replace('post_content', config);
        }
        if ($('#item_charecters').length > 0) {
            CKEDITOR.replace('item_charecters', config);
        }
        if ($('#new_post_content').length > 0) {
            CKEDITOR.replace('new_post_content', config);
        }
        if ($('#new_item_charecters').length > 0) {
            CKEDITOR.replace('new_post_content', config);
        }

    },

    initDatePicker: function() {
        var datePickerElement = $("input[id^='datepicker_']");
        if (datePickerElement.length > 0) {
            datePickerElement.datepicker({showOn: 'button', buttonImage: '/images/icons/calendar.png', buttonImageOnly: true});
        }
    }
};
