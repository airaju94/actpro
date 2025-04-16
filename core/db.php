<?php

/*
* -------------------------------------
* | CodeDynamic                       |
* -------------------------------------
* 
* @Author: A.I Raju
* @License: MIT
* @Copyright: CodeDynamic 2024
* @File: Database.php
* @Version: 2.0
* 
*/

/* Prevent Direct Access */
defined('BASE') or die('No Direct Access!');

/**
 * Database Class
 * 
 * Provides a simple, secure interface for MySQL database operations
 */
class db {

    protected static $host;
    protected static $user;
    protected static $password;
    protected static $dbName;
    protected static $dbConnected = false;
    protected static $db;
    protected static $lastQuery;
    protected static $lastError;

    /**
     * Constructor - automatically connects to database
     */
    function __construct() {
        self::connect();
    }

    /**
     * Connect to the database
     * 
     * @throws Exception If connection fails
     */
    public static function connect() {
        if (!self::$dbConnected) {
            self::$host = DB_HOST;
            self::$user = DB_USER;
            self::$password = DB_PASS;
            self::$dbName = DB_NAME;

            // Validate configuration
            if (!self::$host || !self::$user || !self::$dbName) {
                throw new Exception('Database configuration incomplete');
            }

            try {
                self::$db = new mysqli(self::$host, self::$user, self::$password, self::$dbName);
                self::$dbConnected = true;
                
                // Set character set to support full Unicode
                if (!self::$db->set_charset('utf8mb4')) {
                    throw new Exception('Error loading character set utf8mb4: ' . self::$db->error);
                }
                
                // Set SQL mode to strict
                self::$db->query("SET SESSION sql_mode = 'STRICT_ALL_TABLES'");
                
            } catch (Exception $e) {
                throw new Exception('Database connection error: ' . $e->getMessage());
            }

            if (self::$db->connect_error) {
                throw new Exception('Database connection error: ' . self::$db->connect_error);
            }
        }else{
            return self::$db;
        }
    }

    /**
     * Execute a SQL query
     * 
     * @param string $sql The SQL query to execute
     * @return mysqli_result|bool Query result or false on failure
     */
    public static function query($sql) {
        if (!self::$dbConnected) {
            self::connect();
        }
        
        self::$lastQuery = $sql;
        $result = self::$db->query($sql);
        
        if (!$result) {
            self::$lastError = self::$db->error;
            return false;
        }
        
        return $result;
    }

    /**
     * Escape a string to prevent SQL injection
     * 
     * @param mixed $value The value to escape
     * @return string The escaped value
     */
    public static function escape($value) {
        if (!self::$dbConnected) {
            self::connect();
        }
        
        if (is_null($value)) {
            return 'NULL';
        }
        
        return self::$db->real_escape_string($value);
    }

    /**
     * Insert data into a table
     * 
     * @param string $table The table name
     * @param array $data Associative array of column => value pairs
     * @return int|bool Insert ID on success, false on failure
     */
    public static function insert($table, array $data) {
        if (empty($table) || empty($data)) {
            return false;
        }
        
        $keys = [];
        $values = [];
        
        foreach ($data as $key => $value) {
            $keys[] = "`" . self::escape($key) . "`";
            $values[] = is_null($value) ? 'NULL' : "'" . self::escape($value) . "'";
        }
        
        $sql = sprintf(
            "INSERT INTO `%s` (%s) VALUES (%s)",
            self::escape($table),
            implode(', ', $keys),
            implode(', ', $values)
        );
        
        $result = self::query($sql);
        
        return $result ? self::$db->insert_id : false;
    }

    /**
     * Update data in a table
     * 
     * @param string $table The table name
     * @param array $data Associative array of column => value pairs
     * @param array|string $where WHERE conditions
     * @return bool True on success, false on failure
     */
    public static function update($table, array $data, $where) {
        if (empty($table) || empty($data)) {
            return false;
        }
        
        $setParts = [];
        foreach ($data as $key => $value) {
            $setParts[] = sprintf(
                "`%s` = %s",
                self::escape($key),
                is_null($value) ? 'NULL' : "'" . self::escape($value) . "'"
            );
        }
        
        $whereClause = self::buildWhereClause($where);
        
        $sql = sprintf(
            "UPDATE `%s` SET %s WHERE %s",
            self::escape($table),
            implode(', ', $setParts),
            $whereClause
        );
        
        return (bool) self::query($sql);
    }

    /**
     * Delete data from a table
     * 
     * @param string $table The table name
     * @param array|string $where WHERE conditions
     * @return bool True on success, false on failure
     */
    public static function delete($table, $where) {
        if (empty($table)) {
            return false;
        }
        
        $whereClause = self::buildWhereClause($where);
        
        $sql = sprintf(
            "DELETE FROM `%s` WHERE %s",
            self::escape($table),
            $whereClause
        );
        
        return (bool) self::query($sql);
    }

    /**
     * Check if records exist matching conditions
     * 
     * @param string $table The table name
     * @param array|string $where WHERE conditions
     * @return int Number of matching records
     */
    public static function has($table, $where) {
        $whereClause = self::buildWhereClause($where);
        
        $sql = sprintf(
            "SELECT COUNT(*) as count FROM `%s` WHERE %s",
            self::escape($table),
            $whereClause
        );
        
        $result = self::query($sql);
        
        return $result ? (int) $result->fetch_assoc()['count'] : 0;
    }

    /**
     * Get count of all records in a table
     * 
     * @param string $table The table name
     * @return int Number of records
     */
    public static function getCount($table) {
        $sql = sprintf(
            "SELECT COUNT(*) as count FROM `%s`",
            self::escape($table)
        );
        
        $result = self::query($sql);
        
        return $result ? (int) $result->fetch_assoc()['count'] : 0;
    }

    /**
     * Get the last inserted ID
     * 
     * @return int The last inserted ID
     */
    public static function getLastInsertId() {
        return self::$db->insert_id;
    }

    /**
     * Get the last executed query
     * 
     * @return string The last SQL query
     */
    public static function getLastQuery() {
        return self::$lastQuery;
    }

    /**
     * Get the last error message
     * 
     * @return string The last error message
     */
    public static function getLastError() {
        return self::$lastError;
    }

    /**
     * Close the database connection
     */
    public static function close() {
        if (self::$dbConnected) {
            self::$db->close();
            self::$dbConnected = false;
        }
    }

    /**
     * Build WHERE clause from conditions
     * 
     * @param array|string $where WHERE conditions
     * @return string The WHERE clause
     */
    protected static function buildWhereClause($where) {
        if (is_array($where)) {
            $conditions = [];
            foreach ($where as $key => $value) {
                $conditions[] = sprintf(
                    "`%s` = %s",
                    self::escape($key),
                    is_null($value) ? 'NULL' : "'" . self::escape($value) . "'"
                );
            }
            return implode(' AND ', $conditions);
        }
        
        return $where;
    }
    
}