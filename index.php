<?php
/*
 Это как MVC каркас, только гораздо проще.
 Для сайта такого размера данной конструкции должно хватить,
 но в разработке гораздо проще и быстрее.
*/

// Создание константы ROOT с путём к папке проекта на сервере
define('ROOT', dirname(__FILE__));
mb_internal_encoding("UTF-8");

// Запуск сессии
session_start();

// Подключение основных функций
require_once(ROOT.'/application/components/functions.core.php');
// Функции для работы с текстом
require_once(ROOT.'/application/components/functions.text.php');
// Функции для работы с базой данных
require_once(ROOT.'/application/components/functions.database.php');

// Получаем строку запроса
$requestURI = !empty($_SERVER['REQUEST_URI']) ? trim($_SERVER['REQUEST_URI'], '/') : '';

$request = explode('?',$requestURI);
$request = explode('/',$request[0]);
$location = array_shift($request);

$requestURI = $requestURI == '' ? '%2F' : urlencode($requestURI);

// Опрделяем какой контент страницы отображать
switch ($location) {
  case '':
    $page = 'index';
    break;
  case 'login':
    if (isset($_GET['logout'])) {
      session_destroy();
      header('Location: /');
      exit();
    }
  case 'register':
    if (isLogged()) {
      header('Location: /user/'.getUser('login')['login']);
      exit();
    }
  case 'helpful':
  case 'about':
  case 'search':
  case 'sitemap':
  case '404':
  case 'na': // na = New Article
    $page = $location;
    break;
  case 'sandbox':
    if (!isset($_GET['p']) || !preg_match('/^\d+$/',$_GET['p'])) {
      header('Location: ?p='.getPagesCount(2));
      exit();
    }
    $page = $location;
    break;
  case 'archive':
    if (!isset($_GET['p']) || !preg_match('/^\d+$/',$_GET['p'])) {
      header('Location: ?p='.getPagesCount());
      exit();
    }
    $page = $location;
    break;
  case 'view':
    if (!(isset($request[0]) && preg_match('/^[0-9]+$/',$request[0]))) PageNotFound();
    $articleId = array_shift($request);
    if (!empty($request)) PageNotFound();
    $page = 'view';
    break;
  case 'edit':
    if (!isLogged() || (getUser('role')['role'] != 'admin') || !isset($request[0]) || !preg_match('/^\d+$/',$request[0])) PageNotFound();
    $page = 'edit';
    $articleId = array_shift($request);
    break;
  case 'user':
    if (!(isset($request[0]) && preg_match('/^[A-Za-z0-9_-]+$/',$request[0]))) PageNotFound();
    $page = 'user';
    $userLogin = $request[0];
    $userId = getUserId($userLogin);
    break;
  case 'post':
    if (($_SERVER['REQUEST_METHOD'] != 'POST') || (!isLogged())) exit();
    $action = array_shift($request);
    switch ($action) {
      case 'comment':
        $targetId = array_shift($request);
        if (!preg_match('/^\d+$/',$targetId) || !isset($_POST['commentText'])) exit();
        $commentText = $_POST['commentText'];
        createComment($targetId,$commentText);
        break;
      case 'article':
        if (!isset($_POST['header'],$_POST['text'],$_POST['tags']) || !isLogged()) PageNotFound();
        createArticle($_POST);
        break;
      case 'edit':
        if (!isset($_POST['header'],$_POST['text'],$_POST['tags']) || !isLogged() || (getUser('role')['role'] != 'admin') || !isset($request[0]) || !preg_match('/^\d+$/',$request[0])) PageNotFound();
        editArticle($request[0],$_POST);
        break;
      case 'userData':
        for ($i = 0; $i <= 4; $i++) {
          if (!isset($_POST[$i])) exit();
          $_POST[$i] = prepareText($_POST[$i]);
        }
        updateUserData($_POST);
        exit();
        break;
      default:
        exit();
        break;
    }
    exit();
    break;
  // cr = Change Rating
  case 'cr':
    if (count($request) != 3) exit();

    changeRep($request[0],$request[1],$request[2]);
    break;
  // d = delete
  case 'd':
    if (!isLogged() || (getUser('role')['role'] != 'admin') || !isset($request[0],$request[1]) || !preg_match('/^\d+$/',$request[1])) PageNotFound();
    switch ($request[0]) {
      case 'a':
        delete('articles',$request[1]);
        break;
      case 'c':
        delete('comments',$request[1],isset($request[2]) ? $request[2] : false);
        break;
      default:
        PageNotFound();
        break;
    }
    break;
  default:
    PageNotFound();
    break;
}


// Отображение страницы
$viewsPath = ROOT.'/application/views/';
include $viewsPath.'header.php';
include $viewsPath.'navbar.php';
include $viewsPath.$page.'.php';
include $viewsPath.'right-navbar.php';
include $viewsPath.'footer.php';
