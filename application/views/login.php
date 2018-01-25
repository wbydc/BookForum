<?php
  if ($_SERVER['REQUEST_METHOD'] == "POST") {

    // Определение адреса откуда произошёл вход
    $backLink = isset($_POST['bl']) ? urldecode($_POST['bl']) : '/';

    if (isLogged()) {
      header('Location: '.$backLink);
      exit();
    }

    if (isset($_POST['email'],$_POST['password'])) {
      if ($_SESSION['uid'] = login($_POST['email'],$_POST['password'])) {
        // Перенаправление
        header('Location: '.$backLink);
        exit();
      } else {
        header('Location: ?e&bl='.urlencode($backLink));
        exit();
      }
    } else {
      header('Location: ?e&bl='.urlencode($backLink));
      exit();
    }
  }
?>
<div class="container col-md-9 col-sm-12">
  <div class="posts-area col-md-12 no-bg">
    <h1>Вход:</h1>
    <form method="POST" action="" class="RegForm col-md-4 col-md-offset-4">
      <?php if (isset($_GET['e'])) echo '<p>-Пользователя с таким Email и паролем не найдено</p>'; ?>
      <h2>
        Email:
      </h2>
      <input type="email" name="email" required="required">
      <h2>
        Пароль:
      </h2>
      <input type="password" name="password" required="required">
      <h2></h2>
      <input type="submit" value="Войти">
    </form>
    <div class="clearfix"></div>
  </div>
</div>
