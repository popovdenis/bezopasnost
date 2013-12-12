<?php require_once("_head.php"); ?>
	<!-- Header implementation -->
	<?php require_once("_header.php"); ?>
	<!-- Content implementation -->
	<div class="content">
		<div class="text">
		<?php
		if(isset($item) && !empty($item)) { ?>
			<h1><?=$item->item_title?></h1>
			<?=$item->item_content?>
		<?php } ?>
			<h2>Наши Сертификаты</h2>
		</div>
		<div class="contacts">
			<div class="t">
				<div class="b">
					<div class="l">
						<div class="r">
							<div class="bl">
								<div class="br">
									<div class="tl">

										<div class="tr">
											<div class="title">
												<img src="<?=base_url()?>images/contacts_icon.png" style="margin-right:10px;"/><h3 class="title">Наши контакты</h3>
											</div>
											<div style="font-size:10px;">&nbsp;</div>
											<div class="left_part" style="margin-top:10px;">Телефон:</div>
											<div class="right_part">
												<p class="phone1"><?=$contacts->contact_phones[0]['contact_kode']?></p>
												<p class="phone2"><?=$contacts->contact_phones[0]['contact_phone']?></p>
											</div>
											<div class="left_part">&nbsp;</div>
											<div class="right_part">
												<p class="phone1"><?=$contacts->contact_phones[1]['contact_kode']?></p>
												<p class="phone2"><?=$contacts->contact_phones[1]['contact_phone']?></p>
											</div>

											<div style="font-size:10px;">&nbsp;</div>
											<div class="left_part">Email:</div>
											<div class="right_part">
												<a class="link" href="mailto:<?=$contacts->contact_emails[0]?>"><?=$contacts->contact_emails[0]?></a>
											</div>
											<?php
												$address1 = null;
												$address2 = null;	
												if(!empty($contacts->contact_address)){
													$contacts->contact_address = unserialize($contacts->contact_address);
													$address1 = (isset($contacts->contact_address[0]) && empty($contacts->contact_address[0]['contact_address'])) ? null : $contacts->contact_address[0]['contact_address'];
													$address2 = (isset($contacts->contact_address[1]) && empty($contacts->contact_address[1]['contact_address'])) ? null : $contacts->contact_address[1]['contact_address'];				
												}
											?>
											<div style="font-size:10px;">&nbsp;</div>
											<div class="left_part">Офис 1:</div>
											<div class="right_part">
												<p><?=$address1?></p>
												<a class="link" href="<?=base_url()?>contacts/">Схема проезда</a>
											</div>
											<div style="font-size:10px;">&nbsp;</div>
											<div class="left_part">Офис 2:</div>
											<div class="right_part">
												<p><?=$address2?></p>
												<a class="link" href="<?=base_url()?>contacts/">Схема проезда</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
<script type="text/javascript" src="<?=base_url()?>js/highslide/highslide-with-gallery.js"></script>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>js/highslide/highslide.css" />
<script type="text/javascript">
hs.graphicsDir = '<?=base_url()?>js/highslide/graphics/';
	hs.align = 'left';
	hs.transitions = ['expand', 'crossfade'];
	hs.outlineType = 'rounded-white';
	hs.fadeInOut = true;
	hs.dimmingOpacity = 0.75;

	// Add the controlbar
	hs.addSlideshow({
		//slideshowGroup: 'group1',
		interval: 3000,
		repeat: false,
		useControls: true,
		fixedControls: 'fit',
		overlayOptions: {
			opacity: .75,
			position: 'bottom center',
			hideOnMouseOut: true
		}
	});


</script>
		<div class="highslide-gallery certificates">
			<?php 
			if($gallery) {
				$image_str = '';
				foreach ($gallery as $attach) {
					$image_str .= '<div class="certificate"><a href="'.base_url().$attach->attach_path.'" class="highslide" onclick="return hs.expand(this)">
									<img src="'.base_url().$attach->attach_preview_path.'" alt="Highslide JS" title="Click to enlarge" /></a></div>';
				}
				echo $image_str;
			}			
			?>
		</div>
		<div style="clear:both;">&nbsp;</div>

	</div>
	<?php require_once('_footer.php'); ?>