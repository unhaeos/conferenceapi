<?php
 /**
 * Class Database
 * 
 * Uses PDO to connect to a SQLite database and execute SQL statements
 *
 * @author John Rooksby
 * @author Jonathan Sanderson
 * @author Maria Salama
 * @author Victor Ayodele
 * @author Haehoon Seo
 * 
 */

class Database 
{
    private $dbConnection;

    /**
     * Constructor that initializes the database connection.
     * 
     * @param string $dbName The name of the SQLite database to connect to.
     */
    public function __construct($dbName) 
    {
        $this->setDbConnection($dbName);  
    }

    /**
     * Sets up the database connection using PDO.
     * 
     * @param string $dbName The name of the SQLite database to connect to.
     * @throws PDOException If there is an error connecting to the database.
     */
    private function setDbConnection($dbName) 
    {
        try {
            $this->dbConnection = new PDO('sqlite:'.$dbName);
            $this->dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            http_response_code(500);
            $data['message'] = $exception->getMessage();
            echo json_encode($data);
            exit();
        }
    }

    /**
     * Executes an SQL statement
     * 
     * @param string $sql The SQL statement to execute
     * @param array $params The parameters to bind to the SQL statement
     * 
     * @return array The result of the SQL statement
     */
    public function executeSQL($sql, $params=[])
    { 
        try {
            $stmt = $this->dbConnection->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            http_response_code(500);
            $data['message'] = $exception->getMessage();
            $data['code'] = $exception->getCode();
            $data['file'] = $exception->getfile();
            $data['line'] = $exception->getLine();
            echo json_encode($data);
            exit();
        }
    }  
}
