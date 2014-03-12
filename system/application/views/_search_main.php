<?php require_once("_head.php"); ?>
    <script type="text/javascript">
        $(document).ready(function () {
            if (window.location.hash.search(/^\#find:/) == 0) {
                var cId = window.location.hash.substr(6);
                //window.location.hash = '';
                search_by_tag(cId);
            }
        });
    </script>
    <!-- Header implementation -->
<?php require_once("_header.php"); ?>
    <!-- Content implementation -->
    <div class="content">
        <!-- Содержание -->
        <div class="module modulealt">
            <ul id="search_filters" class="buttons"><?= $search_result['main_category'] ?></ul>
        </div>
        <div class="infocontent" style="position:relative;float:left;">
            <h1>Результаты поиска</h1>

            <div
                style="float:left;width:590px;background:#EFEFEF none repeat scroll 0 0;font-size:12px;margin-top:10px;padding:14px 20px;">
                <div style="float:left;">Около <span id="count_result"><?= $search_result['count'] ?></span> результов
                    найдено для &quot;<?= $keywords ?>&quot;</div>
                <div style="float:right;">
                    <form id="searchform" name="searchform" method="POST" action="<?= base_url() ?>search"
                          enctype="multipart/form-data">
                        <input id="keywords" class="idleField search_input" type="text" value="" name="keywords"/>
                        <img src="<?= base_url() ?>images/search.png" class="search_lens"
                             onclick="javascript: jQuery( '#searchform' ).submit();">
                    </form>
                </div>
            </div>
            <div id="items_block" style="float:left;"><?= $search_result['template'] ?></div>
            <!-- Навигация по страницам -->
            <div class="page_container"><?php echo paginate_ajax($search_result['paginate_args']); ?></div>
        </div>
        <?php require_once('_search_block.php'); ?>
        <!-- Тэги -->
        <!--<div class="infobox" style="clear:left; margin-top:15px;">
			<div class="t">
				<div class="b">
					<div class="l">
						<div class="r">
							<div class="bl">
								<div class="br">

									<div class="tl">
										<div class="tr">
											<div class="tags"><ul><?= $tagclouds ?></ul></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>-->
        <div style="clear:both;">&nbsp;</div>
    </div>
<?php require_once('_footer.php'); ?>