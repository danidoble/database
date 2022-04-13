<?php
/*
 * Created by  (c)danidoble 2022.
 */

namespace Danidoble\Database;

use Danidoble\Database\Interfaces\Connection as IDD;
use PDO;
use PDOStatement;

/**
 * Class Connection
 * @package Danidoble\Database
 */
class Connection implements IDD
{

    protected string $db_username;
    protected string $db_name;
    protected string $db_password;
    protected string $db_host;
    public ?PDO $db_connection;
    public PDOStatement $db_sql_statement;
    public array $db_bindings = [];

    /**
     * Connection constructor.
     * @param string $db_host
     * @param string $db_name
     * @param string $db_password
     * @param string $db_username
     */
    public function __construct(string $db_name = "danidoble", string $db_password = "", string $db_username = "root", string $db_host = "localhost")
    {
        $this->db_username = $db_username;
        $this->db_name = $db_name;
        $this->db_password = $db_password;
        $this->db_host = $db_host;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return json_encode($this);
    }

    /**
     * @param $db_username
     * @return $this
     */
    public function setDbUsername($db_username): static
    {
        $this->db_username = $db_username;
        return $this;
    }

    /**
     * @param $db_name
     * @return $this
     */
    public function setDbName($db_name): static
    {
        $this->db_name = $db_name;
        return $this;
    }

    /**
     * @param $db_password
     * @return $this
     */
    public function setDbPassword($db_password): static
    {
        $this->db_password = $db_password;
        return $this;
    }

    /**
     * @param $db_host
     * @return $this
     */
    public function setDbHost($db_host): static
    {
        $this->db_host = $db_host;
        return $this;
    }

    /**
     * @return $this
     */
    public function closeConnection(): static
    {
        $this->db_connection = null;
        return $this;
    }


    /**
     * @param int $exception
     * @return $this
     */
    public function connect($exception = PDO::ERRMODE_EXCEPTION): static
    {
        $this->db_connection = $this->setDbConnection();
        $this->db_connection?->setAttribute(PDO::ATTR_ERRMODE, $exception);

        return $this;
    }

    /**
     * @return ?PDO
     */
    private function setDbConnection(): ?PDO
    {
        $dsn = "mysql:host=$this->db_host;dbname=$this->db_name";
        return new PDO($dsn, $this->db_username, $this->db_password);
    }

}