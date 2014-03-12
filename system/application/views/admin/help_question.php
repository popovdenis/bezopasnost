<?php
	$str = "";
	$selected = "";
	foreach ($questions as $question)
	{
		if($question->note_id == $selected_question_id) $selected = "selected";
		else $selected = "";
		$str .= '<div id="question_'.$question->note_id.'">
			<a href="#" id="question_a_'.$question->note_id.'" class="questionsRowAdmin '.$selected.'" onclick="get_records(\''.$question->note_id.'\');"><span id="question_note_'.$question->note_id.'" style="font-weight:bold;">'.$question->note.'</span>
				<div class="right" style="width:50px;"><img onclick="if(confirm(\'Are you sure you want to delete question?\')) delete_record(\''.$question->note_id.'\');" src="'.base_url().'images/trashhold.gif" alt=""/>
				<img class="marTop4" title="reorder down" alt="eorder down" onclick="reorder_question(\''.$question->note_id.'\', \'down\');" src="'.base_url().'images/reorder-down.gif" alt=""/>
				<img class="marTop4" title="reorder up" alt="reorder up" onclick="reorder_question(\''.$question->note_id.'\', \'up\');" src="'.base_url().'images/reorder-up.gif" alt=""/></div>
			</a>
		</div>';
	}
	echo $str;
?>