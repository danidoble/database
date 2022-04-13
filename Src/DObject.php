<?php
/*
 * Created by  (c)danidoble 2022.
 */

namespace Danidoble\Database;

use Danidoble\Database\Interfaces\DObject as IDObject;
use JetBrains\PhpStorm\Pure;

/**
 * Class DObject
 * @package Danidoble\Database
 */
class DObject implements IDObject
{
    protected bool $error = false;
    protected array $errors = [];
    public mixed $items;
    public int $no_page;
    public int $total_no_pages;

    /**
     * DObject constructor.
     */
    public function __construct()
    {

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
     * @param $name
     * @return mixed
     */
    public function __get($name): mixed
    {
        if (!isset($this->{$name})) {
            return null;
        }
        return $this->{$name};
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset(string $name): bool
    {
        if (!property_exists($this, $name)) {
            return false;
        }
        return true;
    }

    /**
     * @return string
     * @return string
     */
    public function __toString(): string
    {
        return json_encode($this);
    }

    /**
     * @return $this
     * @return $this
     */
    public function __invoke(): static
    {
        return $this;
    }

    /**
     * @param object|array $items
     * @return void
     */
    public function assoc(object|array $items): void
    {
        unset ($this->items);
        foreach ($items as $key => $val) {
            $this->{$key} = $val;
        }
    }


    /**
     * @return string
     */
    private function incorrectClass(): string
    {
        return "This class not support this function, Maybe an error occurred before arrived here";
    }

    /**
     * @return string
     */
    #[Pure] public function save(): string
    {
        return $this->incorrectClass();
    }

    /**
     * @return string
     */
    #[Pure] public function find(): string
    {
        return $this->incorrectClass();
    }

    /**
     * @return string
     */
    #[Pure] public function first(): string
    {
        return $this->incorrectClass();
    }

    /**
     * @return string
     */
    #[Pure] public function update(): string
    {
        return $this->incorrectClass();
    }

    /**
     * @return string
     */
    #[Pure] public function delete(): string
    {
        return $this->incorrectClass();
    }

    /**
     * @return string
     */
    #[Pure] public function forceDelete(): string
    {
        return $this->incorrectClass();
    }

    /**
     * @return string
     */
    #[Pure] public function get(): string
    {
        return $this->incorrectClass();
    }

}