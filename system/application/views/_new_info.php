<!-- Полезная информация -->
<div class="contacts" style="margin-top:10px; max-height:200px;">
	<div class="t">
		<div class="b">
			<div class="l">
				<div class="r">
					<div class="bl">
						<div class="br">
							<div class="tl">
								<div class="tr">
									<div class="title">
										<a href="<?=base_url()?>information"><h3 class="title">Полезная информация</h3></a>
									</div>
									<?php if(isset($information) && !empty($information)) { foreach ($information as $info) { ?>
                                    <div class="articles_item">
										<a class="link" href="<?=base_url().'information/about/'.$info->item_id?>"><?=$info->item_title?></a>
									</div>
									<?php } } ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>