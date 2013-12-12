<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

require_once('MY_Router.php');

class BI_Router extends MY_Router
{
	public function _validate_request($segments)
	{
		//////////////// start of bi_block ///////////////////
		$ctr_subdirs = $this->config->item('controllers_subdirs');
		
		if ( is_array($ctr_subdirs))
		{ 
			foreach ($ctr_subdirs as $subdir)
			{
				if (strcmp($segments[0],$subdir)==0) 
				{
					return $this->_ci_validate_request($segments);
				}
			}
		}
		//////////////// end of bi_block /////////////////////
		
		(isset($segments[1])) OR $segments[1] = NULL;
	
		/* locate the module controller */
		list($module, $controller) = Router::locate($segments);

		/* no controller found */
		($module === FALSE) AND show_404($controller);
		
		/* set the module directory */
		Router::$path = ($controller) ? $module : NULL ;
		
		/* set the module path */
		$path = ($controller) ? MODOFFSET.$module.'/controllers/' : NULL;

		$this->set_directory($path);

		/* remove the directory segment */
		if ($controller != $module AND $controller != NULL)
			$segments = array_slice($segments, 1);

		return $segments;
	}
	
	public function _ci_validate_request($segments)
	{
		// Does the requested controller exist in the root folder?
		if (file_exists(APPPATH.'controllers/'.$segments[0].EXT))
		{
			return $segments;
		}

		// Is the controller in a sub-folder?
		if (is_dir(APPPATH.'controllers/'.$segments[0]))
		{		
			// Set the directory and remove it from the segment array
			$this->set_directory($segments[0]);
			$segments = array_slice($segments, 1);
			
			if (count($segments) > 0)
			{
				// Does the requested controller exist in the sub-folder?
				if ( ! file_exists(APPPATH.'controllers/'.$this->fetch_directory().$segments[0].EXT))
				{
					show_404($this->fetch_directory().$segments[0]);
				}
			}
			else
			{
				$this->set_class($this->default_controller);
				$this->set_method('index');
			
				// Does the default controller exist in the sub-folder?
				if ( ! file_exists(APPPATH.'controllers/'.$this->fetch_directory().$this->default_controller.EXT))
				{
					$this->directory = '';
					return array();
				}
			
			}

			return $segments;
		}

		// Can't find the requested controller...
		show_404($segments[0]);
	}
}
