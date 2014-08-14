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
class Attachment extends Model
{
    var $thumb_width = 75;
    var $thumb_height = 50;

    function __constructor()
    {
        parent::Model();
    }

    function get_attach($attach_id = null)
    {
        $query = "select * from attachment where 1";
        if ($attach_id) {
            $query .= " and attach_id=" . clean($attach_id);
        }

        if (!$query = $this->db->query($query)) {
            return false;
        }
        return $query->result();
    }

    function get_attach_item($item_id = null, $attach_type = 'item_gallery')
    {
        try {
            if (!isset($item_id) && empty($attach_type)) {
                throw new Exception('Error with inputed data');
            }

            $query = "select a.*, i.item_id, ai.item_attach_type
                from
                    attachment a, attachment_item ai
                inner join items i on (i.item_id=ai.item_id)
                inner JOIN item_type it on (i.item_type_id = it.item_type_id)
                where
                    ai.attach_id=a.attach_id
                    and ai.item_attach_type = '" . $attach_type . "'";
            if (isset($item_id)) {
                $query .= " and ai.item_id = " . $item_id;
            }

            if (!$query = $this->db->query($query)) {
                throw new Exception($this->db->_error_message());
            }
            return $query->result();

        } catch (Exception $e) {
            log_message(
                'error',
                $e->getMessage() . "\n" .
                "file: " . $e->getFile() . "\n" .
                "code: " . $e->getCode() . "\n" .
                "line: " . $e->getLine()
            );
        }
        return false;
    }

    function get_attach_by_title($title)
    {
        if (empty($title)) {
            return false;
        }

        $query = "select * from attachment where attach_title=" . clean($title);
        $query = $this->db->query($query);
        if (!$query) {
            return false;
        }
        return $query->row();
    }

    function set($info)
    {
        try {
            if (!$info) {
                throw new Exception('inputed data are empty');
            }

            if (!$this->db->insert('attachment', $info)) {
                throw new Exception($this->db->_error_message());
            }
            return $this->db->insert_id();

        } catch (Exception $e) {
            log_message(
                'error',
                $e->getMessage() . "\n" .
                "file: " . $e->getFile() . "\n" .
                "code: " . $e->getCode() . "\n" .
                "line: " . $e->getLine()
            );
        }
        return false;
    }

    function update_attachment($attach_id, $info)
    {
        try {
            if (!$attach_id || !$info) {
                throw new Exception('attach_id or info are empty');
            }

            $this->db->where('attach_id', $attach_id);
            if (!$res = $this->db->update('attachment', $info)) {
                throw new Exception($this->db->_error_message());
            }

            return true;

        } catch (Exception $e) {
            log_message(
                'error',
                $e->getMessage() . "\n" .
                "file: " . $e->getFile() . "\n" .
                "code: " . $e->getCode() . "\n" .
                "line: " . $e->getLine()
            );
        }
        return false;
    }

    function update_attachment_item($attach_id, $item_id, $item_attach_type, $delete_duplicate = true)
    {
        try {
            if (!$attach_id || !$item_id || !$item_attach_type) {
                throw new Exception('inputed data are empty');
            }

            $info = array(
                'attach_id' => $attach_id,
                'item_id' => $item_id,
                'item_attach_type' => $item_attach_type
            );

            $duplicate = null;
            $query = "select a.*
                        from
                            attachment a, attachment_item ai
                        where
                            ai.attach_id = a.attach_id
                        and	ai.item_id = " . clean($item_id) . "
                        and	ai.item_attach_type = " . clean($item_attach_type) . " order by a.attach_date";

            if (!$query = $this->db->query($query)) {
                throw new Exception($this->db->_error_message());
            }
            $duplicate = $query->result();

            if (count($duplicate) >= 1 && $delete_duplicate) {
                $duplicate = $duplicate[0];

                $this->db->where('attach_id', $duplicate->attach_id);
                if (!$this->db->delete('attachment')) {
                    throw new Exception($this->db->_error_message());
                }
            }
            if (!$this->db->insert('attachment_item', $info)) {
                throw new Exception($this->db->_error_message());
            }

            return true;

        } catch (Exception $e) {
            log_message(
                'error',
                $e->getMessage() . "\n" .
                "file: " . $e->getFile() . "\n" .
                "code: " . $e->getCode() . "\n" .
                "line: " . $e->getLine()
            );
        }
        return false;
    }

