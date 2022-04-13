<?php
/*
 * Created by (c)danidoble 2022.
 */

namespace Danidoble\Database\Interfaces;
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
    public function assoc(object|array $items): void;

    /**
     * @return string
     */
    public function __toString(): string;

    /**
     * @return DObject
     */
    public function __invoke(): DObject;

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
     * @param $name
     * @return mixed
     */
    public function __get($name): mixed;

    /**
     * @param string $name
     * @return bool
     */
    public function __isset(string $name): bool;
}