<?php

namespace App\Controllers;

use \Core\View;

/**
 * Контроллер главной страницы
 *
 */
class Home extends \Core\Controller
{

    /**
     * Действие по показу главной страницы
     *
     * @throws \Exception Если файл не найден
     *
     * @return void
     */
    public function indexAction()
    {
        View::renderTemplate('Home/index.html');
    }
}
