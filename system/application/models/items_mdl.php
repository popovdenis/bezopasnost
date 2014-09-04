<?php

/**
 * Ites_mdl
 *
 * User: Denis
 * Date: 29.05.11
 * Time: 10:25
 */
class Items_mdl extends Model
{
    /**
     * @param null   $item_id
     * @param null   $type
     * @param bool   $item_mode
     * @param null   $category_id
     * @param int    $per_page
     * @param int    $page
     * @param bool   $with_count
     * @param string $extras
     * @param string $orderby
     * @param string $groupby
     *
     * @return bool|array
     */
    function get_item(
        $item_id = null,
        $type = null,
        $item_mode = true,
        $category_id = null,
        $per_page = 0,
        $page = 1,
        $with_count = false,
        $extras = '',
        $orderby = 'order by i.item_added desc',
        $groupby = 'group by i.item_id'
    ) {
        $page  = empty($page) ? 1 : $page;
        $limit = empty($per_page) ? '' : ' limit ' . $per_page * ($page - 1) . ',' . $per_page;
        $query = "select SQL_CALC_FOUND_ROWS i.*, it.item_type, a.*
        from items i
        left JOIN item_type it on (i.item_type_id = it.item_type_id)
        left join attachment_item ai on (i.item_id=ai.item_id)
        left JOIN attachment a on (ai.attach_id=a.attach_id)
        left JOIN item_category ic on (ic.item_id = i.item_id)
        left JOIN categories c on (c.category_id = ic.category_id) where 1 ";
        if ($item_id) {
            $query .= " and i.item_id = " . clean($item_id);
        }
        if ($item_mode) {
            $query .= " and i.item_mode = 'open' and i.item_production <= now() ";
        }
        if ($type) {
            $query .= " and it.item_type = " . clean($type);
        }
        if (!empty($category_id)) {
            $query .= " and ic.category_id = " . clean($category_id);
        }
        $query .= " " . $extras . " " . $groupby . " " . $orderby;
        $query .= $limit;
        $response = $this->db->query($query);
        if (!$response) {
            return false;
        }
        $result = $response->result();
        if ($with_count) {
            $response = $this->db->query("select found_rows() as count");
            if (!$response) {
                return false;
            }
            $result['count'] = $response->row()->count;
        }
        return $result;
    }

    /**
     * @param null $item_type_id
     * @param null $item_type
     * @param int  $item_parent
     *
     * @return bool
     */
    function get_item_type($item_type_id = null, $item_type = null, $item_parent = 0)
    {
        try {
            if (!$item_type_id && !$item_type) {
                throw new Exception("all inputed data are null");
            }
            $query = "select * from item_type where ";
            if ($item_type_id) {
                $query .= " item_type_id = '$item_type_id'";
            }
            if ($item_type) {
                $query .= " item_type = '$item_type'";
            }
            $query = $this->db->query($query);
            if (!$query) {
                throw new Exception($this->db->_error_message());
            } else {
                return $query->row();
            }
        } catch (Exception $e) {
            log_message('error', $e->getMessage() . '\n' . $e->getFile() . '\n' . $e->getCode());
        }
        return false;
    }

    /**
     * @param $item_id
     *
     * @return array
     */
    function get_item_category($item_id)
    {
        $query = "select c.*
                from item_category ic, categories c
                where c.category_id = ic.category_id
                and ic.item_id = " . clean($item_id) . " and category_parent > 0 order by c.category_parent";
        $query = $this->db->query($query);
        return $query->result();
    }

