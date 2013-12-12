var ajax_autorize_path = "ajax_handlers/admin_handler/ajax_actions";	

function base_url(url) {
	ajax_autorize_path = url+ajax_autorize_path;
}

function show_form(id){	
	var el = document.getElementById(id).style.display;
    	
	if(el == 'none')  $('#'+id).show("blind");
	else  $('#'+id).hide("blind");
}

function authorize(){
	$.ajax({
		type: "POST", 
		url: ajax_autorize_path,
		dataType: "html",
		data: { 
				'action':'authorize',
				'login_email':$('#login_email').val(),
				'password':$('#password').val()
			  },
		beforeSend: function()
		{
			FCKeditorAPI = null;
			__FCKeditorNS = null;
			$("#about").html('');
			$("#information").html('');
			$("#partners").html('');
			$("#products").html('');				
			$("#sertificates").html('');				
			
			$("#"+page).html('<img border="0" src="<?php echo base_url(); ?>images/add-note-loader.gif" alt="loading..." style="padding-top: 7px;text-align:center;"/>');
		},	  
		success: function(data)
		{
			$("#"+page).html(data);
	    },
	    error: function(data)
		{	
			$("#"+page).html('');
	    }
	});
	
	return true;
}

function authorize() {
	var ajax_actions_path = "<?php echo base_url(); ?>users/ajax_actions"; 
	var err = '';
	$.ajax({
			type: "POST", 
			url: ajax_actions_path,
			dataType: "json",
			data: { 'action':'authorize', 
					'login': $("#login-email").val(), 
					'password': $("#password").val()
				  },
			beforeSend: function(data){ 
				$("#login_process").show();
	            $("#login_process").html('<img alt="loading..." border="0" src="<?php echo base_url(); ?>images/loading-blue.gif" />'); 
	            $("#login-page").hide();
			},
			success: function(data)
			{
				if(data.status<0)
				{
					err = data.login_err+'<br/>'+data.password_err;
					if( data.login_err!='' ) $("#login-email").css({'background-color':'#eecccc'});
					if( data.password_err!='' ) $("#password").css({'background-color':'#eecccc'});
				}
				else if(data.status<1) err = 'Error - Please provide valid login credentials';//'wrong login or password';
				else if(data.status==1)
				{
					location = data.auth_success_path;
				}
				else if(data.status==2)
				{
					acceptance(data);
				}	
		   },
		   complete:  function(data){ 
		   		if (err=='') return;
				$("#login_process").hide();
				$("#login-page").show();
				$("#login_feedback").html(err);	
			}
	});
}

function authorize_step2(){
	var ajax_actions_path = "<?php echo base_url(); ?>users/ajax_actions";
	$.ajax({
			type: "POST", 
			url: ajax_actions_path,
			dataType: "html",
			data: { 'action':'authorize_step2'},
			success: function(data){
				return true;
			}
	});
	return true;		
}

function acceptance(data){
	
	var message = "<b>AAA</b>";
	$.prompt(message,{ 
		buttons:{Accept:true, Cansel:false},
		submit:function(v,m){
			if(v) {
				if(authorize_step2() && data.auth_success_path!='') 
					location = data.auth_success_path;
				
			} else {
				location = "<?php echo base_url(); ?>users/logout";
			}
		}
	});
}

function forgot_password(){
	var ajax_actions_path = "<?php echo base_url(); ?>users/ajax_actions";
	$.ajax({
			type: "POST", 
			url: ajax_actions_path,
			dataType: "html",
			data: { 'action':'forgot_password',
					'username': $("#username").val()
			},
			beforeSend: function(data){
				$("#forgot_password").hide("blind");
				$("#username").val('');
				$("#msg").html('<img alt="loading..." border="0" src="<?php echo base_url(); ?>images/loading-blue.gif" />');
			},
			success: function(data) {
				$("#msg").html(data);									
		   	},
		   	error: function(data) {
		   		$("#msg").html('');
		   	}
	});
}
