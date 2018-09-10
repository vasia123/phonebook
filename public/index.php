<?php

/**
 * Единая точка входа
 *
 */

/**
 * Composer
 */
require dirname(__DIR__) . '/vendor/autoload.php';


/**
 * Роутинг
 */
$router = new Core\Router();

// Регистрируем все пути для сайта
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('phones/get-all', ['controller' => 'Phones', 'action' => 'getAll']);
$router->add('phones/add-one', ['controller' => 'Phones', 'action' => 'addOne']);
$router->add('phones/remove-one', ['controller' => 'Phones', 'action' => 'removeOne']);
$router->add('phones/edit-one', ['controller' => 'Phones', 'action' => 'editOne']);
$router->add('phones/search', ['controller' => 'Phones', 'action' => 'search']);

$query = '';
if (isset($_SERVER['QUERY_STRING'])) {
    $query = $_SERVER['QUERY_STRING'];
}
$router->dispatch($query);
