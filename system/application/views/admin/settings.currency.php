<div id="set_currency_block" class="section_block">
    <div class="innerTableHeaderGreen">
        <div class="left padAll5">Установка валюты</div>
        <div class="padAll5 right">
            <img class="marRight5" src="<?=base_url()?>images/big-plus.gif" alt=""/>
            <a onclick="return adminObj.add_form('currency');" href="#">Добавить Новую Валюту</a>
        </div>
    </div>
    <div id="currency_block_header" style="float: left; width: 100%;">
        <div id="new_currency_block" style="float:left;width:917px;margin-bottom:10px;display:none;">
            <div class="left padAll5" style="color:#676767;">Новая валюта</div>
            <div style="width:100%;float:left;">
                <input type="text" id="currency_value" value="" />&nbsp;
                <input type="button" onclick="adminObj.add_currency();" value="Добавить" />
            </div>
        </div>
        <div id="currencyList" style="float:left;margin-bottom:10px;margin-top:10px;">
            <select id="search_currency_list" class="Txt67 fwNormal" name="search_currency_list" style="width:235px;">
                <?php
                if($currency) {
                    $options = "";
                    foreach ($currency as $value) :
                        $options .= '<option value="'.$value->currency_id.'">'.$value->currency_value.'</option>';
                    endforeach;
                    echo $options;
                }
                ?>
            </select>&nbsp;<input type="button" onclick="adminObj.get_currency();" value="Найти" />
        </div>
        <span><img id="currency_img" border="0" src="<?=base_url()?>images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;display:none;"/></span>
    </div>
    <div id="set_currency_found" style="width: 200px; float: left;"></div>
</div>
