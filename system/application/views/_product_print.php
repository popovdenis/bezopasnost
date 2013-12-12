<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <?=$meta_tags;?>
</head>
<body leftmargin="6" topmargin="6" marginheight="6" marginwidth="6">
<style type="text/css">
@page: left {margin-left:3cm; margin-right:5cm;}
@page: right {margin-left:5cm; margin-right:3cm;}
h1 {page-break-after: always;}
h3 {page-break-before: right;}
h6 {page-break-after: avoid;}
p {widows: 2;}

a {
	color:#CC6600;
	outline:0 none;
	text-decoration:none;
}
p {
	font-size:14px;
}
img {
	border:0;
}
div.infocontent {
	float:left;
	margin:0 10px 0 0;
	padding:0;
	width:630px;
}
div.product_title {
	clear:left;
	float:left;
	font-size:28px;
	margin-top:10px;
}

div.product_description  {
	clear:both;
	margin:0;
	padding:0;
}
div.product_description p {
	margin:0;
	padding-top:10px;
}

div.product_info {
	display:table-cell;
	float:left;
	vertical-align:top;
}
div.product_photo {
	float:left;
	max-width:235px;
	padding-top:25px;
	text-align:center;
	width:235px;
}
div.product_details {
	float:left;
	margin:0;
	padding-left:20px;
	padding-right:0;
	padding-top:25px;
	width:350px;
}
.floatL{
    float:left;
}
</style>
<!-- Содержание -->
<div class="infocontent" align="left">
	<div>
		<div class="product_title"><?=$product->item_title?></div>
	</div>
	<div class="product_description">
		<p><?=$product->item_preview?></p>
	</div>
	<div class="product_info">
        <div class="product_photo">
        <?php if(isset($product->attach) && !empty($product->attach)) { ?>
            <a href="<?=base_url().$product->attach->attach_path?>" class="highslide" onclick="return hs.expand(this)">
                <img src="<?=base_url().$product->attach->attach_single_path?>" title="Click to enlarge" />
            </a>
            <div style="float: left; font-size: 17px; margin-top: 20px;color:#CC6600;">
                <span>Цена:</span>
                <span><?=$product->item_price?></span>
            </div>
        <?php } ?>
        </div>
        <div class="product_details">
            <?=$product->item_characters?>
            <div style="float: left; font-size: 17px; color:#CC6600;">
                <span>Цена:</span>
                <span><?=$product->item_price?></span>
            </div>
        </div>            
    </div>
    <div class="floatL"><?=$product->item_content?></div>
</div>
</body>
</html>