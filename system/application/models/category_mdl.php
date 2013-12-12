<?php
    define( 'UNCATEGORIZED', 'Без рубрики' );

class Category_mdl extends Model
{

    function __constructor()
    {
        parent::Model();
    }

    function get_category( $category_id = null, $parent_id = null, $category_title = null, $orderby = "category_position" )
    {
        try
        {
            $query = "select * from categories ";
            if ( isset( $category_id ) || isset( $parent_id ) || isset( $category_title ) ) {
                $query .= " where ";
            }
            if ( isset( $category_id ) ) {
                $query .= " category_id = '$category_id'";
            }
            if ( isset( $parent_id ) )
            {
                if ( isset( $category_id ) ) {
                    $query .= ' and ';
                }
                $query .= " category_parent = '$parent_id'";
            }
            if ( isset( $category_title ) )
            {
                if ( isset( $parent_id ) || isset( $category_id ) ) {
                    $query .= ' and ';
                }
                $query .= " category_title = '$category_title'";
            }
            $query .= ' order by ' . $orderby;
            $query = $this->db->query( $query );
            if ( !$query ) {
                throw new Exception( $this->db->_error_message() );
            }

            return $query->result();

        } catch ( Exception $e )
        {
            log_message( 'error', $e->getMessage() . '\n' . $e->getFile() . '\n' . $e->getCode() );
        }
        return false;
    }

    function get_category_attachment( $category_id = null, $attach_id = null, $category_attach_type = null )
    {
        $query = 'select a.*
			from attachment a
			left join attachment_category ac on (ac.attach_id=a.attach_id)
			left JOIN categories c on (ac.category_id=c.category_id) ';
        if ( isset( $category_id ) || isset( $attach_id ) || isset( $category_attach_type ) ) {
            $query .= " where ";
        }

        if ( isset( $category_id ) ) {
            $query .= " ac.category_id = '$category_id'";
        }

        if ( isset( $attach_id ) )
        {
            if ( isset( $category_id ) ) {
                $query .= ' and ';
            }
            $query .= " ac.attach_id = '$attach_id'";
        }
        if ( isset( $category_attach_type ) )
        {
            if ( isset( $category_id ) || isset( $attach_id ) ) {
                $query .= ' and ';
            }
            $query .= " ac.category_attach_type = '$category_attach_type'";
        }

        $query .= ' group by a.attach_id order by a.attach_date desc';

        $query = $this->db->query( $query );

        if ( !$query ) {
            return false;
        }

        return $query->result();
    }

    function get_category_partner( $category_id = null, $partner_id = null, $parent_id = null )
    {
        try
        {
            $query = "select i.*, c.*, cp.category_partner_id from category_partners cp, categories c, items i where cp.category_id=c.category_id and cp.item_id=i.item_id ";
            if ( isset( $category_id ) ) {
                $query .= " and cp.category_id = '$category_id'";
            }
            if ( isset( $partner_id ) )
            {
                $query .= " and cp.item_id = '$partner_id'";
            }
            if ( isset( $parent_id ) )
            {
                $query .= " and c.category_parent = '$parent_id'";
            }
            $query .= ' and (select count(*) from item_category ci, categories c, items i, category_partners cp where ci.category_id=c.category_id and ci.item_id=i.item_id and ci.category_id = cp.category_id) > 0
				order by i.item_title';
            $query = $this->db->query( $query );
            if ( !$query ) {
                throw new Exception( $this->db->_error_message() );
            }

            return $query->result();

        } catch ( Exception $e )
        {
            log_message( 'error', $e->getMessage() . '\n' . $e->getFile() . '\n' . $e->getCode() );
        }
        return false;
    }

    function get_category_item( $category_id = null, $item_id = null, $parent_id = null, $item_mode = true )
    {
        try
        {
            $query = "select i.* from item_category ci, categories c, items i where ci.category_id=c.category_id and ci.item_id=i.item_id ";
            if ( !empty( $category_id ) ) {
                $query .= " and ci.category_id = '$category_id'";
            }
            if ( !empty( $item_id ) )
            {
                $query .= " and ci.item_id = '$item_id'";
            }
            if ( !empty( $parent_id ) )
            {
                $query .= " and c.category_parent = '$parent_id'";
            }
            if ( $item_mode )
            {
                $query .= " and i.item_mode = 'open' and i.item_production <= now() ";
            }
            $query .= ' order by i.item_title';
            $query = $this->db->query( $query );
            if ( !$query ) {
                throw new Exception( $this->db->_error_message() );
            }

            return $query->result();

        } catch ( Exception $e )
        {
            log_message( 'error', $e->getMessage() . '\n' . $e->getFile() . '\n' . $e->getCode() );
        }
        return false;
    }

