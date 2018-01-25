<?php

/**
 * Создаёт PDO объект с подключением к базе данных если его ещё нет
 *
 * @return {PDO Object} Объект базы данных
 */
$db = false;
function connectDb() {
  global $db;
  if (!$db) {
    $db = new PDO('mysql:host=127.0.0.1;dbname=kkp2017','root','');
    $db->exec("set names utf8");
  }
  return $db;
}

/**
 * Выполняет запрос к базе данных и возвращает результат
 *
 * @param {String} Запрос
 * @param {String} Параметры для запроса
 * @param {Boolean} Возвращать массив и цифровыми индексами или нет
 * @return {mixed} Полученный результат
 */
function query($sql,$params,$numIndexes = false) {
  $db = connectDb();
  $result = $db->prepare($sql);

  // Расшифровка параметров
  $params = json_decode($params,true);

  // Подготовка запроса
  foreach ($params as $key => $value) {
    $result->bindParam(':'.$key, $params[$key]);
  }

  // Указываем, что хотим получить данные в виде массива
  if ($numIndexes) $result->setFetchMode(PDO::FETCH_NUM);
  else $result->setFetchMode(PDO::FETCH_ASSOC);

  // Выполнение запроса
  $result->execute();

  $return = array();

  $c = 0;
  while ($row = $result->fetch()) {
    foreach ($row as $key => $value) {
      $return[$c][$key] = $value;
    }
    $c++;
  }

  return $return;
}

/**
 * Возвращает запрошенные данные текущего пользователя
 *
 * @param {String} какие данные возвращать
 * @return {Array} Массив запрошенных данных
 */
function getUser($request) {
  if (!isLogged()) exit();

  $params['id'] = $_SESSION['uid'];
  $params = json_encode($params);
  $sql = 'SELECT '.$request.' FROM users WHERE id = :id';
  $result = query($sql,$params);

  return $result[0];
}

/**
 * Возвращает логин пользователя с id = userId
 *
 * @param {Integer} Id пользователя
 * @return {String} Логин пользователя
 */
function getUserLogin($userId) {
  $sql = 'SELECT login FROM users WHERE id = :id';
  $params['id'] = $userId;
  $params = json_encode($params);

  $result = query($sql,$params);
  return $result[0]['login'];
}

/**
 * Возвращает класс числа репутации
 *
 * @param {String} Откуда поступил запрос: a = статья, u = пользователь, c = комментарий
 * @param {Integer} Id проверяемой записи
 * @return {String} Класс объекта
 */
function getClass($ref, $postId) {
  if (!isLogged()) return '';

  // static переменная нужна чтобы не обращаться
  // к базе данных при каждом вызове функции
  static $likes = false;

  if (!$likes) {
    $sql = 'SELECT likes, dislikes FROM users WHERE id = :id';
    $params['id'] = $_SESSION['uid'];
    $params = json_encode($params);

    $result = query($sql,$params);

    $likes = $result[0]['likes'];
    $likes = json_decode($likes,true);
    foreach ($likes as $key => $value) {
      $likes[$key] = explode(',',$value);
    }

    $dislikes = $result[0]['dislikes'];
    $dislikes = json_decode($dislikes,true);
    foreach ($dislikes as $key => $value) {
      $dislikes[$key] = explode(',',$value);
    }
  }

  if (!empty($likes[$ref]) && in_array($postId,$likes[$ref])) return 'liked';
  if (!empty($dislikes[$ref]) && in_array($postId,$dislikes[$ref])) return 'disliked';
  return '';
}

/**
 * Возвращает аватар пользователя с id = userId
 *
 * @param {Integer} Id пользователя
 * @return {String} Файл аватара пользователя
 */
function getUserAvatar($userId) {
  return file_exists(ROOT.'/application/assets/images/user/'.$userId.'.jpg') ? $userId : 'no-image';
}

/**
 * Возвращает id пользователя с login = login
 *
 * @param {String} логин пользователя
 * @return {Integer} Id пользователя
 */
function getUserId($login) {
  $sql = 'SELECT id FROM users WHERE login = :login';
  $params['login'] = $login;
  $params = json_encode($params);

  $result = query($sql, $params);
  if (empty($result)) PageNotFound('Location: /404');
  return $result[0]['id'];
}

