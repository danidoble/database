<?php

namespace Danidoble\Database\Interfaces;

/**
 * Interface Sql
 * @package Danidoble\Database\Interfaces
 */
interface Sql
{

    /**
     * @param bool $debug
     * @return mixed
     */
    public function debug(bool $debug = false);

    /**
     * @return mixed
     */
    public function save();

    /**
     * @return mixed
     */
    public function delete();

    /**
     * @param $field
     * @param string $order
     * @return mixed
     */
    public function orderBy($field, string $order = "asc");

    /**
     * @param $field
     * @return mixed
     */
    public function groupBy($field);

    /**
     * @param array $arr
     * @return mixed
     */
    public function get(array $arr = []);

    /**
     * @return mixed
     */
    public function first();

    /**
     * @param int $limit
     * @param int $no_page
     * @param array $fields
     * @return mixed
     */
    public function paginate(int $limit, int $no_page, array $fields = []);

    /**
     * @param $field
     * @param $value
     * @param string $eval
     * @return mixed
     */
    public function where($field, $value, string $eval = "=", $bypass = false);

    /**
     * @param $table
     * @return mixed
     */
    public static function from($table);

    /**
     * @param $table
     * @return mixed
     */
    public function table($table);

    /**
     * @param $table
     * @return mixed
     */
    public function join($table);

    /**
     * @param $name
     * @return mixed
     */
    public function id($name);

    /**
     * @param $stmt
     * @return mixed
     */
    public function raw($stmt);

    /**
     * @return mixed
     */
    public function getItems();

    /**
     * @return mixed
     */
    public function getErrors();
}