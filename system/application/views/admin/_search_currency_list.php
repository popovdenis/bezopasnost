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
</select>&nbsp;<input type="button" onclick="get_currency();" value="Найти" />