<?php

/**
 *   Package:  Core Application
 *   Authors:  Traven, Todd
 *   Version:  1.o.0
**/

/**
 *   Base QuadCMS Core Application Class  
 *   Sets up the environment as necessary and acts as the registry
 *   for QuadCMS and the Core Application.
**/

class Application extends PhalconPHP {
    
    //  Stores if QuadCMS has been initialized.
    protected $_initialized = false;
    
    //  Provides a few configuration options for the
    //  initialization process.
    protected static $_initConfig = array(
        'undoMagicQuotes' => true,
        'setMemoryLimit' => true,
        'resetOutputBuffering' => true
        );
    
    //  Unused currently
    protected $_lazyLoaders = $array();
    
    //  Cache of random data (Binary characters)
    protected static $_randomData = '';
    
    //  Cache of dynamic classes and what they resolve to
    protected static $_classCache = array();
    
    //  If true, Exceptions.php will handle PHP errors/warnings/notices
    //  If false, PHP will most likely handle the errors
    protected static $_errorHandler = true;
    
    //  Controls if QuadCMS is in debug mode
    protected static $_debug;
    
    //  Current time of the web server (Unix timestamp)
    public static $time = 0;
    
    // Hostname of the server
    public static $hostname = 'localhost';
    
    //  Are we using SSL/TSL?
    //  Default is false
    public static $secure = false;
    
    //  Current printable and human-readable versions
    //  Also, usable for visual display of version
    public static $version   = '1.0.0';
    public static $versionID = '10000100';
    
    //  Path to the Configuration files
    //  Path to the root directory
    protected $_configPath = '.';
    protected $_rootPath   = '.';
    
    //  Relative path to the Data directory 
    public static $dataPath = 'data';
    
    
    
    //  Start the application. Sets up the environment.
    public function startApp($configPath = '.', $rootPath = '.', $loadData = true) {
        if($this->_initialized) {
            return;
        }
        
        if (self::$_initConfig['undoMagicQuotes'] && function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
			self::undoMagicQuotes($_GET);
			self::undoMagicQuotes($_POST);
			self::undoMagicQuotes($_COOKIE);
			self::undoMagicQuotes($_REQUEST);
		}
        
        if (function_exists('get_magic_quotes_runtime') && get_magic_quotes_runtime()) {
			@set_magic_quotes_runtime(false);
		}

		if (self::$_initConfig['setMemoryLimit']) {
			self::setMemoryLimit(64 * 1024 * 1024);
		}
        
        if (self::$_initConfig['resetOutputBuffering']) {
			@ini_set('output_buffering', false);
			@ini_set('zlib.output_compression', 0);

			if (!@ini_get('output_handler')) {
				$level = ob_get_level();
				while ($level) {
					@ob_end_clean();
					$newLevel = ob_get_level();
					if ($newLevel >= $level) {
						break;
					}
					$level = $newLevel;
				}
			}
		}
        
        error_reporting(E_ALL | E_STRICT & ~8192);
		set_error_handler(array('Application', 'handlePhpError'));
		set_exception_handler(array('Application', 'handleException'));
		register_shutdown_function(array('Application', 'handleFatalError'));

		date_default_timezone_set('UTC');
		self::$time = time();
		self::$hostname = (empty($_SERVER['HTTP_HOST']) ? '' : $_SERVER['HTTP_HOST']);
		self::$secure = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on');
    
        $this->_configPath = $configPath;
		$this->_rootPath   = $rootPath;
        $this->addLazyLoader('requestPaths', array($this), 'loadRequestPaths')
        
        if ($loadData) {
			$this->loadData();
		}
        
        //  Becomes initialized
        $this->_initialized = true;
    }
    
    
    
    
    //  Helper function for initialization
    public static function initialize() {
        self::className(__CLASS__);
        self::changeInitConfig($initChanges);
		self::getInstance()->startApp($$this_configPath, $this->_rootPath, $this->_loadData);
    }
    
    
    
    //  Loads the default data for the application
    public function loadData() {
        $config = $this->loadConfig();
        self::set('config', $config);
		self::setDebugMode($config->debug);
		self::$DataPath = (string)$config->DataPath;
    }
    
    
    
    //  Merges changes into the configuration
    public static function mergeChanges(array $changes) {
        if($changes) {
            self::$_initConfig = array_merge(self::_initConfig, $changes);
        }
    }
}