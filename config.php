<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 08.10.2014
 * Time: 18:04
 */

define('APP_ID', 3110087); //ID приложения
define('CLIENT_SECRET', 'qJCagpPwpyBWuM9ClRNi'); //Секретный ключ в приложении
define('CALLBACK', 'callback.php'); //Имя колбэк файла
define('SERVER_NAME', $_SERVER['SERVER_NAME'] == basename(dirname(__FILE__)) ? $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'] : $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'] .'/'. basename(dirname(__FILE__))); //Имя хоста
define('API_VER', '5.25'); //Версия API

session_start();