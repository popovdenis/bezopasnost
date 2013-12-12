<?php
	$itemsstr = "";
	foreach ($items as $index=>$item) {
		$item->item_preview = trim($item->item_preview);
		$price_uah = $price_usd = $price_eur = '&nbsp;';
	?>
	<div id="item_block_<?=$item->item_id?>" style="margin:5px 0 5px 0; float:left;">
		<span class="spn_chb"><input type="checkbox" id="item_chb_delete_<?=$item->item_id?>" name="item_chb_delete_<?=$item->item_id?>" /></span>
		<span class="spn_num"><?=($index+1)?></span>
		<span class="spn_mode"><img style="width:25px;height:25px;cursor:pointer;" title="<?=$item->item_mode?>" src="<?=base_url()?>images/icons/<?=$item->item_mode?>.png" /></span>
		<span class="spn_cat"><?=$item->cat_str?></span>
		<span class="spn_title"><a href="#item<?=$item->item_id?>" onclick="javascript:get_page('<?=$item_type?>', '<?=$item->item_id?>');return false;"><?=$item->item_title?></a></span>
		<span class="spn_desc"><?php echo empty($item->item_preview)?'&nbsp;':$item->item_preview; ?></span>
		<?php if($item_type == "products") { ?>
		<div class="price_head">							
			<div>
				<div class="price_name_head_cost price_name" onclick="change_price_value('<?=$item->item_id?>', 'hs_set'); return hs.htmlExpand(this, {contentId:'hs_<?=$item->item_id?>'})">
					<span id="price_item_<?=$item->item_id?>"><?=$item->item_price?></span>
					<input type="hidden" id="item_price_<?=$item->item_id?>" value="<?=$item->item_price?>" />
				</div>
				<div class="price_name_head_value">
					<select id="price_select_<?=$item->item_id?>" onchange="javascript:change_price_value('<?=$item->item_id?>', 'display')">
						<option value="uah">UAH</option>
						<option value="usd">USD</option>
						<option value="eur">EUR</option>
					</select>
				</div>
			</div>
		</div>
		<?php } ?>
		<span class="spn_delete">
			<div id="delete_btn_<?=$item->item_id?>" class="delete_btn" onclick="if(confirm('Статья удалится вместе с прикрепленным к ней материалом. Вы уверены, что хотите удалить эту статью?')) delete_item('<?=$item->item_id?>', '<?=$item_type?>');">
				<span class="delete_btn_span">Удалить</span>
			</div>
		</span>
	</div>
	<div class="highslide-html-content" id="hs_<?=$item->item_id?>">
		<div class="highslide-header"><ul><li class="highslide-move"><a href="#" onclick="return false">Move</a></li><li class="highslide-close"><a href="#" onclick="return hs.close(this)"></a></li></ul></div>
		<div class="highslide-body">
			<div style="margin:5px 0;">
                <input size="10" id="cr_val_<?=$item->item_id?>" value="" onkeyup="javascript:change_price_value('<?=$item->item_id?>', 'change');">&nbsp;
				<select id="price_select_change_<?=$item->item_id?>" onchange="javascript:change_price_value('<?=$item->item_id?>', 'change')" style="margin:0;width:87px;">
					<option value="uah">UAH</option>
					<option value="usd">USD</option>
					<option value="eur">EUR</option>
				</select>
			</div>
			<div>
                <div style="margin:5px auto;"><strong>UAH</strong> - <span id="cr_uah_<?=$item->item_id?>"></span></div>
				<div style="margin:5px auto;"><strong>USD</strong> - <span id="cr_usd_<?=$item->item_id?>"></span></div>
				<div style="margin:5px auto;"><strong>EUR</strong> - <span id="cr_eur_<?=$item->item_id?>"></span></div>
			</div>
			<div>
				<input type="button" value="Применить" onclick="javascript:change_price('<?=$item->item_id?>');" />
				<img id="loader_<?=$item->item_id?>" src="<?=base_url()?>images/ajax-loader.gif" style="display:none;" />
			</div>
		</div>
	</div>
	<?php
	}
?>
<div style="float:left;">
	<div style="width:255px;float:left;">&nbsp;<img style="float:right;display:none;position:relative;top:21px;" id="paginate_img" src="<?=base_url()?>images/add-note-loader.gif"/></div>
	<div class="page_container" id="paginate_args">
		<div class="page_info">Страница <?=$cur_page?> из <?=$num_pages?></div>
		<?php echo paginate_ajax($paginate_args); ?>
	</div>
</div>