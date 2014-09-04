<script type="text/javascript" src="<?=base_url()?>js/ui/ui.sortable.js"></script>
<style type="text/css">
#sortable { list-style-type: none; margin:10px 0 0 0; padding: 0; width:400px; }
#sortable li { margin: 0 5px 5px 5px; padding: 5px; font-size: 1.2em; height: 1.5em; width:100%; }
html>body #sortable li { height: 1.5em; line-height: 1.2em; }
.ui-state-highlight { height: 1.5em; line-height: 1.2em; }
</style>
<script type="text/javascript">
$(function() {
    $("#items_sortable").sortable({
        placeholder: 'ui-state-highlight'
    });
    $("#items_sortable").disableSelection();
});
</script>
<div class="sortable_block">
    <div id="ann_items_block">
        <ul id="items_sortable">
        <?php if ( $items && !empty( $items ) )
        {
            $str = '';
            foreach ( $items as $item )
            {
                $str .= '<li id="item_' . $item->item_id . '" class="ui-state-default" style="margin-bottom:3px;">
                <div style="float: left; width: 280px;">
                <div style="float: left; margin: 3px 0 0 5px; width: 250px;">' . $item->item_title . '</div>
                <div style="float: right;"><img title="удалить" src="' . base_url( ) . 'images/icons/cancel.png" onclick="delete_ann_item(\'' . $item->item_id . '\');" style="bottom: 3px; cursor: pointer; width: 17px; height: 17px; position: relative; "/></div>
                </div>
                </li>';
            }
            echo $str;
        }?>
        </ul>
    </div>
</div>