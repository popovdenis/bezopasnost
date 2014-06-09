<style type="text/css">
.tab ul, .tab li{border:0; margin:0; padding:0; list-style:none;}
.tab ul{border-bottom:solid 1px #DEDEDE; height:29px; padding-left:20px;}
.tab li{float:left; margin-right:2px;}
.tab a:link, .tab a:visited{
	background:url(<?=base_url()?>images/tab-round.png) right 60px;
	color:#666666;
	display:block;
	font-weight:bold;
	height:30px;
	line-height:30px;
	text-decoration:none;
}
.tab a span{
	background:url(<?=base_url()?>images/tab-round.png) left 60px;
	display:block;
	height:30px;
	margin-right:14px;
	padding-left:14px;
}
.tab a:hover{
	background:url(<?=base_url()?>images/tab-round.png) right 30px;
	display:block;
}
.tab a:hover span{
	background:url(<?=base_url()?>images/tab-round.png) left 30px;
	display:block;
}

/* -------------------------------- */
/* 	ACTIVE ELEMENTS					*/
.active a:link, .active a:visited, .active a:visited, .active a:hover{
	background:url(<?=base_url()?>images/tab-round.png) right 0 no-repeat;
}
.active a span, .active a:hover span{
	background:url(<?=base_url()?>images/tab-round.png) left 0 no-repeat;
}

</style>
<div style="float:left;margin:0 auto;position:relative;width:1100px;">
	<div style="float:left;">
		<ul class="tab">
			<li id="li_main" class="active"><a href="#main" onclick="get_page('main', 0, 'Главная');return false;"><span>Главная</span></a></li>
			<li id="li_about"><a href="#about" onclick="get_page('about', 0, 'О Компании');return false;"><span>О Компании</span></a></li>
		    <li id="li_information"><a href="#information" onclick="get_page('information', 0, 'Информация');return false;"><span>Информация</span></a></li>
			<li id="li_partners"><a href="#partners" onclick="get_page('partners', 0, 'Партнеры');return false;"><span>Партнеры</span></a></li>
		    <li id="li_products"><a href="#products" onclick="get_page('products', 0, 'Продукция');return false;"><span>Продукция</span></a></li>
            <li id="li_contacts"><a href="#contacts" onclick="get_page('contacts', 0, 'Контакты');return false;"><span>Контакты</span></a></li>
			<li id="li_gallery"><a href="#gallery" onclick="get_page('gallery', 0, 'Галлереи');return false;"><span>Галлереи</span></a></li>
			<li id="li_settings"><a href="#settings" onclick="get_page('settings', 0, 'Настройки');return false;"><span>Настройки</span></a></li>
		</ul>
	</div>
	<div class="logout">
		<span style="float:left;">
			<a href="<?=base_url()?>" style="color:#B22222;">На сайт </a>
		</span>
		<span style="float:left;margin-left:5px;margin-right:5px;">
			<a>|</a>
		</span>
		<span style="float:left;">
			<a href="<?=base_url()?>users/logout">Выход</a>
		</span>
	</div>
</div>
