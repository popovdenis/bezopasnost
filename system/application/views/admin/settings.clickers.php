<script type="text/javascript">
    $(document).ready(function () {
        adminObj.initChessHeader();
    });
</script>
<div id="settings_clickers_main" class="section_block">
    <div class="innerTableHeaderGreen">
        <div class="left padAll5">Рекламные блоки шапки</div>
    </div>
    <div class="mrg10">
        <select class="settings_clickers_categories Txt67 fwNormal">
            <option value="0">Выберите имя категории</option>
            <?=$categories?>
        </select>
    </div>
    <div class="mrg10">
        <select class="settings_clickers_items Txt67 fwNormal" disabled>
            <option value="0">Список статей</option>
        </select>
        <input class="clickers_apply_btn" type="button" value="Применить">
    </div>
    <div id="flashMessage" class="flash_messages"></div>
    <div class="chess_header mrg10">
        <?php require_once(APPPATH . 'views/chess_header_elements.php'); ?>
    </div>
</div>
