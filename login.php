<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 26.09.2014
 * Time: 3:22
 */

$client_id = 3110087;
$scope = 'photo,friends';
$redirect_uri = 'http://tester.loc/vk_photo_saver/index.php';

$url = 'http://oauth.vk.com/authorize?client_id='.$client_id.'&scope='.$scope.'&redirect_uri='.$redirect_uri.'&response_type=code&v=5.24';

?>

<a href='<?=$url?>'>Войти ВКонтакте</a>