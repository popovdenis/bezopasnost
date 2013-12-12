<script type="text/javascript" src="<?=base_url()?>js/ui/ui.sortable.js"></script>
<style type="text/css">
#sortable_gallery { list-style-type: none; margin: 0; padding: 0; }
#sortable_gallery li { margin: 3px 3px 3px 0; padding: 1px; float: left; width: 104px; height: 120px; font-size: 4em; text-align: center; }
.sortable_img {cursor:pointer;height:15px;margin-bottom:25px;margin-left:85px;margin-top:3px;width:15px;}
</style>
<script type="text/javascript">
    $(function() {
        $("#sortable_gallery").sortable();
        $("#sortable_gallery").disableSelection();
    });
</script>
<div id="wrap" class="normalBoxContent workNotesBox highslide-gallery">
<?php
    $gallery_str = "";
    if(!empty($gallery)) {
    
            $gallery_str = '<div style="float:left;width:100%;"><div id="gallery_reorder_apply" style="float:left;"><a href="#" onclick="javascript:reorder_attach_gallery('.$gallery[0]->gallery_id.');return false;">Применить</a>
            <img id="loader_gallery" alt="loading..." border="0" src="'.base_url().'images/add-note-loader.gif" style="display:none;" /></div>
            <div style="float:right;"><a href="#" onclick="javascript:delete_item_gallery(\''.$gallery[0]->gallery_id.'\', \''.$item_id.'\');return false;">Открепить</a></div></div>
            <ul id="sortable_gallery">';
            
            foreach($gallery as $attachment) {
                $gallery_str .= '<li class="ui-state-default" id="'.$attachment->attach_id.'">
                    <a href="'.base_url().$attachment->attach_path.'" class="highslide" onclick="return hs.expand(this)">
                        <img style="width:100px;height:90px;" src="'.base_url().$attachment->attach_path.'">
                    </a>
                    <div class="highslide-caption">
                        <div><strong>Название: </strong>'.$attachment->attach_title.'</div>
                        <div><strong>Описание: </strong>'.$attachment->attach_desc.'</div>                        
                    </div>
                    <img class="sortable_img" src="'.base_url().'images/icons/cancel.png" onclick="javascript:delete_attach_gallery('.$attachment->gallery_id.','.$attachment->attach_id.');">
                </li>';                
            }
            $gallery_str .= '</ul>';
            
        /*} else {
            $gallery_str = '<div><ul id="sortable_gallery"></ul></div>';
        }*/
    }
    echo $gallery_str; 
?>
</div>