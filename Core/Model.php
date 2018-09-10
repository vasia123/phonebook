<?php

namespace Core;

use PDO;
use App\Config;


/**
 * Базовая модель
 *
 */
class Model
{
    static $db = null;

    /**
     * Возвращает объект соединения PDO с базой данных
     *
     * @return PDO
     */
    public static function getDB()
    {

        if (self::$db === null) {
            $dsn = 'mysql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_NAME . ';charset=utf8';
            self::$db = new PDO($dsn, Config::DB_USER, Config::DB_PASSWORD);

            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }

        return self::$db;
    }

    /**
     * Вспомогательная функция для вывода ajax сообщения
     * об успешной операции.
     *
     * @param mixed $output  вывод
     *
     * @return array
     */
    public static function success($output='')
    {
        $out = array(
            'success' => true,
            'message' => $output
        );
        return $out;
    }

    /**
     * Вспомогательная функция для вывода ajax сообщения
     * об ошибках в операции.
     *
     * @param mixed $output  вывод
     *
     * @return array
     */
    public static function error($output='')
    {
        $out = array(
            'success' => false,
            'message' => $output
        );
        return $out;
    }

}
