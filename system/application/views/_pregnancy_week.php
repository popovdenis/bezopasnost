<div style="float:left;position:relative;width:960px;">
	<div style="float:left;width:965px;margin-bottom:15px;">
		<div style="float:left;width:100px;">Неделя</div>
		<div style="float:left;width:155px;">Последний день недели</div>
		<div style="float:left;">Примечание</div>
	</div>
	<?php
		$i = 1;
		$current_date = date("d.m.Y", strtotime($start_day));
		while ($i!=41) {
			if($i > 1) {
				$current_date = date("d.m.Y", strtotime($current_date . "+1 week"));
			}
	?>
	<div style="float:left;width:965px;margin-bottom:10px;">
		<div style="float:left;width:100px;"><?=$i?></div>
		<div style="float:left;width:155px;"><?=$current_date?></div>
		<div style="float:left;text-align:left;width:710px;">Итак, неделя номер <b><?=$i?></b> Конечно, это еще не беременность, поскольку у Вас - очередное менструальное кровотечение. Однако, хотите Вы этого или нет - организм начал готовиться к возможному материнству снова</div>
	</div>
	<?php $i++;
		} 
	?>
</div>