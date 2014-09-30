<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 26.09.2014
 * Time: 3:22
 */

define('USER_ID', 5820095);

// Считаем количество файлов в диретории
function count_files($dir){
  $c=0; // количество файлов. Считаем с нуля
  $d=dir($dir); //
  while($str=$d->read()){
    if($str{0}!='.'){
      if(is_dir($dir.'/'.$str)) $c+=count_files($dir.'/'.$str);
      else $c++;
    };
  }
  $d->close(); // закрываем директорию
  return $c;
}

// Получаем Access Token
function getAccesstoken () {
  $client_id = 3110087;
  $client_secret = 'qJCagpPwpyBWuM9ClRNi';
  $redirect_uri = 'http://tester.loc/vk_photo_saver/index.php';
  $access_token_json = 'https://oauth.vk.com/access_token?client_id=' . $client_id . '&client_secret=' . $client_secret . '&code=' . $_REQUEST['code'] . '&redirect_uri=' . $redirect_uri;
  $access_token = json_decode(file_get_contents($access_token_json));
  return $access_token->access_token;
}

// Получаем фотографии
function getPhoto($offset = 0, $access_token){
  $extended = 0;
  $count = 200;
  $photo_sizes = 0;
  $no_service_albums = 0;
//  $access_token = getAccesstoken();
  $photos_getAll_json = 'https://api.vk.com/method/photos.getAll?owner_id=' . USER_ID . '&extended=' . $extended . '&offset=' . $offset . '&count=' . $count . '&photo_sizes=' . $photo_sizes . '&no_service_albums=' . $no_service_albums . '&access_token=' . $access_token;
  $photos_getAll =  json_decode(file_get_contents($photos_getAll_json), TRUE);
  return $photos_getAll;
}

if (isset($_REQUEST['code'])) {
  $access_token = getAccesstoken();
  $get_photo_count = getPhoto(0, $access_token)['response'][0];

  //Получаем информацию о пользователе и создаём папку
  $users_get_json = 'https://api.vk.com/method/users.get?user_ids=' . USER_ID;
  $users_get = json_decode(file_get_contents($users_get_json), TRUE);
  foreach($users_get['response'] as $user){
    $name = $user['first_name'].' '.$user['last_name'];
    if(!file_exists($name)){
      @mkdir(iconv("utf-8","windows-1251", $name));
    }else{
      continue;
    }
  }

  for($i = 0; count_files(iconv("utf-8","windows-1251",$name)) < $get_photo_count; $i = $i+200){
    $photos_getAll = getPhoto($i, $access_token);
    echo '<pre>';
    print_r($photos_getAll);
    echo '</pre>';
    foreach ($photos_getAll['response'] as $dat) {
      if (isset($dat['src_xxxbig'])) {
        $da[] = $dat['src_xxxbig'];
      }
      elseif (isset($dat['src_xxbig']) && !isset($dat['src_xxxbig'])) {
        $da[] = $dat['src_xxbig'];
      }
      elseif (isset($dat['src_xbig']) && !isset($dat['src_xxxbig']) && !isset($dat['src_xxbig'])) {
        $da[] = $dat['src_xbig'];
      }
      elseif (isset($dat['src_big']) && !isset($dat['src_xxxbig']) && !isset($dat['src_xxbig']) && !isset($dat['src_xbig'])) {
        $da[] = $dat['src_big'];
      }
    }

    foreach ($da as $d) {
      $image_name = basename($d);
      if (!file_exists($name . '/' . $image_name)) {
        file_put_contents(iconv("utf-8","windows-1251", $name) . '/' . $image_name, file_get_contents($d));
      }
    }
  }

//  echo '<pre>';
//  print_r($da);
//  echo '</pre>';
}
else {
  header('Location: login.php');
}