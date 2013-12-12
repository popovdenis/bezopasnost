<?php

    /**
     * Class Gallery_mdl
     *
     * class for gallery
     *
     * @author   Popov
     * @access   public
     * @package  Attachment.class.php
     * @created  Sun Jan 25 15:52:12 EET 2009
     */
    class Gallery_mdl extends Model {
        
        function __constructor() {
            parent::Model();
        }
        
        function get_gallery($gallery_id=null, $gallery_title=""){
	    
            $query = "select * from gallery where 1 ";
            if($gallery_id) $query .= " and gallery_id=".$gallery_id;
            if($gallery_title) $query .= " and gallery_title=".clean($gallery_title);
            $query .= " order by gallery_date desc";
            $query = $this->db->query($query);
            if ( ! $query) return FALSE;
            return $query->result();    
        }
        
        function get_gallery_attachment($gallery_id=null){
            $query = "select 
                attachment.*
            from attachment
            left JOIN gallery_attachment on gallery_attachment.attachment_id=attachment.attach_id            
            left JOIN gallery on gallery_attachment.gallery_id=gallery.gallery_id
            where 1 ";
            if($gallery_id) $query .= " and gallery.gallery_id=".$gallery_id;
            $query .= " order by gallery_attachment.attach_position";
            $query = $this->db->query($query);
            if ( ! $query) return FALSE;
            return $query->result();
        }
        
        function add_gallery($gallery_data){
            try {
                if(!$gallery_data)
                    throw new Exception('inputed data are empty');
                $duplicate = $this->get_gallery(null, $gallery_data['gallery_title']);
                if(empty($duplicate)) {                
                    if(!$this->db->insert('gallery', $gallery_data))
                            throw new Exception($this->db->_error_message());
                    return $this->db->insert_id();
                } else {
                    return $duplicate[0]->gallery_id;
                }
            } catch (Exception $e) {
                log_message('error',$e->getMessage()."\n".
                                    "file: ".$e->getFile()."\n".
                                    "code: ".$e->getCode()."\n".
                                    "line: ".$e->getLine());
            }
            return false;
        }
        
        function update_gallery($gallery_id, $gallery_data){
            try {
                if(!$gallery_data)
                    throw new Exception('inputed data are empty');
		    
                $this->db->where('gallery_id', $gallery_id);
		if(!$res = $this->db->update('gallery', $gallery_data))
			throw new Exception($this->db->_error_message());
		return true;
	    
            } catch (Exception $e) {
                log_message('error',$e->getMessage()."\n".
                                    "file: ".$e->getFile()."\n".
                                    "code: ".$e->getCode()."\n".
                                    "line: ".$e->getLine());
            }
            return false;
        }
        
        function delete_gallery($gallery_id){
	    try {  
		$gallery_attach = $this->get_attach_gallery($gallery_id);
		
		
		if(!empty($gallery_attach)) {
		    foreach($gallery_attach as $attach){
			$query = "select * from attachment where attach_id=".clean($attach->attachment_id);
			$attachment = $this->db->query($query)->row();
			
			if($attachment) {			    
			    $this->db->where('attachment_id', $attach->attachment_id);
			    if(!$this->db->delete('gallery_attachment'))
				throw new Exception($this->db->_error_message());
			    $this->db->where('attach_id', $attach->attachment_id);
			    if(!$this->db->delete('attachment'))
				    throw new Exception($this->db->_error_message());
			        
			    if(is_file(dirname(BASEPATH).'/'.$attachment->attach_path))
				    unlink(dirname(BASEPATH).'/'.$attachment->attach_path);
			    if(is_file(dirname(BASEPATH).'/'.$attachment->attach_preview_path))
				    unlink(dirname(BASEPATH).'/'.$attachment->attach_preview_path);
			    if(is_file(dirname(BASEPATH).'/'.$attachment->attach_single_path))
				    unlink(dirname(BASEPATH).'/'.$attachment->attach_single_path);
			    if(is_file(dirname(BASEPATH).'/'.$attachment->attach_preview_main_path))
				    unlink(dirname(BASEPATH).'/'.$attachment->attach_preview_main_path);				
			}			
		    }
		}
		$gallery_item = $this->delete_item_gallery($gallery_id);		
		if(!empty($gallery_item)) {
		    foreach($gallery_item as $gal) {
			$this->db->where('gallery_id', $gal->gallery_id);
			if(!$this->db->delete('gallery_item'))
			    throw new Exception($this->db->_error_message());
		    }
		}
		
		$this->db->where('gallery_id', $gallery_id);
		if(!$this->db->delete('gallery'))
		    throw new Exception($this->db->_error_message());
		
	    } catch (Exception $e) {
                log_message('error',$e->getMessage()."\n".
                                    "file: ".$e->getFile()."\n".
                                    "code: ".$e->getCode()."\n".
                                    "line: ".$e->getLine());
            }
            return false;
        }
        
        function get_attach_gallery($gallery_id=null, $attach_id=null, $with_count = false){
            $query = "select SQL_CALC_FOUND_ROWS gallery_attachment.*
            from gallery_attachment
            inner JOIN gallery on gallery.gallery_id = gallery_attachment.gallery_id
            INNER JOIN attachment on attachment.attach_id = gallery_attachment.attachment_id
            where 1 ";
            
            if($gallery_id) $query .= " and gallery_attachment.gallery_id = ".$gallery_id;
            if($attach_id) $query .= " and gallery_attachment.attachment_id = ".$attach_id;
            $query .= " order by gallery.gallery_date desc";
            $query = $this->db->query($query);
            if ( ! $query) return FALSE;
            $result = $query->result();
            
            if($with_count) {
				$query = $this->db->query("select found_rows() as count");
				if ( ! $query) return FALSE;
				$result['count'] = $query->row()->count;				
			}
			return $result;    
        }
        
        function set_attach_gallery($gallery_id, $attach_id){            
            try {
                if(empty($gallery_id) || empty($attach_id))
                    throw new Exception('inputed data are empty');
                    
                $duplicate = $this->get_attach_gallery($gallery_id, $attach_id);
                
                $gall_attach_count = $this->get_attach_gallery($gallery_id, null, true);
                $count = $gall_attach_count['count'];                
                unset($gall_attach_count['count']);
                
                if(empty($duplicate)) {
                    $gallery_data = array(
                        "gallery_id" => $gallery_id,
                        "attachment_id" => $attach_id,
                        "attach_position" => ($count+1)
                    );                
                    if(!$this->db->insert('gallery_attachment', $gallery_data))
                            throw new Exception($this->db->_error_message());                                           
                }
                return true;
                
            } catch (Exception $e) {
                log_message('error',$e->getMessage()."\n".
                                    "file: ".$e->getFile()."\n".
                                    "code: ".$e->getCode()."\n".
                                    "line: ".$e->getLine());
            }
            return false;    
        }
        
        function update_attach_gallery($gallery_id, $attach_id){
            
        }
        
        /**
         * @todo сделать удаление из attachment, и из всех остальных таблиц. в т.ч. физическое удаление файла
         */
        function delete_attach_gallery($gallery_id, $attach_id){
        	try {
        		$attach = $this->get_attach_gallery($gallery_id, $attach_id);
	        	if($attach) {
	        		$query = "DELETE FROM gallery_attachment WHERE gallery_id = ".$gallery_id." and attachment_id = ".$attach_id;
	        		
					if(!$this->db->query($query))
						throw new Exception($this->db->_error_message());
					return true;
	        	}
        	} catch (Exception $e) {
                log_message('error',$e->getMessage()."\n".
                                    "file: ".$e->getFile()."\n".
                                    "code: ".$e->getCode()."\n".
                                    "line: ".$e->getLine());
            }
            return false;
        }
        
        function get_item_gallery ($gallery_id=null, $item_id=null){
            if(empty($gallery_id) && empty($item_id)) return false;
            
            $query = "select gallery.*, attachment.*
            from gallery_item
            inner JOIN gallery on gallery.gallery_id = gallery_item.gallery_id
            INNER JOIN items on items.item_id = gallery_item.item_id
            INNER JOIN gallery_attachment on gallery_attachment.gallery_id = gallery.gallery_id
            inner join attachment on gallery_attachment.attachment_id = attachment.attach_id
            where 1 ";
            
            if($gallery_id) $query .= " and gallery_item.gallery_id = ".$gallery_id;
            if($item_id) $query .= " and gallery_item.item_id = ".$item_id;
            $query .= " order by gallery_attachment.attach_position";
            $query = $this->db->query($query);
            if ( ! $query) return FALSE;
            return $query->result();    
        }
        
        function set_item_gallery ($gallery_id, $item_id){
            try {
                if(empty($gallery_id) || empty($item_id))
                    throw new Exception('inputed data are empty');
                
                $this->delete_item_gallery($gallery_id, $item_id);
                
                $gallery_data = array(
                    "gallery_id" => $gallery_id,
                    "item_id" => $item_id
                );
                
                if(!$this->db->insert('gallery_item', $gallery_data))
                        throw new Exception($this->db->_error_message());
                return true;
                
            } catch (Exception $e) {
                log_message('error',$e->getMessage()."\n".
                                    "file: ".$e->getFile()."\n".
                                    "code: ".$e->getCode()."\n".
                                    "line: ".$e->getLine());
            }
            return false;
        }
        
        function reorder_attach_gallery($gallery_id, $reorder_attach){
            try {
                if(empty($gallery_id) || empty($reorder_attach))
                    throw new Exception('Inputed data is empty');
                
                foreach($reorder_attach as $index=>$attach){
                    $query = "UPDATE gallery_attachment SET attach_position = '".$index."' WHERE attachment_id = ".clean($attach)." and gallery_id=".$gallery_id;                    
                    if(!$query = $this->db->query($query))
                        throw new Exception($this->db->_error_message());
                }
                return true;
                
            } catch(Exception $e){
                log_message('error', $e->getMessage().'\n'.$e->getFile().'\n'.$e->getCode());
            }
            return false;
        }
        
        function update_item_gallery (){}
        
        function delete_item_gallery ($gallery_id, $item_id=null){
            try {
                $gallery = $this->get_item_gallery($gallery_id, $item_id);
                if($gallery) {
                    $query = "DELETE FROM gallery_item WHERE gallery_id = ".$gallery_id." and item_id = ".$item_id;
                    
                    if(!$this->db->query($query))
                        throw new Exception($this->db->_error_message());
                    return true;
                }
            } catch (Exception $e) {
                log_message('error',$e->getMessage()."\n"."file: ".$e->getFile()."\n"."code: ".$e->getCode()."\n"."line: ".$e->getLine());
            }
            return false;
        }
        
        /**
         * Destructor of Gallery_mdl 
         *
         * @access  public
         */
        function __destructor() {}        
    }

?>
