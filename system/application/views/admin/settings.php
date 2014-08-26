<?php require_once("header.php"); ?>
    <div id="settings_block" style="border:1px solid #CCCCCC;border-top:none;">
        <?php include("settings.currency.php"); ?>
        <!--Пользователи-->
        <?php include("settings.users.php"); ?>
        <!--Категории-->
        <?php include("settings.categories.php"); ?>
        <!--Рекламный блок-->
        <?php include("settings.advertisement.php"); ?>
        <?php include("settings.clickers.php"); ?>
    </div>
<?php require_once("footer.php"); ?>
