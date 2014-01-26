<?php
class Help extends Controller {

	function __construct()
	{
		parent::Controller();
		$user_id = $this->db_session->userdata('user_id');
		$user_role = $this->db_session->userdata('user_role');
		$user_role = null;
		if (empty($user_id) || empty($user_role)) {
			$data = 5;
			$this->output->set_output($data);
		}
	}
	
	function get_records($question_id = null, $note_category = ''){
		$this->load->model('note_mdl','note');
		
		$questions = $this->note->get_notes($question_id, 'help_question', null, $note_category);	log_message('debug', var_export($questions, true));	
		if($questions && !empty($questions)) {
			foreach ($questions as $question) {
				$answer = $this->note->get_notes(null, 'help_answer', $question->note_id);
				if($answer && is_array($answer)) $answer = $answer[0];
				$question->answer = $answer;
			}
		}
		return $questions;
	}
	
	function ajax_actions() {

		$action = $this->input->post('action');

		$data = '';
		switch ($action ) {
			case "get_records":
				$question_id = $this->input->post('question_id');
				
				$notes = $this->get_records($question_id);
				
				if(empty($question_id)) {
					$values = array();
					$questions_block = "";
					
					$values['questions'] = $notes;
					$values['selected_question_id'] = null;
					$questions_block = $this->load->view('admin/help_question', $values, true);
					
					$data = (Object)array("questions_block" => $questions_block);
					
				} else {
					if(!empty($notes)) {
						if(is_array($notes)) $notes = $notes[0];
						$data = array(
							"question_id" => $notes->note_id,
							"question" => $notes->note							
						);
						$answer_id = null;
						$answer = null;
						if(!empty($notes->answer)) {
							$answer_id = $notes->answer->note_id;
							$answer = $notes->answer->note;
						}
						$data['answer_id'] = $answer_id;
						$data['answer'] = $answer;
						
						$data = (Object)$data;
					}					
				}
				$data = json_encode($data);
			break;
				
			case "save_records":
				$question_id = $this->input->post('question_id');
				$question = $this->input->post('question');
				$answer_id = $this->input->post('answer_id');
				$answer = $this->input->post('answer');
				$to_page = $this->input->post('to_page');
				log_message('debug', 'help'.var_export($_POST, true));
				$this->load->model('note_mdl','note');
				
				$note_category_id = null;
				if(!empty($to_page)) {
					$note_category = $this->note->get_note_category(null, $to_page);
					if($note_category) $note_category_id = $note_category->note_category_id;
				}
				
				$note_question_type = $this->note->get_note_type("help_question");
				$note_answer_type = $this->note->get_note_type("help_answer");
				
				if(empty($question_id)) {
					$question_new = array(
						"note" => $question,
						"note_date" => date("Y-m-d"),
						"note_parent" => 0,
						"note_type_id" => $note_question_type->note_type_id
					);
					if($note_category_id) $question_new['note_category_id'] = $note_category_id;
					$question_id = $this->note->add_note($question_new);
					
					$answer_new = array(
						"note" => $answer,
						"note_date" => date("Y-m-d"),
						"note_parent" => $question_id,
						"note_type_id" => $note_answer_type->note_type_id
					);
					if($note_category_id) $answer_new['note_category_id'] = $note_category_id;
					$answer_id = $this->note->add_note($answer_new);
					
					$notes = $this->get_records($question_id);
					$values['questions'] = $notes;
					$values['selected_question_id'] = null;
					$questions_block = $this->load->view('admin/help_question', $values, true);
					
					$data = (Object)array(
						"question_block" => $questions_block,
						"question_id" => $question_id,
						"question" => $question,
						"answer" => $answer,
						"answer_id" => $answer_id
					);
					
				} else {
					$question_up = array(
						"note" => $question,
						"note_type_id" => $note_question_type->note_type_id
					);
					if($note_category_id) $question_up['note_category_id'] = $note_category_id;
					$this->note->update_note($question_id, $question_up);
					
					$this->note->delete_notes(null, $question_id);
					$answer_up = array(
						"note" => $answer,
						"note_parent" => $question_id,
						"note_type_id" => $note_answer_type->note_type_id
					);
					if($note_category_id) $answer_up['note_category_id'] = $note_category_id;
					$answer_id = $this->note->add_note($answer_up);									
					
					$data = (Object)array(						
						"question_id" => $question_id,
						"question" => $question,
						"answer" => $answer,
						"answer_id" => $answer_id
					);									
				}													
				$data = json_encode($data);
			break;
				
			case "delete_record":
				$note_id = $this->input->post('note_id');
				$this->load->model('note_mdl','note');
				$this->note->delete_notes($note_id);
				$this->note->delete_notes(null, $note_id);
				
				$data = true;
				
			break;
			
			case "filter_qeustions":
				$sort_type_value = $this->input->post('sort_type_value');				
				$notes = $this->get_records(null, $sort_type_value);
				
				if(empty($question_id)) {
					$values = array();
					$questions_block = "";
					
					$values['questions'] = $notes;
					$values['selected_question_id'] = null;
					$questions_block = $this->load->view('admin/help_question', $values, true);
					
					$data = (Object)array("questions_block" => $questions_block);
					
				} else {
					if(!empty($notes)) {
						if(is_array($notes)) $notes = $notes[0];
						$data = array(
							"question_id" => $notes->note_id,
							"question" => $notes->note							
						);
						$answer_id = null;
						$answer = null;
						if(!empty($notes->answer)) {
							$answer_id = $notes->answer->note_id;
							$answer = $notes->answer->note;
						}
						$data['answer_id'] = $answer_id;
						$data['answer'] = $answer;
						
						$data = (Object)$data;
					}					
				}
				$data = json_encode($data);
				break;
			
			default:
				break;
		}
		$this->output->set_output($data);
	}
	
