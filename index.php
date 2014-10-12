<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 26.09.2014
 * Time: 3:22
 */

require_once 'config.php';

// Получаем фотографии
function getPhoto($user_id, $offset = 0) {
  $extended = 0;
  $count = 200;
  $photo_sizes = 0;
  $no_service_albums = 0;

  $photos_getAll_json = 'https://api.vk.com/method/photos.getAll?owner_id=' . $user_id . '&extended=' . $extended . '&offset=' . $offset . '&count=' . $count . '&photo_sizes=' . $photo_sizes . '&no_service_albums=' . $no_service_albums . '&access_token=' . $_SESSION['access_token'];
  $photos_getAll = json_decode(file_get_contents($photos_getAll_json), TRUE);
  return $photos_getAll;
}

if (isset($_SESSION['access_token'])) {
  if ($_SERVER['REQUEST_METHOD'] == 'POST' and isset($_POST['user_link'])) {

    if (trim(strip_tags($_POST['switch'])) == 'user') {
      //Получаем информацию о пользователе и создаём папку
      $user_id_row = basename(trim(strip_tags($_POST['user_link'])));
      $users_get_json = 'https://api.vk.com/method/users.get?user_ids=' . $user_id_row;
      $users_get = json_decode(file_get_contents($users_get_json), TRUE);
      foreach ($users_get['response'] as $user) {
        $name = preg_replace('/[^(\x20-\x7F)]\*/','', $user['first_name'] . ' ' . $user['last_name']);
        $user_id = $user['uid'];
        if (!file_exists($name)) {
          @mkdir(iconv("utf-8", "windows-1251", $name));
        }
        else {
          continue;
        }
      }
    }
    elseif (trim(strip_tags($_POST['switch'])) == 'group') {
      $user_id_row = basename(trim(strip_tags($_POST['user_link'])));
      $users_get_json = 'https://api.vk.com/method/groups.getById?group_id=' . $user_id_row;
      $users_get = json_decode(file_get_contents($users_get_json), TRUE);
      foreach ($users_get['response'] as $user) {
        $name = preg_replace('/[^(\x20-\x7F)]\*/','', $user['name']);
        $user_id = '-' . $user['gid'];
        if (!file_exists($name)) {
          @mkdir(iconv("utf-8", "windows-1251", $name));
        }
        else {
          continue;
        }
      }
    }else{
      echo "<h1 style='width: 382px; margin-left: auto; margin-right: auto; margin-top: 50px'>Укажите на что ведёт ссылка!</h1>";
    }
    //Общее количество фото
    $get_photo_count = getPhoto($user_id, 0)['response'][0];

    //Выбираем фотку максимального качества из доступных в вк
    for ($i = 0; $i < ceil($get_photo_count / 200); $i++) {
      $photos_getAll = getPhoto($user_id, $i * 200);

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

    //Парсим список фоток в папку юзверя
    foreach ($da as $d) {
      $image_name = basename($d);
      if (!file_exists(iconv("utf-8", "windows-1251", $name) . '/' . $image_name)) {
        file_put_contents(iconv("utf-8", "windows-1251", $name) . '/' . $image_name, file_get_contents($d));
      }
      else {
        file_put_contents(iconv("utf-8", "windows-1251", $name) . '/errors.txt', $d . "\r\n", FILE_APPEND);
      }
    }

    $count_photo = count($da);

//    echo '<pre>';
//    print_r($da);
//    echo '</pre>';
  }
}
else {
  header('Location: login.php');
}

?>

<!doctype html>
<html lang="en-US">
<head>
  <meta charset="UTF-8">
  <title>ВКонтакте фото сэйвер</title>
</head>
<body>
<form method="post" action="<?= $_SERVER['PHP_SELF'] ?>"
      style="width: 382px; margin-left: auto; margin-right: auto; margin-top: 50px">
  <input type="text" name="user_link" size="60"
         style="margin-bottom: 5px;"/><br/>
  <span>Ссылка на пользователя</span><input type="radio" name="switch"
                                            value="user"/> или
  группу <input type="radio" name="switch" value="group"/><br/>
  <input type="submit" value="Спарсить фото!" style="width: 382px;"/><br/>
</form>
<?php if($da): ?>
  <p>Скачано фотографий: <?=$count_photo?><br/>
    Название: <?=$name?>
  </p>
<?php endif; ?>
</body>
</html>