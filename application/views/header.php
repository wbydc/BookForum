<?
  $oses = array(
    'Android' => '(Android)',
    'iPod' => '(iPod)',
    'iPhone' => '(iPhone)',
    'iPad' => '(iPad)'
  );
  $mobile = false;
  $userAgent = $_SERVER['HTTP_USER_AGENT'];
  foreach($oses as $os=>$pattern){
    if(preg_match("/$pattern/i", $userAgent)) {
        $mobile = true;
    }
  }
?>
<!DOCKTYPE html>
<html lang="ru">
<head>
  <title>Книжный Форум</title>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

  <meta name="title" content="Книжный Форум" />
  <meta name="author" content="https://gdmone.ru" />
  <meta name="description" content="Книжный Форум" />

  <!-- Иконки -->
  <link rel="icon" href="https://gdmone.ru/favicon.png" type="image/x-icon">
  <link rel="shortcut icon" href="https://gdmone.ru/favicon.png" type="image/x-icon">

  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="/application/assets/css/reset.css">
  <link rel="stylesheet" href="/application/assets/css/bootstrap.min.css">
  <!-- Font Awesome 4.7.0 -->
  <link rel="stylesheet" href="/application/assets/css/font-awesome.min.css">
  <!-- Main Style -->
  <link rel="stylesheet" href="/application/assets/css/main.css">
  <link rel="stylesheet" href="/application/assets/css/<?= $mobile ? 'mobile' : 'pc'; ?>.nav.css">
</head>
<body>
  <div class="background">
