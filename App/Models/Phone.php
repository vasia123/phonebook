<?php

namespace App\Models;

use PDO;
use \Core\Model;

/**
 * Модель телефонной книги
 *
 */
class Phone extends Model
{

    /**
     * Вспомогательная функция для очистки
     * номера телефона от всего лишнего
     *
     * @param string $phone строка телефона
     *
     * @return integer
     */
    private static function _preparePhone($phone)
    {
        $phone = preg_replace("/[^0-9\+\(\)]/", "",$phone);
        return $phone;
    }

    /**
     * Получить все номера из базы данных.
     * Есть заготовка под пагинацию
     *
     * @param integer $limit   ограничение по количеству
     * @param integer $offset  отступ
     *
     * @return array
     */
    public static function getPhones($limit=0, $offset=0)
    {
        $db = Model::getDB();
        $limit_str = '';
        if ($limit > 0) {
            $limit_str = ' LIMIT '.$offset.','.$limit;
        }
        $stmt = $db->query('SELECT name, phone FROM phones'.$limit_str);
        $rows = $stmt->fetchAll();
        $all_rows_count = (int) $db->query('SELECT FOUND_ROWS();')->fetch(PDO::FETCH_COLUMN);
        return self::success(array('data' => $rows, 'count_all' => $all_rows_count));
    }

    /**
     * Добавить один номер с именем
     *
     * @param string $name   имя
     * @param string $phone  номер
     *
     * @return array
     */
    public static function addPhone($name, $phone)
    {
        $phone = self::_preparePhone($phone);
        $db = static::getDB();
        $stmt = $db->prepare('SELECT count(*) FROM phones WHERE phone=:phone');
        $stmt->execute(['phone' => $phone]);
        $rowCount = $stmt->fetchColumn();
        if($rowCount == 0) {
            $stmt = $db->prepare("INSERT INTO phones(name, phone) VALUES(:name, :phone)");
            $stmt->execute(['name' => $name, 'phone' => $phone]);
            if($stmt->rowCount() > 0) {
                return self::success('Успешно');
            } else {
                return self::error('Не удалось добавить номер!');
            }
        } else {
            return self::error('Такой номер уже добавлен!');
        }
    }

    /**
     * Удалить одну запись по номеру
     *
     * @param string $phone  номер
     *
     * @return array
     */
    public static function removePhone($phone)
    {
        $phone = self::_preparePhone($phone);
        $db = static::getDB();
        $stmt = $db->prepare("DELETE FROM phones WHERE phone = :phone");
        $stmt->execute(['phone' => $phone]);
        $count = $stmt->rowCount();
        if ($count > 0) {
            return self::success('Успешно!');
        } else {
            return self::error('Запись не найдена!');
        }
    }

    /**
     * Отредактировать одну запись по номеру
     *
     * @param string $old_phone  номер для поиска
     * @param string $new_name   новое значение для имени
     * @param string $new_phone  новое значение для телефона
     *
     * @return array
     */
    public static function editPhone($old_phone, $new_name, $new_phone)
    {
        $old_phone = self::_preparePhone($old_phone);
        $db = static::getDB();
        $stmt = $db->prepare('SELECT COUNT(*) FROM phones WHERE phone=:phone');
        $stmt->execute(['phone' => $old_phone]);
        $rowCount = $stmt->fetchColumn();
        if($rowCount == 1) {
            $stmt = $db->prepare("UPDATE phones SET name=:name, phone=:phone WHERE phone=:old_phone");
            $stmt->execute(['name' => $new_name, 'phone' => $new_phone, 'old_phone' => $old_phone]);
            if ($stmt->rowCount() > 0) {
                return self::success('Успешно!');
            } else {
                return self::error('Запись не отредактирована!');
            }
        } else {
            return self::error('Запись не найдена! '.$rowCount);
        }


    }

    /**
     * Поиск по базе по имени или номеру
     *
     * @param string $input  строка для поиска
     *
     * @return array
     */
    public static function searchPhone($input)
    {
        $phone_input = self::_preparePhone($input);
        $db = static::getDB();
        $stmt = $db->prepare("SELECT name, phone FROM phones WHERE phone LIKE :phone OR name LIKE :name");
        $stmt->bindValue(':phone', '%'.$phone_input.'%', PDO::PARAM_STR);
        $stmt->bindValue(':name', '%'.$input.'%', PDO::PARAM_STR);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $all_rows_count = (int) $db->query('SELECT FOUND_ROWS();')->fetch(PDO::FETCH_COLUMN);
        return self::success(array('data' => $rows, 'count_all' => $all_rows_count));
    }
}
