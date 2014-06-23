<?php

/**
 *   Package:  Core Application
 *   Authors:  Traven, Todd
 *   Version:  1.o.0
**/

/**
 *   Base QuadCMS Authentication Class  
 *   Sets up the environment as necessary and acts as the registry
 *   for QuadCMS and the Core Application.
**/

class Authentication {
  
    //  Information for this authentication object
    protected $_data = array();
    
    //  The object used to generate salts and passwords
    protected $_hashFunction = '';
    
    protected function _setupHash() {
        if($this->_hashFunction) {
            return;
        }
        
        if(extension_loaded('hash')) {
            //  Default hashing algorithm
            $this->_hashFunction = 'sha512';
        }
        else {
            //  Backup option if not sha512 not supported
            $this->_hashFunction = 'sha256';
        }
    }
    
    //  Perform the generation of the hash based on the function set
    protected function generateHash($data) {
        $this->_setupHash();
        switch($this->_hashFunction) {
            case 'sha512':
                return hash('sha512', $data);
            case 'sha256':
                return hash('sha256', $data);
            default:
                $message = 'Uknown hash type.'
                throw new Exception($message);
        }
    }
}
