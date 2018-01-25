<div class="container col-md-9 col-sm-12">
  <div class="posts-area col-md-12 no-bg">
    <div class="row">
      <div class="col-md-4">
        <div class="img-wrapper">
          <img alt="UserAvatar" src="/application/assets/images/user/<?php echo getUserAvatar($userId); ?>.jpg">
        </div>
      </div>
      <div class="col-md-8">
        <h1 class="userTop">
          <b><?php echo $userLogin; ?></b>
          <?php
            $isCurUser = (isLogged() && ($userId == $_SESSION['uid'])) ? true : false;
            if ($isCurUser) echo ' <i class="fa fa-pencil userEdit"></i>';
          ?>
          <span class="pull-right">
            Репутация:
            <?php echo $userRating = getUserRating($userId); ?>
          </span>
        </h1>
        <div class="overview">
          <ul>
            <?php
            if ($isCurUser) echo '<form action="/post/userData" method="POST">';
            ?>
            <?php $userData = getUserData($userId); ?>
            <li><b>Группа:</b> <?php echo $userData['role']; ?></li>
            <li><b>Реальное имя:</b> <span class="userData"><?php echo $userData['name']; ?></span></li>
            <li><b>Город:</b> <span class="userData"><?php echo $userData['city']; ?></span></li>
            <li><b>Любимые книги:</b> <span class="userData"><?php echo $userData['books']; ?></span></li>
            <li><b>Сайт:</b> <span class="userData"><?php echo $userData['site'] != 'Не указано' ? '<a href="'.(preg_match('/https?\:\/\//',$userData['site']) ? '' : '//').$userData['site'].'">'.$userData['site'].'</a>' : $userData['site']; ?></span></li>
            <?php
              if ($isCurUser) echo '
                <input type="hidden" name="4" value="'.$_SESSION['uid'].'">
                <input type="submit" class="userEditSubmit pull-right" value="Сохранить" style="display: none">
              ';
            ?>
            <?php if ($isCurUser) echo '</form>'; ?>
          </ul>
        </div>
      </div>
      <div class="col-md-12">
        <h2>
          Достижения:
          <?php
            $achievements = getFContent(ROOT.'/application/components/achievements.json');
            $userAchievements = getUserAchievements($userId);
            if ($userAchievements) foreach ($userAchievements as $achievement):
          ?>
            <span class="dropdown">
              <i aria-hidden="true" class="fa fa-<?php echo $achievement; ?>"></i>
              <div class="dropdown-content">
                <?php echo $achievements[$achievement]; ?>
              </div>
            </span>
          <?php endforeach; ?>
        </h2>
      </div>
      <div class="col-md-12">
        <?php
          $userArticles = getUserArticles($userId);
          if ($userArticles):
        ?>
          <h2>Статьи пользователя:</h2>
          <?php foreach ($userArticles as $article): ?>
            <a class="userActionBody" href="/view/<?php echo $article['id']; ?>">
              <div class="col-md-6"><?php echo strlen($article['header']) > 50 ? mb_substr($article['header'],0,50,'UTF-8')."..." : $article['header']; ?></div>
              <div class="col-md-2"><?php echo $article['date']; ?></div>
              <div class="col-md-2"><i class="fa fa-line-chart"></i> <?php echo $article['rating']; ?></div>
              <div class="col-md-2"><i class="fa fa-eye"></i> <?php echo $article['views']; ?></div>
            </a>
            <div class="clearfix userAction"></div>
          <?php endforeach; ?>
        <?php
          endif;
          $userComments = getUserComments($userId);
          if ($userComments):
        ?>
          <h2>Комментарии пользователя:</h2>
          <?php foreach ($userComments as $comment): ?>
            <a class="userActionBody" href="/view/<?php echo $comment['idto']; ?>#comment-<?php echo $comment['id']; ?>">
              <div class="col-md-6"><?php echo strlen($comment['text']) > 50 ? substr($comment['text'],0,50)."..." : $comment['text']; ?></div>
              <div class="col-md-4"><?php echo $comment['date']; ?></div>
              <div class="col-md-2"><i class="fa fa-line-chart"></i> <?php echo $comment['rating']; ?></div>
            </a>
            <div class="clearfix userAction"></div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
