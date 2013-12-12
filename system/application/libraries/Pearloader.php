<?php

class Pearloader {
	function load($package, $class = NULL, $options = NULL) {
		if (is_null($class)) {
			require_once $package. '.php';
			$classname = $package;
		} else {
			require_once $package. '/'. $class. '.php';
			$package = str_replace("/", "_", $package);
			$classname = $package. '_'. $class;
		}
		if (($count = func_num_args()) > 2) {
			$params = '';
			for ($i = 2; $i < $count; $i++) {
				eval("\$var$i = func_get_arg($i);");
				$params .= "\$var$i". (($i + 1 == $count)? '': ', ');
			}
			eval("\$instance = new $classname($params);");
			return $instance;
		} else {
			return new $classname();
		}
	}
}

?>