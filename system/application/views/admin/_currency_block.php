<?php
	$currency_id = $currency_rate->currency_id;
	$currency_value = $currency_rate->currency_value;
	unset($currency_rate->currency_id);
	unset($currency_rate->currency_value);
?>
<div>
	<span>1</span> <?=$currency_value?>&nbsp;<strong>=</strong><br />
	<?php
		foreach ($currency_rate as $rate=>$value) {
	?>
	<input type="text" class="currency" id="currencyname_<?=$rate?>" data-currency-name="<?=$rate?>" value="<?=round($value, 2); ?>"/> <strong><?=strtoupper($rate)?></strong><br />
	<?php }?>
</div>
<div id="update_currency_block">
	<input type="button" onclick="adminObj.update_currency_rate('<?=$currency_id?>');" value="Обновить" />
</div>