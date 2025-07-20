<?php

/**
 * Class Award
 * 
 * This class handles the endpoint related to "award" in the API. It retrieves author data
 *
 * @author Haehoon Seo
 */

class Award extends Endpoint
{
    /**
     * Retrieves a list of awards.
     * 
     * If any GET parameters are provided, an error is thrown.
     *
     * @throws ClientError If parameters are provided when they shouldn't be.
     * 
     * @return void
     */
    protected function get() 
    {
        $db = new Database('db/chi2023.sqlite');
        $sql = 'SELECT award.id AS award_id, award.name FROM award';
        $params = [];
        if (!empty($_GET)) {
            throw new ClientError("Parameter not available", 400);
        }

        $data = $db->executeSQL($sql, $params);
        $this->setData($data);
    }

    /**
     * Adds a new award.
     * 
     * Validates the 'name' parameter and ensures it doesn't already exist.
     * 
     * @throws ClientError If the provided data is invalid or the award already exists.
     * 
     * @return void
     */
    protected function post()
    {
        $db = new Database('db/chi2023.sqlite');
        $sql = 'INSERT INTO award (name) VALUES (:name)';
        $request_body = file_get_contents('php://input');
        $request_body = json_decode($request_body, true);
        
        if ($request_body === null) {
            throw new ClientError("No data provided", 400);
        }

        foreach ($request_body as $key => $value) {
            if (!in_array($key, ['name'])) {
                throw new ClientError("Invalid parameter : $key", 400);
            }
        }
        
        if (!is_string($request_body['name'])) {
            throw new ClientError("Award name should be string",400);
        }

        if (array_key_exists('name', $request_body)) {
            $params['name'] = $request_body['name'];
        } else {
            throw new ClientError("Name is required", 400);
        }

        $checkSql = 'SELECT COUNT(*) FROM award WHERE award.name = :name';
        $checkParams = [];
        $checkParams[':name'] = $request_body['name'];
        $result = $db->executeSQL($checkSql, $checkParams);

        if ($result[0]['COUNT(*)'] > 0) {
            throw new ClientError("Award name already exists", 400);
        }
        

        $db->executeSQL($sql, $params);
        $this->setData("Award added");
    }

    /**
     * Updates an existing award.
     * 
     * Validates 'name' and 'award_id', checks if award name already exists, and updates the award.
     * 
     * @throws ClientError If the provided data is invalid, the award doesn't exist, or the award name already exists.
     * 
     * @return void
     */
    protected function patch() 
    {
        $db = new Database('db/chi2023.sqlite');
        $sql = 'UPDATE award SET name = :name WHERE award.id = :award_id';
        $request_body = file_get_contents('php://input');
        $request_body = json_decode($request_body, true);

        if ($request_body === null) {
            throw new ClientError("No data provided", 400);
        }
        
        foreach ($request_body as $key => $value) {
            if (!in_array($key, ['name','award_id'])) {
                throw new ClientError("Invalid parameter : $key", 400);
            }
        }
        
        if (array_key_exists('name', $request_body)) {

            if (!is_string($request_body['name'])) {
                throw new ClientError("Award name should be string",400);
            }
    
            $params['name'] = $request_body['name'];
        } else {
            throw new ClientError("Name is required", 400);
        }
        
        if (array_key_exists('award_id', $request_body)) {

            if (!is_string($request_body['award_id'])) {
                throw new ClientError("Award name should be string",400);
            }
            $params['award_id'] = $request_body['name'];
        } else {
            throw new ClientError("Award ID is required", 400);
        }

        $checkSql = 'SELECT COUNT(*) FROM award WHERE award.name = :name';
        $checkParams = [];
        $checkParams[':name'] = $request_body['name'];
        $result = $db->executeSQL($checkSql, $checkParams);

        if ($result[0]['COUNT(*)'] > 0) {
            throw new ClientError("Award name already exists", 400);
        }

        $db->executeSQL($sql, $params);
        $this->setData("Award patched");

    }

    /**
     * Deletes an existing award.
     * 
     * Validates 'award_id' and deletes the award from the database.
     * 
     * @throws ClientError If the provided data is invalid or the award doesn't exist.
     * 
     * @return void
     */
    protected function delete() 
    {
        $db = new Database('db/chi2023.sqlite');
        $sql = 'DELETE FROM award WHERE award.id = :award_id';
        $request_body = file_get_contents('php://input');
        $request_body = json_decode($request_body, true);
        
        if ($request_body === null) {
            throw new ClientError("No data provided", 400);
        }

        foreach ($request_body as $key => $value) {
            if (!in_array($key, ['award_id'])) {
                throw new ClientError("Invalid parameter : $key", 400);
            }
        }

        if (array_key_exists('award_id', $request_body)) {

            if (!is_numeric($request_body['award_id'])) {
                throw new ClientError("Award name should be string",400);
            }
            $params['award_id'] = $request_body['award_id'];
        } else {
            throw new ClientError("Award ID is required", 400);
        }

        $db->executeSQL($sql, $params);
        $this->setData("Award deleted");
    }

}