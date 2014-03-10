<?php require_once("_head.php"); ?>
<?php modules::load_file('ajax_search.php', APPPATH . '/js_ajax/'); ?>
    <script type="text/javascript">
        jQuery().ready(function () {
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
        <div class="infocontent" style="position:relative;float:left;">
            <h1>Результаты поиска</h1>

            <div id="items_block" style="float:left;"><?= $search_result['template'] ?></div>
            <!-- Навигация по страницам -->
<!--            <div class="page_container">--><?php //echo paginate_ajax($search_result['paginate_args']); ?><!--</div>-->
        </div>
        <?php require_once('_search_block.php'); ?>

        <div style="clear:both;">&nbsp;</div>
    </div>
<?php require_once('_footer.php'); ?>