<?php
/**
 * User: Denis
 * Date: 10.03.14
 * Time: 15:47
 */

class BI_Output extends CI_Output
{
    private $ci = null;
    private static $is_cached = false;

    public function __construct()
    {
//        $this->ci =& get_instance();
    }

    private function loadInstance()
    {
        if ($this->ci === null) {
            $this->ci =& get_instance();
        }
    }

    public function init()
    {
        self::loadInstance();

        $segment = $this->ci->uri->segment(1);
        if (empty($segment)) {
            return false;
        }
        switch ($segment) {
            case 'about':
            case 'information':
            case 'partners':
            case 'products':
            case 'sertificates':
            case 'contacts':
            case 'settings':
                $pathToCache = BASEPATH . 'cache/' . $segment . '/';
                if (! is_dir($pathToCache)) {
                    mkdir($pathToCache);
                }
                $this->setCacheDir($pathToCache);
                break;
        }

        return true;
    }

    private function setCacheDir($cacheDir)
    {
        $this->ci->config->set_item('cache_path', $cacheDir);
    }

    function cache($time)
    {
        $this->init();
//        $this->get_cache_URI();
        parent::cache($time);
    }

    /**
     * Get the uri of a given cached page
     *
     * @access    public
     *
     * @return array|bool
     */
    function get_cache_URI()
    {
        $cache_path = ($this->ci->config->item('cache_path') == '')
            ? BASEPATH . 'cache/'
            : $this->ci->config->item('cache_path');
        if (!is_dir($cache_path) OR !is_writable($cache_path)) {
            return false;
        }

        $uri = $this->ci->config->item('base_url').
            $this->ci->config->item('index_page').
            $this->ci->uri->uri_string();
        $cache_path .= md5($uri);

        if (file_exists($cache_path)) {
            $cacheFileSize = filesize($cache_path);
            $realFileSize = strlen(file_get_contents($uri));
            if ($cacheFileSize == $realFileSize) {
                self::$is_cached = true;
            }
        }
        return array('path' => $cache_path, 'uri' => $uri);
    }

    public function _write_cache($output)
    {
        if (!self::$is_cached) {
            $path       = $this->ci->config->item('cache_path');
            $cache_path = ($path == '') ? BASEPATH . 'cache/' : $path;

            if (!is_dir($cache_path) OR !is_really_writable($cache_path)) {
                return;
            }

            $uri = $this->ci->config->item('base_url') .
                $this->ci->config->item('index_page') .
                $this->ci->uri->uri_string();
            $cache_path .= md5($uri);

            if (!$fp = @fopen($cache_path, FOPEN_WRITE_CREATE_DESTRUCTIVE)) {
                return;
            }

            if (flock($fp, LOCK_EX)) {
                fwrite($fp, $output);
                flock($fp, LOCK_UN);
            } else {
                return;
            }
            fclose($fp);
            @chmod($cache_path, DIR_WRITE_MODE);
        }
    }
} 