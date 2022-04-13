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
     * @param $name
     * @return mixed
     */
    public function __get($name): mixed;

    /**
     * @param string $name
     * @return bool
     */
    public function __isset(string $name): bool;

    /**
     * @return string
     */
    public function save(): string;

    /**
     * @return string
     */
    public function find(): string;

    /**
     * @return string
     */
    public function first(): string;

    /**
     * @return string
     */
    public function update(): string;

    /**
     * @return string
     */
    public function delete(): string;

    /**
     * @return string
     */
    public function forceDelete(): string;

    /**
     * @return string
     */
    public function get(): string;
}