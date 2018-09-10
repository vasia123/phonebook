<?php

namespace Core;

/**
 * Базовый контроллер
 *
 */
abstract class Controller
{

    /**
     * Переопределение магического метода __call для вызова методов
     * определенных в классе с окончанием 'Action'.
     *
     * @param string $name  Имя метода
     * @param array $args   Аргументы
     *
     * @throws \Exception Если метод не найден
     *
     * @return void
     */
    public function __call($name, $args)
    {
        $method = $name . 'Action';
        if (method_exists($this, $method)) {
            call_user_func_array([$this, $method], $args);
        } else {
            throw new \Exception("Метод $method не найден в контроллере " . get_class($this));
        }
    }
}
