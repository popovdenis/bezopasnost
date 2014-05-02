<?php require_once("_head.php"); ?>
<div id="set_gallery_block" style="float: left; width: 100%;margin-bottom:15px;">
    <div class="innerTableHeaderGreen">
        <div id="" class="left padAll5">Галлереи</div>
        <div class="padAll5 right">
            <img class="marRight5" src="<?=base_url()?>images/big-plus.gif" alt=""/>
            <a id="" onclick="return adminObj.add_form('gallery');" href="#">Добавить Новую Галлерею</a>
        </div>
    </div>
    <div id="gallery_block_header" style="float: left; width: 100%;">
        <div id="new_gallery_block" style="float:left;width:700px;margin-bottom:10px;display:none;">
            <div style="width:100%;float:left;">
                <div style="float:left; margin-bottom: 0px;margin-top:0;">
                    Название:<br />
                    <input type="text" id="new_gal_title" value="" />
                </div>
                <div style="margin: 10px 0pt 0pt 5px; float: left;">
                    <input type="button" value="Создать" onclick="adminObj.add_gallery();" />
                </div>
            </div>
        </div>
        <div id="" style="float:left;margin-bottom:10px;margin-top:10px;width:100%;">
            <select id="galleries">
                <option value="0">выберите галерею</option>
                <?php
                    $gallery_str = "";
                    if(!empty($galleries)) {
                        foreach($galleries as $gallery) {
                            $gallery_str .= '<option value="'.$gallery->gallery_id.'">'.$gallery->gallery_title.'</option>';
                        }
                    }
                    echo $gallery_str;
                ?>
            </select>
            <input type="button" onclick="adminObj.get_gallery();" value="Найти" />
        </div>
        <span><img id="gallery_img" border="0" src="<?=base_url()?>images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;display:none;"/></span>
    </div>
    <div><img id="loader_gallery" alt="loading..." border="0" src="<?=base_url()?>images/add-note-loader.gif" style="display:none;" /></div>
    <div id="gallery_info" style="width:100%; float: left;"></div>
    <div id="gallery_images" style="width:100%; float: left;"></div>
</div>
<?php require_once("footer.php"); ?>