<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 08.10.2014
 * Time: 18:04
 */

define('APP_ID', 3110087); //ID приложения
define('CLIENT_SECRET', 'qJCagpPwpyBWuM9ClRNi'); //Секретный ключ в приложении
define('HOST', $_SERVER['SERVER_NAME'] == basename(dirname(__FILE__)) ? $_SERVER['SERVER_NAME'] : $_SERVER['SERVER_NAME'] .'/'. basename(dirname(__FILE__))); //Имя хоста

session_start();