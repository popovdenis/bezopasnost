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
    $("#msg").html(""); $(id).toggle("blind");
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
            if(data.status<=0) {
                err = data.login_err+'<br/>'+data.password_err;
                if( data.login_err!='' ) $("#login_email").css({'background-color':'#eecccc'});
                if( data.password_err!='' ) $("#password").css({'background-color':'#eecccc'});
            }
            else if(data.status==1) {
                location = data.auth_success_path;
            }
            else if(data.status==2) {
                acceptance(data);
            }
        },
        complete:  function(data) {
            if (err=='') return;
            $("#login_process").hide();
            $("#login_feedback").html(err);
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
