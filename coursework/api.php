<?php

/** 
 * Front door script for the API
 * 
 * This a simple API that returns data about academic conferencd databse : chi2023. 
 * 
 * @author John Rooksby
 * @author Jonathan Sanderson
 * @author Maria Salama
 * @author Victor Ayodele
 * 
 * @version couresework
 */

require 'classes/Autoloader.php';
Autoloader::register();
require 'classes/ExceptionHandler.php';
ExceptionHandler::register();

$response = new Response();

$env = require 'env.php';
$api_key = new ApiKey($env['api_key']);

if (!($api_key->validateApi_key())) {
    throw new ClientError("Invalid Key", 401);
}


switch (strtolower(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) 
{

    case '/kv5035/coursework/developer':
        $endpoint = new Developer();
        break;

    case '/kv5035/coursework/author':
        $endpoint = new Author();
        break;

    case '/kv5035/coursework/content':
        $endpoint = new Content();
        break;
    
    case '/kv5035/coursework/award':
        $endpoint = new Award();
        break;
    
    case '/kv5035/coursework/awardmanage':
        $endpoint = new AwardManage();
        break;


    default:
        throw new ClientError("Endpoint not found",404);
}
 
$data = $endpoint->getData();
$response->outputJSON($data);