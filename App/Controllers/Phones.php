<?php

namespace App\Controllers;

use \App\Models\Phone;
use \Core\View;
use \Core\Router;


/**
 * Контроллер для записей в телефонной книге
 *
 */
class Phones extends \Core\Controller
{

    /**
     * Получить все номера с именами
     *
     * @return void
     */
    public function getAllAction()
    {
        $all_phones = Phone::getPhones();
        View::renderJson($all_phones);
    }

    /**
     * Добавить один номер с именем
     *
     * @return void
     */
    public function addOneAction()
    {
        $postData = Router::getJsonPostData();
        $phone = $postData['phone'];
        $name = $postData['name'];
        $response = Phone::addPhone($name, $phone);
        View::renderJson($response);
    }

    /**
     * Удалить один телефон по номеру
     *
     * @return void
     */
    public function removeOneAction()
    {
        $postData = Router::getJsonPostData();
        $phone = $postData['phone'];
        $response = Phone::removePhone($phone);
        View::renderJson($response);
    }

    /**
     * Отредактировать один телефон по номеру
     *
     * @return void
     */
    public function editOneAction()
    {
        $postData = Router::getJsonPostData();
        $phone = $postData['phone'];
        $name = $postData['name'];
        $old_phone = $postData['old_phone'];
        $response = Phone::editPhone($old_phone, $name, $phone);
        View::renderJson($response);
    }

    /**
     * Поиск по номеру или имени
     *
     * @return void
     */
    public function searchAction()
    {
        $postData = Router::getJsonPostData();
        $search = $postData['search'];
        $response = Phone::searchPhone($search);
        View::renderJson($response);
    }
}
