<?php
    /**
     * get_tag_clouds
     *
     * @author  Popov
     * @access  public
     */
    function get_tag_clouds()
    {
        static $ci;
        if (!is_object($ci)) {
            $ci = & get_instance();
        }
        $ci->load->model('items_mdl', 'items');
        $items = $ci->items->get_item_marks();
        if (!$items) {
            return null;
        }
        $tagsArray = [];
        foreach ($items as $item) {
            $tagsArray = array_merge($tagsArray, explode(",", $item->item_marks));
        }
        $tagsArray = array_count_values($tagsArray);
        foreach ($tagsArray as $index => $count) {
            if ($count < 10) {
                unset($tagsArray[$index]);
            }
        }
        return generateTagCloud($tagsArray);
    }

    function generateTagCloud($tags) {
        $max_size = 25; // max font size in pixels
        $min_size = 12; // min font size in pixels

        $max_qty = max(array_values($tags));
        $min_qty = min(array_values($tags));

        $spread = $max_qty - $min_qty;
        if ($spread == 0) { // we don't want to divide by zero
            $spread = 1;
        }
        $step = ($max_size - $min_size) / ($spread);
        $str = '';
        foreach ($tags as $word => $value) {
            $size = round($min_size + (($value - $min_qty) * $step));
            $str .= '<li style="font-size:'.$size.'px">
                <a style="font-size:'
                . $size . 'px" href="'
                . base_url()
                . 'search#find:'
                . $word . '" title="'
                . $value . '" alt="'
                . $value . '"> '
                . $word . ' </a>
            </li>';
        }
        return $str;
    }
