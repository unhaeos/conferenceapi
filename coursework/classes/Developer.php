<?php

/**
 * Class Developer
 * 
 * This class give developer's information.
 *
 * @author Haehoon Seo
 */

class Developer extends Endpoint 
{
    protected function get() 
    {
        if (!empty($_GET)) {
            throw new ClientError("Parameter not available", 400);
        }
        http_response_code(200);
        $data['name'] = 'Haehoon Seo';
        $data['id'] = 'w24057077';
        $this -> setData($data);
    }
}