    function update_attachment_category($attach_id, $category_id, $cat_attach_type)
    {
        try {

            if (!$attach_id || !$category_id || !$cat_attach_type) {
                throw new Exception('inputed data are empty');
            }

            $info = array(
                'attach_id' => $attach_id,
                'category_id' => $category_id,
                'category_attach_type' => $cat_attach_type
            );

            $duplicate = null;
            $query = "select a.*
                        from
                            attachment a, attachment_category ac
                        where
                            ac.attach_id = a.attach_id
                        and	ac.category_id = " . clean($category_id) . "
                        and	ac.category_attach_type = " . clean($cat_attach_type) . " order by a.attach_date";

            if (!$query = $this->db->query($query)) {
                throw new Exception($this->db->_error_message());
            }
            $duplicate = $query->result();

            if (count($duplicate) >= 1) {
                $duplicate = $duplicate[0];

                $this->db->where('attach_id', $duplicate->attach_id);
                if (!$this->db->delete('attachment')) {
                    throw new Exception($this->db->_error_message());
                }
            }
            if (!$this->db->insert('attachment_category', $info)) {
                throw new Exception($this->db->_error_message());
            }

            return true;

        } catch (Exception $e) {
            log_message(
                'error',
                $e->getMessage() . "\n" .
                "file: " . $e->getFile() . "\n" .
                "code: " . $e->getCode() . "\n" .
                "line: " . $e->getLine()
            );
        }
        return false;
    }

    function delete_attach_item($attach_id, $item_id)
    {
        try {
            $query = "select * from attachment_item where attach_id=" . clean($attach_id) . " and item_id=" . clean(
                    $item_id
                );
            $attachment = $this->db->query($query)->row();

            if ($attachment) {
                $this->db->where('attach_id', $attach_id);
                if (!$this->db->delete('attachment_item')) {
                    throw new Exception($this->db->_error_message());
                }

                return true;
            }
            return false;
        } catch (Exception $e) {
            log_message(
                'error',
                $e->getMessage() . "\n" .
                "file: " . $e->getFile() . "\n" .
                "code: " . $e->getCode() . "\n" .
                "line: " . $e->getLine()
            );
        }
        return false;
    }

    function delete_attach($attach_id, $delete_file = true)
    {
        try {
            $query = "select * from attachment where attach_id=" . clean($attach_id);
            $attachment = $this->db->query($query)->row();

            if ($attachment) {
                $this->db->where('attach_id', $attach_id);
                if (!$this->db->delete('attachment')) {
                    throw new Exception($this->db->_error_message());
                }

                $this->db->where('attach_id', $attach_id);
                if (!$this->db->delete('attachment_item')) {
                    throw new Exception($this->db->_error_message());
                }

                $this->db->where('attach_id', $attach_id);
                if (!$this->db->delete('attachment_category')) {
                    throw new Exception($this->db->_error_message());
                }

                if ($delete_file) {
                    if (is_file(dirname(BASEPATH) . '/' . $attachment->attach_path)) {
                        unlink(dirname(BASEPATH) . '/' . $attachment->attach_path);
                    }
                    if (is_file(dirname(BASEPATH) . '/' . $attachment->attach_preview_path)) {
                        unlink(dirname(BASEPATH) . '/' . $attachment->attach_preview_path);
                    }
                    if (is_file(dirname(BASEPATH) . '/' . $attachment->attach_single_path)) {
                        unlink(dirname(BASEPATH) . '/' . $attachment->attach_single_path);
                    }
                    if (is_file(dirname(BASEPATH) . '/' . $attachment->attach_preview_main_path)) {
                        unlink(dirname(BASEPATH) . '/' . $attachment->attach_preview_main_path);
                    }
                }
                return true;
            }
            return false;
        } catch (Exception $e) {
            log_message(
                'error',
                $e->getMessage() . "\n" .
                "file: " . $e->getFile() . "\n" .
                "code: " . $e->getCode() . "\n" .
                "line: " . $e->getLine()
            );
        }
        return false;
    }
}