    function get_item_category( $category_id = null, $item_id = null, $parent_id = null, $item_mode = true )
    {
        try
        {
            $query = "select c.* from item_category ci, categories c, items i where ci.category_id=c.category_id and ci.item_id=i.item_id ";
            if ( !empty( $category_id ) ) {
                $query .= " and ci.category_id = '$category_id'";
            }
            if ( !empty( $item_id ) )
            {
                $query .= " and ci.item_id = '$item_id'";
            }
            if ( !empty( $parent_id ) )
            {
                $query .= " and c.category_parent = '$parent_id'";
            }
            if ( $item_mode )
            {
                $query .= " and i.item_mode = 'open' and i.item_production <= now() ";
            }
            $query .= ' order by i.item_title';
            $query = $this->db->query( $query );
            if ( !$query ) {
                throw new Exception( $this->db->_error_message() );
            }

            return $query->result();

        } catch ( Exception $e )
        {
            log_message( 'error', $e->getMessage() . '\n' . $e->getFile() . '\n' . $e->getCode() );
        }
        return false;
    }

    function set_category( $info )
    {
        try
        {
            if ( empty( $info ) ) {
                throw new Exception( 'Inputed data is empty' );
            }

            $duplicate = null;
            $duplicate = $this->get_category( $info['category_title'] );

            if ( !$duplicate )
            {
                if ( !$this->db->insert( 'categories', $info ) ) {
                    throw new Exception( $this->db->_error_message() );
                }
                return $this->db->insert_id();

            }
            else
            {
                return $duplicate->category_id;
            }
        } catch ( Exception $e )
        {
            log_message( 'error', $e->getMessage() . '\n' . $e->getFile() . '\n' . $e->getCode() );
        }
        return false;
    }

    function set_category_partner( $category_id = null, $partner_id = null )
    {
        try
        {
            $duplicate = null;
            $duplicate = $this->get_category_partner( $category_id, $partner_id );
            if ( !$duplicate )
            {
                $info = array( 'category_id' => $category_id, 'item_id' => $partner_id );
                if ( !$this->db->insert( 'category_partners', $info ) ) {
                    throw new Exception( $this->db->_error_message() );
                }
                return true;
            }
            else
            {
                return false;
            }
        } catch ( Exception $e )
        {
            log_message( 'error', $e->getMessage() . '\n' . $e->getFile() . '\n' . $e->getCode() );
        }
        return false;
    }

    function update_category( $category_id, $info )
    {
        try
        {
            if ( empty( $category_id ) || empty( $info ) ) {
                throw new Exception( 'Inputed data is empty' );
            }

            $category = $this->get_category( $category_id );

            if ( !$category || empty( $category ) ) {
                throw new Exception( 'Category data not found' );
            }

            $this->db->where( 'category_id', $category_id );
            if ( !$res = $this->db->update( 'categories', $info ) ) {
                throw new Exception( $this->db->_error_message() );
            }

            return true;
        } catch ( Exception $e )
        {
            log_message( 'error', $e->getMessage() . '\n' . $e->getFile() . '\n' . $e->getCode() );
        }
        return false;
    }

    function delete_category( $category_id )
    {
        try
        {
            if ( empty( $category_id ) ) {
                throw new Exception( 'Inputed data is empty' );
            }

            $category = $this->get_category( $category_id );
            $category_unc = $this->get_category( null, null, UNCATEGORIZED );

            if ( !$category || empty( $category ) ) {
                throw new Exception( 'Category data not found' );
            }

            $category_items = $this->get_category_item( $category_id );
            if ( !empty( $category_items ) )
            {
                if ( $category_unc )
                {
                    if ( is_array( $category_unc ) ) {
                        $category_unc = $category_unc[0];
                    }

                    $query = "UPDATE item_category SET category_id = '" . $category_unc->category_id . "' WHERE category_id = " . $category_id;
                    if ( !$query = $this->db->query( $query ) ) {
                        throw new Exception( $this->db->_error_message() );
                    }
                }
            }
            $this->db->where( 'category_id', $category_id );
            if ( !$res = $this->db->delete( 'categories' ) ) {
                throw new Exception( $this->db->_error_message() );
            }

            return true;
        } catch ( Exception $e )
        {
            log_message( 'error', $e->getMessage() . '\n' . $e->getFile() . '\n' . $e->getCode() );
        }
        return false;
    }

