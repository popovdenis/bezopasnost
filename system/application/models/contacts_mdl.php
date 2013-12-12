<?php
	class Contacts_mdl extends Model {
		
		function __constructor() {
			parent::Model();
		}
		
		function get_contacts(){
			try {
				$query = "select * from contacts";				
				$query = $this->db->query($query);
				
				if(!$query)
					throw new Exception($this->db->_error_message());				
				return $query->row();
					
			} catch(Exception $e){
				log_message('error', $e->getMessage().'\n'.$e->getFile().'\n'.$e->getCode());
			}
			return false;
		}
		
		function update_contacts($info){
			try {
				if(empty($info))
					throw new Exception('Inputed data is empty');
				
				$contacts = $this->get_contacts();
				
				if(!$contacts || empty($contacts)) {
					if(!$this->db->insert('contacts', $info))
						throw new Exception($this->db->_error_message());
					return $this->db->insert_id();
					
				} else {
					$this->db->where('contact_id', $contacts->contact_id);
					if(!$res = $this->db->update('contacts', $info))
						throw new Exception($this->db->_error_message());	
				}				
				return true;
					
			} catch(Exception $e){
				log_message('error', $e->getMessage().'\n'.$e->getFile().'\n'.$e->getCode());
			}
			return false;
		}
	}
?>