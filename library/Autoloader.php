<?php

/**
 *   Package:  Core Application
 *   Authors:  Traven, Todd
 *   Version:  1.o.0
**/

/**
 *   Base QuadCMS Core Autoloader Class  
 *   This has to be the first class loaded and sets up as
 *   the applications registry. Other classes depends on 
 *   this class to load them.
**/

class Autoloader {
    
    // Instance of the class
    protected static $_instance
    
    //  Stores if the class has been setup yet
    protected $_setup = false;
    
    //  Path to the root directory
    protected $_rootPath = '.';
    
    
    //  Do not use the constructor. Protected status.
    //  getInstance() instead of the constructor.
    protected function __construct() {
    }
    
    public function setupAutoloader($rootPath) {
        if($this->_setup) {
            return;
        }
        
        $this->_setupAutoloader();
        $this->_rootPath;
        
        //  Returns true once setup
        $this->_setup = true;
    }
    
    //  Internal method that actually applies our autoloader.
    protected function _setupAutoloader() {
        if (@ini_get('open_basedir')) {
            //  Servers don't seem to set include_path correctly with open_basedir.
            //  This is our work around for fixing the issue.
			set_include_path($this->_rootPath . PATH_SEPARATOR . '.');
		}
		else {
			set_include_path($this->_rootPath . PATH_SEPARATOR . '.' . PATH_SEPARATOR . get_include_path());
		}
        
        //  require 'library/PhalconPHP/Autoloader.php';
        //  $autoloader = PhalconPHP_AutoLoader::Instance();
        //  $autoloader->Instance();
        //  $autoloader->PhalconPHP/Autoloader();
    }
    
    //  Autoload the spedificed class
    public function Autoload($class) {
        if (class_exists($class, false) || interface_exists($class, false)) {
			return true;
		}
        
        $filename = $this->autoloaderClassToFile($class);
		if (!$filename) {
			return false;
		}

		if (file_exists($filename)) {
			include($filename);
			return (class_exists($class, false) || interface_exists($class, false));
		}

		return false;
    }
    
    //  Returns a class name to the autoloader path
    public function classToFile($class) {
		if (preg_match('#[^a-zA-Z0-9_\\\\]#', $class)) {
			return false;
		}

		return $this->_rootPath . '/' . str_replace(array('_', '\\'), '/', $class) . '.php';
	}
    
    //  Returns the root path
    public function returnRootPath()
	{
		return $this->_rootPath;
	}
    
    //  Returns the instance of the class
    public static function getInstance() {
		if (!self::$_instance) {
			self::$_instance = new self();
		}

        //  Returns the instance to a usable state
		return self::$_instance;
	}
}