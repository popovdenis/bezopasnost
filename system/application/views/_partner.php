<?php require_once("_head.php"); ?>
	<!-- Header implementation -->
	<?php require_once("_header.php"); ?>
	<!-- Content implementation -->
	<!-- Content implementation -->
	<div class="content">
	
		<div class="partners">
			<div class="t">
				<div class="b">
					<div class="l">
						<div class="r">
							<div class="bl">
								<div class="br">
									<div class="tl">
										<div class="tr">
											<div style="font-size:10px;">&nbsp;</div>
											<?php
												$str = '';
												foreach ($partners as $single) {
													if($single->item_id == $partner->item_id)
														$str .= '<div class="partner_selected">'.$single->item_title.'</div>';
													else
														$str .= '<div class="partner"><a class="link" href="'.base_url().'partners/about/'.$single->item_id.'">'.$single->item_title.'</a></div>';
												}
												echo $str;
											?>
											
											<div style="font-size:10px;">&nbsp;</div>
										</div>
									</div>
								</div>
							</div>
						</div>
	
					</div>
				</div>
			</div>
		</div>
		<div class="partner_text">
			<h1><?=$partner->item_title?></h1>
			<a href="<?=base_url()?>products/partner/<?=$partner->item_id?>"><div class="button">Продукция >></div></a>
			<?php if(isset($partner->attach) && !empty($partner->attach)) { ?>
			<img style="margin-top:10px;margin-bottom:10px;" src="<?=base_url().$partner->attach->attach_single_path?>"/>
			<?php } ?>
			<?=html_entity_decode($partner->item_content, ENT_QUOTES, 'UTF-8');?>
		</div>
		<a href="<?=base_url()?>products/partner/<?=$partner->item_id?>"><div class="button">Продукция >></div></a>
		<div style="clear:both;">&nbsp;</div>
	</div>
	<?php require_once('_footer.php'); ?>