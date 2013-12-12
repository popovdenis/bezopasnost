<?php

class User_mdl extends Model {
	
    const PRIVATE_KEY = 'Popov';

	function get_user_by_login($login){
		if(empty($login)) return false;
		
		$query = "select u.*
				where 
					u.user_login=".clean($login)." 
				group by user_id";

    	$query = $this->db->query($query);
	
		if ( ! $query) return FALSE;
		else return $query->row();
    }
    
	function get_user_id_by_email ($email){
    	$query = $this->db->query('select user_id from users where email="'.$email.'"');
		
    	if ($query->num_rows() > 0){
			$rs = $query->result();
			return $rs[0]->user_id;
		}
    }
    
	function get_group_id_by_name ($group_name){
    	$query = $this->db->query("select id from khacl_aros where name = ".clean($group_name));
		
    	if ($query->num_rows() > 0){
			$rs = $query->result();
			return $rs[0]->group_id;
		}
    }
    
    function pre_activate($login, $email, $language, $activation_code){
    	$registration_ip = $_SERVER['REMOTE_ADDR'];
    	$group_id = $this->get_group_id_by_name('users');
		$query = 'insert into users (login, email, activation_code, registration_date, registration_ip, group_id, language) 
				  values ('.clean($login).', '.clean($email).', '.clean($activation_code).', now(), '.clean($registration_ip).', '.clean($group_id).', '.clean($language).')';
    	$query = $this->db->query($query);
    	return $this->db->insert_id();
    }
    
	function get_userdata_by_activation_info ($activation_user_id, $activation_code){
		$query = $this->db->query('select login, email from users where user_id = '.clean($activation_user_id).' and activation_code='.clean($activation_code));
			
    	if ($query->num_rows() > 0){
			$rs = $query->result();
			return array('login'=>$rs[0]->login,'email'=>$rs[0]->email);
		}
		else{
			return false;
		}
    }
    
    function get_users($user_id = null, $extra='', $order_by = 'user_login', $limit = null){
    	
    	$query = "select * from users ";
    	if($user_id) $query .= ' where user_id='.clean($user_id);
    	if($user_id && $extra) $query .= " and ";
    	
    	$query .= " ".$extra." ";
    	$query .= "order by ".$order_by;
    	if($limit) $query .= " limit ".$limit;
    	
		$query = $this->db->query($query);
	
		if ( ! $query) return FALSE;
		else return $query->result();
    }    
    
	function get_user_by_email($email) {
		$query = "select c.client_id, u.* from
			cf_users u, cf_clients c, cf_user_map um where
			um.user_id = u.user_id and um.client_id = c.client_id and
			(um.user_role = 'su' or um.user_role = 'u') and
			(u.user_email1 = ? or u.user_email2 = ?)
			order by u.registration_date";
		if (!($res = $this->db->query($query, array($email, $email)))) {
			throw new Exception('Query failed');
		}
		if ($user = $res->row()) {
			return $user;
		}
		return false;
	}

	function get_user_by_real_id($userId) {
		$query = "select c.client_id, u.* from
			cf_users u, cf_clients c, cf_user_map um where
			um.user_id = u.user_id and um.client_id = c.client_id and
			(um.user_role = 'su' or um.user_role = 'u') and
			u.user_id = ? order by u.registration_date";
		if (!($res = $this->db->query($query, array($userId)))) {
			throw new Exception('Query failed');
		}
		if ($user = $res->row()) {
			return $user;
		}
		return false;
	}

	function get_users_by_id($ids)
	{
		if (!count($ids)) {
			$ids = array(0);
		}
		$set = join(', ', $ids);
		$res = $this->db->query('select * from cf_users ' . "where user_id in ($set) order by user_id");
		if (!$res) {
			throw new Exception('Query failed');
		}
		return $res;
	}
    
    /**
	 * Generate random 7-digit password (l and 1 not used in generation for better readability)
	 * @return string $password  
 	*/
	function create_random_password() {
	    $chars = "abcdefghijkmnopqrstuvwxyz023456789";
	    srand((double)microtime()*1000000);
	    $i = 0;
	    $pass = '' ;
	    while ($i <= 7) {
	        $num = rand() % 33;
	        $tmp = substr($chars, $num, 1);
	        $pass = $pass . $tmp;
	        $i++;
	    }
	    return $pass;
	}

    function activate($activation_user_id, $activation_code) {
    	$new_password = $this->create_random_password();
		$query = 'update users u, khacl_aros g set 
					u.password=md5('.$this->db->escape($new_password) . self::PRIVATE_KEY .'),
					u.activation_code="",
					u.group_id=g.id  
					where g.name="reg_users" 
					and u.user_id = '.clean($activation_user_id).' and u.activation_code='.clean($activation_code);
    	$query = $this->db->query($query);
    	
    	if ($this->db->affected_rows() > 0) return $new_password;
    	else return FALSE;
    }
    
	function authorize($login, $password){
		$query = "select u.*
				from users u
				where 
					u.user_login=".clean($login)." 
				and u.user_password=md5(".$this->db->escape($password . self::PRIVATE_KEY) .") 
				and (u.user_status='a' or u.user_status is null)
				and (u.user_role='a' or u.user_role='e')
				group by user_id";
		
    	$query = $this->db->query($query);
    	if ($query->num_rows() > 0){
			$rs = $query->result();
			$user = $rs[0];

			if ($user->user_id != '') {
				$query = 'update users set last_login_date=now() where user_id = ?';
				$this->db->query($query, array($user->user_id));
				
				$user->last_login_date = date("Y-m-d");
			}

			return $user;
		}
		else return 0;
    }
	
	function update_user($user_id, $data){
		if ( empty($data) || ! is_array($data) || empty($user_id) ) return FALSE;
		
		$user_fields = array('user_login',       
							'user_password',    
							'alias',              
							'user_email',      
							'activation_code',      
							'first_name',
							'last_name',   
							'registration_date',     
							'last_login_date',     
							'user_role');
		$user_data = array();
		foreach($data as $key=>$value) {
			if (in_array($key, $user_fields)) $user_data[$key] = $value;
		}
		$res = true;
		if ( ! empty($user_data)) {
			$this->db->where('user_id', $user_id);
			$res = $res && $this->db->update('users', $user_data);
		}
		return $res;		
	}
	
	function add_user($data_user) {
		if (empty($data_user)) return FALSE;
		
    	$this->db->insert('users', $data_user); 
    	if ($this->db->affected_rows() > 0) return $this->db->insert_id();
		return false;
	}
}