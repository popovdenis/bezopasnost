<?php
	$str = "";
	$selected = "";
	$editable = "";
	foreach ($questions as $question)
	{
		$str .= '<span id="answer_title" class="'.$editable.'" name="note">'.$question->note.'</span>
		<span style="display: none;" name="note_id">'.$question->note_id.'</span>
		<span style="display: none;" name="question_id">'.$question_id.'</span>';
	}
	echo $str;
?>