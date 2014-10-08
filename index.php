<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 26.09.2014
 * Time: 3:22
 */

require_once 'config.php';

//define('USER_ID', 26538964); //341 - Яна Абышова
define('USER_ID', 14670387); //221 - Сергей Врублевский
//define('USER_ID', 17829185); //211 - Юля Швед
//define('USER_ID', 15906596); //228 - Дмитрий Болбас
//define('USER_ID', 18661298); //205 - Никита Митюшников

//define('USER_ID', 5820095);


// Получаем фотографии
function getPhoto($offset = 0) {
  $extended = 0;
  $count = 200;
  $photo_sizes = 0;
  $no_service_albums = 0;

  $photos_getAll_json = 'https://api.vk.com/method/photos.getAll?owner_id=' . USER_ID . '&extended=' . $extended . '&offset=' . $offset . '&count=' . $count . '&photo_sizes=' . $photo_sizes . '&no_service_albums=' . $no_service_albums . '&access_token=' . $_SESSION['access_token'];
  $photos_getAll = json_decode(file_get_contents($photos_getAll_json), TRUE);
  return $photos_getAll;
}

if (isset($_SESSION['access_token'])) {
  //Общее количество фото
  $get_photo_count = getPhoto(0)['response'][0];
  //Получаем информацию о пользователе и создаём папку
  $users_get_json = 'https://api.vk.com/method/users.get?user_ids=' . USER_ID;
  $users_get = json_decode(file_get_contents($users_get_json), TRUE);
  foreach ($users_get['response'] as $user) {
    $name = $user['first_name'] . ' ' . $user['last_name'];
    if (!file_exists($name)) {
      @mkdir(iconv("utf-8", "windows-1251", $name));
    }
    else {
      continue;
    }
  }

  for ($i = 0; $i < ceil($get_photo_count / 200); $i++) {
    $photos_getAll = getPhoto($i * 200);
    foreach ($photos_getAll['response'] as $dat) {
      if (isset($dat[0])) {
        continue;
      }
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
  }

  foreach ($da as $d) {
    $image_name = basename($d);
    if (!file_exists(iconv("utf-8", "windows-1251", $name) . '/' . $image_name)) {
      file_put_contents(iconv("utf-8", "windows-1251", $name) . '/' . $image_name, file_get_contents($d));
    }
    else {
      file_put_contents(iconv("utf-8", "windows-1251", $name) . '/errors.txt', $d . "\r\n", FILE_APPEND);
    }
  }

  echo '<pre>';
  print_r($da);
  echo '</pre>';
}
else {
  header('Location: login.php');
}