<?php

/** 
 * ExceptionHandler class
 * 
 * This is the class for all Error Exception. 
 * 
 * @author John Rooksby
 * @author Jonathan Sanderson
 * @author Maria Salama
 * @author Victor Ayodele
 * @author Haehoon Seo
 * 
 */

abstract class ExceptionHandler
{
    public static function register() 
    {
        set_exception_handler(array(__CLASS__, 'handleException'));
    }
 
    public static function handleException($e) 
    {        
        http_response_code($e->getCode());
        $output['details']['exception'] = $e->getMessage();
        $output['details']['file'] = $e->getFile();
        $output['details']['line'] = $e->getLine();
        echo json_encode($output);
        exit(); 
    }
}
