var base_url = "http://" + window.location.hostname + "/";
var ajax_actions_path = base_url + "users/ajax_actions";

$(document).ready(function() {
    $("#login_email, #password").focus(function(){ $(this).css({'background-color':'#ffffff'}); });
    $("#login_email, #password").bind("keypress", function(event){
        if(event.keyCode == 13) {
            event.preventDefault();
            event.stopPropagation();
            authorize();
        }
    });
});

function show_form(id){
    $("#msg").html("");
	$('#' + id).toggle("blind");
}

function authorize() {
    var err = '';
    $.ajax({
        type: "POST", url: ajax_actions_path, dataType: "json",
        data: { 'action':'authorize', 'login': $("#login_email").val(), 'password': $("#password").val() },
        beforeSend: function(data){
            $("#login_process").show();
            $("#login_process").html('<img alt="loading..." border="0" src="'+ base_url +'images/loading-blue.gif" />');
        },
        success: function(data) {
            $("#login_process").hide();
            if (data.status == 1) {
                window.location.href = base_url + 'admin';
            } else {
                $("#login_email").css({'background-color':'#eecccc'});
                $("#password").css({'background-color':'#eecccc'});
            }
        }
    });
}

function authorize_step2() {
    $.ajax({
        type: "POST", url: ajax_actions_path, dataType: "html",
        data: { 'action':'authorize_step2'},
        success: function(data){ return true; }
    });
    return true;
}

function acceptance(data){ /* not used currently */ }

function forgot_password() {
    $.ajax({
        type: "POST", url: ajax_actions_path, dataType: "html",
        data: { 'action':'forgot_password', 'username': $("#username").val() },
        beforeSend: function(data){
            $("#forgot_password").hide("blind");
            $("#username").val('');
            $("#msg").html('<img alt="loading..." border="0" src="' + base_url + 'images/loading-blue.gif" />');
        },
        success: function(data) { $("#msg").html(data); },
        error: function(data) { $("#msg").html(''); }
    });
}