    /**
     * @param string $keywords
     * @param null   $categories
     * @param int    $per_page
     * @param int    $page
     * @param bool   $with_count
     *
     * @return bool|null
     */
    function get_item_search_common(
        $keywords = '',
        $categories = null,
        $per_page = 0,
        $page = 1,
        $with_count = true
    ) {
        try {
            $page  = empty($page) ? 1 : $page;
            $limit = empty($per_page) ? '' : ' limit ' . $per_page * ($page - 1) . ',' . $per_page;
            if ($categories) {
                $count_common = 0;
                $query        = "";
                foreach ($categories as $index => $category) {
                    $query = "select SQL_CALC_FOUND_ROWS it.item_type,
                        c.category_id, c.category_title, i.*
                        from
                            items i
                        left JOIN item_category ic on ic.item_id=i.item_id
                        left JOIN item_type it on it.item_type_id=i.item_type_id
                        left JOIN categories c on ic.category_id=c.category_id
                        where item_mode = 'open'
                            and c.category_id = '" . $category->category_id . "'
                            and (i.item_title LIKE '" . $keywords . "%'
                            or i.item_title LIKE '%" . $keywords . "%'
                            or i.item_content LIKE '%" . $keywords . "%') order by category_title" . $limit;
                    $query                   = $this->db->query($query);
                    $category->search_result = $query->result();
                    $query = $this->db->query("select found_rows() as count");
                    if (!$query) {
                        return false;
                    }
                    $category->search_count = $query->row()->count;
                    $count_common += $category->search_count;
                }
                $categories['count_common'] = $count_common;
                return $categories;
            }
            return null;
        } catch (Exception $e) {
            log_message('error', $e->getMessage() . '\n' . $e->getFile() . '\n' . $e->getCode());
        }
        return false;
    }

    /**
     * @param string $keywords
     * @param null   $category_id
     * @param int    $per_page
     * @param int    $page
     * @param bool   $with_count
     *
     * @return bool|array
     */
    function get_item_search_category(
        $keywords = '',
        $category_id = null,
        $per_page = 0,
        $page = 1,
        $with_count = true
    ) {
        try {
            $page  = empty($page) ? 1 : $page;
            $limit = empty($per_page) ? '' : ' limit ' . $per_page * ($page - 1) . ',' . $per_page;
            $query = "select * from categories where category_id = '" . $category_id . "'";
            $query = $this->db->query($query);
            if (!$query) {
                throw new Exception($this->db->_error_message());
            }
            $categories = $query->result();
            if ($categories) {
                $count_cats = count($categories) - 1;
                $count_common  = 0;
                $query         = "";
                $query_general = "";
                foreach ($categories as $index => $category) {
                    $query = "select SQL_CALC_FOUND_ROWS
                    it.item_type, c.category_id, c.category_title, i.*
                    from items i
                    left JOIN item_category ic on ic.item_id=i.item_id
                    left JOIN item_type it on it.item_type_id=i.item_type_id
                    left JOIN categories c on ic.category_id=c.category_id
                    where
                        item_mode = 'open'
                        and c.category_id = '" . $category->category_id . "'
                        and (i.item_title LIKE '" . $keywords . "%'
                        or i.item_title LIKE '%" . $keywords . "%'
                        or i.item_content LIKE '%" . $keywords . "%')
                        order by category_title " . $limit;
                    $query_general .= " " . $query . " ";
                    if ($index < $count_cats) {
                        $query_general .= " union ";
                    }
                    $query = $this->db->query($query);
                    if (!$query) {
                        return false;
                    }
                    $category->search_result = $query->result();
                    $query = $this->db->query("select found_rows() as count");
                    if (!$query) {
                        return false;
                    }
                    $category->search_count = $query->row()->count;
                    $count_common += $category->search_count;
                }
                $categories['count_common'] = $count_common;
                $query                      = $this->db->query($query_general);
                if (!$query) {
                    return false;
                }
                $search_result               = $query->result();
                $categories['search_common'] = $search_result;
                return $categories;
            }
            return null;
        } catch (Exception $e) {
            log_message('error', $e->getMessage() . '\n' . $e->getFile() . '\n' . $e->getCode());
        }
        return false;
    }

    public function get_item_marks()
    {
        $query = '
            select
                i.item_marks
            from
                items i
            inner JOIN item_category ic on (ic.item_id = i.item_id)
            inner JOIN categories c on (ic.category_id = c.category_id)
            where
                i.item_marks != \'\'
            and
                ic.category_id in (
                    select c.category_id
                    from categories c
                    where c.category_parent = (
                        select category_id from categories where category_title = \'Продукция\'
                    )
                )';
        $query = $this->db->query($query);
        if (!$query) {
            return false;
        }
        return $query->result();
    }

