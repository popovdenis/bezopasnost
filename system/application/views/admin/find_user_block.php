<div class="innerTableHeaderGreen">
	<div id="" class="left padAll5">Пользователи</div>
	<div class="padAll5 right">
		<img class="marRight5" src="<?=base_url()?>images/big-plus.gif" alt=""/>
		<a id="" onclick="javascript: return add_form('user');" href="#">Добавить Нового Пользователя</a>
	</div>
</div>
<div id="users_block_header">
	<div id="search_user_name">
		Имя пользователя:
		<select id="search_user_list" class="Txt67 fwNormal" name="search_user_list" style="width:235px;">
			<option value="0">Выберите имя пользователя</option>
			<?=$users_list?>
		</select>
		<input type="button" onclick="javascript:search_user();" value="Найти" />
	</div>
	<div id="new_user_block" style="float:left;width:917px;margin-bottom:10px;display:none;">
		<div id="" class="left padAll5" style="color:#676767;">Новый пользователь</div>
		<div style="width:100%;float:left;">
			<div style="float:left;">
				<div style="float: left; width: 159px;">Имя пользователя:</div>
				<div style="float: left; margin-left: 10px;"><input type="text" id="new_user_login" style="width:320px;"/></div>
				<div style="clear: left; float: left; width: 159px;">Пароль:</div>
				<div style="float: left; margin-left: 10px;"><input type="password" id="new_user_password" style="width:320px;"/></div>
			</div>
			<div id="new_users_list" style="float:right;">
				Права доступа:
				<select id="new_user_role">
					<option value="0">Выберите тип доступа</option>
					<option value="admin">Администратор</option>
					<option value="editor">Редактор</option>
				</select>
			</div>
		</div>
		<div style="float: left; width: 100%;">
			<div style="float:right;">
				<input type="button" onclick="javascript:new_user();" value="Добавить" />
			</div>
		</div>
	</div>
</div>