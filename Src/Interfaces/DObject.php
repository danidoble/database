<?php
/*
 * Created by (c)danidoble 2022.
 */

namespace Danidoble\Database\Interfaces;

use Danidoble\Database\DObject as CDObject;

/**
 * Interface DObject
 * @package Danidoble\Database\Interfaces
 */
interface DObject
{
    /**
     * @param object|array $items
     * @return void
     */
    public function assoc($items): void;

    /**
     * @return string
     */
    public function __toString(): string;

    /**
     * @return DObject
     */
    public function __invoke(): CDObject;

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
     * @param $name
     * @return mixed
     */
    public function __get($name);

    /**
     * @param string $name
     * @return bool
     */
    public function __isset(string $name): bool;

    /**
     * @return string
     */
    public function save():string;

    /**
     * @return string
     */
    public function find():string;

    /**
     * @return string
     */
    public function first():string;

    /**
     * @return string
     */
    public function update():string;

    /**
     * @return string
     */
    public function delete():string;

    /**
     * @return string
     */
    public function forceDelete():string;

    /**
     * @return string
     */
    public function get():string;
}