    public function getItemsByTag($tag)
    {
        if (empty($tag)) {
            return [];
        }

        $query = 'SELECT
            DISTINCT i.item_id, i.item_title, i.item_preview,
            it.item_type,
            GROUP_CONCAT(ic.category_id) as categories
        FROM
            items i
        INNER JOIN item_category ic ON (ic.item_id = i.item_id)
        INNER JOIN item_type it ON (it.item_type_id = i.item_type_id)
        WHERE
          i.item_marks LIKE "%' . $tag . '%"
        GROUP BY i.item_id
        ORDER BY i.item_id desc';

        $query = $this->db->query($query);

        if (!$query) {
            return false;
        }
        return $query->result_array();
    }

    /**
     * @param string $keywords
     * @param int    $per_page
     * @param int    $page
     * @param bool   $with_count
     *
     * @return bool|array
     */
    function get_item_search($keywords = '', $per_page = 0, $page = 1, $with_count = false)
    {
        $page  = empty($page) ? 1 : $page;
        $limit = empty($per_page) ? '' : ' limit ' . $per_page * ($page - 1) . ',' . $per_page;
        $category1 = "select category_id from categories where category_title = 'Двери'";
        $category1 = $this->db->query($category1)->row();
        $category2 = "select category_id from categories where category_title = 'Замки'";
        $category2 = $this->db->query($category2)->row();
        $category3 = "select category_id from categories where category_title = 'Сейфы'";
        $category3 = $this->db->query($category3)->row();
        $category4 = "select category_id from categories where category_title = 'Системы контроля доступа'";
        $category4 = $this->db->query($category4)->row();
        $category5 = "select category_id from categories where category_title = 'Цилиндры'";
        $category5 = $this->db->query($category5)->row();
        $category6 = "select category_id from categories where category_title = 'Комплектующие и аксессуары'";
        $category6 = $this->db->query($category6)->row();
        $category7 = "select category_id from categories where category_title = 'Бренды'";
        $category7 = $this->db->query($category7)->row();
        $category8 = "select category_id from categories where category_title = 'Информация'";
        $category8 = $this->db->query($category8)->row();
        $query = "select SQL_CALC_FOUND_ROWS it.item_type, c.category_id, c.category_title, i.* from items i left JOIN item_category ic on ic.item_id=i.item_id
    left JOIN item_type it on it.item_type_id=i.item_type_id left JOIN categories c on ic.category_id=c.category_id where item_mode = 'open' and c.category_parent = '" . $category1->category_id . "'
    and (i.item_title LIKE '%" . $keywords . "%' or i.item_content like '%" . $keywords . "%') ";
        $query .= "union select it.item_type, c.category_id, c.category_title, i.* from items i left JOIN item_category ic on ic.item_id=i.item_id
    left JOIN item_type it on it.item_type_id=i.item_type_id left JOIN categories c on ic.category_id=c.category_id where item_mode = 'open' and c.category_parent = '" . $category2->category_id . "'
    and (i.item_title LIKE '%" . $keywords . "%' or i.item_content like '%" . $keywords . "%') ";
        $query .= "union select it.item_type, c.category_id, c.category_title, i.* from items i left JOIN item_category ic on ic.item_id=i.item_id
    left JOIN item_type it on it.item_type_id=i.item_type_id left JOIN categories c on ic.category_id=c.category_id where item_mode = 'open' and c.category_parent = '" . $category3->category_id . "'
    and (i.item_title LIKE '%" . $keywords . "%' or i.item_content like '%" . $keywords . "%') ";
        $query .= "union select it.item_type, c.category_id, c.category_title, i.* from items i left JOIN item_category ic on ic.item_id=i.item_id
    left JOIN item_type it on it.item_type_id=i.item_type_id left JOIN categories c on ic.category_id=c.category_id where item_mode = 'open' and c.category_parent = '" . $category4->category_id . "'
    and (i.item_title LIKE '%" . $keywords . "%' or i.item_content like '%" . $keywords . "%') ";
        $query .= "union select it.item_type, c.category_id, c.category_title, i.* from items i left JOIN item_category ic on ic.item_id=i.item_id
    left JOIN item_type it on it.item_type_id=i.item_type_id left JOIN categories c on ic.category_id=c.category_id where item_mode = 'open' and c.category_parent = '" . $category5->category_id . "'
    and (i.item_title LIKE '%" . $keywords . "%' or i.item_content like '%" . $keywords . "%') ";
        $query .= "union select it.item_type, c.category_id, c.category_title, i.* from items i left JOIN item_category ic on ic.item_id=i.item_id
    left JOIN item_type it on it.item_type_id=i.item_type_id left JOIN categories c on ic.category_id=c.category_id where item_mode = 'open' and c.category_parent = '" . $category6->category_id . "'
    and (i.item_title LIKE '%" . $keywords . "%' or i.item_content like '%" . $keywords . "%') ";
        $query .= "union select it.item_type, c.category_id, c.category_title, i.* from items i left JOIN item_category ic on ic.item_id=i.item_id
    left JOIN item_type it on it.item_type_id=i.item_type_id left JOIN categories c on ic.category_id=c.category_id where item_mode = 'open' and c.category_parent = '" . $category7->category_id . "'
    and (i.item_title LIKE '%" . $keywords . "%' or i.item_content like '%" . $keywords . "%') ";
        $query .= "union select it.item_type, c.category_id, c.category_title, i.* from items i left JOIN item_category ic on ic.item_id=i.item_id
    left JOIN item_type it on it.item_type_id=i.item_type_id left JOIN categories c on ic.category_id=c.category_id where item_mode = 'open' and c.category_parent = '" . $category8->category_id . "'
    and (i.item_title LIKE '%" . $keywords . "%' or i.item_content like '%" . $keywords . "%') order by category_title " . $limit;
        $query = $this->db->query($query);
        if (!$query) {
            return false;
        }
        $result = $query->result();
        if ($with_count) {
            $query = $this->db->query("select found_rows() as count");
            if (!$query) {
                return false;
            }
            $result['count'] = $query->row()->count;
        }
        return $result;
    }

