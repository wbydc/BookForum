<div class="right-navbar col-md-3 pull-right">
  <div class="no-bg">
    <div class="user">
      <?php if (isLogged()): ?>
        <p>Welcome back,</p>
        <p><?php echo getUser('login')['login']; ?>!</p>
        <?php
          $userRating = getUserRating($_SESSION['uid']);
          if ($userRating > 20) echo '<a class="form-control" href="/na">Написать статью</a>';
        ?>
        <a class="form-control" href="/user/<?php echo getUser('login')['login']; ?>">Личная страница</a>
        <a class="form-control" href="/login?logout">Выйти</a>
      <?php else: ?>
      <form action="/login" method="POST">
        <input type="hidden" name="bl" value="<?php echo $requestURI; ?>">
        <div class="input-group">
          <span class="input-group-addon"><i class="fa fa-envelope-o fa-fw" aria-hidden="true"></i></span>
          <input type="email" name="email" placeholder="Email" required="required">
        </div>
        <div class="input-group">
          <span class="input-group-addon"><i class="fa fa-key fa-fw" aria-hidden="true"></i></span>
          <input type="password" name="password" placeholder="Password" required="required">
        </div>
        <div>
          <input type="submit" value="Войти">
        </div>
        <a class="form-control" href="/register?bl=<?php echo $requestURI; ?>">Зарегистрироваться</a>
      </form>
    <?php endif; ?>
    </div>
    <hr>
    <div class="hot">
      <h2>Горячие статьи:</h2>
      <ul class="hot-ul">
        <?php
          $hotarticles = getArticles('rating');
          foreach ($hotarticles as $article): ?>
          <li><a href="/view/<?php echo $article['id']; ?>"><?php echo $article['header']; ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <hr>
    <div class="best">
      <h2>Самые читаемые:</h2>
      <ul class="best-ul">
        <?php
          $bestarticles = getArticles('views');
          foreach ($bestarticles as $article): ?>
          <li><a href="/view/<?php echo $article['id']; ?>"><?php echo $article['header']; ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</div>
