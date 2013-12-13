<?php
    function url($alias = '', $params = array())
    {
        $url = base_url();
        if (empty($alias)) {
            return $url;
        }
        static $ci;
        if (! is_object($ci)) {
            $ci = & get_instance();
        }
        $ci->load->library('Properties');
        $props = new Properties;
        $props->load(file_get_contents('url.properties'));
        $map = $props->toArray();
        if (! empty($alias)) {
            if (array_key_exists($alias, $map)) {
                $url = $map[$alias];
            }
        }
        if (is_array($params)) {
            foreach ($params as $key => $value) {
                $url = str_replace("%$key%", $value, $url);
            }
        } elseif (is_string($params) || is_int($params)) {
            $url = preg_replace("(\%.*\%)", $params, $url);
        }
        return $url;
    }

    function uri_segment($index)
    {
        static $ci;
        if (! is_object($ci)) {
            $ci = & get_instance();
        }
        return $ci->uri->segment($index);
    }

    function get_app_vars()
    {
        $data = array();
        $data['site_title'] = 'phh title';
        static $ci;
        if (! is_object($ci)) {
            $ci = & get_instance();
        }
        $data['user_id'] = $ci->db_session->userdata('user_id');
        return $data;
    }

    function clean($str)
    {
        static $ci;
        if (! is_object($ci)) {
            $ci = & get_instance();
        }
        $str = $ci->input->xss_clean($str);
        $str = $ci->db->escape($str);
        return $str;
    }

    function paginate($args)
    {
        static $ci;
        if (! is_object($ci)) {
            $ci = & get_instance();
        }
        $page_part = strstr($_SERVER['REQUEST_URI'], '/page/');
        $url       = $_SERVER['REQUEST_URI'];
        if ($page_part) {
            $url = str_replace($page_part, '', $url);
        }
        $args['base_url'] = $url . '/page/' . $args['sort_by'] . '/' . $args['sort_as'];
        $url = trim($url, '/');
        $url = explode('/', $url);
        $args['uri_segment'] = count($url) + 1;
        $ci->load->library('pagination');
        $ci->pagination->initialize($args);
        return $ci->pagination->create_page_links();
    }

    function paginate_ajax($args)
    {
        static $ci;
        if (! is_object($ci)) {
            $ci = & get_instance();
        }
        if (empty($args['base_url'])) {
            $url = uri_string();
        } else {
            $url = $args['base_url'];
        }
        $page_part = strstr($url, '/page/');
        if ($page_part) {
            $url = str_replace($page_part, '', $url);
        }
        $args['base_url'] = $url . '/page/';
        $url = trim($url, '/');
        $url = explode('/', $url);
        $args['uri_segment'] = count($url) + 2;
        $args['cur_page'] = empty($args['cur_page']) ? '-' : $args['cur_page'];
        $ci->load->library('pagination');
        $ci->pagination->initialize($args);
        return $ci->pagination->create_page_links_ajax();
    }

    function set_error($error_lang_alias = 'error_unknown')
    {
        static $ci;
        if (! is_object($ci)) {
            $ci = & get_instance();
        }
        $error_lang_alias = empty($error_lang_alias) ? 'error_unknown' : $error_lang_alias;
        $error_lang_alias = (! is_array($error_lang_alias)) ? array($error_lang_alias) : $error_lang_alias;
        foreach ($error_lang_alias as $error_lang_alias) {
            $ci->errors[] = $error_lang_alias;
        }
    }

    function display_errors()
    {
        static $ci;
        if (! is_object($ci)) {
            $ci = & get_instance();
        }
        if (! empty($ci->errors)) {
            error($ci->errors);
        }
        unset($ci->errors);
    }

    function error($error_lang_alias)
    {
        $error =& load_class('Exceptions');
        $error_heading = lang('error_heading');
        $error_heading = empty($error_heading) ? 'An Error Was Encountered' : $error_heading;
        $error_message    = array();
        $error_lang_alias = (! is_array($error_lang_alias)) ? array($error_lang_alias) : $error_lang_alias;
        foreach ($error_lang_alias as $error_lang_alias) {
            $message         = lang($error_lang_alias);
            $error_message[] = empty($message) ? $error_lang_alias : $message;
        }
        echo $error->show_error($error_heading, $error_message);
    }

    /**
     * the_excerpt_reloaded
     *
     * the excerpt reloaded
     *
     * @author  Popov
     * @class
     * @access  public
     *
     * @param   string $str
     *
     * @return  string  $str
     */
    function the_excerpt_reloaded(
        $str,
        $excerpt_length = 120,
        $allowed_tags = '<a>',
        $filter_type = 'none',
        $use_more_link = true,
        $more_link_text = "(more...)",
        $force_more = true,
        $fakeit = 1,
        $fix_tags = true,
        $no_more = false,
        $more_tag = 'div',
        $more_link_title = 'Continue reading this entry',
        $showdots = true
    ) {
        $output = "";
        $text   = $str;
        if ($excerpt_length < 0) {
            $output = $text;
        } else {
            if (! $no_more && strpos($text, '<!--more-->')) {
                $text      = explode('<!--more-->', $text, 2);
                $l         = count($text[0]);
                $more_link = 1;
            } else {
                $text = explode(' ', $text);
                if (count($text) > $excerpt_length) {
                    $l        = $excerpt_length;
                    $ellipsis = 1;
                } else {
                    $l              = count($text);
                    $more_link_text = '';
                    $ellipsis       = 0;
                }
            }
            for ($i = 0; $i < $l; $i ++) {
                $output .= $text[$i] . ' ';
            }
        }
        if ('all' != $allowed_tags) {
            $output = strip_tags($output, $allowed_tags);
        }
        //	$output = str_replace(array("\r\n", "\r", "\n", "  "), " ", $output);
        $output = rtrim($output, "\s\n\t\r\0\x0B");
        $output = ($fix_tags) ? $output : balanceTags($output);
        $output .= ($showdots && $ellipsis) ? '...' : '';
        switch ($more_tag) {
            case('div') :
                $tag = 'div';
                break;
            case('span') :
                $tag = 'span';
                break;
            case('p') :
                $tag = 'p';
                break;
            default :
                $tag = 'span';
        }
        /*if ($use_more_link && $more_link_text) {
          if($force_more) {
            $output .= ' <' . $tag . ' class="more-link"><a href="'. get_permalink($post->ID) . '#more-' . $post->ID .'" title="' . $more_link_title . '">' . $more_link_text . '</a></' . $tag . '>' . "\n";
          } else {
            $output .= ' <' . $tag . ' class="more-link"><a href="'. get_permalink($post->ID) . '" title="' . $more_link_title . '">' . $more_link_text . '</a></' . $tag . '>' . "\n";
          }
        }*/
        //	$output = apply_filters($filter_type, $output);
        return $output;
    }

    /**
     * datetoarray
     *
     * parse date
     *
     * @author  Popov
     * @class
     * @access  public
     *
     * @param   string $date
     *
     * @return  array  $date
     */
    function datetoarray($date)
    {
        $str_date = explode(" ", $date);
        $date     = $str_date[0];
        $time     = $str_date[1];
        $date     = explode("-", $date);
        $time     = explode(":", $time);
        $arr_date = array(
            'year'   => $date[0],
            'month'  => $date[1],
            'day'    => $date[2],
            'hour'   => $time[0],
            'minute' => $time[1],
            'secund' => $time[2]
        );
        return $arr_date;
    }
?>
