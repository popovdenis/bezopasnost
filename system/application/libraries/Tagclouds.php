<?php
	class Tagclouds {

		private $tags;
	
		function __construct($tags) {	
			shuffle($tags);
			$this->tags = $tags;	
		}
	
		private function get_tag_count($tag_name, $tags) {	
			$count = 0;	
			foreach ($tags as $tag) {
				if ($tag == $tag_name) {
					$count++;
				}
			}	
			return $count;	
		}
	
		private function tagsclouds($tags) {	
			$tags_list = array();	
			foreach ($tags as $tag) {
				$tags_list[$tag] = $this->get_tag_count($tag, $tags);
			}	
			return $tags_list;	
		}
	
		public function get_cloud() {
			$tags = $this->tagsclouds($this->tags);
	       
	        $max_size = 25; // max font size in pixels
	        $min_size = 12; // min font size in pixels
	       
	        // largest and smallest array values
	        $max_qty = max(array_values($tags));
	        $min_qty = min(array_values($tags));
	       
	        // find the range of values
	        $spread = $max_qty - $min_qty;
	        if ($spread == 0) { // we don't want to divide by zero
	                $spread = 1;
	        }	       
	        // set the font-size increment
	        $step = ($max_size - $min_size) / ($spread);	       
	        // loop through the tag array
	        $str = '';
	        foreach ($tags as $word => $value) {
	                // calculate font-size
	                // find the $value in excess of $min_qty
	                // multiply by the font-size increment ($size)
	                // and add the $min_size set above
	                $size = round($min_size + (($value - $min_qty) * $step));
	       
	                $str .= '<li style="font-size:'.$size.'px"><a style="font-size:'.$size.'px" href="'.base_url().'search#find:'.$word.'" title="'.$value.'" alt="'.$value.'"> '.$word.' </a></li>';
	        }
	        return $str;	
		}
	}
?>