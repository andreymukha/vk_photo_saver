<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 08.10.2014
 * Time: 20:11
 */

require_once 'config.php';

if (isset($_REQUEST['code'])) {
  $access_token_json = 'https://oauth.vk.com/access_token?client_id=' . APP_ID . '&client_secret=' . CLIENT_SECRET . '&code=' . $_REQUEST['code'];
  $access_token = json_decode(file_get_contents($access_token_json));
  $_SESSION['access_token'] = $access_token->access_token;
  header('Location:'.SERVER_NAME);
}