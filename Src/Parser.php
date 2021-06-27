<?php
/*
 * Created by  (c)danidoble 2021.
 */

namespace Danidoble\Database;

use Danidoble\Database\Interfaces\Parser as IParser;


class Parser implements IParser
{
    /**
     * @param array $arr
     * @return string
     */
    protected function strAttributes(array $arr = []): string
    {
        $attributes = "";
        if (!empty($arr)) {
            foreach ($arr as $attribute) {
                if (trim($attributes) !== "") {
                    $attributes .= ", ";
                }
                $attributes .= $attribute;
            }
        } else {
            $attributes = $this->table . ".*";
        }
        return $attributes;
    }

    /**
     * @return string
     */
    protected function strConditions(): string
    {
        $wheres = "";
        if (!empty($this->where)) {
            foreach ($this->where as $key => $where) {
                if (trim($wheres) !== "") {
                    $wheres .= " and ";
                }
                if(!$where[3]) {
                    $binding = "dd_bound_" . $key;
                    $this->connection->db_bindings[] = [
                        $binding => $where[2],
                    ];

                    $wheres .= $where[0] . " " . $where[1] . " :" . $binding;
                }else{

                    $wheres .= $where[0] . " " . $where[1] . " " . $where[2];
                }
            }
            if (trim($wheres) !== "") {
                $wheres = " where " . $wheres;
            }
        }
        return $wheres;
    }

    /**
     * @return string
     */
    protected function strOrder(): string
    {
        $order = "";
        if (!empty($this->order_by)) {
            foreach ($this->order_by as $order_by) {
                if (trim($order) !== "") {
                    $order .= ", ";
                }
                $order .= $order_by[0] . " " . $order_by[1];
            }
            if (trim($order) !== "") {
                $order = " order by " . $order;
            }
        }
        return $order;
    }

    /**
     * @return string
     */
    protected function strGroup(): string
    {
        $group = "";
        if (!empty($this->group_by)) {
            foreach ($this->group_by as $group_by) {
                if (trim($group) !== "") {
                    $group .= ", ";
                }
                $group .= $group_by[0];
            }
            if (trim($group) !== "") {
                $group = " group by " . $group;
            }
        }
        return $group;
    }

    /**
     * @return string
     */
    protected function strLimit(): string
    {
        return " limit " . $this->limit . " offset " . $this->offset;
    }




    protected function makeStmtSelect($arr): string
    {
        //select
        $stmt = "select " . $this->strAttributes($arr) . " from " . $this->table;

        //joins
        $stmt .= "";

        return $stmt;
    }

    protected function strValues(array $arr = []): string
    {
        $attributes = "";
        $values = [];
        if (!empty($arr)) {
            foreach ($arr as $attribute => $value) {
                if (trim($attributes) !== "") {
                    $attributes .= ", ";
                }
                $attributes .= $attribute;
                $values[] = $value;
            }
        } else {
            $attributes = $this->table . ".*";
            $values[] = $this->table . ".*";
        }

        $values = $this->strBindings($values);

        //$dObject = new DObject();
        //$dObject->assoc(["attributes"=>$attributes,"values"=>$values]);
        return "(".$attributes .") ".$values;
    }

    protected function strBindings($arr): string
    {
        $values = "";
        if (!empty($arr)) {
            foreach ($arr as $key => $value) {
                if (trim($values) !== "") {
                    $values .= ", ";
                }
                $binding = "dd_bound_" . $key;
                $this->connection->db_bindings[] = [
                    $binding => $value,
                ];

                $values .= ":" . $binding;
            }
            if (trim($values) !== "") {
                $values = "values (" . $values . ")";
            }
        }
        return $values;
    }

    /**
     * @return mixed
     */
    protected function makeStmtInsert()
    {
        $properties = json_decode(json_encode($this),true);
        return "insert into ".$this->table." ".$this->strValues($properties);
    }

    protected function removePublicItems(){
        $properties = json_decode($this,true);

        foreach ($properties as $key => $property) {
            if($key !== "_dd_items") {
                unset($this->{$key});
            }
        }
    }

    protected function makeStmt($type, $arr)
    {
        $condition = false;
        $grouping = false;
        $ordering = false;
        $stmt = "";
        switch ($type) {
            case "select":
                $stmt = $this->makeStmtSelect($arr);
                $grouping = true;
                $ordering = true;
                break;
            case "insert":
                $stmt = $this->makeStmtInsert();
                break;
            case "update":
                $stmt = $this->makeStmtUpdate();
                $condition = true;
                break;
            case "delete":
                $stmt = $this->makeStmtDelete();
                $condition = true;
                break;
            default:
                $this->errors[] = [
                    "Selection" => "Please use a valid selector, 'select', 'insert', 'update', 'delete'",
                ];
                return false;
                break;
        }


        //where
        $where = $this->strConditions();
        if ($condition && trim($where) === "") {
            $this->errors[] = [
                "where" => "where is obligatory in {$type}",
            ];
            return false;
        }
        $stmt .= $where;

        if ($grouping) {
            //group
            $stmt .= $this->strGroup();
        }

        if ($ordering) {
            //order
            $stmt .= $this->strOrder();
        }

        return $stmt;
    }

    /**
     * @return mixed
     */
    protected function makeStmtUpdate()
    {
        // TODO: Implement makeStmtUpdate() method.
    }

    /**
     * @return mixed
     */
    protected function makeStmtDelete()
    {
        // TODO: Implement makeStmtDelete() method.
    }

}