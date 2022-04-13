<?php
/*
 * Created by (c)danidoble 2022.
 */

namespace Danidoble\Database\Interfaces;

use Danidoble\Database\DBObject as CDBObject;
use Danidoble\Database\DObject;
use Danidoble\Database\Sql as CSql;

/**
 * Interface DBObject
 * @package Danidoble\Database\Interfaces
 */
interface DBObject
{
    /**
     * @return string
     */
    public function __toString(): string;

    /**
     * @return CDBObject
     */
    public function __invoke(): CDBObject;

    /**
     * DObject constructor.
     */
    public function __construct();

    /**
     * @param string $name
     * @param mixed $val
     * @return void
     */
    public function __set(string $name, $val): void;

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name);

    /**
     * @param string $name
     * @return bool
     */
    public function __isset(string $name): bool;

    /**
     * @return string
     */
    public function toJSON(): string;

    /**
     * @return array
     */
    public function toArray(): array;

    /**
     * @param string $name
     * @return void
     */
    public function setTable(string $name): void;

    /**
     * @return CDBObject|CSql
     */
    public function save();

    /**
     * @return CDBObject|DObject|$this|CSql
     */
    public function delete();

    /**
     * @return CDBObject|DObject|CSql|$this
     */
    public function forceDelete();

    /**
     * @return DObject
     */
    public function getOriginals(): DObject;

    /**
     * @param bool $val
     * @return $this
     */
    public function debug(bool $val = true): CDBObject;

    /**
     * @return mixed
     */
    public function getItems();
}