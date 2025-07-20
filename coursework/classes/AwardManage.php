<?php

/**
 * Class AuthorManage
 * 
 * This class handles the endpoint related to "authormanage" in the API.
 * It can give or take away the award from the content.
 * 
 * @author Haehoon Seo
 */

class AwardManage extends Endpoint 
{
    /**
     * Gives an award to a content.
     * 
     * Validates 'content_id' and 'award_id', checks if the content already has the award,
     * and inserts the new award.
     *
     * @throws ClientError If the provided data is invalid or the content already has the award.
     * 
     * @return void
     */
    protected function post() 
    {
        $db = new Database('db/chi2023.sqlite');
        $sql = 'INSERT INTO content_has_award (content, award) VALUES (:content_id, :award_id)';
        $request_body = file_get_contents('php://input');
        $request_body = json_decode($request_body, true);

        if ($request_body === null) {
            throw new ClientError("No data provided", 400);
        }
        foreach ($request_body as $key => $value) {
            if (!in_array($key, ['content_id','award_id'])) {
                throw new ClientError("Invalid parameter : $key", 400);
            }
        }

        if (array_key_exists('content_id', $request_body)) {

            if (!is_numeric($request_body['content_id'])) {
                throw new ClientError("Content ID should be numeric",400);
            }
            $params['content_id'] = $request_body['content_id'];
        } else {
            throw new ClientError("Content ID is required", 400);
        }

        if (array_key_exists('award_id', $request_body)) {

            if (!is_numeric($request_body['award_id'])) {
                throw new ClientError("Award ID should be numeric",400);
            }
            $params['award_id'] = $request_body['award_id'];
        } else {
            throw new ClientError("Award ID is required", 400);
        }

        $checkSql = 'SELECT COUNT(*) FROM content_has_award WHERE content_has_award.content = :content_id';
        $checkParams = [];
        $checkParams[':content_id'] = $request_body['content_id'];
        $result = $db->executeSQL($checkSql, $checkParams);

        if ($result[0]['COUNT(*)'] > 0) {
            throw new ClientError("This content already got award", 400);
        }

        $db->executeSQL($sql, $params);
        $this->setData("Award given");

    }

    /**
     * Removes an award from a content.
     * 
     * Validates 'content_id' and removes the award from the database.
     *
     * @throws ClientError If the provided data is invalid.
     * 
     * @return void
     */
    protected function delete() 
    {
        $db = new Database('db/chi2023.sqlite');
        $sql = 'DELETE FROM content_has_award WHERE content_has_award.content = :content_id';
        $request_body = file_get_contents('php://input');
        $request_body = json_decode($request_body, true);
     
        if ($request_body === null) {
            throw new ClientError("No data provided", 400);
        }

        foreach ($request_body as $key => $value) {
            if (!in_array($key, ['content_id'])) {
                throw new ClientError("Invalid parameter : $key", 400);
            }
        }

        if (array_key_exists('content_id', $request_body)) {
            
            if (!is_numeric($request_body['content_id'])) {
                throw new ClientError("Award name should be string",400);
            }
            $params['content_id'] = $request_body['content_id'];
        } else {
            throw new ClientError("Content ID is required", 400);
        }

        $db->executeSQL($sql, $params);
        $this->setData("Award taken");
    }

}