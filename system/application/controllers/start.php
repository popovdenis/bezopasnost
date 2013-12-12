<?php
class Start extends Controller {
	
	function index() {
		
		$home = true;

		if (isset($this->db_session)) {
			$user_id = $this->db_session->userdata('user_id');
			log_message('debug', 'AUTORIZE 4. start. user_id: '.var_export($user_id, true));
			if ($user_id) {
				$this->load->model('user_mdl','user');
				$user = $this->user->get_users($user_id);
				log_message('debug', 'AUTORIZE 5. start. user: '.var_export($user, true));
				if(!$user || !is_array($user)) {
					log_message('debug', 'AUTORIZE. OK ');
					$this->load->view('_login', null);
					$home = false;
				} else {
					log_message('debug', 'AUTORIZE. NO ');
					redirect(base_url().'admin/home', 'admin');
				}
			}
		}

		if ($home) {
			redirect(base_url().'admin/home', 'admin');
		}
	}
}

?>