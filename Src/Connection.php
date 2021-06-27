<?php
/*
 * Created by  (c)danidoble 2021.
 */

namespace Danidoble\Database;

use Danidoble\Database\Interfaces\Connection as IDD;
use PDO;
use PDOException;

/**
 * Class Connection
 * @package Danidoble\Database
 */
class Connection implements IDD {

    protected $db_username;
    protected $db_name;
    protected $db_password;
    protected $db_host;
    public $db_result;
    public $db_connection;
    public $db_sql_statement;
    public $db_bindings;

    /**
     * Connection constructor.
     * @param string $db_host
     * @param string $db_name
     * @param string $db_password
     * @param string $db_username
     */
    public function __construct(string $db_name="danidoble", string $db_password="", string $db_username="root", string $db_host="localhost")
    {
        $this->db_username = $db_username;
        $this->db_name = $db_name;
        $this->db_password = $db_password;
        $this->db_host = $db_host;
    }

    /**
     * @return false|string
     */
    public function __toString()
    {
        return json_encode($this);
    }


    /**
     * @param $db_username
     * @return $this
     */
    public function setDbUsername($db_username): Connection
    {
        $this->db_username = $db_username;
        return $this;
    }

    /**
     * @param $db_name
     * @return $this
     */
    public function setDbName($db_name): Connection
    {
        $this->db_name = $db_name;
        return $this;
    }

    /**
     * @param $db_password
     * @return $this
     */
    public function setDbPassword($db_password): Connection
    {
        $this->db_password = $db_password;
        return $this;
    }

    /**
     * @param $db_host
     * @return $this
     */
    public function setDbHost($db_host): Connection
    {
        $this->db_host = $db_host;
        return $this;
    }

    /**
     * @return PDO|null
     */
    private function setDbConnection(): ?PDO
    {
        try {
            $dsn = "mysql:host=$this->db_host;dbname=$this->db_name";
            return new PDO($dsn, $this->db_username, $this->db_password);
        } catch (PDOException $e){
            echo $e->getMessage();
        }
        return null;
    }

    /**
     * @return $this
     */
    public function closeConnection(): Connection
    {
        $this->db_connection = null;
        return $this;
    }


    /**
     * @param int $exception
     * @return $this
     */
    public function connect($exception = PDO::ERRMODE_SILENT): Connection
    {
        $this->db_connection = $this->setDbConnection();
        if($this->db_connection !== null){
            $this->db_connection->setAttribute(PDO::ATTR_ERRMODE, $exception);
        }

        return $this;
    }
}