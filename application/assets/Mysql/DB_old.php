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
    private static $connection;

    private static function ParamTrimmer($params): string
    {
        $keys = array_keys($params);
        $q = "";
        foreach ($keys as $i) {
            if ($i == "next") {
                $q .= $params[$i] . " ";
            } else {
                $q .= $i . " = :" . $i . " ";
            }
        }
        return $q;
    }

    public static function DBInit()
    {
        self::$connection = Connection::TryConnect();
        $sql = "CREATE TABLE IF NOT EXISTS `session` (
            `id` BIGINT PRIMARY KEY AUTO_INCREMENT,
            `value` LONGTEXT,
            `sessionKey` TEXT,
            `created_at` DATETIME,
            `expiration` DATETIME
        );";

        try {
            $stmt = self::$connection->prepare($sql);
            // $stmt->execute();
        } catch (PDOException $e) {
            echo "Error creating table: " . $e->getMessage();
        }
    }

    public static function find(String $table, JoinTables $join = null, $params): DBResponse
    {
        $keys = array_keys($params);
        $q = self::ParamTrimmer($params);
        $sql = 'SELECT * FROM ' . $table . ' WHERE ' . $q . " LIMIT 1";
        $stmt = self::$connection->prepare($sql);
        foreach ($keys as $i) {
            if ($i == "next") continue;
            $stmt->bindParam(':' . $i, $params[$i], PDO::PARAM_STR);
        }
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return new DBResponse(json_encode($results), $results);
    }

    public static function runSql($sql): DBResponse
    {
        $stmt = self::$connection->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return new DBResponse(json_encode($results), $results);
    }

    public static function findMany(String $table, JoinTables $join = null, $params = [], $limit = 0, $offset = -1): DBResponse
    {
        $keys = array_keys($params);
        $q = "";
        if (count($params) > 0) {
            $q .= " WHERE ";
            foreach ($keys as $i) {
                if ($i == "next") {
                    $q .= $params[$i] . " ";
                } else {
                    $q .= $i . " = :" . $i . " ";
                }
            }
        }
        $sql = 'SELECT * FROM ' . $table . $q;
        if ($limit > 0 && $offset > -1) {
            $sql .= " LIMIT " . $limit . " OFFSET " . $offset;
        }
        $stmt = self::$connection->prepare($sql);
        foreach ($keys as $i) {
            if ($i == "next") continue;
            $stmt->bindParam(':' . $i, $params[$i], PDO::PARAM_STR);
        }
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return new DBResponse(json_encode($results), $results);
    }
    public static function findManySimilar(String $table, JoinTables $join = null, $params = []): DBResponse
    {
        $keys = array_keys($params);
        $q = "";
        if (count($params) > 0) {
            $q .= " WHERE ";
            foreach ($keys as $i) {
                if ($i == "next") {
                    $q .= $params[$i] . " ";
                } else {
                    $q .= $i . " LIKE :" . $i . " ";
                }
            }
        }
        $sql = 'SELECT * FROM ' . $table . $q;
        $stmt = self::$connection->prepare($sql);
        foreach ($keys as $i) {
            if ($i == "next") continue;
            $stmt->bindParam(':' . $i, $params[$i], PDO::PARAM_STR);
        }
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return new DBResponse(json_encode($results), $results);
    }

    public static function update(String $table, $values = [], $params = []): DBResponse
    {
        $val_keys = array_keys($values);
        $set_clause = "";
        foreach ($val_keys as $i) {
            $set_clause .= $i . " = :s_" . $i . ", ";
        }
        $set_clause = rtrim($set_clause, ", ");

        $keys = array_keys($params);
        $where_clause = "";
        if (count($params) > 0) {
            $where_clause = " WHERE ";
            foreach ($keys as $i) {
                if ($i == "next") {
                    $where_clause .= $params[$i] . " ";
                } else {
                    $where_clause .= $i . " LIKE :" . $i . " AND ";
                }
            }
            $where_clause = rtrim($where_clause, " AND ");
        }

        $sql = 'UPDATE ' . $table . ' SET ' . $set_clause . $where_clause;
        $stmt = self::$connection->prepare($sql);
        foreach ($val_keys as $i) {
            $stmt->bindParam(':s_' . $i, $values[$i], PDO::PARAM_STR);
        }
        foreach ($keys as $i) {
            if ($i == "next") continue;
            $stmt->bindParam(':' . $i, $params[$i], PDO::PARAM_STR);
        }
        $stmt->execute();
        $affectedRows = $stmt->rowCount();
        return new DBResponse(json_encode(['affectedRows' => $affectedRows]), ['affectedRows' => $affectedRows]);
    }
    public static function Insert($table, $values): DBResponse
    {
        $query = "INSERT INTO $table ";
        $keys = array_keys($values);

        $query .= "(" . join(", ", $keys) . ") VALUES ";

        $placeholders = array_map(fn($key) => ":$key", $keys);
        $query .= "(" . join(", ", $placeholders) . ")";
        $stmt = self::$connection->prepare($query);
        foreach ($keys as $key) {
            $value = $values[$key];
            $paramType = is_null($value) ? PDO::PARAM_NULL : PDO::PARAM_STR;
            $stmt->bindValue(":$key", $value, $paramType);
        }
        $stmt->execute();
        $affectedRows = $stmt->rowCount();
        return new DBResponse(json_encode(['affectedRows' => $affectedRows]), ['affectedRows' => $affectedRows]);
    }
}
