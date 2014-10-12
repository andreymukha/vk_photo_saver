<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 26.09.2014
 * Time: 3:22
 */

require_once 'config.php';

$scope = 'photos,friends';
$redirect_uri = 'http://tester.loc/vk_photo_saver/index.php';

$url = 'http://oauth.vk.com/authorize?client_id='.APP_ID.'&scope='.$scope.'&redirect_uri='.SERVER_NAME.'/'.CALLBACK.'&response_type=code&v='.API_VER;

?>

<a href='<?=$url?>'>Войти ВКонтакте</a>