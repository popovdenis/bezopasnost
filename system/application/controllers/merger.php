<?php
	/**
	 * Class Merger
	 *
	 * class_definition
	 *
	 * @author   Popov
	 * @access   public
	 * @package  Merger.class.php
	 * @created  Tue Apr 13 16:22:05 EEST 2010
	 */
	class Merger extends Controller 
	{
		private $DB2 = null;
		/**
		 * Constructor of Merger
		 *
		 * @access  public
		 */
		function __construct() {
			parent::Controller();
		}
		
		function index(){
			/*$regions = $this->get_geo_regions();
			if(!empty($regions)) {
				foreach ($regions as $region) {
					
				}
			}
			echo "Regions completed";*/
			
			/*$query = "select
				count(city.city_name) as count
			FROM
				geo_cities city
			inner join geo_regions region on region.region_id=city.rid
			inner join geo_countries country on country.country_id=region.cid";
			$result = $this->db->query($query);
			if ( ! $result) return FALSE;
			$cities_count = $result->row();
			
			if($cities_count->count > 0) {
				for($i = 1889; $i < $cities_count->count; $i++) {
					log_message('debug', "iteration: ".$i);
					$city = $this->get_geo_cities(null, null, null, $i);
					if(!empty($city)){
						$_city = $this->get_cities($city->city_name);
						$city_id = null;
						if(empty($_city)) {
							$_region = $this->get_regions($city->region_name);
							$_country = $this->get_countries($city->country_name);
							if($_region && $_country) {
								$_region = $_region[0];
								$_country = $_country[0];
								
								$city_id = $this->insert_city($city->city_name, $_region->id, $_country->id);
							}						
						}
					}
				}
			}*/
			
			$cities = $this->get_geo_cities();

			$i = 0;
			if(!empty($cities)){
				foreach ($cities as $city) {
					log_message('debug', "iteration: ".$i);
					$_city = $this->get_cities($city->city_name);					
					$city_id = null;
					if(empty($_city)) {
						$_region = $this->get_regions($city->region_name);
						$_country = $this->get_countries($city->country_name);
						if($_region && $_country) {
							$_region = $_region[0];
							$_country = $_country[0];
							
							$city_id = $this->insert_city($city->city_name, $_region->id, $_country->id);
						}						
					}
					$i++;
				}
			}
			echo "All cities, regions and countries have been added";
		}
		
		function get_geo_countries($country=null){
			$query = "select * from geo_countries ";
			if(!empty($country)) $query .= " where geo_countries.country_name='".$country->country_name."'";
			
			$result = $this->db->query($query);
			if ( ! $result) return FALSE;
			return $result->result();
		}
		
		function get_geo_regions($region=null, $country_id=null){
			$query = "select gr.*
			from geo_regions gr
			inner join geo_countries gc on gr.cid = gc.country_id where 1";
			if(!empty($region)) $query .= " and gr.region_name='".$region->region_name."'";
			if(!empty($country_id)) $query .= " and gr.cid='".$country_id."'";
			
			$result = $this->db->query($query);
			if ( ! $result) return FALSE;
			return $result->result();
		}
		
		function get_geo_cities($city=null, $region_id=null, $country_id=null){
			$query = "select
				city.city_name,region.region_id, region.region_name,country.country_id, country.country_name
			FROM
				geo_cities city
			inner join geo_regions region on region.region_id=city.rid
			inner join geo_countries country on country.country_id=region.cid where 1 ";
			
			if(!empty($city)) $query .= " and city.city_name='".$city->city_name."'";
			if(!empty($region_id)) $query .= " and city.rid='".$region_id."'";
			if(!empty($country_id)) $query .= " and city.cid='".$country_id."'";
			
//			$query .= " group by city.city_id";
			$query .= " limit 100000, 200000";
			$result = $this->db->query($query);
			if ( ! $result) return FALSE;
			return $result->result();			
		}
		
		function get_countries($country_name=null){
			$query = "select * from country";
			
			if(!empty($country_name)) $query .= " where country.country='".$country_name."'";
			
			$result = $this->db->query($query);
			if ( ! $result) return FALSE;
			return $result->result();
		}
		
		function get_regions($region_name, $country_id=null){
			$query = "select r.*
			from region r
			inner join country_region cr on cr.id_region=r.id
			inner join country c on c.id=cr.id_country where 1 ";
			
			if(!empty($region_name)) $query .= " and r.region='".$region_name."'";
			if(!empty($country_id)) $query .= " and cr.id_country='".$country_id."'";
			
			$query .= " group by r.id";
			
			$result = $this->db->query($query);
			if ( ! $result) return FALSE;
			return $result->result();
		}
		
		function get_cities($city_name=null, $region_id=null, $country_id=null){			
			$query = "select
				city.city,
				region.id as region_id,
				region.region,
				country.id as country_id,
				country.country
			FROM
				city
			inner join region_city on region_city.id_city=city.id
			inner join region on region.id=region_city.id_region
			inner join country_region on country_region.id_region=region.id
			inner join country on country.id=country_region.id_country where 1 ";
			
			if(!empty($city_name)) $query .= " and city.city='".$city_name."'";
			if(!empty($region_id)) $query .= " and region.id='".$region_id."'";
			if(!empty($country_id)) $query .= " and country.id='".$country_id;
			
//			$query .= " group by city.id";
			
			$result = $this->db->query($query);
			if ( ! $result) return FALSE;
			return $result->result();
		}
		
		function insert_country($country){
			$region_data = array(
				'country' => $country->country_name,
				'countrycode' => 0
			);			
			$this->db->insert('country', $region_data);
			log_message('debug', "country $country->country_name has been added");
			
			return $this->db->insert_id();
		}
		
		function insert_country_diff($country, $country_id){
			$country_new = $this->get_countries($country_id);
			if($country_new) {
				$country_new = $country_new[0];
				
				$country_data = array(
					'country_id_old' => $country->country_id,
					'country_id_new' => $country_new->id,
					'country_name_old' => $country->country_name,
					'country_name_new' => $country_new->country
				);			
				$this->db->insert('country_diff', $country_data);
			}
		}
		
		function insert_region($region, $country_id){
			$region_data = array(
				'region' => $region->region_name
			);			
			$this->db->insert('region', $region_data);
			log_message('debug', "region $region->region_name has been added");
			
			$region_id = $this->db->insert_id();
			
			$region_data = array(
				'id_region' => $region_id,
				'id_country' => $country_id
			);
			$this->db->insert('country_region', $region_data);
			log_message('debug', "region $region->region_name in country_region has been added");
			
			return $region_id;
		}
		
		function insert_region_diff($region, $region_id){
			$region_new = $this->get_regions($region_id);
			if($region_new) {
				$region_new = $region_new[0];				
				$region_data = array(
					'region_id_old' => $region->region_id,
					'region_id_new' => $region_new->id,
					'region_name_old' => $region->region_name,
					'region_name_new' => $region_new->region
				);			
				$this->db->insert('region_diff', $region_data);
			}
		}
		
		function insert_city($city_name, $region_id){
			$city_data = array(
				'city' => $city_name,
				'telcode' => 0,
				'timezone' => 0
			);			
			$this->db->insert('city', $city_data);
			log_message('debug', "city $city_name has been added");
			
			$city_id = $this->db->insert_id();
			
			$city_data = array(
				'id_city' => $city_id,
				'id_region' => $region_id
			);
			$this->db->insert('region_city', $city_data);
			log_message('debug', "city $city_name in region_city has been added");
			
			return $city_id;
		}
		
		function insert_city_diff($city, $city_id){
			$city_new = $this->get_cities($city_id);
			if($city_new) {
				$city_new = $city_new[0];				
				$city_data = array(
					'city_id_old' => $country->city_id,
					'city_id_new' => $city_new->id,
					'city_name_old' => $country->city_name,
					'city_name_new' => $city_new->city
				);			
				$this->db->insert('city_diff', $city_data);
			}
		}
		
		// MERGER'S USERS
		function index2(){			
			$this->DB2 = $this->load->database('temp', TRUE, TRUE);
			
			$res = $this->db->query("SHOW columns FROM photos LIKE 'photo_id_old'");
			if ($res) {
				$res = $res->result();
				if(empty($res)) $this->db->query('ALTER TABLE photos ADD photo_id_old int(11) AFTER photo_id');
			}
			$res = $this->db->query("SHOW columns FROM photos LIKE 'score'");
			if ($res) {
				$res = $res->result();
				if(empty($res)) $this->db->query('ALTER TABLE photos ADD score int(11)');
			}
			$result1 = $this->get_users(null, $this->DB2);			

			if(!empty($result1)) {
				foreach ($result1 as $user) {					
					$db2_user = $this->get_users($user);
					
					// add user
					$user_id = null;
					if(empty($db2_user)) {
						$user_id = $this->insert_user($user);
					}
					else {
						$user_id = $user->user_id;					
					}					
					// add user's photos
					$user_photos = $this->get_user_photos($user->user_id);
					if(!empty($user_photos)) {						
						$this->insert_photo($user_photos, $user_id);						
					}
				}
				echo "All users have been added with their photos";
			} 
		}
		
		function check_photos(){
			$photo_path = dirname(BASEPATH)."/uploads/photos/temp/";
			
			$query = "SELECT SQL_CALC_FOUND_ROWS DISTINCT p.*, count(commented_object_id) as comcnt, login, rating_totals.see_cnt, rating_totals.balls,if( p.md_width >p.md_height,true,false) as land 
						FROM photos p LEFT JOIN rating_totals ON (p.photo_id = rating_totals.on_what_id 
						AND rating_totals.on_what='foto')
						LEFT JOIN comments_tree ct ON (ct.commented_object_id = p.photo_id AND ct.commented_object_type = 'photo'),
						competition_photos cp, competitions c, users u
						WHERE p.moderation_state >= 0
                        AND c.competition_id = cp.competition_id
                        AND u.user_id = p.user_id
						AND cp.photo_id = p.photo_id
						AND c.competition_id = 1 GROUP BY p.photo_id ORDER BY p.title";
			
			$qresult = $this->db->query($query);
			if ( ! $qresult) return FALSE;
			$result = $qresult->result();
			foreach ($result as $photo) {
				$file_head = photo_location().date('m', strtotime($photo->date_added)).'/'.$photo->photo_id.'-head'.$photo->extension;
				$file_lg = photo_location().date('m', strtotime($photo->date_added)).'/'.$photo->photo_id.'-lg'.$photo->extension;
				$file_md = photo_location().date('m', strtotime($photo->date_added)).'/'.$photo->photo_id.'-md'.$photo->extension;
				$file_sm = photo_location().date('m', strtotime($photo->date_added)).'/'.$photo->photo_id.'-sm'.$photo->extension;
				
				$image_head = file_get_contents($file_head);
				if(!empty($image_head)) {
                	$path = $photo_path.$photo->photo_id.'-head'.$photo->extension;
               		file_put_contents($path, $image_head);
				}
               	
               	$image_lg = file_get_contents($file_lg);
               	if(!empty($image_lg)) {
               		$path = $photo_path.$photo->photo_id.'-lg'.$photo->extension;							
					file_put_contents($path, $image_lg);
               	}
				
				$image_md = file_get_contents($file_md);
				if(!empty($image_md)) {
               		$path = $photo_path.$photo->photo_id.'-md'.$photo->extension;							
					file_put_contents($path, $image_md);
				}
				
				$image_sm = file_get_contents($file_sm);
				if(!empty($image_sm)) {
               		$path = $photo_path.$photo->photo_id.'-sm'.$photo->extension;							
					file_put_contents($path, $image_sm);
				}
			}
		}
		
		function get_photos(){
			$this->load->helper('file');
			
			$result1 = $this->get_users();
			$photo_path = dirname(BASEPATH)."/uploads/photos/from_kz/".date("m")."/";
			
			$user_photos = "";//$this->get_user_photos();
			
			$competition = modules::run('competition_mod/competition_ctr/get_competition');
			if($competition) {
				if(is_array($competition)) $competition = $competition[0];
				$competition->photos = modules::run('competition_mod/competition_ctr/get_competition_photos', $competition->competition_id, 0, 1, 1);
				$user_photos = $competition->photos;
			}			
			
			if(!empty($user_photos)) {
				foreach ($user_photos as $photo) {
					$file_head = "http://pinpix.prototypeboom.com/uploads/photos/".date('m', strtotime($photo->date_added)).'/'.$photo->photo_id.'-head'.$photo->extension;
					$file_lg = "http://pinpix.prototypeboom.com/uploads/photos/".date('m', strtotime($photo->date_added)).'/'.$photo->photo_id.'-lg'.$photo->extension;
					$file_md = "http://pinpix.prototypeboom.com/uploads/photos/".date('m', strtotime($photo->date_added)).'/'.$photo->photo_id.'-md'.$photo->extension;
					$file_sm = "http://pinpix.prototypeboom.com/uploads/photos/".date('m', strtotime($photo->date_added)).'/'.$photo->photo_id.'-sm'.$photo->extension;
					
					$image_head = file_get_contents($file_head);
					if(!empty($image_head)) {
                    	$path = $photo_path.$photo->photo_id.'-head'.$photo->extension;
                   		file_put_contents($path, $image_head);
					}
                   	
                   	$image_lg = file_get_contents($file_lg);
                   	if(!empty($image_lg)) {
                   		$path = $photo_path.$photo->photo_id.'-lg'.$photo->extension;							
						file_put_contents($path, $image_head);
                   	}
					
					$image_md = file_get_contents($file_md);
					if(!empty($image_md)) {
                   		$path = $photo_path.$photo->photo_id.'-md'.$photo->extension;							
						file_put_contents($path, $image_md);
					}
					
					$image_sm = file_get_contents($file_sm);
					if(!empty($image_sm)) {
                   		$path = $photo_path.$photo->photo_id.'-sm'.$photo->extension;							
						file_put_contents($path, $image_sm);
					}
				}
			}
		}
		
		function insert_user($user){
			$user_data = array(
				'login' => $user->login,
				'password' => $user->password,
				'email' => $user->email,
				'email_for_change' => $user->email_for_change,
				'secret_question_id' => $user->secret_question_id,
				'secret_question_answer' => $user->secret_question_answer,
				'activation_code' => $user->activation_code,
				'first_name' => $user->first_name,
				'last_name' => $user->last_name,
				'birthdate' => $user->birthdate,
				'country_iso' => $user->country_iso,
				'country_id' => $user->country_id,
				'city_id'  => $user->city_id,
				'region_id'  => $user->region_id,
				'google_map_point'  => $user->google_map_point,
				'gender'  => $user->gender,
				'registration_date' => $user->registration_date,
				'registration_ip' => $user->registration_ip,
				'last_login_date' => $user->last_login_date,
				'last_login_ip' => $user->last_login_ip,
				'about' => $user->about,
				'group_id' => $user->group_id,
				'interests' => $user->interests,
				'avatar' => $user->avatar,
				'predefined_avatar' => $user->predefined_avatar,
				'moderation_state' => $user->moderation_state,
				'group_id' => $user->group_id,
				'language' => $user->language
			);
			
			$this->db->insert('users', $user_data);
			
			log_message('debug', "user $user->login has been added");
			
			return $this->db->insert_id();
		}
		
		function insert_photo($photo_array, $user_id){
			try {
				if(empty($photo_array) || empty($user_id))
					throw new Exception("data are empty");
					
				foreach ($photo_array as $photo) {
					$photo_data = array(
						'photo_id_old' => $photo->photo_id,
						'title' => $photo->title,
						'user_id' => $user_id,
						'date_added' => date("Y-m-d H:i:s"),
						'date_modified' => date("Y-m-d H:i:s"),
						'added_from_ip' => $photo->added_from_ip,
						'lg_width' => $photo->lg_width,
						'lg_width' => $photo->lg_width,
						'lg_height' => $photo->lg_height,
						'md_width' => $photo->md_width,
						'sm_width' => $photo->sm_width,
						'sm_height' => $photo->sm_height,
						'size' => $photo->size,
						'extension' => $photo->extension,
						'exif_camera' => $photo->exif_camera,
						'exif_shooting_date' => $photo->exif_shooting_date,
						'exif_focal_length' => $photo->exif_focal_length,
						'exif_exposure_time' => $photo->exif_exposure_time,
						'exif_aperture' => $photo->exif_aperture,
						'exif_focus_dist' => $photo->exif_focus_dist,
						'google_map_point' => $photo->google_map_point,
						'view_allowed' => $photo->view_allowed,
						'erotic_p' => $photo->erotic_p,
						'extension' => $photo->extension,
						'moderation_state' => $photo->moderation_state,
						'description' => $photo->description,
						'score' => $photo->score
					);
					if(!$this->db->insert('photos', $photo_data))
						throw new Exception($this->db->_error_message());
						
					$photo_id = $this->db->insert_id();
					
					$photo->photo_id_old = $photo->photo_id;
					$photo->photo_id = $photo_id;
					
					$photo_category_map = array(
						'photo_id' => $photo_id,
						'category_id' => '96'
					);
					if(!$this->db->insert('photo_category_map', $photo_category_map))
						throw new Exception($this->db->_error_message());
					
					$this->change_photos($photo);					
				}
				return true;
				
			} catch(Exception $e){
				log_message('error', $e->getMessage().'\n'.
									$e->getFile().'\n'.
									$e->getCode());
			}
			return false;
		}
	
		function get_users($user=null, $prefix_db=null){
			$query = "select * from users ";
			if(!empty($user)) $query .= " where login=".clean($user->login)." and email=".clean($user->email);
			
			if($prefix_db) $qresult = $prefix_db->query($query);
			else $qresult = $this->db->query($query);
			if ( ! $qresult) return FALSE;
			if($user) return $qresult->row();
			else return $qresult->result();
		}
		
		function get_user_photos($user_id=null, $photo_id=null){
			$query = "SELECT p.*, count(commented_object_id) as comcnt, login, rating_totals.see_cnt, rating_totals.balls,if( p.md_width >p.md_height,true,false) as land 
						FROM photos p LEFT JOIN rating_totals ON (p.photo_id = rating_totals.on_what_id 
						AND rating_totals.on_what='foto')
						LEFT JOIN comments_tree ct ON (ct.commented_object_id = p.photo_id AND ct.commented_object_type = 'photo'),
						competition_photos cp, competitions c, users u
						WHERE p.moderation_state >= 0
		                AND c.competition_id = cp.competition_id
		                AND u.user_id = p.user_id
						AND cp.photo_id = p.photo_id
						AND c.competition_id = 1 ";
			if($photo_id) $query .= " and p.photo_id=".clean($photo_id);
			if($user_id) $query .= " and p.user_id=".clean($user_id);
			$query .= " GROUP BY p.photo_id";
			
			$qresult = $this->DB2->query($query);
			if ( ! $qresult) return FALSE;
			if($photo_id) return $qresult->row();
			else return $qresult->result();
		}
		
		function change_photos($photo){
			$photo_path = dirname(BASEPATH)."/uploads/photos/kz/";
			$photo_path_new = dirname(BASEPATH)."/uploads/photos/04/";
			
			$file_head = $photo_path.$photo->photo_id_old.'-head'.$photo->extension;
			$file_lg = $photo_path.$photo->photo_id_old.'-lg'.$photo->extension;
			$file_md = $photo_path.$photo->photo_id_old.'-md'.$photo->extension;
			$file_sm = $photo_path.$photo->photo_id_old.'-sm'.$photo->extension;
			
			$photos_arr = array();
			if(is_file($photo_path_new.$photo->photo_id.'-head'.$photo->extension)) unlink($photo_path_new.$photo->photo_id.'-head'.$photo->extension);
			if(is_file($file_head)) {
				rename($file_head, $photo_path_new.$photo->photo_id.'-head'.$photo->extension);
				$photos_arr[] = $photo_path_new.$photo->photo_id.'-head'.$photo->extension;
			}
			
			if(is_file($photo_path_new.$photo->photo_id.'-lg'.$photo->extension)) unlink($photo_path_new.$photo->photo_id.'-lg'.$photo->extension);	
			if(is_file($file_lg)) {
				rename($file_lg, $photo_path_new.$photo->photo_id.'-lg'.$photo->extension);
				$photos_arr[] = $photo_path_new.$photo->photo_id.'-lg'.$photo->extension;
			}
			
			if(is_file($photo_path_new.$photo->photo_id.'-md'.$photo->extension)) unlink($photo_path_new.$photo->photo_id.'-md'.$photo->extension);
			if(is_file($file_md)) {
				rename($file_md, $photo_path_new.$photo->photo_id.'-md'.$photo->extension);
				$photos_arr[] = $photo_path_new.$photo->photo_id.'-md'.$photo->extension;
			}
			
			if(is_file($photo_path_new.$photo->photo_id.'-sm'.$photo->extension)) unlink($photo_path_new.$photo->photo_id.'-sm'.$photo->extension);
			if(is_file($file_sm)) {
				rename($file_sm, $photo_path_new.$photo->photo_id.'-sm'.$photo->extension);
				$photos_arr[] = $photo_path_new.$photo->photo_id.'-sm'.$photo->extension;
			}
			
			log_message('debug', "fotos has been copied sucsessfully: ".var_export($photos_arr, true));
		}
		
		/**
		 * Destructor of Merger 
		 *
		 * @access  public
		 */
		function __destruct() {}
		
	}