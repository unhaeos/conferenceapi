<?php
$env = require 'env.php';

class ApiKey
{
    /**
     * @var string The stored API key.
     */
    private $api_key;

    /**
     * ApiKey constructor.
     * 
     * Initializes the ApiKey object with a predefined API key.
     *
     * @param string $api_key The API key to be validated against.
     */
    public function __construct($api_key)
    {
        $this -> setApi_key($api_key);
    }

    /**
     * Sets the API key.
     * 
     * This method stores the provided API key for later validation.
     *
     * @param string $api_key The API key to store.
     * 
     * @return void
     */
    private function setApi_key($api_key) 
    {
        $this -> api_key = $api_key;
    }

    
    /**
     * Validates the API key from the Authorization header.
     * 
     * This method checks if the 'Authorization' header exists in the incoming request.
     * 
     * @throws Exception If the 'Authorization' header is missing, invalid, or the API key does not match.
     * 
     * @return bool Returns true if the provided API key matches the stored key, false otherwise.
     */
    public function validateApi_key() 
    {
        $allHeaders = getallheaders();
        
        if (array_key_exists('Authorization', $allHeaders)) {
            $authorizationHeader = $allHeaders['Authorization'];
        } elseif (array_key_exists('authorization', $allHeaders)) {
            $authorizationHeader = $allHeaders['authorization'];
        } else {
            throw new Exception("Authorization Header Not Found", 401);
        }

        if (substr($authorizationHeader, 0, 7) != 'Bearer ') {
            throw new Exception("Invalid Authorization Header", 401);
        }

        $providedApiKey = trim(substr($authorizationHeader, 7));

        return $providedApiKey === $this->api_key;
    }

}