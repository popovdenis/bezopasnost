<?php
class Note_mdl extends Model
{
	function __constructor() {
		parent::Model();
	}

	function add_note($note)
	{
		if(empty($note)) return FALSE;

		if( ! array_key_exists('note_date', $note)) $note['note_date'] = date("Y-m-d h:i:s");
		
		$query = $this->db->insert('notes', $note);

		if ( ! $query) return FALSE;
		if ($this->db->affected_rows() > 0){
			return $this->db->insert_id();
		} else return FALSE;
	}

	function update_note($note_id=null, $data, $note_parent_id=null) {

		if (empty($data)) return FALSE;

		$query = " UPDATE notes SET note = '".$data['note']."'";
        if(isset($data['note_category_id']) && !empty($data['note_category_id'])) $query .= ", note_category_id = '".$data['note_category_id']."'";
        $query .= " WHERE ";
		if($note_id) $query .= " note_id = '".$note_id."'";
		elseif($note_parent_id) $query .= " note_parent = '".$note_parent_id."'";
		log_message('debug', $query);
		$res = $this->db->query($query);
		
		return $res;

	}
	
	function get_notes($note_id = null, $note_type = null, $note_parent_id = null, $note_category=null){
		$query = "select
					n.*
				from
					notes n
				left join note_type nt on (nt.note_type_id = n.note_type_id)				
				left join note_categories nc on (nc.note_category_id = n.note_category_id)
				where 1 ";
		if($note_id) $query .= " and n.note_id = '".$note_id."'";
		if($note_type) $query .= " and nt.note_type = '".$note_type."'";
		if($note_parent_id) $query .= " and n.note_parent = '".$note_parent_id."'";
		if($note_category) $query .= " and nc.note_category = '".$note_category."'";
		
		$query .= " group by n.note_id order by n.note_id desc";
		
		$query = $this->db->query($query);
		if ( ! $query) return FALSE;
		return $query->result();
	}
	
	function delete_notes($note_id = null, $parent_note_id = null) {

		if($parent_note_id) $this->db->where('note_parent', $parent_note_id);
		elseif($note_id) $this->db->where('note_id', $note_id);
		else return false;

		$this->db->delete('notes');

		return true;
	}

	function get_note_category($note_category_id = null, $note_category = null) {
		$query = "select * from note_categories where 1 ";

		if($note_category_id) $query .= " and note_category_id = ".clean($note_category_id);
		elseif($note_category) $query .= " and note_category = ".clean($note_category);

		$query .= " order by note_category";

		$query = $this->db->query($query);

		if ( ! $query) return FALSE;

		if($note_category_id || $note_category)
		return $query->row();
		else
		return $query->result();
	}

	function get_note_type($note_type = null){
		$query = "select * FROM note_type";
		if($note_type) $query .= " where note_type=".clean($note_type);
		$query .= " order by note_type";

		$query = $this->db->query($query);

		if ( ! $query) return FALSE;

		if($note_type)
		return $query->row();
		else
		return $query->result();
	}

	function reorder_notes($question_id, $reorder_type){

		// select all questions
		$query = "select
    					n.* 
    				from 
    					notes n, note_type nt 
    				where 
    					n.note_type_id = nt.note_type_id
					and	
						nt.note_type = 'help_question'
					order by 
						note_id desc";

		$query = $this->db->query($query);

		if ( ! $query) return FALSE;
		else {
			$i = 0; $next = null; $prev = null;
			$qeustions = $query->result();
			// find needle question
			foreach ($qeustions as $qeustion) {
				if($qeustion->note_id == $question_id) {

					if(isset($qeustions[$i+1])) $next = $qeustions[$i+1];
					if(isset($qeustions[$i-1])) $prev = $qeustions[$i-1];

					if($reorder_type == "down" && $next) {

						$query = "select * from notes where note_parent=".clean($qeustion->note_id);
						$query = $this->db->query($query);
						if ( ! $query) return FALSE;
						$answers = $query->row();

						$query_next = "select * from notes where note_parent=".clean($next->note_id);
						$query_next = $this->db->query($query_next);
						if ( ! $query_next) return FALSE;
						$answers_next = $query_next->row();

						$data_update = array(
							'to_user_id' => $qeustion->to_user_id,
							'from_user_id' => $qeustion->from_user_id,
							'note_type_id' => $qeustion->note_type_id,
							'note' => $qeustion->note,
							'note_category_id' => $qeustion->note_category_id,
							'note_date' => $qeustion->note_date
						);
						$this->update_note($next->note_id, $data_update);

						if($answers_next) {
							$query = "update notes set note_parent = ".clean($answers->note_parent)." where note_parent = ".clean($answers_next->note_parent). " and note_id=".clean($answers_next->note_id);
							$this->db->query($query);
						}

						$data_update = null;

						$data_update = array(
							'to_user_id' => $next->to_user_id,
							'from_user_id' => $next->from_user_id,
							'note_type_id' => $next->note_type_id,
							'note' => $next->note,
							'note_category_id' => $next->note_category_id,
							'note_date' => $next->note_date
						);
						$this->update_note($qeustion->note_id, $data_update);

						if($answers) {
							$query = "update notes set note_parent = ".clean($answers_next->note_parent)." where note_parent = ".clean($answers->note_parent). " and note_id=".clean($answers->note_id);
							$this->db->query($query);
						}

						unset($data_update);

					} elseif ($reorder_type == "up" && $prev) {

						$query = "select * from notes where note_parent=".clean($qeustion->note_id);
						$query = $this->db->query($query);
						if ( ! $query) return FALSE;
						$answers = $query->row();

						$query_prev = "select * from notes where note_parent=".clean($prev->note_id);
						$query_prev = $this->db->query($query_prev);
						if ( ! $query_prev) return FALSE;
						$answers_prev = $query_prev->row();

						$data_update = array(
							'to_user_id' => $qeustion->to_user_id,
							'from_user_id' => $qeustion->from_user_id,
							'note_type_id' => $qeustion->note_type_id,
							'note' => $qeustion->note,
							'note_category_id' => $qeustion->note_category_id,
							'note_date' => $qeustion->note_date
						);
						$this->update_note($prev->note_id, $data_update);

						if($answers_prev) {
							$query = "update notes set note_parent = ".clean($answers->note_parent)." where note_parent = ".clean($answers_prev->note_parent). " and note_id=".clean($answers_prev->note_id);
							$this->db->query($query);
						}

						$data_update = null;

						$data_update = array(
							'to_user_id' => $prev->to_user_id,
							'from_user_id' => $prev->from_user_id,
							'note_type_id' => $prev->note_type_id,
							'note' => $prev->note,
							'note_category_id' => $prev->note_category_id,
							'note_date' => $prev->note_date
						);
						$this->update_note($qeustion->note_id, $data_update);

						if($answers) {
							$query = "update notes set note_parent = ".clean($answers_prev->note_parent)." where note_parent = ".clean($answers->note_parent). " and note_id=".clean($answers->note_id);
							$this->db->query($query);
						}

						break;
					}
					return true;
				}
				$i++;
			}
			return true;
		}
	}
	
	/**
	 * change_status_note
	 *
	 * change status note
	 *
	 * @author  Popov
	 * @class   Note_mdl
	 * @access  public
	 * @param   int     $note_id  
	 * @param   string  $status 
	 * @return  boolean  
	 */
	function change_status_note($note_id, $status) {
		if(empty($note_id) || ($status != "hidden" && $status != "visible")) return false;
		
		$data_update = array(
			'note_status' => $status
		);
		return $this->update_note($note_id, $data_update);
	}
	
}
/* End of file */
