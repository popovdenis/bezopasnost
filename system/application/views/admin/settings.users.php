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
                <span>Имя пользователя:</span>
                <select id="search_user_list" class="Txt67 fwNormal" name="search_user_list" style="width:235px;">
                    <option value="0">Выберите имя пользователя</option>
                    <?php
                    if($users) {
                        $options = "";
                        foreach ($users as $user) :
                            $options .= '<option value="'.$user->user_id.'">'.$user->user_login.'</option>';
                        endforeach;
                        echo $options;
                    }
                    ?>
                </select>&nbsp;<input type="button" onclick="adminObj.get_user();" value="Найти" />
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
