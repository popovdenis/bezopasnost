<!-- Наши контакты -->
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
										<img src="<?=base_url()?>images/contacts_icon.png" style="margin-right:10px;"/>
										<a href="<?=base_url()?>contacts"><h3 class="title">Наши контакты</h3></a>
									</div>
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
									<div style="font-size:5px;">&nbsp;</div>
									<div class="left_part">Email:</div>
									<div class="right_part">
										<a class="link" href="mailto:<?=$contacts->contact_emails[0]?>"><?=$contacts->contact_emails[0]?></a>
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
