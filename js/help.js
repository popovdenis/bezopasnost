var base_help_url = "http://" + window.location.hostname + "/";
var actions_help_path = base_help_url + "help/ajax_actions";

function dump(obj, step) {
	if (typeof step == 'undefined') {
		step = -1;
	}
	step++;
	var pad = new Array(2*step).join('   ');
	var str = typeof(obj)+":\n";
	for(var p in obj){
		if (typeof obj[p] == 'object') {
			str += pad+'   ['+p+'] = '+dump(obj[p], step);
		} else {
			str += pad+'   ['+p+'] = '+obj[p]+"\n";
		}
	}
	return str;
}

function set_base_url(url){
   base_help_url = url;
   actions_help_path = base_help_url + "help/ajax_actions";
}

function get_records(question_id){
	if(question_id == undefined) question_id = ""
	
	$.ajax({
		type: "POST", url: actions_help_path, dataType: "json",
		data: {
			'action':'get_records',
			'question_id': question_id
		},
		beforeSend: function(data)
		{
			$("#questions_block").html('<span class="noteSaving"><img alt="loading..." border="0" src="'+base_help_url+'images/loading-blue.gif" /></span>');
			$("#admin_question").val('');
			$("#admin_answer").val('');
			$("#admin_answers_block").html('');
		},
		success: function(data)
		{
			if(question_id == ""){
				$("#admin_questions_block").html(data.questions_block);
				
			} else {				
				$("#admin_question").val(data.question);
				$("#admin_answer").val(data.answer);
				$("#admin_answers_block").html('<span class="questionsRowAdmin colorTextBlue" id="answer" name="note">'+ data.answer +'</span><input type="hidden" id="selected_note_id" name="selected_note_id" value="'+ data.answer_id +'" /><input type="hidden" id="selected_question_id" value="'+ data.question_id +'" name="selected_question_id" />');
			}
		},
		error: function(data)
		{alert('error');}
	});
}

function save_records(){
	var question_selected = $('#selected_question_id').val();	
	var question = $('#admin_question').val();	
	var answer = $('#admin_answer').val();
		
	if(question_selected == undefined) question_selected = 0;
	
	$.ajax({
		type: "POST", url: actions_help_path, dataType: "json",
		data: {
			'action':'save_records',
			'question_id': question_selected,
			'question': $('#admin_question').val(),
			'answer_id': $('#selected_note_id').val(),
			'answer': $('#admin_answer').val(),
			'to_page': $("#help_pages option:selected").val()
		},
		beforeSend: function(data)
		{
			$("#admin_question").val('');
			$("#admin_answer").val('');
		},
		success: function(data)
		{
			if(question_selected == 0) {
				$("#admin_questions_block").prepend(data.question_block);
				$("#admin_answers_block").html('<span class="questionsRowAdmin colorTextBlue" id="answer" name="note">'+ data.answer +'</span><input type="hidden" id="selected_note_id" name="selected_note_id" value="'+ data.answer_id +'" /><input type="hidden" id="selected_question_id" value="'+ data.question_id +'" name="selected_question_id" />');			
			}
			$("#question_note_"+question_selected).text(question);
			$("#answer").text(answer);
		},
		error: function(data)
		{alert('error');}

	});
}

function delete_record(note_id){
	if(note_id == undefined || note_id == "") return;
	
	$.ajax({
		type: "POST", url: actions_help_path, dataType: "html",
		data: {
			'action':'delete_record',
			'note_id': note_id
		},
		beforeSend: function(data)
		{
			
		},
		success: function(data)
		{
			$("#question_"+note_id).remove();
			$("#admin_answers_block").html('');
			$("#admin_question").val('');
			$("#admin_answer").val('');			
		},
		error: function(data)
		{alert('error');}

	});
}

function clear_all(){
	$("#admin_question").val('');
	$("#admin_answer").val('');
	$("#help_question_id").val('');
	$("#admin_answers_block").html('');
	$('.questionsRowAdmin').removeClass('selected');

	return;
}

function filter_qeustions(){
	$.ajax({
		type: "POST", url: actions_help_path, dataType: "json",
		data: {
			'action':'filter_qeustions',
			'sort_type_value': $("#help_pages_filter option:selected").val()
		},
		beforeSend: function(data)
		{
			$("#questions_block").html('<span class="noteSaving"><img border="0" src="'+base_help_url+'images/loading-blue.gif" /></span>');
			$("#admin_question").val('');
			$("#admin_answer").val('');
			$("#admin_answers_block").html('');
			$('#image_qa_separatior').attr('src', base_help_url+'images/loading-blue.gif');
		},
		success: function(data)
		{
			$("#admin_questions_block").html(data.questions_block);						
			$("#admin_question").val("");
			$("#admin_answer").val("");
			$("#admin_answers_block").html('');
			$('#image_qa_separatior').attr('src',base_help_url+'images/help-green-arrow.jpg');
			
		},
		error: function(data)
		{alert('error');}
	});
}
