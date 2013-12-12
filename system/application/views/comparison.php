<?php mb_internal_encoding("UTF-8"); require_once("_head.php"); ?>
<?php modules::load_file('ajax_products.php',APPPATH.'/js_ajax/'); ?>
	<!-- Header implementation -->
	<?php require_once("_header.php"); ?>
	<!-- Content implementation -->
	<div class="content">
		<!-- Содержание -->
		<div class="infocontent">
            <!-- Каталог продукции -->
            <div class="products_catalog">
            	<div style="float:left;" id="products_block"></div>               
            </div>
		</div>
<style type="text/css">
.scroll{
	overflow:auto;
	width:860px;   
    height: auto; /* Высота блока */
    padding: 5px; /* Поля вокруг текста */
}
.comparison_data {
	vertical-align:top;
	width:17.75em;
	overflow:visible;
	padding:0;
	height:auto;
}
</style>
		<div class="scroll comparison" id="compare_products">
		<table>
			<tr>
			<?php foreach ($items as $item) { ?>
			<td class="comparison_data">			
				<div style="width:17.75em;">
					<div><?=$item->item_title?></div>
					<div><?=$item->item_characters?></div>
				</div>
			</td>
			<?php } ?>
			</tr>
		</table>
		</div>
		<div style="clear:both;">&nbsp;</div>
	</div>
	<?php require_once('_footer.php'); ?>