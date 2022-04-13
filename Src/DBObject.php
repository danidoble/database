<?php
/*
 * Created by  (c)danidoble 2022.
 */

namespace Danidoble\Database;

use Danidoble\Database\Interfaces\DBObject as IDBObject;

/**
 * Class DObject
 * @package Danidoble\Database
 */
class DBObject implements IDBObject
{
    protected DObject $original;
    protected DObject $items;
    protected ?string $table;
    protected ?string $name_id;
    protected bool $lock = false;
    protected bool $debug = false;
    protected bool $force_delete = false;

    public function __construct(...$data_table)
    {
        $this->table = $data_table[0];
        $this->name_id = $data_table[1];
        $this->lock();
    }

    /**
     * @param string $name
     * @param mixed $val
     * @return void
     */
    public function __set(string $name, mixed $val): void
    {
        if (!isset($this->items)) {
            $this->items = new DObject();
        }
        if (!isset($this->original)) {
            $this->original = new DObject();
        }

        $this->items->{$name} = $val;
        if (!$this->lock) {
            $this->original->{$name} = $val;
        }
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name): mixed
    {
        if ($name === "original" && !isset($this->items->{$name})) {
            return null;
        }
        if ($name === "items" && !isset($this->items->{$name})) {
            return null;
        }
        if ($name === "lock" && !isset($this->items->{$name})) {
            return null;
        }
        if (!isset($this->items->{$name})) {
            return null;
        }
        return $this->items->{$name};
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset(string $name): bool
    {
        if (!property_exists($this, $name)) {
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJSON();
    }

    /**
     * @return $this
     */
    public function __invoke(): static
    {
        return $this;
    }

    /**
     * @return string
     */
    public function toJSON(): string
    {
        return json_encode($this->items);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return json_decode(json_encode($this->items), true);
    }

    /**
     * @param bool $val
     * @return $this
     */
    public function debug(bool $val = false): static
    {
        $this->debug = true;
        return $this;
    }

    /**
     * @return DObject
     */
    public function getOriginals(): DObject
    {
        return $this->original;
    }

    /**
     * @return DObject
     */
    public function getItems(): DObject
    {
        return $this->items;
    }

    /**
     * @param string $name
     * @return void
     */
    public function setTable(string $name): void
    {
        $this->table = $name;
    }

    /**
     * Update register of DB
     * @return $this|Sql
     * @throws Exceptions\DatabaseException
     */
    public function save(): static|Sql
    {
        $x = new Sql();
        $x->table($this->table);
        $x->id($this->name_id);
        $x->debug($this->debug);
        foreach ($this->items as $key => $value) {
            if (property_exists($this->original, $key)) {
                if ($key === "updated_at") {
                    $x->{$key} = date('Y-m-d H:i:s');
                } else {
                    $x->{$key} = $value;
                }
            }
        }
        return $x->save();
    }

    /**
     * Force delete even if deleted_at exist
     * @return $this|DBObject|DObject|Sql
     * @throws Exceptions\DatabaseException
     */
    public function forceDelete(): static|DBObject|DObject|Sql
    {
        $this->force_delete = true;
        return $this->delete();
    }

    /**
     * Only update if deleted_at exist, but if not exist is deleted
     * @return $this|DBObject|DObject|Sql
     * @throws Exceptions\DatabaseException
     */
    public function delete(): static|DBObject|DObject|Sql
    {
        $x = new Sql();
        $x->table($this->table);
        $x->id($this->name_id);
        $x->debug($this->debug);
        foreach ($this->items as $key => $value) {
            if (property_exists($this->original, $key)) {
                if ($key === "updated_at") {
                    $x->{$key} = date('Y-m-d H:i:s');
                } elseif ($key === "deleted_at") {
                    $x->{$key} = date('Y-m-d H:i:s');
                } else {
                    $x->{$key} = $value;
                }
            }
        }
        if ($this->force_delete) {
            return $x->forceDelete();
        }
        if (property_exists($this->original, "deleted_at")) {
            return $x->delete();
        } else {
            return $x->forceDelete();
        }
    }

    /**
     * @return void
     */
    private function lock(): void
    {
        $this->lock = true;
    }

}
