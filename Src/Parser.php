<?php
/*
 * Created by  (c)danidoble 2022.
 */

namespace Danidoble\Database;

use Danidoble\Database\Exceptions\DatabaseException;


class Parser
{
    protected $table;
    protected $limit = 0;
    protected $offset = 0;
    protected $connection;
    protected $errors;
    protected $sets = [];
    protected $where = [];
    protected $inner_join = [];
    protected $group_by = [];
    protected $order_by = [];
    protected $left_join = [];
    protected $right_join = [];
    protected $cross_join = [];
    protected $debug = false;

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
                    if ($where[4] == "or") {
                        $wheres .= " or ";
                    } else {
                        $wheres .= " and ";
                    }
                }
                if (!$where[3]) {
                    $binding = "dd_bound_" . $key;
                    $this->connection->db_bindings[] = [
                        $binding => $where[2],
                    ];

                    $wheres .= $where[0] . " " . $where[1] . " :" . $binding;
                } else {

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
    protected function strInnerJoins(): string
    {
        $joins = "";
        if (!empty($this->inner_join)) {
            foreach ($this->inner_join as $join) {
                $joins .= " inner join $join[0] $join[4] on $join[1] $join[2] $join[3]";
            }
            if (trim($joins) !== "") {
                $joins .= " ";
            }
        }
        return $joins;
    }

    /**
     * @return string
     */
    protected function strLeftJoins(): string
    {
        $joins = "";
        if (!empty($this->left_join)) {
            foreach ($this->left_join as $join) {
                $joins .= " left join $join[0] $join[4] on $join[1] $join[2] $join[3]";
            }
            if (trim($joins) !== "") {
                $joins .= " ";
            }
        }
        return $joins;
    }

    /**
     * @return string
     */
    protected function strRightJoins(): string
    {
        $joins = "";
        if (!empty($this->right_join)) {
            foreach ($this->right_join as $join) {
                $joins .= " right join $join[0] $join[4] on $join[1] $join[2] $join[3]";
            }
            if (trim($joins) !== "") {
                $joins .= " ";
            }
        }
        return $joins;
    }

    /**
     * @return string
     */
    protected function strCrossJoins(): string
    {
        $joins = "";
        if (!empty($this->cross_join)) {
            foreach ($this->cross_join as $join) {
                $joins .= " cross join $join[0] $join[4] on $join[1] $join[2] $join[3]";
            }
            if (trim($joins) !== "") {
                $joins .= " ";
            }
        }
        return $joins;
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


    /**
     * @param $arr
     * @return string
     */
    protected function makeStmtSelect($arr): string
    {
        //select
        if ((!empty($this->inner_join) || !empty($this->left_join) || !empty($this->right_join) || !empty($this->cross_join)) && empty($arr)) {
            $arr[] = $this->table . ".*";
            if (!empty($this->inner_join)) {
                foreach ($this->inner_join as $join) {
                    if ($join[4] !== null) {
                        $arr[] = $join[4] . ".*";
                    } else {
                        $arr[] = $join[0] . ".*";
                    }
                }
            }
            if (!empty($this->left_join)) {
                foreach ($this->left_join as $join) {
                    if ($join[4] !== null) {
                        $arr[] = $join[4] . ".*";
                    } else {
                        $arr[] = $join[0] . ".*";
                    }
                }
            }
            if (!empty($this->right_join)) {
                foreach ($this->right_join as $join) {
                    if ($join[4] !== null) {
                        $arr[] = $join[4] . ".*";
                    } else {
                        $arr[] = $join[0] . ".*";
                    }
                }
            }
            if (!empty($this->cross_join)) {
                foreach ($this->cross_join as $join) {
                    if ($join[4] !== null) {
                        $arr[] = $join[4] . ".*";
                    } else {
                        $arr[] = $join[0] . ".*";
                    }
                }
            }
        }
        $stmt = "select " . $this->strAttributes($arr) . " from " . $this->table;

        //joins
        $stmt .= "";

        return $stmt;
    }

    /**
     * @param array $arr
     * @return string
     */
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
        return "(" . $attributes . ") " . $values;
    }

    /**
     * @param $arr
     * @return string
     */
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
     * @return string
     */
    protected function makeStmtInsert(): string
    {
        $properties = json_decode(json_encode($this), true);
        return "insert into " . $this->table . " " . $this->strValues($properties);
    }

    /**
     * @return void
     */
    protected function removePublicItems()
    {
        $debug = $this->debug;
        $this->debug = true;
        $properties = json_decode($this, true);
        $this->debug = $debug;

        foreach ($properties as $key => $property) {
            if ($key !== "_dd_items") {
                unset($this->{$key});
            }
        }
    }

    /**
     * @param $type
     * @param array $arr
     * @return false|string
     * @throws DatabaseException
     */
    protected function makeStmt($type, array $arr = [])
    {
        $condition = false;
        $grouping = false;
        $ordering = false;
        $joining = false;
        switch ($type) {
            case "select":
                $stmt = $this->makeStmtSelect($arr);
                $grouping = true;
                $ordering = true;
                $joining = true;
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
        }

        // JOINS
        if ($joining) {
            // inner
            $inner_joins = $this->strInnerJoins();
            $stmt .= $inner_joins;

            // left
            $left_joins = $this->strLeftJoins();
            $stmt .= $left_joins;

            // right
            $right_joins = $this->strRightJoins();
            $stmt .= $right_joins;

            // cross
            $cross_joins = $this->strCrossJoins();
            $stmt .= $cross_joins;
        }

        //where
        $where = $this->strConditions();
        if ($condition && trim($where) === "") {
            throw new DatabaseException("Where is required on $type");
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
     * @throws DatabaseException
     */
    protected function makeStmtUpdate(): string
    {
        $properties = json_decode(json_encode($this), true);
        if (empty($properties)) {
            $properties = [];
            foreach ($this->sets as $set) {
                $properties[$set[2]] = [$set[0], $set[1]];
            }

            if (count($properties) < 1) {
                throw new DatabaseException("You need at least one data to update");
            }

            $str = "update " . $this->table . " set ";
            $values = "";

            foreach ($properties as $key => $value) {
                if (trim($values) !== "") {
                    $values .= ", ";
                }
                $binding = "dd_bound_update_" . $key;
                $this->connection->db_bindings[] = [
                    $binding => $value[1],
                ];
                $values .= $value[0] . "=:" . $binding;
            }

            return $str . $values;
        }

        $str = "update " . $this->table . " set ";
        $values = "";

        foreach ($properties as $key => $value) {
            if (trim($values) !== "") {
                $values .= ", ";
            }
            $binding = "dd_bound_update_" . $key;
            $this->connection->db_bindings[] = [
                $binding => $value,
            ];
            $values .= $key . "=:" . $binding;
        }

        return $str . $values;
    }

    /**
     * @return mixed
     */
    protected function makeStmtDelete(): string
    {
        return "delete from " . $this->table;
    }

}