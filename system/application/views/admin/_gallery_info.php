<?php if(isset($gallery) && is_object($gallery)) { ?>
<div style="float: left;margin-bottom:3px;margin-top:15px;">
    <div style="margin-bottom: 10px;">Название:</div>
    <div style="float: left; margin-right: 10px;">
        <input type="text" value="<?=$gallery->gallery_title?>" id="gal_title" style="margin-bottom: 10px;">
    </div>
    <div style="float: left; margin-bottom: 5px;">
        <input type="button" value="Сохранить" onclick="adminObj.update_gallery('<?=$gallery->gallery_id?>');">
        <input type="button" value="Удалить" onclick="if(confirm('Вы уверены, что хотите удалить галерею вместе с фотографиями в ней?')) adminObj.delete_gallery('<?=$gallery->gallery_id?>');">
    </div>
</div>
<?php } ?>