    /**
     * @param      $item_data
     * @param null $item_id
     *
     * @return bool|int
     */
    function save_item($item_data, $item_id = null)
    {
        try {
            if (empty($item_data)) {
                throw new Exception("item info for added is empty");
            }
            if ($item_id) {
                if (isset($item_data['item_type'])) {
                    unset($item_data['item_type']);
                }
                $this->db->where('item_id', $item_id);
                if (!$res = $this->db->update('items', $item_data)) {
                    throw new Exception($this->db->_error_message());
                }
                return $item_id;
            } else {
                if (isset($item_data['item_type']) && !empty($item_data['item_type'])) {
                    $type = $this->get_item_type(null, $item_data['item_type']);
                    if ($type) {
                        unset($item_data['item_type']);
                        $item_data['item_type_id'] = $type->item_type_id;
                        if (!$this->db->insert('items', $item_data)) {
                            throw new Exception($this->db->_error_message());
                        }
                        return $this->db->insert_id();
                    }
                } else {
                    throw new Exception('Item\'s type is empty');
                }
            }
        } catch (Exception $e) {
            log_message('error', $e->getMessage() . '\n' . $e->getFile() . '\n' . $e->getCode());
        }
        return false;
    }

    /**
     * @param $category_id
     * @param $item_id
     *
     * @return bool
     */
    function save_item_category($category_id, $item_id)
    {
        try {
            if (!$category_id && !$item_id) {
                throw new Exception("all inputed data are null");
            }
            $duplicate = null;
            $query = "select * from item_category
                   where category_id = " . clean($category_id) . " and item_id = " . clean($item_id);
            $query = $this->db->query($query);
            if (!$query) {
                throw new Exception($this->db->_error_message());
            } else {
                $duplicate = $query->row();
            }
            if (!$duplicate) {
                $product_cat = array('category_id' => $category_id, 'item_id' => $item_id);
                if (!$this->db->insert('item_category', $product_cat)) {
                    throw new Exception($this->db->_error_message());
                }
            }
        } catch (Exception $e) {
            log_message('error', $e->getMessage() . '\n' . $e->getFile() . '\n' . $e->getCode());
        }
        return false;
    }

