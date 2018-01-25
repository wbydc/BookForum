<?php

/**
 * Переводит на страницу Error404
 */
function PageNotFound() {
  header('Location: /404');
  exit();
}

/**
 * Возвращает массив с содержанием файла
 *
 * @param {String} Имя файла
 * @param {String} Тип файла, по умолчанию 'json'
 * @return {Array} || {Boolean} Массив если всё правильно, false если возникла ошибка
 */
function getFContent($filename,$encoding = 'json') {
  if (file_exists($filename)) {
    switch ($encoding) {
      case 'json':
        return json_decode(file_get_contents($filename),true);
        break;
      case 'txt':
        return file($filename,FILE_SKIP_EMPTY_LINES);
        break;
      default:
        return false;
        break;
    }
  } else return false;
}

/**
 * Проверяет вошёл ли пользователь
 *
 * @return {Boolean} True если вошёл, False иначе
 */
function isLogged() {
  return (isset($_SESSION['uid']) && $_SESSION['uid']) ? true : false;
}

/**
 * Добавляет достижения
 *
 * @param {String} Причина
 * @param {Mixed} Параметр
 */
function achievementManager($area,$param) {
  static $achievementsList = false;
  if (!$achievementsList) $achievementsList = getFContent(ROOT.'/application/components/achievementsList.json');

  if (!isset($achievementsList[$area]) || !isset($achievementsList[$area][$param])) return 0;
  $achievement = $achievementsList[$area][$param]['fa'];
  $bonus = $achievementsList[$area][$param]['bonus'];

  $userId = $_SESSION['uid'];
  $userRating = getUserRating($userId);
  $userAchievements = getUserAchievements($userId);
  if (!$userAchievements) $userAchievements = array();
  $userAchievements[] = $achievement;

  $sql = 'UPDATE users SET rating = :rating, achievements = :achievements WHERE id = :id';
  $params['rating'] = $userRating + $bonus;
  $params['achievements'] = implode(',',$userAchievements);
  $params['id'] = $userId;
  $params = json_encode($params);
  query($sql,$params);
}

/**
 * Проверяет существование пользователя, логирует его если всё правильно
 *
 * @param {String} Логин пользователя
 * @param {String} Пароль
 * @param {Boolean} Запомнить ли куки, по умолчанию false
 * @return {Boolean} True если есть, False иначе
 */
function login($email, $password, $save = false) {
  $email = filter_var($email, FILTER_VALIDATE_EMAIL);
  $password = md5(sha1($password));

  // Подготовка параметров
  $params['email'] = $email;
  $params['password'] = $password;
  $params = json_encode($params);

  // Запрос к бд
  $sql = 'SELECT id FROM users WHERE ( email = :email AND password = :password )';

  // Получение резулльтата
  $result = query($sql,$params,true);

  return $result[0][0];
}