	function get_questions_admin_block($template, $question_id=null, $answer_id=null){
		if(empty($template)) return false;

		$template_file = '';
		if($template == 'help_question'){
			$template_file = 'admin/help_question';
		}elseif($template == 'help_answer'){
			$template_file = 'admin/help_answer';
		}
		
		$this->load->model('note_mdl','note');

		$question_list = "";
		$extra = '';
		if($question_id) $extra ='AND note_parent='.$question_id;
		$questions = $this->note->get_notes($answer_id, $template, "n.note_id desc", $extra);
		
		if (!empty($questions))
		{
			if(!$question_id) $question_id = 0;
			
			$data['questions'] = $questions;
			$data['selected_question_id'] = $question_id;
			$data['question_id'] = $question_id;
			$question_list = $this->load->view($template_file, $data, true);
		}//log_message('debug', 'questions   :    '.var_export($question_list,true));
		return $question_list;
	}

	function get_answers_block($user_id, $question_id, $user_role = null){
		if(empty($user_id)) return false;

		$this->load->model('note_mdl','note');

		$question_list = "";
		$question = $this->note->get_notes($question_id, "help_question", "n.note_date desc");

		$answer_list = '';
		if($user_role && $user_role == 'm') {
			$result = array();

			$result['answer_list'] = $answer_list;

			if($question && !empty($question)) $result['answer'] = $question[0]->note;
			else $result['answer'] = "";

			$result['question'] = $question[0]->note;			

			return $result;

		} else {
			return $answer_list;
		}
	}

	function _add_answer_question_block(){
		$str = "";

		$user_id = $this->db_session->userdata('user_id');
		$user_role = $this->db_session->userdata('user_role');

		$data['user_role'] = $user_role;

		$str .= $this->load->view('_add_answer_question', $data, true);

		return $str;
	}

	function _add_answer_question_admin_block(){
		$str = "";

		$user_id = $this->db_session->userdata('user_id');
		$user_role = $this->db_session->userdata('user_role');

		$data['user_role'] = $user_role;

		$str .= $this->load->view('admin/_add_answer_question', $data, true);

		return $str;
	}
	
	function reorder_questions($question_id, $reorder_type) {
		
		if(empty($question_id) || empty($reorder_type)) return false;

		$this->load->model('note_mdl','note');
		
		$res = $this->note->reorder_notes($question_id, $reorder_type);
		
	}
}