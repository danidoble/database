<?php
/*
 * Created by  (c)danidoble 2022.
 */

namespace Danidoble\Database;

use Danidoble\Database\Exceptions\DatabaseCredentialsException;
use Danidoble\Database\Exceptions\DatabaseException;
use Danidoble\Database\Interfaces\Sql as ISql;
use PDO;

/**
 * Class Sql
 * @package Danidoble\Database
 */
class Sql extends Parser implements ISql
{
    protected string $_id_dd_db = 'id';
    protected array $errors = [];
    protected DBObject|DObject|array|bool $_dd_items;
    protected string|int|null $inserted_id = null;
    protected DObject $paginate;
    private bool $this_use_joins = false;
    private bool $multiple_response = false;
    private bool $response = true;
    protected int $affected_rows = 0;

    /**
     * Sql constructor.
     * @param ?string $table
     * @param ?DatabaseCredentials $db_config
     * @throws DatabaseCredentialsException|DatabaseException
     */
    public function __construct(?string $table = null, ?DatabaseCredentials $db_config = null)
    {
        $this->bind($table, $db_config);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->debug ? json_encode($this) : json_encode($this->_dd_items);
    }

    /**
     * @return object|array
     */
    public function __invoke(): object|array
    {
        return $this->_dd_items;
    }

    /**
     * @param string $name
     * @param mixed $val
     * @return void
     */
    public function __set(string $name, mixed $val): void
    {
        $this->{$name} = $val;
    }

    /**
     * @param bool $debug
     * @return $this
     */
    public function debug(bool $debug = false): static
    {
        $this->debug = $debug;
        return $this;
    }

    /**
     * @param string $table
     * @param ?DatabaseCredentials $db_config
     * @return Sql
     * @throws DatabaseCredentialsException|DatabaseException
     */
    public static function from(string $table, ?DatabaseCredentials $db_config = null): Sql
    {
        if ($db_config !== null) {
            return self::db($db_config)->table($table);
        }
        return (new Sql())->table($table);
    }

    /**
     * @param $stmt
     * @return Sql
     * @throws DatabaseException
     */
    public function raw($stmt): Sql
    {
        $this->executeStmt($stmt);
        return $this;
    }

    /**
     * @param $table
     * @return Sql
     */
    public function table($table): Sql
    {
        $this->table = $table;
        return $this;
    }

    /**
     * @param $field
     * @param string $order
     * @return Sql
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
    public function where($field, $value, string $eval = "=", bool $bypass = false): Sql
    {
        if (!str_contains($field, '.')) {
            $field = $this->table . '.' . $field;
        }
        $this->where[] = [
            $field,
            $eval,
            $value,
            $bypass,
            "and"
        ];
        return $this;
    }

    /**
     * @param $field
     * @return Sql
     */
    public function whereNotNull($field): Sql
    {
        if (!str_contains($field, '.')) {
            $field = $this->table . '.' . $field;
        }
        $this->where[] = [
            $field,
            "is not",
            "null",
            true,
            "and"
        ];
        return $this;
    }

