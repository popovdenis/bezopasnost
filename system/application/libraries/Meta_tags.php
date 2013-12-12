<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Denis
 * Date: 19.06.11
 * Time: 20:01
 *
 * Usage example:
 * -------------------
 * $config['doctype'] = 'xhtml';
 * $this->load->library('meta_tags');
 * $this->meta_tags->initialize($config);
 * $this->meta_tags->set_meta_tag('author', 'Per Sikker Hansen');
 * $this->meta_tags->add_robots_rule('NOINDEX');
 * $this->meta_tags->add_keyword('php');
 * echo $this->meta_tags->generate_meta_tags();
 * -------------------
 * Optionally you can store a default configuration array in config.php with this prototype:
 * $config['meta_tags']['doctype'] = 'xhtml';
 * $config['meta_tags']['tags'] = array('tagname'=>'tagcontent', 'another tag'=>'some other content');
 * $config['meta_tags']['robots'] = array('NOINDEX', 'FOLLOW', 'NOARCHIVE');
 * $config['meta_tags']['title'] = array('great', 'php', 'framework');
 * $config['meta_tags']['keywords'] = array('great', 'php', 'framework');
 * $config['meta_tags']['description'] = array('great', 'php', 'framework');
 *
 */
class Meta_tags {

    var $CI;
    var $doctype;
    var $tags;
    var $title;
    var $keywords;
    var $description;
    var $robots;

    /**
     * Class constructor with optional parameter, which calls the initialize() method
     * @param $config array optional array containing configuration
     */
    function Meta_tags($config = array())
    {
        $this->CI =& get_instance();
        $this->initialize($config);
    }

    /**
     * Initializes the library with the configuration given either in a parameter or in config.php
     * @param $config array optional array containing configuration and global default meta tags
     */
    function initialize($config = array())
    {
        if(empty($config))
        {
            $config = $this->CI->config->item('meta_tags');
            if(!$config)
                $config = array();
        }

        if(isset($config['doctype']))
        {
            $this->doctype = $config['doctype'];
        }
        else
        {
            $this->doctype = 'xhtml'; //if no doctype is given, default to XHTML
        }

        if(isset($config['tags']))
            $this->tags = $config['tags'];

        if(isset($config['title']))
            $this->title = $config['title'];

        if(isset($config['keywords']))
            $this->keywords = $config['keywords'];

        if(isset($config['description']))
            $this->description = $config['description'];

        if(isset($config['robots']))
            $this->robots = $config['robots'];
    }

    /**
     * Sets the doctype for which the tags will be generated
     * @param $doctype string choices are 'xhtml' and 'html'
     */
    function set_doctype($doctype)
    {
        $this->doctype = $doctype;
    }

    /**
     * Sets a meta tag with name and content
     * @param $name string
     * @param $content string
     */
    function set_meta_tag($name, $content)
    {
        $this->tags->$name = $content;
    }

    /**
     * Removes a meta tag
     * @param $name string name of the tag
     */
    function unset_meta_tag($name)
    {
        unset($this->tags->$name);
    }

    /**
     * Adds a unit to the title array
     * @param $title string
     */
    function add_title($title)
    {
        $this->remove_title($title);
        $this->title = $title;
    }

    /**
     * Searches the title array and removes the given keyword
     * @param $title string
     */
    function remove_title($title)
    {
        $this->_search_and_remove($title, $this->tags);
    }

    /**
     * Adds a unit to the keyword array
     * @param $keyword string
     */
    function add_keyword($keyword)
    {
        $this->remove_keyword($keyword);
        $this->keywords[] = $keyword;
    }

    /**
     * Searches the keywords array and removes the given keyword
     * @param $keyword string
     */
    function remove_keyword($keyword)
    {
        $this->_search_and_remove($keyword, $this->tags);
    }

    /**
     * Adds a unit to the description array
     * @param $description string
     */
    function add_description($description)
    {
        $this->remove_description($description);
        $this->description[] = $description;
    }

    /**
     * Searches the description array and removes the given keyword
     * @param $description string
     */
    function remove_description($description)
    {
        $this->_search_and_remove($description, $this->tags);
    }

    /**
     * Adds a rule to the robots array
     * @param $rule string
     */
    function add_robots_rule($rule)
    {
        $this->remove_robots_rule($rule);
        $this->robots[] = $rule;
    }

    /**
     * Searches the robots array and removes the given rule
     * @param $rule string
     */
    function remove_robots_rule($rule)
    {
        $this->_search_and_remove($rule, $this->robots);
    }

    /**
     * Library-only function for searching and removing
     * @param $needle string
     * @param $haystack array
     */
    function _search_and_remove($needle, $haystack)
    {
        $key = array_search($needle, $haystack);
        if($key)
        {
            unset($haystack[$key]);
        }
    }

    /**
     * Passes the contained data to private functions for processing
     * @return string the compiled meta tags for insertion into your view
     */
    function generate_meta_tags()
    {
        switch(strtolower($this->doctype))
        {
            case 'xhtml':
                $output = $this->_generate_xhtml_meta_tags();
                break;
            case 'html':
                $output = $this->_generate_html_meta_tags();
                break;
        }
        return $output;
    }

    /**
     * Generates meta tags following the XHTML conventions
     * @return string the compiled meta tags for insertion into your view
     */
    function _generate_xhtml_meta_tags()
    {
        $output = "\n";

        if(!empty($this->tags))
        {
            foreach($this->tags as $name=>$content)
            {
                $output .= '<meta name="'.$name.'" content="'.$content.'" />'."\n";
            }
        }

        if(!empty($this->title))
            $output .= '<title>'.$this->title."</title>\n";

        if(!empty($this->keywords))
            $output .= '<meta name="keywords" content="'.implode(',', $this->keywords).'" />'."\n";

        if(!empty($this->description))
            $output .= '<meta name="description" content="'.implode(',', $this->description).'" />'."\n";

        if(!empty($this->robots))
            $output .= '<meta name="robots" content="'.implode(',', $this->robots).'" />'."\n";

        return $output;
    }

    /**
     * Generates meta tags following the HTML conventions
     * @return string the compiled meta tags for insertion into your view
     */
    function _generate_html_meta_tags()
    {
        $output = "\n";

        if(!empty($this->tags))
        {
            foreach($this->tags as $name=>$content)
            {
                $output .= '<META NAME="'.$name.'" CONTENT="'.$content.'">'."\n";
            }
        }

        if(!empty($this->title))
            $output .= '<title>'.$this->title."</title>\n";

        if(!empty($this->keywords))
            $output .= '<META NAME="keywords" CONTENT="'.implode(',', $this->keywords).'">'."\n";

        if(!empty($this->description))
            $output .= '<META NAME="description" CONTENT="'.implode(',', $this->description).'">'."\n";

        if(!empty($this->robots))
            $output .= '<META NAME="robots" CONTENT="'.implode(',', $this->robots).'">'."\n";

        return $output;
    }

}

/* End of file meta_tags.php */
/* Location: ./system/application/libraries/meta_tags.php */