    /**
     * @param $item_id
     *
     * @return bool
     */
    function delete_item($item_id)
    {
        try {
            if (!$item_id) {
                throw new Exception("inputed data are null");
            }
            $this->db->where('item_id', $item_id);
            if ($this->db->delete('items')) {
                return true;
            }
        } catch (Exception $e) {
            log_message('error', $e->getMessage() . '\n' . $e->getFile() . '\n' . $e->getCode());
        }
        return false;
    }

    /**
     * @param $item_id
     *
     * @return bool
     */
    function delete_item_category($item_id)
    {
        try {
            if (!$item_id) {
                throw new Exception("all inputed data are null");
            }
            $this->db->where('item_id', $item_id);
            $this->db->delete('item_category');
            return;
        } catch (Exception $e) {
            log_message('error', $e->getMessage() . '\n' . $e->getFile() . '\n' . $e->getCode());
        }
        return false;
    }

    /**
     * @param $item_id
     * @param $cat_id
     *
     * @return bool
     */
    function add_item_ad($item_id, $cat_id)
    {
        try {
            if (!$item_id) {
                throw new Exception("all inputed data are null");
            }
            $duplicate = $this->get_item($item_id, null, null, $cat_id);
            log_message('debug', 'add_item. duplicate.' . var_export($duplicate, true));
            if (!$duplicate) {
                $add_cat = array('category_id' => $cat_id, 'item_id' => $item_id);
                log_message('debug', 'add_item. duplicate2.' . var_export($add_cat, true));
                if (!$this->db->insert('item_category', $add_cat)) {
                    throw new Exception($this->db->_error_message());
                }
                return true;
            }
            return;
        } catch (Exception $e) {
            log_message('error', $e->getMessage() . '\n' . $e->getFile() . '\n' . $e->getCode());
        }
        return false;
    }

    /**
     * @param $item_id
     * @param $cat_id
     *
     * @return bool
     */
    function delete_item_ad($item_id, $cat_id)
    {
        try {
            if (!$item_id) {
                throw new Exception("all inputed data are null");
            }
            $duplicate = $this->get_item($item_id, null, null, $cat_id);
            if (!empty($duplicate)) {
                $add_cat = array('category_id' => $cat_id, 'item_id' => $item_id);
                if (!$this->db->delete('item_category', $add_cat)) {
                    throw new Exception($this->db->_error_message());
                }
                return true;
            }
            return;
        } catch (Exception $e) {
            log_message('error', $e->getMessage() . '\n' . $e->getFile() . '\n' . $e->getCode());
        }
        return false;
    }

    public function getItem(array $options = [], $single = false)
    {
        $this->db
            ->select('items.*, categories.category_id')
            ->from('items')
            ->join('item_category', 'item_category.item_id = items.item_id')
            ->join('categories', 'item_category.category_id = categories.category_id')
            ->groupBy('items.item_id');

        if (isset($options['id'])) {
            $this->db->where('item_id', intval($options['id']));
        }
        if (isset($options['hOrder'])) {
            $this->db->where('item_category.hOrder', intval($options['hOrder']));
        }
        if (isset($options['vOrder'])) {
            $this->db->where('item_category.vOrder', intval($options['vOrder']));
        }
        if (isset($options['category_id'])) {
            $this->db->where('item_category.category_id', intval($options['category_id']));
        }

        $query = $this->db->get();

        return $single ? $query->row_array() : $query->result_array();
    }

    public function saveItemCoordinates($itemId, $categoryId, array $options)
    {
        $this->db->update(
            'item_category',
            [
                'vOrder' => NULL,
                'hOrder' => NULL,
            ],
            [
                'vOrder' => $options['vOrder'],
                'hOrder' => $options['hOrder'],
            ]
        );

        $this->db->where('item_id', $itemId);
        $this->db->where('category_id', $categoryId);
        $this->db->update('item_category', $options);
    }
}
