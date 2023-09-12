<?php

namespace App\Db;

class Db
{
    private static $instance = null;
    private $db = null;

    private function __construct()
    {
        $db_config = require_once $_SERVER['DOCUMENT_ROOT'] . '/config/db_config.php';
        $this->db = new \PDO('mysql:host=' . $db_config['host'] . ';dbname=' . $db_config['db_name'], $db_config['user'], $db_config['password']);
    }

    private function __clone() {}

    /**
     * Создать инстанс класса (подключения к базе)
     * @return Db|null
     */
    public static function get_instance()
    {
        if(is_null(self::$instance)) {
            return new self();
        }
        return self::$instance;
    }

    /**
     * Выполнить запрос к базе
     * @param $sql_query
     * @param array $params
     */
    public function query($sql_query, $params = [])
    {

        $stmt = $this->db->prepare($sql_query);

        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
        }

        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Получить все записи
     * @param $table
     * @param string $sql
     * @param array $params
     */
    public function getAll($table, $sql = '', $params = [])
    {
        return $this->query("SELECT * FROM $table" . $sql, $params);
    }

    /**
     * Получить одну запись
     * @param $table
     * @param string $sql
     * @param array $params
     * @return mixed
     */
    public function getRow($table, $sql = '', $params = [])
    {
        $result = $this->query("SELECT * FROM $table" . $sql, $params);
        if (isset($result[0])) {
            return $result[0];
        }
        return NULL;
    }

    /**
     * Возвращает id последней вставленной записи
     * @return string
     */

    /**
     * @return int
     */
    public function get_last_insert_id(): int
    {
        return (int)$this->db->lastInsertId();
    }
}