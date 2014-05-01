<div style="width:100%;float:left;">
	<div class="left padAll5" style="color:#676767;width:100%;">Найденый пользователь: <?=strtoupper($user->user_login)?></div>
	<div style="float:left;">
		<div style="float: left; width: 80px;margin-bottom:2px;">Логин:</div>
		<div style="float: left; margin-left: 10px;margin-bottom:2px;"><input type="text" id="user_login" value="<?=$user->user_login?>" style="width:320px;"/></div>
		<div style="clear: left; float: left; width: 80px;margin-bottom:2px;">Пароль:</div>
		<div style="float: left; margin-left: 10px;margin-bottom:2px;">
			<input type="password" id="user_password" value="" disabled style="width:320px;float:left;"/>
			<div id="paswd_link" style="float:left;margin-left:3px;"><a style="font-size:11px;" href="#" onclick="adminObj.change_password('<?=$user->user_id?>'); return false;">Изменить пароль</a></div>
			<img id="user_img_loading" src="<?php echo base_url(); ?>images/add-note-loader.gif" style="display:none;" />
		</div>
		<div style="clear: left; float: left; width: 80px;margin-bottom:2px;">Имя:</div>
		<div style="float: left; margin-left: 10px;margin-bottom:2px;"><input type="text" id="first_name" value="<?=$user->first_name?>" style="width:320px;"/></div>
		<div style="clear: left; float: left; width: 80px;margin-bottom:2px;">Фамилия:</div>
		<div style="float: left; margin-left: 10px;margin-bottom:2px;"><input type="text" id="last_name"value="<?=$user->last_name?>" style="width:320px;"/></div>
		<div style="clear: left; float: left; width: 80px;margin-bottom:2px;">Email:</div>
		<div style="float: left; margin-left: 10px;margin-bottom:2px;"><input type="text" id="user_email" value="<?=$user->user_email?>" style="width:320px;"/></div>
	</div>
	<div id="users_list" style="float:right;">
		Права доступа:
		<select id="user_role">
			<option value="0">Выберите тип доступа</option>
			<option value="a" <?php if($user->user_role == 'a') echo 'selected'; ?>>Администратор</option>
			<option value="e" <?php if($user->user_role == 'e') echo 'selected'; ?>>Редактор</option>
			<option value="u" <?php if($user->user_role == 'u') echo 'selected'; ?>>Пользователь</option>
		</select>
	</div>
</div>
<div style="float: left; width: 100%;">
	<div style="float:right;">
		<input type="button" onclick="adminObj.update_user('<?=$user->user_id?>');" value="Обновить" />
	</div>
</div>