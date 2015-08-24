<?php
	$contacts = get_contacts();
?>
<!-- Footer implementation -->
<div class="footer">
	<div class="mailbox">&nbsp;</div>
	<div class="phone">
		<p>Звоните нам:</p>
		<p>
			<font class="phone_prefix"><?=$contacts->contact_phones[0]['contact_kode']?></font>
			<font class="phone_number"><?=$contacts->contact_phones[0]['contact_phone']?></font>
		</p>
	</div>
    <div style="float: left; margin-left: 55px; margin-top: 32px;">
        <!--LiveInternet counter--><script type="text/javascript"><!--
            document.write("<a href='http://www.liveinternet.ru/click' "+
                "target=_blank><img src='//counter.yadro.ru/hit?t11.6;r"+
                escape(document.referrer)+((typeof(screen)=="undefined")?"":
                ";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
                    screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
                ";"+Math.random()+
                "' alt='' title='LiveInternet: показано число просмотров за 24"+
                " часа, посетителей за 24 часа и за сегодня' "+
                "border='0' width='88' height='31'><\/a>")
            //--></script><!--/LiveInternet-->
    </div>
	<div class="navigation">
		<p>
			<a href="<?=base_url()?>">Главная</a> |
			<a href="<?=base_url()?>about">О Компании</a> |
			<a href="<?=base_url()?>contacts">Контакты</a> |
			<a href="<?=base_url()?>map">Карта сайта</a>
		</p>
		<p class="copyright">© 2006-2010 Компания „Безопасность”. Все права защищены</p>
	</div>
</div>
<?php $this->load->view('ga_tracker.php') ?>
</body>
</html>
<div style="display:none;">
<?php
	$this-> benchmark-> mark('code_end');
	echo "<p> Time generation: ".$this-> benchmark-> elapsed_time('code_start', 'code_end').'"</p> ';
	echo "<p> Memory usage: ".$this->benchmark->memory_usage().'"</p> ';
?>
</div>
