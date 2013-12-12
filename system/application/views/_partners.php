<?php require_once("_head.php"); ?>
	<!-- Header implementation -->
	<?php require_once("_header.php"); ?>
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
												foreach ($partners as $partner) {
													$str .= '<div class="partner"><a class="link" href="'.base_url().'partners/about/'.$partner->item_id.'">'.$partner->item_title.'</a></div>';
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
<!--			<div style="font-size:10px;">&nbsp;</div>-->
			<?php
				$str = '';
				foreach ($partners as $partner) {
					$style = 'position:relative;height:200px;';
					if(isset($partner->attach_preview_path) && !empty($partner->attach_preview_path)) $style .= 'background:url(\''.base_url().$partner->attach_preview_path.'\');';
					$style .= 'background-repeat:no-repeat;background-position:center;';
		
					$str .= '<div class="product_preview">
					            	<div class="pt"><div class="pb">
					            		<div class="pl"><div class="pr"><div class="pbl"><div class="pbr"><div class="ptl"><div class="ptr_partner">
					                <a href="'.base_url().'partners/about/'.$partner->item_id.'"><div style='.$style.'"></div></a>
					                <div style="position:relative;bottom:0px;">
					                	<a href="'.base_url().'partners/about/'.$partner->item_id.'"><h3>'.$partner->item_title.'</h3></a>
					                	<a href="'.base_url().'products/partner/49" class="podrobno">Продукция</a>
					                	<a href="'.base_url().'partners/about/'.$partner->item_id.'" class="info">Информация</a>
					                </div>
					            </div></div></div></div></div></div></div></div>
					        </div>';
				}
				echo $str;
			?>
		</div>

		<div style="clear:both;">&nbsp;</div>
	</div>
	<?php require_once('_footer.php'); ?>