    /**
     * @param $field
     * @return Sql
     */
    public function whereNull($field): Sql
    {
        if (!str_contains($field, '.')) {
            $field = $this->table . '.' . $field;
        }
        $this->where[] = [
            $field,
            "is",
            "null",
            true,
            "and"
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
    public function orWhere($field, $value, string $eval = "=", bool $bypass = false): Sql
    {
        if (!str_contains($field, '.')) {
            $field = $this->table . '.' . $field;
        }
        $this->where[] = [
            $field,
            $eval,
            $value,
            $bypass,
            "or"
        ];
        return $this;
    }

    /**
     * @param $field
     * @return Sql
     */
    public function orWhereNotNull($field): Sql
    {
        if (!str_contains($field, '.')) {
            $field = $this->table . '.' . $field;
        }
        $this->where[] = [
            $field,
            "is not",
            "null",
            true,
            "or"
        ];
        return $this;
    }

    /**
     * @param $field
     * @return Sql
     */
    public function owWhereNull($field): Sql
    {
        if (!str_contains($field, '.')) {
            $field = $this->table . '.' . $field;
        }
        $this->where[] = [
            $field,
            "is",
            "null",
            true,
            "or"
        ];
        return $this;
    }

    /**
     * @param array $arr
     * @return Sql|DObject|array|DBObject
     * @throws DatabaseException
     */
    public function first(array $arr = []): Sql|DObject|array|DBObject
    {
        $stmt = $this->makeStmt("select", $arr);

        // limit
        $this->limit(1);
        $this->offset(0);
        $stmt .= $this->strLimit();

        $this->executeStmt($stmt);
        return ($this->debug) ? $this : $this->_dd_items;
    }

    /**
     * @param string|int $id
     * @param array $arr
     * @return Sql|DObject|array|DBObject
     * @throws DatabaseException
     */
    public function find(string|int $id, array $arr = []): Sql|DObject|array|DBObject
    {
        $this->where($this->_id_dd_db, $id);
        $stmt = $this->makeStmt("select", $arr);

        // limit
        $this->limit(1);
        $this->offset(0);
        $stmt .= $this->strLimit();

        $this->executeStmt($stmt);
        return ($this->debug) ? $this : $this->_dd_items;
    }

    /**
     * @param array $arr
     * @param ?int $limit
     * @param ?int $offset
     * @return Sql|DObject|array
     * @throws DatabaseException
     */
    public function get(array $arr = [], ?int $limit = null, ?int $offset = null): Sql|DObject|array
    {
        $this->multiple_response = true;
        $stmt = $this->makeStmt("select", $arr);

        if ($limit !== null) {
            $this->limit($limit);
        }
        if ($offset !== null) {
            $this->offset($offset);
        }

        if ($this->limit !== 0) {
            $stmt .= $this->strLimit();
        }

        $this->executeStmt($stmt);
        //return $this;
        return ($this->debug) ? $this : $this->_dd_items;
    }

    /**
     * @param $id
     * @param string $name
     * @return DObject|Sql|array
     * @throws DatabaseException
     */
    public function count($id = null, string $name = "total"): Sql|DObject|array
    {
        $counter = $id !== null ? $id : "*";
        $stmt = $this->makeStmt("select", ["count($counter) as $name"]);

        $this->offset(0);
        $this->limit(0);
        $this->executeStmt($stmt);

        return ($this->debug) ? $this : $this->_dd_items;
    }

    /**
     * @param int $limit
     * @param int $no_page
     * @param array $fields
     * @return DObject|Sql|array
     * @throws DatabaseException
     */
    public function paginate(int $limit = 20, int $no_page = 1, array $fields = []): DObject|static|array
    {
        $this->multiple_response = true;
        $stmt = $this->makeStmt("select", $fields);
        $limit = max($limit, 1);
        $no_page = max($no_page, 1);
        $this->limit($limit);
        $id_countable = null;
        if (!empty($fields)) {
            $id_countable = $fields[0];
        }
        $this->countPaginate($this, $id_countable);
        $this->calcPagination($no_page);
        $stmt .= $this->strLimit();
        $this->executeStmt($stmt);


        if (!isset($this->paginate)) {
            $this->paginate = new DObject();
        }
        $dObject = (new DObject());
        $dObject->assoc(["items" => $this->_dd_items, "paginate" => $this->paginate]);
        if ($this->paginate->no_page < 1 || $this->paginate->no_page > $this->paginate->total_no_pages) {
            $dObject->error = true;
            $dObject->errors = [
                "The number of current page is less than '1' or is more than total of pages"
            ];
        }
        if ($this->debug) {
            return $this;
        }
        return $dObject;
    }

    /**
     * @param $name
     * @return $this
     */
    public function id($name): static
    {
        $this->_id_dd_db = $name;
        return $this;
    }

    /**
     * @return array|bool|DBObject|DObject|static
     * @throws DatabaseException
     */
    public function update(): array|bool|DBObject|DObject|static
    {
        $type = "update";
        if (!isset($this->{$this->_id_dd_db})) {
            $this->response = false;
        }
        $stmt = $this->makeStmt($type, []);

        $this->executeStmt($stmt);
        $this->connection->db_bindings = [];
        $last_id = $this->connection->db_connection->lastInsertId();
        if (intval($last_id) > 0) {
            $this->raw("select * from $this->table where $this->_id_dd_db = $last_id");
            $this->removePublicItems();
            $this->inserted_id = $last_id;
            return $this->_dd_items;
        }
        if ($this->response) {
            $this->errors[] = $this->connection->db_sql_statement->errorInfo();
        } else {
            $this->removePublicItems();
            $this->_dd_items->updated = $this->affected_rows > 0;
            $this->_dd_items->affected_rows = $this->affected_rows;
            return $this->debug ? $this : $this->_dd_items;
        }
        return $this;
    }

    /**
     * @param string $field
     * @param mixed $value
     * @return $this
     */
    public function set(string $field, mixed $value): static
    {
        if (!str_contains($field, '.')) {
            $field_table = $this->table . '_' . $field;
            $field = $this->table . '.' . $field;
        } else {
            $field_table = str_replace('.', '_', $field);
        }
        $this->sets[] = [
            $field,
            $value,
            $field_table,
        ];
        return $this;
    }

    /**
     * @return array|bool|DBObject|DObject|Sql
     * @throws DatabaseException
     */
    public function save(): array|bool|DBObject|DObject|Sql
    {
        if ($this->this_use_joins) {
            $this->errors[] = "Multiples tables were set, you can only insert or update one table at time.";
            return $this;
        }

        $type = "insert";
        if (isset($this->{$this->_id_dd_db})) {
            $type = "update";
            $this->where($this->_id_dd_db, $this->{$this->_id_dd_db}, $eval = "=");
        }
        $stmt = $this->makeStmt($type, []);

        $this->executeStmt($stmt);
        $this->connection->db_bindings = [];
        $last_id = $this->connection->db_connection->lastInsertId();
        if (intval($last_id) > 0) {
            $this->raw("select * from $this->table where $this->_id_dd_db = $last_id");
            $this->removePublicItems();
            $this->inserted_id = $last_id;
            return $this->_dd_items;
        } elseif (isset($this->_id_dd_db) && isset($this->{$this->_id_dd_db}) && $this->{$this->_id_dd_db} !== null) {
            $this->raw("select * from $this->table where $this->_id_dd_db = " . $this->{$this->_id_dd_db});
            $last_id = $this->connection->db_connection->lastInsertId();
            $this->removePublicItems();
            $this->inserted_id = $last_id;
            return $this->_dd_items;
        }
        if ($this->response) {
            $this->errors[] = $this->connection->db_sql_statement->errorInfo();
        } else {
            $this->removePublicItems();
            $this->_dd_items->affected_rows = $this->affected_rows;
            $this->_dd_items->updated = $this->affected_rows > 0;
            return $this->debug ? $this : $this->_dd_items;
        }
        return $this;
    }

    /**
     * @return array|bool|DBObject|DObject|$this
     * @throws DatabaseException
     */
    public function delete(): array|bool|DBObject|DObject|Sql
    {
        $this->response = false;
        if (!property_exists($this, 'deleted_at')) {
            return $this->forceDelete();
        }
        return $this->save();
    }

    /**
     * @return array|bool|DBObject|DObject|$this
     * @throws DatabaseException
     */
    public function forceDelete(): array|bool|DBObject|DObject|Sql
    {
        $this->response = false;
        $this->where($this->_id_dd_db, $this->{$this->_id_dd_db});
        $stmt = $this->makeStmt("delete");

        $this->executeStmt($stmt);
        return ($this->debug) ? $this : $this->_dd_items;
    }

    /**
     * @return DObject|array
     */
    public function getItems(): DObject|array
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
     * @param string $table
     * @param string $name_id
     * @param string $eval
     * @param string $foreign_name_id
     * @param string|null $alias
     * @return $this
     */
    public function join(string $table, string $name_id, string $eval, string $foreign_name_id, ?string $alias = null): static
    {
        return $this->joinsPush('inner', $table, $name_id, $eval, $foreign_name_id, $alias);
    }

    /**
     * @param string $table
     * @param string $name_id
     * @param string $eval
     * @param string $foreign_name_id
     * @param string|null $alias
     * @return $this
     */
    public function innerJoin(string $table, string $name_id, string $eval, string $foreign_name_id, ?string $alias = null): static
    {
        return $this->joinsPush('inner', $table, $name_id, $eval, $foreign_name_id, $alias);
    }

    /**
     * @param string $table
     * @param string $name_id
     * @param string $eval
     * @param string $foreign_name_id
     * @param string|null $alias
     * @return $this
     */
    public function leftJoin(string $table, string $name_id, string $eval, string $foreign_name_id, ?string $alias = null): static
    {
        return $this->joinsPush('left', $table, $name_id, $eval, $foreign_name_id, $alias);
    }

    /**
     * @param string $table
     * @param string $name_id
     * @param string $eval
     * @param string $foreign_name_id
     * @param string|null $alias
     * @return $this
     */
    public function rightJoin(string $table, string $name_id, string $eval, string $foreign_name_id, ?string $alias = null): static
    {
        return $this->joinsPush('right', $table, $name_id, $eval, $foreign_name_id, $alias);
    }

    /**
     * @param string $table
     * @param string $name_id
     * @param string $eval
     * @param string $foreign_name_id
     * @param string|null $alias
     * @return $this
     */
    public function crossJoin(string $table, string $name_id, string $eval, string $foreign_name_id, ?string $alias = null): static
    {
        return $this->joinsPush('cross', $table, $name_id, $eval, $foreign_name_id, $alias);
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit): static
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @param int $offset
     * @return $this
     */
    public function offset(int $offset): static
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @param string $stmt
     * @throws DatabaseException
     */
    protected function executeStmt(string $stmt)
    {
        $this->connection->db_sql_statement = $this->connection->db_connection->prepare($stmt);
        foreach ($this->connection->db_bindings as $db_binding) {
            foreach ($db_binding as $key => $val) {
                $type_bind = PDO::PARAM_STR;
                switch (gettype($val)) {
                    case "boolean":
                        $type_bind = PDO::PARAM_BOOL;
                        break;
                    case "integer":
                        $type_bind = PDO::PARAM_INT;
                        break;
                    case "NULL":
                        $type_bind = PDO::PARAM_NULL;
                        break;
                }
                $this->connection->db_sql_statement->bindValue($key, $val, $type_bind);
            }
        }

        $this->connection->db_sql_statement->execute();
        $this->affected_rows = $this->connection->db_sql_statement->rowCount();
        if ($this->connection->db_sql_statement->errorInfo()[1] !== null || $this->connection->db_sql_statement->errorInfo()[2] !== null) {
            throw new DatabaseException($this->connection->db_sql_statement->errorInfo()[2], $this->connection->db_sql_statement->errorInfo()[1]);
        }
        $dObject = new DObject();

        if ($this->multiple_response) {
            $dObject->items = $this->connection->db_sql_statement->fetchAll(PDO::FETCH_CLASS, DBObject::class, [$this->table, ($this->_id_dd_db ?? 'id')]);
        } else {
            $dObject->items = $this->connection->db_sql_statement->fetchObject(DBObject::class, [$this->table, ($this->_id_dd_db ?? 'id')]);
            if ($dObject->items === false) {
                $dObject->items = new DObject();
                if ($this->response) {
                    $dObject->items->error = true;
                    $dObject->items->errors = ["Empty result of query"];
                } else {
                    $dObject->items->affected_rows = $this->affected_rows;
                }
            }
        }
        $this->_dd_items = $dObject->items;
    }

    /**
     * @param ?string $table
     * @param ?DatabaseCredentials $db_config
     * @return void
     * @throws DatabaseCredentialsException
     * @throws DatabaseException
     */
    protected function bind(?string $table = null, ?DatabaseCredentials $db_config = null)
    {
        if ($db_config === null) {
            if (!isset($_ENV['DB_HOST'])) {
                throw new DatabaseCredentialsException("Host of connection is required. Set super global \$_ENV['DB_HOST'] ");
            }
            if (!isset($_ENV['DB_PASS'])) {
                throw new DatabaseCredentialsException("Password of connection is required. Set super global \$_ENV['DB_PASS'] ");
            }
            if (!isset($_ENV['DB_USER'])) {
                throw new DatabaseCredentialsException("Username of database is required. Set super global \$_ENV['DB_USER'] ");
            }
            if (!isset($_ENV['DB_NAME'])) {
                throw new DatabaseCredentialsException("Name of database is required. Set super global \$_ENV['DB_NAME'] ");
            }
            $db_config = new DatabaseCredentials($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);
            if ($this->debug === false) {
                $db_config->setPDOErrorMode(PDO::ERRMODE_SILENT);
            }
        }
        $db_config->check();
        $this->connection = new Connection($db_config->getName(), $db_config->getPass(), $db_config->getUser(), $db_config->getHost());
        $this->connection->connect($db_config->getPDOErrorMode());
        if ($table !== null) {
            $this->table($table);
        }
    }

    /**
     * @param DatabaseCredentials $db_config
     * @return Sql
     * @throws DatabaseCredentialsException|DatabaseException
     */
    private static function db(DatabaseCredentials $db_config): Sql
    {
        return (new Sql(null, $db_config));
    }

    /**
     * @param $ins
     * @param ?string $id
     * @throws DatabaseException
     */
    private function countPaginate($ins, ?string $id = null): void
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

        $n->executeStmt($stmt);

        $this->paginate = new DObject();
        if (!isset($n->_dd_items->getOriginals()->total)) {
            $this->paginate->items = new DObject();
        } else {
            $this->paginate->items = (integer)$n->_dd_items->getOriginals()->total;
        }
    }

    /**
     * @param int $no_page
     * @return void
     */
    private function calcPagination(int $no_page = 1)
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
     * @param string $name_join
     * @param string $table
     * @param string $name_id
     * @param string $eval
     * @param string $foreign_name_id
     * @param string|null $alias
     * @return $this
     */
    private function joinsPush(string $name_join, string $table, string $name_id, string $eval, string $foreign_name_id, ?string $alias = null): static
    {
        $this->this_use_joins = true;
        if (!str_contains($name_id, '.')) {
            if ($alias !== null) {
                $name_id = $alias . '.' . $name_id;
            } else {
                $name_id = $table . '.' . $name_id;
            }
        }
        if (!str_contains($foreign_name_id, '.')) {
            $foreign_name_id = $this->table . '.' . $foreign_name_id;
        }
        if ($name_join == "inner") {
            $this->inner_join[] = [
                $table,
                $name_id,
                $eval,
                $foreign_name_id,
                $alias,
            ];
        }
        if ($name_join == "left") {
            $this->left_join[] = [
                $table,
                $name_id,
                $eval,
                $foreign_name_id,
                $alias,
            ];
        }
        if ($name_join == "right") {
            $this->right_join[] = [
                $table,
                $name_id,
                $eval,
                $foreign_name_id,
                $alias,
            ];
        }
        if ($name_join == "cross") {
            $this->cross_join[] = [
                $table,
                $name_id,
                $eval,
                $foreign_name_id,
                $alias,
            ];
        }
        return $this;
    }

}