    function delete_category_partner( $category_id = null, $partner_id = null )
    {
        try
        {
            $this->db->where( 'category_id', $category_id );
            $this->db->where( 'item_id', $partner_id );
            if ( !$res = $this->db->delete( 'category_partners' ) ) {
                throw new Exception( $this->db->_error_message() );
            }

            return true;

        } catch ( Exception $e )
        {
            log_message( 'error', $e->getMessage() . '\n' . $e->getFile() . '\n' . $e->getCode() );
        }
        return false;
    }

    function reorder_categories( $category_id, $subcats_ordered )
    {
        try
        {
            if ( empty( $category_id ) || empty( $subcats_ordered ) ) {
                throw new Exception( 'Inputed data is empty' );
            }

            foreach ( $subcats_ordered as $index => $cat )
            {
                $query = "UPDATE categories SET category_position = '" . $index . "' WHERE category_id = " . clean( $cat );
                if ( !$query = $this->db->query( $query ) ) {
                    throw new Exception( $this->db->_error_message() );
                }
            }
            return true;

        } catch ( Exception $e )
        {
            log_message( 'error', $e->getMessage() . '\n' . $e->getFile() . '\n' . $e->getCode() );
        }
        return false;
    }

    function get_category_tree2( $category, $list )
    {
        $query = "select category_id, category_title, category_parent from categories where category_parent = '" . $category->category_id . "' ORDER BY category_id ASC ";
        $query = $this->db->query( $query );
        $result = $query->result();
        if ( !empty( $result ) ) {
            $category->subcategories = $result;
        }
        foreach ( $result as $res )
        {
            $list[] = $res;
            $list = $this->get_category_tree( $res->category_id, $list );
        }
        return $list;
    }

    function get_categories_tree( $category, $list, $level )
    {
        $categories = $this->get_category( null, $category->category_id, null, "category_position" );
        if ( !empty( $categories ) )
        {
            $level++;
            $category->subcategories = $categories;
        }
        foreach ( $categories as $cat )
        {
            $cat->level = $level;
            $list[] = $cat;
            $list = $this->get_categories_tree( $cat, $list, $level );
        }
        return $list;
    }

    function get_ctgs_tree( $parent, $level )
    {
        $categories = $this->get_category( null, $parent, null, "category_position" );
        $ctg = array(); // категории
        foreach ( $categories as $category )
        {
            // Уровень вложенности категории
            $category->level = $level;
            $ctg[] = $category;
            // получаем подкатегории для текущей категории
            $children = $this->get_ctgs_tree( $category->category_id, $level + 1 );
            // добавляем детей текущей категории в конец массива $ctg[]
            foreach ( $children as $ch )
            {
                $ctg[] = $ch;
            }
        }
        return $ctg;
    }

    function ShowTree( $ParentID, $current_catid, $slug, $parent_main, $partners_cat = array() )
    {

        $categories = $this->get_category( null, $ParentID, null, "category_position" );
        if ( $categories )
        {
            echo "<UL>";
            foreach ( $categories as $category )
            {
                $ID1 = $category->category_id;
                $link = base_url() . $slug . '/subcat/' . $category->category_id;

                $class = 'class="';
                if ( !empty( $partners_cat ) && !in_array( $category, $partners_cat ) ) {
                    $class .= ' not_active ';
                }
                if ( $category->category_parent != $parent_main ) {
                    $class .= ' not_main ';
                }
                elseif ( $category->category_parent == $parent_main ) {
                    $class .= ' main ';
                }
                $class .= '"';

                $cat_str = '<li ' . $class . '>';
                if ( $category->category_id == $current_catid ) {
                    $cat_str .= '<div class="menubox_item_selected">' . $category->category_title . '</div>';
                }
                else
                {
                    $cat_str .= '<div><a class="link" href="' . $link . '">' . $category->category_title . '</a></div>';
                }
                echo $cat_str;
                $this->ShowTree( $ID1, $current_catid, $slug, $parent_main, $partners_cat );
            }
            echo "</UL>";
        }
    }
}

?>