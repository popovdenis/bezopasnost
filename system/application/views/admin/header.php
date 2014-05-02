<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head style="color: #9AB8F3">
	<title><?= config_item('base_name') ?></title>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<link type="text/css" href="<?=base_url()?>css/jquery/demos.css" rel="stylesheet" />
	<link type="text/css" href="<?=base_url()?>css/admin_style.css" rel="stylesheet" />
	<link type="text/css" href="<?=base_url()?>css/importu.css" rel="stylesheet" />

  	<link type="text/css" href="<?=base_url()?>css/jquery/ui.all.css" rel="stylesheet" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>js/jquery.json-1.3.js"></script>

	<script type="text/javascript" src="<?=base_url()?>js/jquery-impromptu.js"></script>
	<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>
	<script type="text/javascript" src="/js/ckeditor/config.js"></script>

	<script type="text/javascript" src="<?=base_url()?>js/help.js"></script>

	<script type="text/javascript">set_base_url('<?php echo base_url(); ?>');</script>

	<script type="text/javascript" src="<?=base_url()?>js/bk_helper.js"></script>
	<script type="text/javascript" src="/js/ajaxupload.js"></script>

    <script type="text/javascript" src="/js/highslide/highslide.js"></script>
    <script type="text/javascript" src="<?=base_url()?>js/admin/productsObj.js"></script>
    <script type="text/javascript" src="<?=base_url()?>js/admin/productObj.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/admin/adminObj.js"></script>
</head>
<body>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/js_ajax/ajax_gallery.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $(".btn-slide").click(function(){
                $("#panel").slideToggle("slow");
                $(this).toggleClass("active"); return false;
            });
        });
    </script>
    <script type="text/javascript">
        $(function() {
            $("#datepicker_new").datepicker({showOn: 'button', buttonImage: '<?=base_url()?>images/icons/calendar.png', buttonImageOnly: true});
        });
    </script>
    <script type="text/javascript" src="<?=base_url()?>js/highslide/highslide.js"></script>
    <script type="text/javascript" src="<?=base_url()?>js/highslide/highslide-with-gallery.js"></script>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>js/highslide/highslide.css" />
    <script type="text/javascript">
        hs.graphicsDir = '<?=base_url()?>js/highslide/graphics/';
        hs.wrapperClassName = 'wide-border';
    </script>

    <style type="text/css">
        a:hover{text-decoration: underline;}
        .acc_block{width: 250px;float: right;}
        .acc_content{text-align:left;margin-left:10px;}
    </style>
    <style type="text/css">
        div.jqi {
            width: 1000px;
        }
    </style>
    <?php require_once("help.php"); ?>
    <div id="tabs" style="margin:0 auto;width:1100px;">
        <?php require_once("_tabs.php"); ?>
        <div id="tabs-1">