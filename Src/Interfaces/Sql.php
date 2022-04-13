<?php
/*
 * Created by (c)danidoble 2022.
 */

namespace Danidoble\Database\Interfaces;

use Danidoble\Database\DBObject;
use Danidoble\Database\DObject;
use Danidoble\Database\Exceptions\DatabaseCredentialsException;
use Danidoble\Database\Exceptions\DatabaseException;
use Danidoble\Database\DatabaseCredentials;
use Danidoble\Database\Sql as CSql;

/**
 * Interface Sql
 * @package Danidoble\Database\Interfaces
 */
interface Sql
{

    /**
     * @param bool $debug
     * @return $this
     */
    public function debug(bool $debug = false): CSql;

    /**
     * @return array|bool|DBObject|DObject|CSql
     * @throws DatabaseException
     */
    public function save();

    /**
     * @param $field
     * @param string $order
     * @return CSql
     */
    public function orderBy($field, string $order = "asc"): CSql;

    /**
     * @param $field
     * @return CSql
     */
    public function groupBy($field): CSql;

    /**
     * @param array $arr
     * @param int $limit
     * @param int $offset
     * @return CSql|DObject|array
     * @throws DatabaseException
     */
    public function get(array $arr = [], int $limit = 0, int $offset = 0);

    /**
     * @param array $arr
     * @return CSql|DObject|array|DBObject
     * @throws DatabaseException
     */
    public function first(array $arr = []);

    /**
     * @param string|int $id
     * @param array $arr
     * @return CSql|DObject|array|DBObject
     * @throws DatabaseException
     */
    public function find($id, array $arr = []);

    /**
     * @param int $limit
     * @param int $no_page
     * @param array $fields
     * @return DObject|Sql|array
     * @throws DatabaseException
     */
    public function paginate(int $limit, int $no_page, array $fields = []);

    /**
     * @param $field
     * @param $value
     * @param string $eval
     * @param bool $bypass
     * @return CSql
     */
    public function where($field, $value, string $eval = "=", bool $bypass = false): CSql;

    /**
     * @param string $table
     * @param ?DatabaseCredentials $db_config
     * @return CSql
     * @throws DatabaseException
     */
    public static function from(string $table, ?DatabaseCredentials $db_config = null): CSql;

    /**
     * @param $table
     * @return CSql
     */
    public function table($table): CSql;

    /**
     * @param $name
     * @return CSql
     */
    public function id($name): CSql;

    /**
     * @param $stmt
     * @return CSql
     * @throws DatabaseException
     */
    public function raw($stmt): CSql;

    /**
     * @return DObject|array
     */
    public function getItems();

    /**
     * @return array
     */
    public function getErrors(): array;

    /**
     * @param $id
     * @param string $name
     * @return DObject|CSql|array
     * @throws DatabaseException
     */
    public function count($id = null, string $name = "total");

    /**
     * @param string $name
     * @param mixed $val
     * @return void
     */
    public function __set(string $name, $val): void;

    /**
     * @param string $table
     * @param string $name_id
     * @param string $eval
     * @param string $foreign_name_id
     * @param string|null $alias
     * @return $this
     */
    public function join(string $table, string $name_id, string $eval, string $foreign_name_id, ?string $alias = null): CSql;

    /**
     * @param string $table
     * @param string $name_id
     * @param string $eval
     * @param string $foreign_name_id
     * @param string|null $alias
     * @return $this
     */
    public function innerJoin(string $table, string $name_id, string $eval, string $foreign_name_id, ?string $alias = null): CSql;

    /**
     * @param string $table
     * @param string $name_id
     * @param string $eval
     * @param string $foreign_name_id
     * @param string|null $alias
     * @return $this
     */
    public function leftJoin(string $table, string $name_id, string $eval, string $foreign_name_id, ?string $alias = null): CSql;

    /**
     * @param string $table
     * @param string $name_id
     * @param string $eval
     * @param string $foreign_name_id
     * @param string|null $alias
     * @return $this
     */
    public function rightJoin(string $table, string $name_id, string $eval, string $foreign_name_id, ?string $alias = null): CSql;

    /**
     * @param string $table
     * @param string $name_id
     * @param string $eval
     * @param string $foreign_name_id
     * @param string|null $alias
     * @return $this
     */
    public function crossJoin(string $table, string $name_id, string $eval, string $foreign_name_id, ?string $alias = null): CSql;


    /**
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit): CSql;

    /**
     * @param int $offset
     * @return $this
     */
    public function offset(int $offset): CSql;


    /**
     * @param string $field
     * @param mixed $value
     * @return $this
     */
    public function set(string $field, $value): CSql;

    /**
     * @return array|bool|DBObject|DObject|static
     * @throws DatabaseException
     */
    public function update();

    /**
     * Sql constructor.
     * @param ?string $table
     * @param ?DatabaseCredentials $db_config
     * @throws DatabaseCredentialsException|DatabaseException
     */
    public function __construct(?string $table = null, ?DatabaseCredentials $db_config = null);

    /**
     * @return string
     */
    public function __toString(): string;

    /**
     * @return object|array
     */
    public function __invoke();

    /**
     * @param $field
     * @return CSql
     */
    public function whereNotNull($field): CSql;

    /**
     * @param $field
     * @return CSql
     */
    public function whereNull($field): CSql;

    /**
     * @param $field
     * @param $value
     * @param string $eval
     * @param bool $bypass
     * @return CSql
     */
    public function orWhere($field, $value, string $eval = "=", bool $bypass = false): CSql;

    /**
     * @param $field
     * @return CSql
     */
    public function orWhereNotNull($field): CSql;

    /**
     * @param $field
     * @return CSql
     */
    public function owWhereNull($field): CSql;


    /**
     * @return array|bool|DBObject|DObject|CSql
     * @throws DatabaseException
     */
    public function delete();

    /**
     * @return array|bool|DBObject|DObject|CSql
     * @throws DatabaseException
     */
    public function forceDelete();
}