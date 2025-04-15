<?php

namespace Application\Database;

require_once "Connection.php";
require_once "DBResponse.php";
require_once __DIR__ . "/HelperObjects/JoinTables.php";

use Application\Assets\Mysql\Connection;
use Application\Assets\Mysql\DBResponse;
use Application\Assets\Mysql\HelperObjects\JoinTables;
use PDO;
use PDOException;

class DB
{
    private static $pdo;
    private $table;
    private $columns = ["*"];
    private $conditions = [];
    private $_limit;
    private $_orderBy = "";
    private $_toJson = false;
    private $_join = [];
    private $use = "";
    public $update_set = [];
    public $lastinsert_id;

    public static function DBInit()
    {
        self::$pdo = Connection::TryConnect();
    }
    public static function table($table)
    {
        $self = new self();
        $self->table = $table;
        return $self;
    }
    public function select($columns = ['*'])
    {
        $this->columns = $columns;
        $this->use = "select";
        return $this;
    }
    public function update($update_set)
    {
        $this->update_set = $update_set;
        $this->use = "update";
        return $this;
    }
    public function where($key, $operator, $value)
    {
        $this->conditions[] = ['type' => 'AND', 'key' => $key, 'operator' => $operator, 'value' => $value];
        return $this;
    }
    public function orWhere($key, $operator, $value)
    {
        $this->conditions[] = ['type' => 'OR', 'key' => $key, 'operator' => $operator, 'value' => $value];
        return $this;
    }
    public function orderBy($key, $type)
    {
        $this->_orderBy = "ORDER BY $key $type";
        return $this;
    }
    public function limit($limit)
    {
        $this->_limit = $limit;
        return $this;
    }
    public function join($type, $table, $on)
    {
        $this->_join[] = ["type" => $type, "table" => $table, "on" => $on];
        return $this;
    }
    public static function runSql($sql)
    {
        $statement = self::$pdo->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }


    public function toJson()
    {
        $this->_toJson = true;
        return $this;
    }

    public function get()
    {

        $columns = implode(', ', $this->columns);
        $query = "SELECT $columns FROM `{$this->table}`";

        if (!empty($this->_join)) {
            $joins = [];
            foreach ($this->_join as $join) {
                $clause = $join["type"] . " " . $join["table"] . " ON " . $join["on"];
                $joins[] = $clause;
            }
            $query .= " " . implode(' ', $joins);
        }

        if (!empty($this->conditions)) {
            $whereClauses = [];
            foreach ($this->conditions as $index => $condition) {
                $clause = "{$condition['key']} {$condition['operator']} ?";
                $whereClauses[] = $clause;
            }
            $query .= " WHERE " . implode(' ', array_map(function ($clause, $index) {
                return ($index === 0 ? '' : $this->conditions[$index - 1]['type'] . ' ') . $clause;
            }, $whereClauses, array_keys($whereClauses)));
        }

        $query .= " " . $this->_orderBy;
        if ($this->_limit != null) $query .= " LIMIT " . $this->_limit;
        $statement = self::$pdo->prepare($query);
        $values = array_column($this->conditions, 'value');
        $statement->execute($values);

        if ($this->_toJson) return json_encode($statement->fetchAll(PDO::FETCH_OBJ));
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }
    public function save()
    {
        $query = "UPDATE `" . $this->table . "` SET ";

        $keys = array_keys($this->update_set);
        $setClauses = [];
        foreach ($keys as $i) {
            $setClauses[] = "`$i` = ?";
        }
        $query .= implode(", ", $setClauses);
        if (!empty($this->conditions)) {
            $whereClauses = [];
            foreach ($this->conditions as $index => $condition) {
                $clause = "`{$condition['key']}` {$condition['operator']} ?";
                $whereClauses[] = $clause;
            }
            $query .= " WHERE " . implode(' ', array_map(function ($clause, $index) {
                return ($index === 0 ? '' : $this->conditions[$index - 1]['type'] . ' ') . $clause;
            }, $whereClauses, array_keys($whereClauses)));
        }
        $statement = self::$pdo->prepare($query);
        $values = array_values($this->update_set);
        $values = array_merge($values, array_column($this->conditions, 'value'));

        $statement->execute($values);
    }
    public function insert($values = [[]], $getid = false)
    {
        //azta kkkk
        $query = "INSERT INTO " . $this->table . " (";
        $cn = [];
        foreach (array_keys($values[0]) as $i) {
            $cn[] = "`" . $i . "`";
        }
        $query .= implode(", ", $cn) . ") VALUES ";
        $valen = count($values);
        $values_query = [];
        for ($i = 0; $i < $valen; $i++) {
            $qss = [];
            for ($j = 0; $j < count($values[0]); $j++) $qss[] = "?";
            $values_query[] = "(" . implode(",", $qss) . ")";
        }
        $query .= implode(', ', $values_query);
        $vk = [];
        for ($i = 0; $i < $valen; $i++) {
            $values_array = array_values($values[$i]);
            foreach ($values_array as $k) {
                $vk[] = $k;
            }
        }
        $statement = self::$pdo->prepare($query);
        $statement->execute($vk);
        $this->lastinsert_id = self::$pdo->lastInsertId();
        return $this;
    }
    public function getLastInsertId()
    {
        return $this->lastinsert_id;
    }
    public function delete()
    {
        return $this;
    }
    public function remove()
    {
        $query = "DELETE FROM " . $this->table;
        if (!empty($this->conditions)) {
            $whereClauses = [];
            foreach ($this->conditions as $index => $condition) {
                $clause = "`{$condition['key']}` {$condition['operator']} ?";
                $whereClauses[] = $clause;
            }
            $query .= " WHERE " . implode(' ', array_map(function ($clause, $index) {
                return ($index === 0 ? ' ' : $this->conditions[$index]['type'] . " ") . $clause;
            }, $whereClauses, array_keys($whereClauses)));
        }
        $vals = [];
        foreach ($this->conditions as $key => $value) {
            $vals[] = $value["value"];
        }
        $statement = self::$pdo->prepare($query);
        $statement->execute($vals);
    }
    public static function arrayToJson(array $arr)
    {
        return json_encode($arr);
    }
}
