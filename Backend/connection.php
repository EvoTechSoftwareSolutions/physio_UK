<?php

class Database {

    public static $connection;

    public static function setUpConnection() {
        if (!isset(Database::$connection)) {
            //Database::$connection = new mysqli("localhost", "root", "Slk2005RC", "", 3306);
            Database::$connection = new mysqli("", "", "", "", 3306);

            if (Database::$connection->connect_error) {
                die("Connection failed: " . Database::$connection->connect_error);
            }
        }
    }

    public static function iud($query, $types = "", ...$params) {
        Database::setUpConnection();
        $stmt = Database::$connection->prepare($query);

        if ($stmt) {
            if ($types && $params) {
                $stmt->bind_param($types, ...$params);
            }
            $stmt->execute();
            $insert_id = $stmt->insert_id;
            $stmt->close();
            return $insert_id;
        } else {
            return null;
        }
    }

    public static function search($query, $types = "", ...$params) {
        Database::setUpConnection();
        $stmt = Database::$connection->prepare($query);

        if ($stmt) {
            if ($types && $params) {
                $stmt->bind_param($types, ...$params);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            return $result;
        } else {
            return null;
        }
    }
}
