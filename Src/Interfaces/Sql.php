<?php
namespace Danidoble\Database\Interfaces;

/**
 * Interface Sql
 * @package Danidoble\Database\Interfaces
 */
Interface Sql{
    /**
     * @return mixed
     */
    public function save();

    /**
     * @return mixed
     */
    public function delete();

    public function orderBy($field,$order="asc");

    public function groupBy($field);

    public function get($arr = []);

    public function first();

    public function paginate($limit,$fields=[]);

    public function where($field,$value,$eval="=");

    public function from($table);

    public function join($table);

}