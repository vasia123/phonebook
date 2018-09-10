<?php

namespace Core;

/**
 * Роутер
 *
 */
class Router
{

    /**
     * Сюда складываются все зарегистрированные пути
     * @var array
     */
    protected $routes = [];

    /**
     * Очистка пути от лишних обрамляющих слэшей и пробелов(на всякий случай)
     *
     * @param string $route  Путь
     *
     * @return string
     */
    public function clearRoutePath($route)
    {
        $route = trim($route, '/ ');
        return $route;
    }

    /**
     * Добавить путь к списку зарегистрированных
     *
     * @param string $route  Путь
     * @param array  $params Параметры (контроллер, действие, пространство имен)
     *
     * @return void
     */
    public function add($route, $params = [])
    {
        $route = $this->clearRoutePath($route);
        $this->routes[$route] = $params;
    }

    /**
     * Получить зарегистрированные пути
     *
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Поиск пути в списке зарегистрированных
     *
     * @param string $url Путь
     *
     * @return boolean  true если совпадение есть, иначе false
     */
    public function match($url)
    {
        if (array_key_exists($url, $this->routes)) {
            return true;
        }
        return false;
    }

    /**
     * Ищем путь в списке зарегистрированных и если найден -
     * создаем контроллер и запускаем соответствующее дейсвтвие
     *
     * @param string $url Путь
     *
     * @throws \Exception Исключение усли путь, контроллер или действие не найдены
     *
     * @return void
     */
    public function dispatch($url)
    {
        $url = $this->removeQueryStringVariables($url);
        $url = $this->clearRoutePath($url);

        if ($this->match($url)) {
            $matched_route_params = $this->routes[$url];
            $controller = $matched_route_params['controller'];
            $controller = $this->getNamespace($matched_route_params) . $controller;

            if (class_exists($controller)) {
                $controller_object = new $controller();
                $action = $matched_route_params['action'];

                if (method_exists($controller_object, $action)) {
                    $controller_object->{$action}();

                } else {
                    throw new \Exception("Метод $action контроллера $controller не найден или не может быть вызван.");
                }
            } else {
                throw new \Exception("Контроллер класса $controller не найден");
            }
        } else {
            throw new \Exception('Путь не найден.', 404);
        }
    }

    /**
     * Очищаем полученный URL от параметров запроса
     *
     * @param string $url Полный URL
     *
     * @return string Только путь без параметров запроса
     */
    protected function removeQueryStringVariables($url)
    {
        if ($url != '') {
            $parts = explode('&', $url, 2);
            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }
        return $url;
    }

    /**
     * Возвращает пространство имен для выбранного контроллера
     *
     * @param array $params Параметры пути
     *
     * @return string Строка с простанством имен.
     */
    protected function getNamespace($params)
    {
        $namespace = 'App\Controllers\\';

        if (array_key_exists('namespace', $params)) {
            $namespace .= $params['namespace'] . '\\';
        }

        return $namespace;
    }

    /**
     * Вспомогательная функция для получения JSON из POST запроса полученного от vue.js
     *
     * @return array массиво POST
     */
    public static function getJsonPostData()
    {
        $postData = json_decode(file_get_contents('php://input'),true);
        return $postData;
    }
}
