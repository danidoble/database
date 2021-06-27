<?php
/*
 * Created by  (c)danidoble 2021.
 */

namespace Danidoble\Database;

/**
 * Class DObject
 * @package Danidoble\Database
 */
class DObject
{
    public $items;

    /**
     * @return string
     */
    public function __toString(): string
    {
        return json_encode($this);
    }

    /**
     * @return $this
     */
    public function __invoke(): DObject
    {
        //$this->__credits_creator__ = "danidoble";
        return $this;
    }

    /**
     * @param $items
     */
    public function associatively($items)
    {
        if (is_array($items) || is_object($items)) {
            unset ($this->items);
            foreach ($items as $key => $val) {
                $this->{$key} = $val;
            }
        } else {
            $this->items = $items;
        }
    }

    /**
     * @param $items_arr
     */
    public function fetch($items_arr)
    {
        $items_obj = [];

        foreach ($items_arr as $item_arr) {
            $nDObject = new DObject();
            if (is_array($item_arr) || is_object($items_arr)) {
                $obj = [];
                foreach ($item_arr as $key => $val) {
                    $obj[$key] = $val;
                }
                $nDObject->associatively($obj);
                $items_obj[] = $nDObject;
            } else {
                $nDObject->associatively($items_arr);
                $items_obj = $nDObject;
            }
        }
        $this->items = $items_obj;
    }
}