/**
 * Возвращает суммарную репутацию пользователя с id = userId
 *
 * @param {Integer} Id пользователя
 * @return {Integer} Репутация
 */
 function getUserRating($userId) {
   $params['uid'] = $userId;
   $params = json_encode($params);

   $sql = 'SELECT rating FROM users WHERE id = :uid';
   $result = query($sql, $params);
   $total = $result[0]['rating'];

   $sql = 'SELECT rating FROM articles WHERE uid = :uid';
   $result = query($sql, $params);
   foreach ($result as $item) {
     $total += $item['rating'];
   }

   $sql = 'SELECT rating FROM comments WHERE uid = :uid';
   $result = query($sql, $params);
   foreach ($result as $item) {
     $total += $item['rating'];
   }

   return $total;
 }

/**
 * Возвращает данные пользователя с id = userId
 *
 * @param {Integer} Id пользователя
 * @return {Array} Данные
 */
function getUserData($userId) {
  $params['uid'] = $userId;
  $params = json_encode($params);

  $sql = 'SELECT role FROM users WHERE id = :uid';
  $role = query($sql, $params);

  $sql = 'SELECT data FROM users WHERE id = :uid';
  $data = query($sql, $params);
  $data = json_decode($data[0]['data'],true);

  $data['role'] = $role[0]['role'];
  foreach ($data as &$value) if ($value == "") $value = 'Не указано';
  return $data;
}

/**
 * Возвращает статьи пользователя с id = userId
 *
 * @param {Integer} Id пользователя
 * @return {Array} Статьи
 */
function getUserArticles($userId) {
  $sql = 'SELECT * FROM articles WHERE uid = :uid';
  $params['uid'] = $userId;
  $params = json_encode($params);

  $result = query($sql, $params);
  if (empty($result)) return false;
  return $result;
}

/**
 * Возвращает комментарии пользователя с id = userId
 *
 * @param {Integer} Id пользователя
 * @return {Array} Комментарии
 */
function getUserComments($userId) {
  $sql = 'SELECT * FROM comments WHERE uid = :uid';
  $params['uid'] = $userId;
  $params = json_encode($params);

  $result = query($sql, $params);
  if (empty($result)) return false;
  return $result;
}

/**
 * Возвращает достижения пользователя с id = userId
 *
 * @param {Integer} Id пользователя
 * @return {Array} Достижения
 */
function getUserAchievements($userId) {
  $sql = 'SELECT achievements FROM users WHERE id = :id';
  $params['id'] = $userId;
  $params = json_encode($params);
  $result = query($sql, $params);

  $result = explode(',',$result[0]['achievements']);
  if (empty($result)) return false;
  return $result;
}

/**
 * Обновляет информацию пользователя
 *
 * @param {Array} Информация
 */
function updateUserData($data) {
  $userId = trim($_POST[4]);
  if (!preg_match('/^[0-9]+$/',$userId)) exit();
  if ($userId != $_SESSION['uid']) exit();

  $sql = 'SELECT data FROM users WHERE id = :id';
  $params['id'] = $userId;
  $params = json_encode($params);

  $udata = query($sql,$params,true);
  $udata = $udata[0][0];
  $udata = json_decode($udata,true);

  $udata['name'] = $data[0];
  $udata['city'] = $data[1];
  $udata['books'] = $data[2];
  $udata['site'] = $data[3];

  $udata = json_encode($udata);

  $sql = 'UPDATE  users SET `data` = :data WHERE id = :id';
  $params = array(
    'data' => $udata,
    'id' => $userId
  );
  $params = json_encode($params);

  query($sql,$params);

  echo 'OK';
}

/**
 * Показывает статью с по её id
 *
 * @param {Integer} Id статьи
 * @return {Array} Данные
 */
function getArticle($articleId) {
  $sql = 'SELECT * FROM articles WHERE id = :id';
  $params['id'] = $articleId;
  $params = json_encode($params);

  $result = query($sql, $params);

  if (empty($result)) PageNotFound();

  $result = $result[0];

  // Увеличение кол-ва просмотров для выбранной статьи
  $views = $result['views'];
  $views++;

  $sql = 'UPDATE `articles` SET views = :views WHERE id = :id';
  $params = [];
  $params['id'] = $articleId;
  $params['views'] = $views;
  $params = json_encode($params);
  query($sql, $params);

  return $result;
}

/**
 * Возвращает count статей сортируя по sortBy
 *
 * @param {String} Сортировать по
 * @param {Integer} Отображение статей
 * @param {Integer} Кол-во статей
 * @return {Array} Статьи
 */
