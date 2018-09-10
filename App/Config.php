<?php

namespace App;

/**
 * Конфиг приложения
 *
 */
class Config
{

    /**
     * Host для соединения с базой данных
     * @var string
     */
    const DB_HOST = 'localhost';

    /**
     * Имя базы данных
     * @var string
     */
    const DB_NAME = 'mvc_tz';

    /**
     * Пользователь базы данных
     * @var string
     */
    const DB_USER = 'phones';

    /**
     * Пароль пользователя
     * @var string
     */
    const DB_PASSWORD = 'uEGJMmb$Jb';

    /**
     * Показывать или логировать ошибки
     * @var boolean
     */
    const SHOW_ERRORS = true;
}
