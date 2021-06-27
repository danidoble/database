<?php

namespace Danidoble\Database;

use Danidoble\Database\Interfaces\Sql as ISql;
use PDO;

/**
 * Class Sql
 * @package Danidoble\Database
 */
class Sql extends Parser implements ISql
{
    protected $table, $limit, $offset, $connection, $_id_dd_db;
    protected $debug = false;
    protected $order_by = [], $group_by = [], $where = [], $errors = [];
    protected $_dd_items;

    /**
     * Sql constructor.
     */
    public function __construct($table = null)
    {
        $this->connection = new Connection($_ENV['DB_NAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_USERNAME'], $_ENV['DB_HOST']);
        $this->connection->connect();
        if ($table !== null) {
            $this->table($table);
        }
    }

    /**
     * @return false|string
     */
    public function __toString()
    {
        return $this->debug ? json_encode($this) : json_encode($this->_dd_items);
    }

    /**
     * @return mixed
     */
    public function __invoke()
    {
        return $this->_dd_items;
    }

    public function debug(bool $debug = false)
    {
        $this->debug = $debug;
        return $this;
    }

    /**
     * @param $table
     * @return Sql
     */
    public static function from($table): Sql
    {
        return (new Sql())->table($table);
    }

    /**
     * @param $stmt
     * @return $this
     */
    public function raw($stmt): Sql
    {
        $this->executeStmt($stmt, 1);

        return $this;
    }

    /**
     * @param $table
     * @return $this
     */
    public function table($table): Sql
    {
        $this->table = $table;
        return $this;
    }

    /**
     * @param $field
     * @param string $order
     * @return $this
     */
    public function orderBy($field, string $order = "asc"): Sql
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
     * @param $field
     * @param $value
     * @param string $eval
     * @param bool $bypass
     * @return Sql
     */
    public function where($field, $value, string $eval = "=", $bypass = false): Sql
    {
        $this->where[] = [
            $field,
            $eval,
            $value,
            $bypass,
        ];
        return $this;
    }

    /**
     * @param array $arr
     * @return Sql|Object
     */
    public function first(array $arr = [])
    {
        $stmt = $this->makeStmt("select", $arr);

        // limit
        $this->limit(1);
        $this->offset(0);
        $stmt .= $this->strLimit();

        $this->executeStmt($stmt, 1);
        return ($this->debug) ? $this : $this->_dd_items;
    }

    /**
     * @param array $arr
     * @return Sql|Object
     */
    public function get(array $arr = [])
    {
        $stmt = $this->makeStmt("select", $arr);

        $this->limit(0);
        $this->offset(0);

        $this->executeStmt($stmt);
        //return $this;
        return ($this->debug) ? $this : $this->_dd_items;
    }

    /**
     * @param null $id
     * @param string $name
     * @return $this|Object
     */
    public function count($id = null, string $name = "total")
    {
        $counter = $id !== null ? $id : "*";

        $stmt = $this->makeStmt("select", ["count($counter) as $name"]);

        $this->offset(0);
        $this->limit(0);
        $this->executeStmt($stmt, 1);

        return ($this->debug) ? $this : $this->_dd_items;
    }

    /**
     * @param int $limit
     * @param int $no_page
     * @param array $fields
     * @return DObject|Sql
     */
    public function paginate(int $limit, int $no_page = 1, array $fields = [])
    {
        $stmt = $this->makeStmt("select", $fields);
        $limit = $limit < 1 ? 1 : $limit;
        $no_page = $no_page < 1 ? 1 : $no_page;
        $this->limit($limit);
        $id_countable = null;
        if (!empty($fields)) {
            $id_countable = $fields[0];
        }
        $this->countPaginate($this, $id_countable);
        $this->calcPagination($no_page);
        $stmt .= $this->strLimit();
        $this->executeStmt($stmt);

        if ($this->debug) {
            return $this;
        }

        if (!isset($this->paginate)) {
            $this->paginate = new DObject();
        }
        $dObject = (new DObject());
        $dObject->assoc(["items" => $this->_dd_items, "paginate" => $this->paginate]);
        return $dObject;
    }

    /**
     * @param $name
     * @return $this
     */
    public function id($name): Sql
    {
        $this->_id_dd_db = $name;
        return $this;
    }

    public function join($table)
    {
        // TODO: Implement join() method.
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        $type = "insert";
        if ($this->{$this->_id_dd_db}) {
            $type = "update";
        }
        $stmt = $this->makeStmt($type, []);

        $this->executeStmt($stmt);
        $this->connection->db_bindings = null;
        $last_id = $this->connection->db_connection->lastInsertId();
        if (intval($last_id) > 0) {
            $this->raw("select * from $this->table where $this->_id_dd_db = ($last_id)");
            $this->removePublicItems();
            $this->inserted_id = $last_id;
            return true;
        }
        $this->errors[] = $this->connection->db_sql_statement->errorInfo();
        return false;
    }

    public function delete()
    {
        // TODO: Implement delete() method.
    }

    /**
     * @return mixed
     */
    public function getItems()
    {
        return $this->_dd_items;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }


    /**
     * @param $ins
     * @param string|null $id
     */
    private function countPaginate($ins, string $id = null): void
    {
        $n = new Sql();
        $n->table = $ins->table;
        $n->where = $ins->where;
        $n->order_by = $ins->order_by;
        $n->group_by = $ins->group_by;
        $n->connection->db_bindings = $ins->connection->db_bindings;

        $counter = $id !== null ? $id : "*";

        $stmt = $n->makeStmt("select", ["count($counter) as total"]);

        $n->limit(0);
        $n->offset(0);

        $n->executeStmt($stmt, 1);

        if (!isset($this->paginate)) {
            $this->paginate = new DObject();
        }
        $this->paginate = new DObject();
        if (!isset($n->items)) {
            $n->items = new DObject();
        }
        $this->paginate->items = (integer)$n->items->total;
    }

    private function calcPagination($no_page)
    {
        if (!isset($this->paginate)) {
            $this->paginate = new DObject();
        }
        $pages = (integer)ceil($this->paginate->items / $this->limit);

        $this->paginate->no_page = $no_page;
        $this->paginate->total_no_pages = $pages;
        $offset = ($this->paginate->no_page - 1) * $this->limit;

        $this->offset($offset);
    }


    /**
     * @param int $limit
     * @return void
     */
    private function limit(int $limit): void
    {
        $this->limit = $limit;
    }

    /**
     * @param int $offset
     * @return void
     */
    private function offset(int $offset): void
    {
        $this->offset = $offset;
    }

    /**
     * @param $stmt
     * @param int $type_fetch
     */
    protected function executeStmt($stmt, int $type_fetch = 0)
    {

        $this->connection->db_sql_statement = $this->connection->db_connection->prepare($stmt);

        foreach ($this->connection->db_bindings as $db_binding) {
            foreach ($db_binding as $key => $val) {
                $this->connection->db_sql_statement->bindValue($key, $val);
            }
        }
        $this->connection->db_sql_statement->execute();
        $dObject = new DObject();

        if ($type_fetch === 1) {
            $dObject->fetch($this->connection->db_sql_statement->fetch(PDO::FETCH_ASSOC));
        } else {
            $dObject->fetch($this->connection->db_sql_statement->fetchAll(PDO::FETCH_ASSOC));
        }
        $this->_dd_items = $dObject->items;
    }


}