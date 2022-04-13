<?php
/*
 * Created by (c)danidoble 2022.
 */

namespace Danidoble\Database\Interfaces;

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
     * @return $this
     */
    public function __invoke(): static;

    /**
     * DObject constructor.
     */
    public function __construct();

    /**
     * @param string $name
     * @param mixed $val
     * @return void
     */
    public function __set(string $name, mixed $val): void;

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name): mixed;

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
     * @return $this|CSql
     */
    public function save(): static|CSql;

    /**
     * @return DObject|$this|CSql
     */
    public function delete(): static|DObject|CSql;

    /**
     * @return $this|DObject|CSql
     */
    public function forceDelete(): static|DObject|CSql;

    /**
     * @return DObject
     */
    public function getOriginals(): DObject;

    /**
     * @param bool $val
     * @return $this
     */
    public function debug(bool $val = true): static;

    /**
     * @return DObject
     */
    public function getItems(): DObject;
}