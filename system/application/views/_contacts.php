<?php require_once("_head.php"); ?>
<!--<script type="text/javascript" src="<?/*=base_url()*/?>js/highslide/highslide-with-html.js"></script>
<link rel="stylesheet" type="text/css" href="<?/*=base_url()*/?>js/highslide/highslide.css" />

<script type="text/javascript">
	hs.graphicsDir = '<?/*=base_url()*/?>js/highslide/graphics/';
	hs.outlineType = 'rounded-white';
</script>-->
<script src="http://api-maps.yandex.ru/1.1/index.xml?key=ADzw600BAAAAnOQ8NQIAxBFbuoPzi14fQXTpnbMudNRqTVEAAAAAAAAAAAA9JEyR6NjLBemwhkInmnzXQBeRQw=="
        type="text/javascript"></script>
<script type="text/javascript" src="<?=base_url()?>js/maps.js"></script>
<?php require_once("_header.php"); ?>
<script type="text/javascript">
function action_map(id, action) {
	if(action == 'show') {
		$('#'+id).show();
//		var link = '<a href="#" onclick="javacript:action_map(\''+ id +'\', \'hide\');return false;">Скрыть карту</a>';
//		$('#link_'+id).html(link);
	}
	else if(action == 'hide'){
		$('#'+id).hide();
//		var link = '<a href="#" onclick="javacript:action_map(\''+ id +'\', \'show\');return false;">Показать на карте</a>';
//		$('#link_'+id).html(link);
	}

	if(id == 'office1') YMaps.load(init1);
	else if(id == 'office2') YMaps.load(init2);

}
jQuery(document).ready(function() {
      action_map('office2', 'show');
      action_map('office1', 'show');
});
</script>
	<div class="content">
		<!-- Содержание -->
		<?php
			$address1 = null;
			$address2 = null;
			if(!empty($contacts->contact_address)){
				$contacts->contact_address = unserialize($contacts->contact_address);
				$address1 = (isset($contacts->contact_address[0]) && empty($contacts->contact_address[0]['contact_address'])) ? null : $contacts->contact_address[0]['contact_address'];
				$address2 = (isset($contacts->contact_address[1]) && empty($contacts->contact_address[1]['contact_address'])) ? null : $contacts->contact_address[1]['contact_address'];
			}
			$map1 = null;
			$map2 = null;
			if(!empty($contacts->contact_maps)){
				$contacts->contact_maps = unserialize($contacts->contact_maps);
				$map1 = isset($contacts->contact_maps['contact_map1']) ? $contacts->contact_maps['contact_map1'] : null;
				$map2 = isset($contacts->contact_maps['contact_map2']) ? $contacts->contact_maps['contact_map2'] : null;
			}
			$photo1 = null;
			$photo2 = null;
			if(!empty($contacts->contact_photos)){
				$contacts->contact_photos = unserialize($contacts->contact_photos);
				$photo1 = isset($contacts->contact_photos['contact_photo1']) ? $contacts->contact_photos['contact_photo1'] : null;
				$photo2 = isset($contacts->contact_photos['contact_photo2']) ? $contacts->contact_photos['contact_photo2'] : null;
			}
			$time1 = null;
			$time2 = null;
			if(!empty($contacts->contact_times)){
				$contacts->contact_times = unserialize($contacts->contact_times);
				$time1 = isset($contacts->contact_times[0]) ? $contacts->contact_times[0] : null;
				$time2 = isset($contacts->contact_times[1]) ? $contacts->contact_times[1] : null;
			}
		?>
		<div class="about">
            <div class="data">
                <h1>Контакты</h1>
                <!-- Разделитель -->
                <div class="line_separator">
                    <div class="line_separator_left">&nbsp;</div>
                    <div class="line_separator_right">&nbsp;</div>
                </div>
                <?php if(isset($contacts)){ ?>
                <div class="part">
                    <div style="float:left; margin-right:20px; margin-top:9px;">
                        Телефон:
                    </div>
                    <div style="float:left;">
                    <?php if(!empty($contacts->contact_phones )) : foreach ($contacts->contact_phones as $phone) { ?>
                        <div>
                        	<p class="phone1" style="font-size:20px;"><?=$phone['contact_kode']?></p>
                        	<p class="phone2" style="font-size:30px;"><?=$phone['contact_phone']?></p>
                        </div>
                    <?php } endif; ?>
                    </div>
                </div>
                <div class="part">
                    <div style="float:left; margin-right:20px; margin-top:9px;">
                        Электронная почта:
                    </div>
                    <div style="float:left; margin-top:9px;">
                    	<?php if(!empty($contacts->contact_emails )) : foreach ($contacts->contact_emails as $email) { ?>
                        <a href="mailto:<?=$email?>"><?=$email?></a>
                        <?php } endif; ?>
                    </div>
                </div>
                <div class="part">
                    <div style="float:left; margin-right:20px; margin-top:9px;">
                        Факс:
                    </div>
                    <div style="float:left;">
                    <?php if(!empty($contacts->contact_faxes )) : foreach ($contacts->contact_faxes as $fax) { ?>
                        <div>
                        	<p class="phone1" style="font-size:30px;"><?=$fax?></p>
                        </div>
                    <?php } endif; ?>
                    </div>
                </div>
                <!-- Разделитель -->
                <div class="line_separator">
                    <div class="line_separator_left">&nbsp;</div>
                    <div class="line_separator_right">&nbsp;</div>

                </div>
                <div class="part">
                    <div style="float:left; margin-top:9px;">
                        <h3>Офис №1:</h3>
                    </div>
                </div>
                <div class="part">

                    <div style="float:left; margin-top:9px;">
                        <h3>Офис №2:</h3>
                    </div>
                </div>
            </div>
            <?php
    			$time_work1 = null;
    			$time_work2 = null;
    			$timeout1 = null;
    			$timeout2 = null;

				if($time1) {
					$time_work1 = "с ".$time1['time_from_h'].":".$time1['time_from_m'];
					$time_work1 .= " до ".$time1['time_to_h'].":".$time1['time_to_m'];
					$timeout2 = "с ".$time1['time_tm_from_h'].":".$time1['time_tm_from_m'];
					$timeout2 .= " до ".$time1['time_tm_to_h'].":".$time1['time_tm_to_m'];
				}
				if($time2) {
					$time_work2 = "с ".$time2['time_from_h'].":".$time2['time_from_m'];
					$time_work2 .= " до ".$time2['time_to_h'].":".$time2['time_to_m'];
					$timeout2 = "с ".$time2['time_tm_from_h'].":".$time2['time_tm_from_m'];
					$timeout2 .= " до ".$time2['time_tm_to_h'].":".$time2['time_tm_to_m'];
				}
			?>
            <div class="floatL data">
            	<div class="part">
                    <div class="contact_line"><img src="<?=base_url().$photo1?>" style="max-height:370px;max-width:370px;"></div>
            		<div class="contact_line"><span>Адрес: </span><span><?=$address1?></span></div>
            		<div class="contact_line"><span>Время работы: </span><span><?=$time_work1?></span></div>
            		<div class="contact_line"><span>Перерыв: </span><span><?=$timeout2?></span></div>
            		<div>
						<div id="office2" style="width:380px;height:400px;display:none;margin-bottom:10px;"></div>
            		</div>
            	</div>
            	<div class="part">
                    <div class="contact_line"><img src="<?=base_url().$photo2?>" style="max-height:370px;max-width:370px;"></div>
            		<div class="contact_line"><span>Адрес: </span><span><?=$address2?></span></div>
            		<div class="contact_line"><span>Время работы: </span><span><?=$time_work2?></span></div>
            		<div class="contact_line"><span>Перерыв: </span><span><?=$timeout2?></span></div>
            		<div>
						<div id="office1" style="width:380px;height:400px;display:none;margin-bottom:10px;"></div>
            		</div>
            	</div>
            </div>
            <?php } ?>
		</div>
		<div style="clear:both;">&nbsp;</div>
	</div>
	<?php require_once('_footer.php'); ?>
