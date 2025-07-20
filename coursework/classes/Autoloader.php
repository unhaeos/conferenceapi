<?php

/** 
 * Autoloader class
 * 
 * This is the class for requring other class automatically. 
 * 
 * @author John Rooksby
 * @author Jonathan Sanderson
 * @author Maria Salama
 * @author Victor Ayodele
 * @author Haehoon Seo
 * 
 */
abstract class Autoloader
{
    /**
     * Registers the autoloader function.
     * 
     * This method registers the static `autoload` method to be called whenever a class
     * is used and has not been loaded yet. It uses PHP's `spl_autoload_register` function.
     * 
     * @return void
     * @throws Exception If registration fails.
     */
    public static function register() 
    {
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    /**
     * Autoloads a class file based on the class name.
     *
     * 
     * @param string $className The name of the class to load.
     * 
     * @return void
     * @throws Exception If the class file does not exist.
     */
    public static function autoload($className) 
    {
        $file = 'classes/' . $className . '.php';
        $file = str_replace('\\', DIRECTORY_SEPARATOR, $file);
        
        if (!file_exists($file)) {
            throw new exception("Error: Class file for $className not found!",400);
        } else {
            require $file;
        }
    }
}