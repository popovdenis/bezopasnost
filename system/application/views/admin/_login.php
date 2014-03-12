<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?= config_item('base_name') ?></title>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/styles_login.css" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>js/_login.js"></script>
</head>
	<body>
		<div id="main_login_block">
			<div id="center_login_block">
				
				<div id="login-page">
					<h3>Авторизация</h3>
<!--					<p id="attantion">Пожалуйста, введите ваш логин и пароль.</p>-->
					<div style="margin-top:17px;">
						<h3 id='login_feedback'><p id="attantion">Пожалуйста, введите ваш логин и пароль.</p></h3>
					</div>
					<form id="loginform">
						<div class="login_input">
							<input type="text" id="login_email" name="login" class="valueFx first" value="" onkeydown="if(event.keyCode==13){authorize();}" />
							<input type="password" id="password" name="password" class="valueFx" value="" onkeydown="if(event.keyCode==13){authorize();}" />
						</div>
						<div>
							<div id="login_process"></div>
							<div style="float:right;"><input id="login_button" type="button" value="Войти" onclick="authorize();" /></div>
						</div>
						
						<p class="clearboth" style="bottom:3px;position:absolute;"><a style="color:black;" href="#" onclick="show_form('forgot_password');return false;">Восстановить ваш пароль?</a></p>
						<div id="forgot_password" class="f-elements clearfix" style="display:none;">
					    	<span>Ваш email: </span>
					    	<input type="text" id="username" name="username" class="usernameFP first" / >
					    	<input class="buttonFP" type="button" value="Submit" onclick="forgot_password();" />
					   	</div>
					   	<div id="msg"></div>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>