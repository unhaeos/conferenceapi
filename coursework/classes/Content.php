<?php

/**
 * Class Content
 * 
 * This class handles the endpoint related to "content" in the API. It retrieves content data
 *
 * @author Haehoon Seo
 */

class Content extends Endpoint 
{
    /**
     * Retrieves content data based on the provided query parameters.
     * 
     * Validates query parameters (content_id, author_id, search, page), and constructs
     * a dynamic SQL query to fetch content data, including related awards and content types.
     *
     * @throws ClientError If any provided parameter is invalid.
     * 
     * @return void
     */
    protected function get() 
    {
        $db = new Database(dbName: 'db/chi2023.sqlite');
        $sql = 'SELECT content.id AS content_id, content.title, content.abstract, content.doi_link, content.preview_video, type.name AS type, award.name AS award
                FROM content 
                LEFT JOIN content_has_award ON content.id = content_has_award.content
                LEFT JOIN award ON content_has_award.award = award.id
                LEFT JOIN type ON content.type = type.id';
        $params = [];

        $_GET = array_change_key_case($_GET, CASE_LOWER);

        foreach ($_GET as $key => $value) {
            if (!in_array($key, ['content_id','author_id','search','page'])) {
                throw new ClientError("Invalid parameter : $key", 400);
            }
        }
        
        if (isset($_GET['author_id'])) {

            if (!is_numeric($_GET['author_id'])){
                throw new ClientError('Invalid author_id : It should be numeric', 400);
            }

            $sql .= ' LEFT JOIN affiliation ON content.id = affiliation.content
                    WHERE affiliation.author = :author_id';
            $params[':author_id'] = $_GET['author_id'];
        }

        if (isset($_GET['content_id'])) {
            if (!is_numeric($_GET['content_id'])){
                throw new ClientError('Invalid content_id : It should be numeric', 400);
            }

            if (isset($_GET['author_id'])){
                $sql .= ' AND';
            } else {
                $sql .= ' WHERE';
            }
                $sql .= ' content.id = :content_id';
                $params[':content_id'] = $_GET['content_id'];

        }

        if (isset($_GET['search'])) {
            if (!is_string($_GET['search'])){
                throw new ClientError('Invalid search param : It should be string', 400);
            }

            $sql .= ' WHERE  LOWER(content.title) LIKE LOWER(:search) OR content.abstract LIKE :search';
            $params[':search'] = '%' . strtolower($_GET['search']) . '%';
        }

        if (isset($_GET['page'])) {
            if ((!is_numeric($_GET['page'])) || ((int)$_GET['page'] <= 0))    {
                throw new ClientError('Invalid page param : It should be a positive integer greater than zero', 400);
            }
            
            $offset = ($_GET['page'] - 1) * 10;
            $sql .= ' LIMIT 10 OFFSET :offset';
            $params[':offset'] = $offset;
        }


        $data = $db->executeSQL($sql, $params);
        $this->setData($data);
    }


}