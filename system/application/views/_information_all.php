<?php require_once("_head.php"); ?>
<?php modules::load_file('ajax_information.php',APPPATH.'/js_ajax/'); ?>
	<!-- Header implementation -->
	<?php require_once("_header.php"); ?>
	<!-- Content implementation -->
	<div class="content">
		<!-- Содержание -->
		<div class="infocontent"><div id="items_block"></div>
			<div class="page_container"><?php //echo paginate_ajax($paginate_args); ?></div>
		</div>
		<?=$categories_tree?>
		<?php require_once('_search_block.php'); ?>
		<!-- Тэги -->
		<div class="infobox" style="clear:left; margin-top:15px;">
			<div class="t">
				<div class="b">
					<div class="l">
						<div class="r">
							<div class="bl">
								<div class="br">
									<div class="tl">
										<div class="tr">
											<div class="tags"><ul><?=$tagclouds?></ul></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div style="clear:both;">&nbsp;</div>
	</div>
	<?php require_once('_footer.php'); ?>