function getArticles($sortBy = 'id',$display = 1,$count = 5) {
  $sql = 'SELECT * FROM articles WHERE display = :display ORDER BY '.$sortBy.' DESC LIMIT '.$count;
  $params['display'] = $display;
  $params = json_encode($params);
  $result = query($sql,$params);

  return $result;
}

/**
 * Возвращает статьи в архивном представлении
 *
 * @param {Integer} Страница
 * @return {Array} Статьи
 */
function getAArticles($page) {
  $from = 20 * ($page - 1);
  $sql = 'SELECT id,header,rating,views,date FROM articles WHERE display = 1 ORDER BY id LIMIT '.$from.',20';

  $result = query($sql,'{}');

  return $result;
}

/**
 * Возвращает кол-во страниц на странице с архивом
 *
 * @return {Integer} Кол-во страниц
 */
function getPagesCount($display = 1) {
  $sql = 'SELECT COUNT(*) FROM articles WHERE display = :display';
  $params['display'] = $display;
  $params = json_encode($params);
  $result = query($sql,$params,true);
  $result = $result[0][0];
  return ceil($result / 20);
}

/**
 * Возвращает комментарии к статье с id = articleId
 *
 * @param {Integer} Id статьи
 * @return {Array} Комментарии
 */
function getComments($articleId) {
  $sql = 'SELECT * FROM comments WHERE idto = :idto ORDER BY id';
  $params['idto'] = $articleId;
  $params = json_encode($params);

  $result = query($sql,$params);

  return $result;
}

/**
 * Возвращает кол-во комментариев к статье с id = articleId
 *
 * @param {Integer} Id статьи
 * @return {Array} Комментарии
 */
function getCommentsCount($articleId) {
  $sql = 'SELECT COUNT(*) FROM comments WHERE idto = :idto ORDER BY id DESC';
  $params['idto'] = $articleId;
  $params = json_encode($params);

  $result = query($sql,$params,true);

  return $result[0][0];
}

/**
 * Изменяет репутацию у объекта $postId
 *
 * @param {String} Откуда поступил запрос: a = статья, u = пользователь, c = комментарий
 * @param {Integer} Id проверяемой записи
 * @param {String} Действие: l = повышение, d = понижение
 */
function changeRep($ref, $postId, $act) {
  if (!isLogged()) exit();

  // Определям из какой БД доставать рейтнинг
  switch ($ref) {
    case 'a':
      $table = 'articles';
      break;
    case 'c':
      $table = 'comments';
      break;
    default:
      exit();
      break;
  }

  $sql = 'SELECT rating FROM '.$table.' WHERE id = :id';
  $params['id'] = $postId;
  $params = json_encode($params);

  $rating = query($sql,$params,true);
  $rating = $rating[0][0];

  if ($ref == 'a') {
    $sql = 'SELECT display FROM articles WHERE id = :id';
    $display = query($sql,$params,true);
  }

  $params = array();

  $sql = 'SELECT likes, dislikes FROM users WHERE id = :id';
  $params['id'] = $_SESSION['uid'];
  $params = json_encode($params);

  $result = query($sql,$params);

  $params = array();

  $likes = $result[0]['likes'];
  $likes = json_decode($likes,true);
  foreach ($likes as $key => $value) {
    $likes[$key] = explode(',',$value);
  }

  $dislikes = $result[0]['dislikes'];
  $dislikes = json_decode($dislikes,true);
  foreach ($dislikes as $key => $value) {
    $dislikes[$key] = explode(',',$value);
  }

  $class = getClass($ref, $postId);
  switch ($class) {
    case '':
      switch ($act) {
        case 'l':
          $rating++;
          $likes[$ref][] = $postId;
          break;
        case 'd':
          $rating--;
          $dislikes[$ref][] = $postId;
          break;
        default:
          exit();
          break;
      }
      break;
    case 'liked':
      unset($likes[$ref][array_search($postId,$likes[$ref])]);
      switch ($act) {
        case 'l':
          $rating--;
          break;
        case 'd':
          $rating--;
          $rating--;
          $dislikes[$ref][] = $postId;
          break;
        default:
          exit();
          break;
      }
      break;
    case 'disliked':
      unset($dislikes[$ref][array_search($postId,$dislikes[$ref])]);
      switch ($act) {
        case 'l':
          $rating++;
          $rating++;
          $likes[$ref][] = $postId;
          break;
        case 'd':
          $rating++;
          break;
        default:
          exit();
          break;
      }
      break;
  }

  foreach ($likes as $key => $value) {
    $likes[$key] = implode(',',$value);
  }
  $likes = json_encode($likes);
  foreach ($dislikes as $key => $value) {
    $dislikes[$key] = implode(',',$value);
  }
  $dislikes = json_encode($dislikes);

  $sql = 'UPDATE '.$table.' SET rating = :rating WHERE id = :id';
  $params['id'] = $postId;
  $params['rating'] = $rating;

  $params = json_encode($params);

  query($sql,$params);

  $params = array();

  $sql = 'UPDATE users SET likes = :likes , dislikes = :dislikes WHERE id = :id';
  $params['id'] = $_SESSION['uid'];
  $params['likes'] = $likes;
  $params['dislikes'] = $dislikes;
  $params = json_encode($params);

  query($sql,$params);

  if (($ref == 'a') && ($display[0][0] == '2') && ($rating > 20)) {
    $sql = 'UPDATE articles SET display = 1 WHERE id = :id';
    $params = array('id' => $postId);
    $params = json_encode($params);

    query($sql,$params);
  }

  echo $rating;
  exit();
}

