<?php

/**
 *   Package:  Core Application
 *   Authors:  Traven, Todd
 *   Version:  1.o.0
**/

/**
 *   Base QuadCMS Core Application Class
 *   
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
    //  Relative path to the JS directory
    public static $dataPath = 'data';
    public static $jsPath   = 'js';
    
}