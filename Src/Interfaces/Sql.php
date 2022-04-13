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
    public function debug(bool $debug = false): static;

    /**
     * @return Sql|array|bool|DBObject|DObject
     * @throws DatabaseException
     */
    public function save(): static|array|bool|DBObject|DObject;

    /**
     * @param $field
     * @param string $order
     * @return $this
     */
    public function orderBy($field, string $order = "asc"): static;

    /**
     * @param $field
     * @return $this
     */
    public function groupBy($field): static;

    /**
     * @param array $arr
     * @param int $limit
     * @param int $offset
     * @return $this|DObject|array
     * @throws DatabaseException
     */
    public function get(array $arr = [], int $limit = 0, int $offset = 0): static|DObject|array;

    /**
     * @param array $arr
     * @return $this|DObject|array|DBObject
     * @throws DatabaseException
     */
    public function first(array $arr = []): static|DObject|array|DBObject;

    /**
     * @param string|int $id
     * @param array $arr
     * @return $this|DObject|array|DBObject
     * @throws DatabaseException
     */
    public function find(string|int $id, array $arr = []): static|DObject|array|DBObject;

    /**
     * @param int $limit
     * @param int $no_page
     * @param array $fields
     * @return DObject|Sql|array
     * @throws DatabaseException
     */
    public function paginate(int $limit, int $no_page, array $fields = []): DObject|static|array;

    /**
     * @param $field
     * @param $value
     * @param string $eval
     * @param bool $bypass
     * @return $this
     */
    public function where($field, $value, string $eval = "=", bool $bypass = false): static;

    /**
     * @param string $table
     * @param ?DatabaseCredentials $db_config
     * @return $this
     * @throws DatabaseException
     */
    public static function from(string $table, ?DatabaseCredentials $db_config = null): static;

    /**
     * @param $table
     * @return $this
     */
    public function table($table): static;

    /**
     * @param $name
     * @return $this
     */
    public function id($name): static;

    /**
     * @param $stmt
     * @return $this
     * @throws DatabaseException
     */
    public function raw($stmt): static;

    /**
     * @return DObject|array
     */
    public function getItems(): DObject|array;

    /**
     * @return array
     */
    public function getErrors(): array;

    /**
     * @param $id
     * @param string $name
     * @return DObject|$this|array
     * @throws DatabaseException
     */
    public function count($id = null, string $name = "total"): static|DObject|array;

    /**
     * @param string $name
     * @param mixed $val
     * @return void
     */
    public function __set(string $name, mixed $val): void;

    /**
     * @param string $table
     * @param string $name_id
     * @param string $eval
     * @param string $foreign_name_id
     * @param string|null $alias
     * @return $this
     */
    public function join(string $table, string $name_id, string $eval, string $foreign_name_id, ?string $alias = null): static;

    /**
     * @param string $table
     * @param string $name_id
     * @param string $eval
     * @param string $foreign_name_id
     * @param string|null $alias
     * @return $this
     */
    public function innerJoin(string $table, string $name_id, string $eval, string $foreign_name_id, ?string $alias = null): static;

    /**
     * @param string $table
     * @param string $name_id
     * @param string $eval
     * @param string $foreign_name_id
     * @param string|null $alias
     * @return $this
     */
    public function leftJoin(string $table, string $name_id, string $eval, string $foreign_name_id, ?string $alias = null): static;

    /**
     * @param string $table
     * @param string $name_id
     * @param string $eval
     * @param string $foreign_name_id
     * @param string|null $alias
     * @return $this
     */
    public function rightJoin(string $table, string $name_id, string $eval, string $foreign_name_id, ?string $alias = null): static;

    /**
     * @param string $table
     * @param string $name_id
     * @param string $eval
     * @param string $foreign_name_id
     * @param string|null $alias
     * @return $this
     */
    public function crossJoin(string $table, string $name_id, string $eval, string $foreign_name_id, ?string $alias = null): static;


    /**
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit): static;

    /**
     * @param int $offset
     * @return $this
     */
    public function offset(int $offset): static;


    /**
     * @param string $field
     * @param mixed $value
     * @return $this
     */
    public function set(string $field, mixed $value): static;

    /**
     * @return array|bool|DBObject|DObject|static
     * @throws DatabaseException
     */
    public function update(): array|bool|DBObject|DObject|static;

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
    public function __invoke(): object|array;

    /**
     * @param $field
     * @return $this
     */
    public function whereNotNull($field): static;

    /**
     * @param $field
     * @return $this
     */
    public function whereNull($field): static;

    /**
     * @param $field
     * @param $value
     * @param string $eval
     * @param bool $bypass
     * @return $this
     */
    public function orWhere($field, $value, string $eval = "=", bool $bypass = false): static;

    /**
     * @param $field
     * @return $this
     */
    public function orWhereNotNull($field): static;

    /**
     * @param $field
     * @return $this
     */
    public function owWhereNull($field): static;


    /**
     * @return array|bool|DBObject|DObject|$this
     * @throws DatabaseException
     */
    public function delete(): static|array|bool|DBObject|DObject;

    /**
     * @return Sql|array|bool|DBObject|DObject
     * @throws DatabaseException
     */
    public function forceDelete(): static|array|bool|DBObject|DObject;
}