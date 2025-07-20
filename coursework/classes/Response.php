<?php

/** 
 * Response class
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
class Response {

    public function __construct() 
    {
        $this -> outputHeaders();
    }

    private function outputHeaders() 
    {
        header('Content-Type: application/json');
    }

    public function outputJSON($data) 
    {
        echo json_encode($data);
    }
}