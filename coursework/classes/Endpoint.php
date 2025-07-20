<?php

/** 
 * Endpoint class
 * 
 * This is the base class for all endpoints. It contains basic functionality
 * for handling requests. It will return a 405 error for any method that is
 * not implemented.
 * 
 * @author John Rooksby
 * @author Jonathan Sanderson
 * @author Maria Salama
 * @author Victor Ayodele
 * @author Haehoon Seo
 * 
 */

class Endpoint
{
    private $data;
 
    public function __construct()
    {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $this->get();
                break;
            case 'POST':
                $this->post();
                break;
            case 'PATCH':
                $this->patch();
                break;
            case 'PUT':
                $this->put();
                break;
            case 'DELETE':
                $this->delete();
                break;
            default:
                http_response_code(405);
                $this->setData("method not allowed");
                break;
        }
    }
 
    protected function setData($data)
    {
        $this->data = $data;
    }
 
    public function getData()
    {
        return $this->data;
    }
    
    protected function get()
    {
        http_response_code(405);
        $this->setData("GET method not allowed");
    }
 
    protected function post()
    {
        http_response_code(405);
        $this->setData("POST method not allowed");
    }
 
    protected function patch()
    {
        http_response_code(405);
        $this->setData("PATCH method not allowed");
    }
 
    protected function put()
    {
        http_response_code(405);
        $this->setData("PUT method not allowed");
    }
 
    protected function delete()
    {
        http_response_code(405);
        $this->setData("DELETE method not allowed");
    }
 
}