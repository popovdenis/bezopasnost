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
			<li id="li_about" <?= ($item_type == 'about') ? 'class="active"' : ''; ?>>
                <a href="<?=base_url()?>admin/about"><span>О Компании</span></a>
            </li>
		    <li id="li_information" <?= ($item_type == 'information') ? 'class="active"' : ''; ?>>
                <a href="<?=base_url()?>admin/information"><span>Информация</span></a>
            </li>
			<li id="li_partners" <?= ($item_type == 'partners') ? 'class="active"' : ''; ?>>
                <a href="<?=base_url()?>admin/partners"><span>Партнеры</span></a>
            </li>
		    <li id="li_products" <?= ($item_type == 'products') ? 'class="active"' : ''; ?>>
                <a href="<?=base_url()?>admin/products"><span>Продукция</span></a>
            </li>
            <li id="li_contacts" <?= ($item_type == 'contacts') ? 'class="active"' : ''; ?>>
                <a href="<?=base_url()?>admin/contacts"><span>Контакты</span></a>
            </li>
			<li id="li_gallery" <?= ($item_type == 'gallery') ? 'class="active"' : ''; ?>>
                <a href="<?=base_url()?>admin/gallery"><span>Галлереи</span></a>
            </li>
			<li id="li_settings" <?= ($item_type == 'settings') ? 'class="active"' : ''; ?>>
                <a href="<?=base_url()?>admin/settings"><span>Настройки</span></a>
            </li>
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