/**
 * Регистрирует нового пользователя
 *
 * @param {Array} Данные, необходимые для регистрации
 * @return {Array} Ошибки, если они возникли
 */
function createUser($data) {
  $errors = false;
  if (!isset($data['login'],$data['email'],$data['password'])) $errors[] = 'Пропущенно одно или несколько полей!';

  $login = $data['login'];
  $email = $data['email'];
  $password = $data['password'];

  if ($password != $data['repeatpassword']) $errors[] = 'Пароли не совпадают';

  // Паттерны из статьи https://habrahabr.ru/post/66931/
  if (!preg_match('/^[\w_]{3,16}$/',$login)) $errors[] = 'Неверный формат логина';
  if (!preg_match('/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/',$email)) $errors[] = 'Неверный формат электронной почты';
  if (!preg_match('/^[\w_]{6,18}$/',$password)) $errors[] = 'Неверный формат пароля';

  if ($errors) return $errors;

  $sql = 'SELECT COUNT(*) FROM users WHERE login = :login OR email = :email';
  $params = array(
    'login' => $login,
    'email' => $email
  );
  $params = json_encode($params);
  $result = query($sql,$params,true);
  $result = $result[0][0];

  if ($result != 0) $errors[] = 'Пользователь с таким логином или почтовым адресом уже существует';

  if ($errors) return $errors;

  $sql = 'INSERT INTO `users`(`email`, `password`, `data`, `login`, `role`,`likes`, `dislikes`)
  VALUES (:email,:password,\'{"name":"","city":"","site":"","books":""}\',:login,\'user\',\'{"a":"","u":"","c":""}\',\'{"a":"","u":"","c":""}\')';
  $params = array(
    'email' => $email,
    'password' => md5(sha1($password)),
    'login' => $login
  );
  $params = json_encode($params);
  query($sql,$params);

  $id = login($email,$password);
  $_SESSION['uid'] = $id;

  $backLink = '/user/'.$login;
  if (isset($_GET['bl'])) {
    $backLink = urldecode($_GET['bl']);
  }
  header('Location: '.$backLink);
  exit();
}

/**
 * Записывает в базу данных комментарий к статье targetId
 *
 * @param {Integer} Id статьи
 * @param {String} Текст комментария
 */
function createComment($targetId,$commentText) {
  $sql = 'SELECT COUNT(*) FROM articles WHERE id = :id';
  $params['id'] = $targetId;
  $params = json_encode($params);
  $result = query($sql,$params,true);
  if ($result[0][0] != 1) exit();
  $params = array();

  $commentText = nl2br(prepareText($commentText));

  $sql = 'INSERT INTO `comments`(`idto`, `uid`, `text`, `date`) VALUES ( :idto , :uid , :text , :date )';
  $params['idto'] = $targetId;
  $params['uid'] = $_SESSION['uid'];
  $params['text'] = $commentText;
  $params['date'] = date('Y.m.d h:i');
  $params = json_encode($params);
  query($sql,$params);

  $params = array();
  $sql = 'SELECT COUNT(*) FROM comments WHERE uid = :uid';
  $params['uid'] = $_SESSION['uid'];
  $params = json_encode($params);
  $result = query($sql,$params,true);
  $result = $result[0][0];
  achievementManager('comment',$result);

  echo 'OK';
}

/**
 * Создаёт статью в базе данных
 *
 * @param {Array} Данные
 */
function createArticle($data) {
  $header = prepareText($data['header']);
  $text = nl2br(strip_tags(filterBadWords($data['text'])));
  $tags = explode(',',$data['tags']);
  foreach ($tags as $key => $tag) {
    $tags[$key] = trim($tag);
    $str = prepareText($tags[$key]);
    if ($str != $tags[$key]) unset($tags[$key]);
  }

  $text = BBCode($text);
  $text = addShy($text);

  $sql = 'SELECT role FROM users WHERE id = :id';
  $params['id'] = $_SESSION['uid'];
  $params = json_encode($params);
  $result = query($sql,$params,true);

  if ($result[0][0] == 'user') {
    $sqlAddon = ',`display`';
    $sqlAddonValue = ',2';
    $tags[] = 'песочница';
  }

  $tags = array_unique($tags);
  $tags = implode(',',$tags);

  $sql = 'INSERT INTO articles (`header`,`uid`,`date`,`tags`,`text`'.$sqlAddon.') VALUES (:header,:uid,:date,:tags,:text'.$sqlAddonValue.')';
  $params = array(
    'header' => $header,
    'uid' => $_SESSION['uid'],
    'date' => date('Y/m/d'),
    'tags' => $tags,
    'text' => $text
  );
  $params = json_encode($params);
  query($sql,$params);

  header('Location: /');
  exit();
}

/**
 * Обновляет статью в базе данных
 *
 * @param {Integer} Id статьи
 * @param {Array} Данные
 */
function editArticle($articleId,$data) {
  $header = prepareText($data['header']);
  $text = nl2br(strip_tags(filterBadWords($data['text'])));

  $tags = explode(',',$data['tags']);
  foreach ($tags as $key => $tag) {
    $tags[$key] = trim($tag);
    $str = prepareText($tags[$key]);
    if ($str != $tags[$key]) unset($tags[$key]);
  }
  $tags = array_unique($tags);
  $tags = implode(',',$tags);

  $text = BBCode($text);

  $sql = 'UPDATE articles SET `header` = :header, `text` = :text, `tags` = :tags WHERE id = :id';
  $params = array(
    'id' => $articleId,
    'header' => $header,
    'text' => $text,
    'tags' => $tags
  );
  $params = json_encode($params);
  query($sql,$params);

  header('Location: /view/'.$articleId);
  exit();
}

/**
 * Удаляет запись из таблицы table по id
 *
 * @param {Srting} Название таблицы
 * @param {Integer} Id статьи
 * @param {Integer} Id статьи
 */
function delete($table, $id, $return = false) {
  $sql = 'DELETE FROM '.$table.' WHERE id = :id';
  $params['id'] = $id;
  $params = json_encode($params);

  query($sql,$params);

  if ($table == 'articles') {
    $sql = 'DELETE FROM comments WHERE idto = :id';

    query($sql,$params);
  }
  if (!$return) header('Location: /archive');
  else
    if ($return == 'r') echo 'OK';
    else header('Location: /view/'.$return);

  exit();
}

/**
 * Выполняет поиск по базе данных
 *
 * @param {String} Поисковый запрос
 * @return {Array} Найденые по запросу статьи
 */
function search($qstr) {
  $qstr = prepareText($qstr);
  $qstr1 = '%'.$qstr;
  $qstr2 = $qstr.'%';
  $qstr3 = '%'.$qstr.'%';
  $params['qstr1'] = $qstr1;
  $params['qstr2'] = $qstr2;
  $params['qstr3'] = $qstr3;
  $params = json_encode($params);

  // Поиск по базе данных
  $sql = 'SELECT * FROM articles WHERE
    header LIKE :qstr1 OR header LIKE :qstr2 OR header LIKE :qstr3
    ORDER BY id DESC';

  $result = query($sql,$params);

  $sql = 'SELECT * FROM articles WHERE
    text LIKE :qstr1 OR text LIKE :qstr2 OR text LIKE :qstr3
    ORDER BY id DESC';

	$result = array_merge($result,query($sql,$params));

  $sql = 'SELECT * FROM articles WHERE
    tags LIKE :qstr1 OR tags LIKE :qstr2 OR tags LIKE :qstr3
    ORDER BY id DESC';

	$result = array_merge($result,query($sql,$params));

	$ids = array();
	foreach ($result as $key => $article) {
		if (in_array($article['id'],$ids)) unset($result[$key]);
		else $ids[] = $article['id'];
	}
  return $result;
}
