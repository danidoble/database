<?php
/*
 * Created by  (c)danidoble 2022.
 */

namespace Danidoble\Database;

use Danidoble\Database\Interfaces\DObject as IDObject;

/**
 * Class DObject
 * @package Danidoble\Database
 */
class DObject implements IDObject
{
    protected $error = false;
    protected $errors = [];
    public $items;
    public $no_page;
    public $total_no_pages;

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
    public function __set(string $name, $val): void
    {
        $this->{$name} = $val;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
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
     * @return DObject
     */
    public function __invoke(): DObject
    {
        return $this;
    }

    /**
     * @param object|array $items
     * @return void
     */
    public function assoc($items): void
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
    public function save(): string
    {
        return $this->incorrectClass();
    }

    /**
     * @return string
     */
    public function find(): string
    {
        return $this->incorrectClass();
    }

    /**
     * @return string
     */
    public function first(): string
    {
        return $this->incorrectClass();
    }

    /**
     * @return string
     */
    public function update(): string
    {
        return $this->incorrectClass();
    }

    /**
     * @return string
     */
    public function delete(): string
    {
        return $this->incorrectClass();
    }

    /**
     * @return string
     */
    public function forceDelete(): string
    {
        return $this->incorrectClass();
    }

    /**
     * @return string
     */
    public function get(): string
    {
        return $this->incorrectClass();
    }

}