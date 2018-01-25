<?php
  $errors = false;
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['login'],$_POST['password'],$_POST['email'],$_POST['repeatpassword'])) PageNotFound();
    $errors = createUser($_POST);
  }
?>
<div class="container col-md-9 col-sm-12">
  <div class="posts-area col-md-12 no-bg">
    <h1>Регистрация:</h1>
    <form method="POST" action="" class="RegForm col-md-4 col-md-offset-4">
      <?php if ($errors): ?>
        <?php foreach ($errors as $error): ?>
          <p>-<?php echo $error; ?></p>
        <?php endforeach; ?>
      <?php endif; ?>
      <h2>
        Логин:
        <span class="dropdown">
          <i aria-hidden="true" class="fa fa-info-circle"></i>
          <div class="dropdown-content">
            От 3 до 16 символов<br/>
            Используется в комментариях и как краткая ссылка на ваш профиль
          </div>
        </span>
      </h2>
      <input type="text" name="login" required="required"<?php if (isset($_POST['login'])) {echo ' value="'.$_POST['login'].'"';} ?>>
      <h2>
        Email:
        <span class="dropdown">
          <i aria-hidden="true" class="fa fa-info-circle"></i>
          <div class="dropdown-content">
            Используется при входе и в информационных рассылках
          </div>
        </span>
      </h2>
      <input type="email" name="email" required="required"<?php if (isset($_POST['email'])) {echo ' value="'.$_POST['email'].'"';} ?>>
      <h2>
        Пароль:
        <span class="dropdown">
          <i aria-hidden="true" class="fa fa-info-circle"></i>
          <div class="dropdown-content">
            От 6 до 18 символов
          </div>
        </span>
      </h2>
      <input type="password" name="password" required="required"<?php if (isset($_POST['password'])) {echo ' value="'.$_POST['password'].'"';} ?>>
      <h2>Повторите пароль:</h2>
      <input type="password" name="repeatpassword" required="required"<?php if (isset($_POST['repeatpassword'])) {echo ' value="'.$_POST['repeatpassword'].'"';} ?>>
      <h2></h2>
      <input type="submit" value="Зарегистрироваться">
    </form>
    <div class="clearfix"></div>
    <p>Дополнительную информацию Вы сможете указать в профиле</p>
  </div>
</div>
