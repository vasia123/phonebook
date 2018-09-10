<?php

namespace Core;

/**
 * Класс Вью
 *
 */
class View
{

    /**
     * Компилируем шаблон используя Twig
     *
     * @param string $template  Файл шаблона
     * @param array $args       Аргументы
     *
     * @throws \Exception если файл не найден
     *
     * @return void
     */
    public static function renderTemplate($template, $args = [])
    {
        static $twig = null;

        if ($twig === null) {
            $loader = new \Twig_Loader_Filesystem(dirname(__DIR__) . '/App/Views');
            $twig = new \Twig_Environment($loader);
        }

        echo $twig->render($template, $args);
    }

    /**
     * Вспомогательная функция для вывода ответов на ajax
     * запросы в виде JSON
     *
     * @param array $data  Массив для вывода
     *
     * @return void
     */
    public static function renderJson($data)
    {
        $out = json_encode($data);
        exit($out);
    }
}
