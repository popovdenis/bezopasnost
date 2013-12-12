<?php
	/**
	 * Class Attachment
	 *
	 * class for attachment
	 *
	 * @author   Popov
	 * @access   public
	 * @package  Attachment.class.php
	 * @created  Sun Jan 25 15:52:12 EET 2009
	 */
	class Currency_mdl extends Model {
	
		function Currency_mdl() {
			parent::Model();
		}
		
		function get_currency($currency_id=null, $currency_value=null){
			$query = "select * from currency where 1";
			if($currency_id) $query .= " and currency_id=".clean($currency_id);
			if($currency_value) $query .= " and currency_value=".clean(strtoupper($currency_value));
			
			$query = $this->db->query($query);
			if ( ! $query) return FALSE;
			else return $query->result();
		}
		
		function get_currency_rate($currency_id=null){
			$currency_list = $this->get_currency();
			
			$query = "SELECT currency.*, ";
			$maxes = "";
			foreach ($currency_list as $index=>$currency) {
				if($index > 0) $maxes .= ",";
				$maxes .= " MAX(IF(currency.currency_id=".$currency->currency_id.",1,IF(tocurrency.currency_id=".$currency->currency_id.",currency_rate.currency_value,NULL))) AS ".strtolower($currency->currency_value);						
			}
			$query .= $maxes." FROM currency
			LEFT OUTER JOIN currency_rate ON (currency_rate.currency_from_id = currency.currency_id)
			LEFT OUTER JOIN currency tocurrency ON (currency_rate.currency_to_id = tocurrency.currency_id)
			where currency.currency_id=".$currency_id."
			GROUP BY currency.currency_value ORDER BY currency.currency_value";
			
			$query = $this->db->query($query);
			return $query->result();			
		}
		
		function add_currency($currency_value){
			try {
				if(empty($currency_value))
					throw new Exception("currency value is empty");
				
				$values = array("currency_value" => $currency_value);
				if(!$this->db->insert('currency', $values))
					throw new Exception($this->db->_error_message());			
				return $this->db->insert_id();
				
			} catch(Exception $e){
				log_message('error', $e->getMessage().'\n'.
									$e->getFile().'\n'.
									$e->getCode());
			}
			return false;
		}
		
		function update_currency_rate($currency_id, $rates){
			try {
				if(empty($currency_id) || empty($rates)) throw new Exception("data is empty");
				
				$query = "select * from currency where currency_value=".clean(strtoupper($rates['name']));
				$query = $this->db->query($query);
				$currency = $query->row();
				if($currency) {
					$query1 = "delete from currency_rate where currency_from_id='".$currency_id."' and currency_to_id='".$currency->currency_id."'";					
					$query2 = "insert into currency_rate(currency_from_id, currency_to_id, currency_value) values('$currency_id', ".$currency->currency_id.", ".$rates['value'].")";
					
					if(!$this->db->query($query1)) throw new Exception($this->db->_error_message());
					if(!$this->db->query($query2)) throw new Exception($this->db->_error_message());
				}				
				return true;
				
			} catch(Exception $e){
				log_message('error', $e->getMessage().'\n'.$e->getFile().'\n'.$e->getCode());
			}
			return false;
		}
		
		function delete_currency(){
			
		}
	}
?>