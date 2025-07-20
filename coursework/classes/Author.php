<?php


/**
 * Class Author
 * 
 * This class handles the endpoint related to "author" in the API. It retrieves author data
 *
 * @author Haehoon Seo
 */
class Author extends Endpoint
{
    /**
     * Retrieves author data based on GET parameters.
     * 
     * Supports filters for `content_id`, `author_id`, `search`, and `page`.
     * Validates parameters and constructs the SQL query dynamically.
     *
     * @throws ClientError If parameters are invalid.
     * 
     * @return void
     */
    protected function get() 
    {
        $db = new Database('db/chi2023.sqlite');
        $sql = 'SELECT id AS author_id, name FROM author';
        $params = [];
        
        foreach ($_GET as $key => $value) {
            if (!in_array($key, ['content_id','author_id','search','page'])) {
                throw new ClientError("Invalid parameter : $key", 400);
            }
        }

        $_GET = array_change_key_case($_GET, CASE_LOWER);


        if (isset($_GET['content_id'])) {

            if (!is_numeric($_GET['content_id'])){
                throw new ClientError('Invalid content_id : It should be numeric', 400);
            }

            $sql .= ' JOIN affiliation ON author.id = affiliation.author
                    WHERE affiliation.content = :content_id';
            $params[':content_id'] = $_GET['content_id'];

        }

        if (isset($_GET['author_id'])) {

            if (!is_numeric($_GET['author_id'])){
                throw new ClientError('Invalid author_id : It should be numeric', 400);
            }

            if (isset($_GET['content_id'])) {
                $sql .= ' AND';
            } else {
                $sql .= ' WHERE';
            }
        
            $sql .= ' author.id = :author_id';
            $params[':author_id'] = $_GET['author_id'];
        }

        if (isset($_GET['search'])) {
            if (!is_string($_GET['search'])){
                throw new ClientError('Invalid search param : It should be string', 400);
            }

            $sql .= ' WHERE  LOWER(author.name) LIKE LOWER(:search)';
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