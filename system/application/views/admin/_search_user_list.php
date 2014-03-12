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
</select>&nbsp;<input type="button" onclick="get_user();" value="Найти" />