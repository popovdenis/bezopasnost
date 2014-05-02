<?php require_once("header.php"); ?>
    <div id="settings_block" style="border:1px solid #CCCCCC;border-top:none;">
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
                    <?=$currencylist?>
                </div>
                <span><img id="currency_img" border="0" src="<?=base_url()?>images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;display:none;"/></span>
            </div>
            <div id="set_currency_found" style="width: 200px; float: left;"></div>
        </div>
        <!--Пользователи-->
        <div class="section_block">
            <div id="set_users_block">
                <div class="innerTableHeaderGreen">
                    <div class="left padAll5">Пользователи</div>
                    <div class="padAll5 right">
                        <img class="marRight5" src="<?=base_url()?>images/big-plus.gif" alt=""/>
                        <a onclick="return adminObj.add_form('user');" href="#">Добавить Нового Пользователя</a>
                    </div>
                </div>
                <div id="users_block_header">
                    <div id="search_user_name" style="float:left;margin-bottom:10px;margin-top:10px;">
                        <?=$userlist?>
                    </div>
                    <div id="new_user_block" style="float:left;width:917px;margin-bottom:10px;display:none;">
                        <div class="left padAll5" style="color:#676767;">Новый пользователь</div>
                        <div style="width:100%;float:left;">
                            <div style="float:left;">
                                <div style="float: left; width: 159px;margin-bottom:2px;">Логин:</div>
                                <div style="float: left; margin-left: 10px;margin-bottom:2px;"><input type="text" id="new_user_login" style="width:320px;"/></div>
                                <div style="clear: left; float: left; width: 159px;margin-bottom:2px;">Пароль:</div>
                                <div style="float: left; margin-left: 10px;margin-bottom:2px;"><input type="password" id="new_user_password" style="width:320px;"/></div>
                                <div style="clear: left; float: left; width: 159px;margin-bottom:2px;">Имя:</div>
                                <div style="float: left; margin-left: 10px;margin-bottom:2px;"><input type="text" id="new_user_first_name" style="width:320px;"/></div>
                                <div style="clear: left; float: left; width: 159px;margin-bottom:2px;">Фамилия:</div>
                                <div style="float: left; margin-left: 10px;margin-bottom:2px;"><input type="text" id="new_user_last_name" style="width:320px;"/></div>
                                <div style="clear: left; float: left; width: 159px;margin-bottom:2px;">Email:</div>
                                <div style="float: left; margin-left: 10px;margin-bottom:2px;"><input type="text" id="new_user_email" style="width:320px;"/></div>
                            </div>
                            <div style="float:right;">
                                Права доступа:
                                <select id="new_user_role">
                                    <option value="0">Выберите тип доступа</option>
                                    <option value="a">Администратор</option>
                                    <option value="e">Редактор</option>
                                    <option value="u">Пользователь</option>
                                </select>
                            </div>
                        </div>
                        <div style="float: left; width: 100%;">
                            <div style="float:right;">
                                <input type="button" onclick="adminObj.add_user();" value="Добавить" />
                            </div>
                        </div>
                    </div>
                </div>
                <div style="clear:both;" ></div>
            </div>
            <div id="set_user_found"></div>
        </div>
        <!--Категории-->
        <div class="section_block">
            <div id="set_cat_title">
                <div class="innerTableHeaderGreen">
                    <div class="left padAll5">Категории</div>
                    <div class="padAll5 right">
                        <img class="marRight5" src="<?=base_url()?>images/big-plus.gif" alt=""/>
                        <a onclick="return adminObj.add_form('category');" href="#">Добавить Новую Категорию</a>
                    </div>
                </div>
                <div id="category_header">
                    <div id="search_cat_name">
                        Имя категории:
                        <select id="search_category_list" class="Txt67 fwNormal" name="search_category_list">
                            <option value="0">Выберите имя категории</option>
                            <?=$categories?>
                        </select>
                        <input type="button" onclick="adminObj.search_category();" value="Найти" />
                        <div id="ad_items"></div>
                    </div>
                    <div id="new_category_block" style="float:left;width:917px;margin-bottom:10px;display:none;">
                        <div class="left padAll5" style="color:#676767;">Новая категория</div>
                        <div style="width:100%;float:left;">
                            <div style="float:left;">
                                Имя категории:
                                <input type="text" id="new_category_title" style="width:320px;"/>
                            </div>
                            <div id="new_category_list" style="float:right;">
                                Родительская категория:
                                <select id="categories_new"><option value="0">Выберите имя категории</option>
                                    <?=$categories?>
                                </select>
                            </div>
                        </div>
                        <div style="float: left; width: 100%;">
                            <div style="float:left;">
                                Описание категории:<br />
                                <textarea id="new_cat_desc" style="width:700px;"></textarea>
                            </div>
                            <div style="float:right;position:relative;top:50px;">
                                <input type="button" onclick="adminObj.new_category();" value="Добавить" />
                            </div>
                        </div>
                    </div>
                </div>
                <div style="clear:both;" />
            </div>
            <div id="set_cat_found"></div>
        </div>
        <!--Рекламный блок-->
        <style type="text/css">
            .ui-state-default{
                height:20px;
                padding-top:3px;
                width:250px;
            }
        </style>
        <div class="section_block">
            <div class="innerTableHeaderGreen">
                <div class="left padAll5">Рекламный блок</div>
            </div>
            <div>
                Список продукции:
                <select id="search_items_list" class="Txt67 fwNormal" name="search_items_list">
                    <option value="0">Выберите имя продукта</option>
                    <?php
                        if(!empty($items)) {
                            $items_str = "";
                            foreach($items as $item) {
                                $items .= '<option value="'.$item->item_id.'">'.$item->item_title.'</option>';
                            }
                            echo $items;
                        }
                    ?>
                </select>
                <input type="button" onclick="adminObj.add_ann_item();" value="Прикрепить" />
            </div>
            <div id="set_ann_item"><?=$ann_items?></div>
        </div>
    </div>
<?php require_once("footer.php"); ?>