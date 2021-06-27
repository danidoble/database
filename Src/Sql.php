<?php

namespace Danidoble\Database;

use Danidoble\Database\Interfaces\Sql as ISql;

/**
 * Class Sql
 * @package Danidoble\Database
 */
class Sql extends Parser implements ISql
{
    protected $table;
    protected $limit = 1;
    protected $offset = 0;
    protected $connection;
    protected $order_by = [];
    protected $group_by = [];
    protected $where = [];
    protected $errors = [];
    public $items;

    /**
     * Sql constructor.
     */
    public function __construct()
    {
        $this->connection = new Connection($_ENV['DB_NAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_USERNAME'], $_ENV['DB_HOST']);
        $this->connection->connect();
    }

    /**
     * @return false|string
     */
    public function __toString()
    {
        return json_encode($this->items);
    }

    /**
     * @return mixed
     */
    public function __invoke()
    {
        return $this->items;
    }

    /**
     * @return mixed
     */
    public function save()
    {
        return $this->table;
        // TODO: Implement save() method.
    }

    /**
     * @return mixed|void
     */
    public function delete()
    {
        // TODO: Implement delete() method.
    }

    /**
     * @param $table
     * @return $this
     */
    public function from($table): Sql
    {
        $this->table = $table;
        return $this;
    }

    /**
     * @param $field
     * @param string $order
     * @return $this
     */
    public function orderBy($field, $order = "asc"): Sql
    {
        $this->order_by[] = [
            $field,
            $order,
        ];
        return $this;
    }

    /**
     * @param $field
     * @return $this
     */
    public function groupBy($field): Sql
    {
        $this->group_by[] = [
            $field,
        ];
        return $this;
    }

    /**
     * @param array $arr
     * @return mixed
     */
    public function first(array $arr=[]){
        $stmt = $this->makeStmt("select",$arr);

        // limit
        $stmt .= $this->strLimit();

        $this->connection->db_sql_statement = $this->connection->db_connection->prepare($stmt);

        foreach ($this->connection->db_bindings as $db_binding) {
            foreach($db_binding as $key => $val){
                $this->connection->db_sql_statement->bindValue($key,$val);
            }
        }
        $this->connection->db_sql_statement->execute();
        $dObject = new DObject();
        $dObject->fetch($this->connection->db_sql_statement->fetch(\PDO::FETCH_ASSOC));
        $this->items = $dObject->items;
        return $this->items;
    }

    /**
     * @param array $arr
     * @return mixed
     */
    public function get($arr = [])
    {
        $stmt = $this->makeStmt("select",$arr);

        $this->connection->db_sql_statement = $this->connection->db_connection->prepare($stmt);

        foreach ($this->connection->db_bindings as $db_binding) {
            foreach($db_binding as $key => $val){
                $this->connection->db_sql_statement->bindValue($key,$val);
            }
        }
        $this->connection->db_sql_statement->execute();
        $dObject = new DObject();
        $dObject->fetch($this->connection->db_sql_statement->fetchAll(\PDO::FETCH_ASSOC));
        $this->items = $dObject->items;
        return $this->items;
    }

    /**
     * @param $limit
     * @param array $fields
     * @return mixed
     */
    public function paginate($limit, $fields = [])
    {
        // TODO: Implement paginate() method.
    }

    /**
     * @param $field
     * @param $value
     * @param string $eval
     * @return mixed
     */
    public function where($field, $value, $eval = "=")
    {
        $this->where[] = [
            $field,
            $eval,
            $value,
        ];
        return $this;
    }

    /**
     * @param $table
     * @return mixed
     */
    public function join($table)
    {
        // TODO: Implement join() method.
    }
}