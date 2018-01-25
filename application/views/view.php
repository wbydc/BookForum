<div class="container col-md-9 col-sm-12">
  <div class="posts-area col-md-12 no-bg">
    <?php
      $article = getArticle($articleId);
    ?>
    <div class="article" id="article-<?php echo $article['id']; ?>">
      <div class="article-head row col-md-12">
        <div class="article-header col-md-8"><h1><?php echo $article['header']; ?></h1></div>
        <div class="article-date col-md-4">
          <h3 class="tiny-text">
            <a href="/user/<?php echo getUserLogin($article['uid']); ?>"><?php echo getUserLogin($article['uid']); ?></a>
             at <?php echo $article['date']; ?>
            <?php if (isLogged() && (getUser('role')['role'] == 'admin')): ?>
              <a href="/d/a/<?php echo $article['id']; ?>" title="Удалить"><i class="fa fa-close fa-fw" area-hidden="true"></i></a>
              <a href="/edit/<?php echo $article['id']; ?>" title="Редактировать"><i class="fa fa-edit fa-fw" area-hidden="true"></i></a>
             <?php endif; ?>
          </h3>
        </div>
      </div>
      <div class="article-body row col-md-12">
        <div class="article-content">
          <?php
			// Добавляет сдвиг в начало каждого параграфа
			$text = '';
			foreach (explode('<br />',$article['text']) as $string) {
				$text .= '&emsp;'.$string.'<br />';
			}
			// Чтобы не выводить лишний пропуск строки в конце текста
			echo substr($text,0,-6);
		  ?>
        </div>
      </div>
      <div class="article-foot row col-md-12">
        <div class="article-rating col-md-4">
          <?php
            if (isLogged()) echo '<i class="fa fa-arrow-up rating" data-dir="l"></i> ';
            echo '<span class="'.getClass('a',$article['id']).'">'.$article['rating'].'</span>';
            if (isLogged()) echo ' <i class="fa fa-arrow-down rating" data-dir="d"></i>';
          ?>
        </div>
        <div class="article-views col-md-4"><i class="fa fa-eye"></i> <?php echo $article['views']; ?></div>
        <div class="article-comments col-md-4"><i class="fa fa-comments-o"></i> <?php echo getCommentsCount($article['id']); ?></div>
        <h2>
          Теги:
          <?php
            $tags = explode(',',$article['tags']);
            foreach ($tags as $tag): ?>
            <a href="/search?q=<?php echo $tag; ?>"><?php echo $tag; ?></a>
          <?php endforeach; ?>
        </h2>
      </div>
    </div>
    <div class="clearfix"></div>
    <hr/>
    <div class="comments">
      <?php
        $comments = getComments($article['id']);
        foreach ($comments as $comment):
      ?>
        <div class="single-comment col-md-12" id="comment-<?php echo $comment['id']; ?>">
          <div class="avatar col-md-2">
            <img src="/application/assets/images/user/<?php echo getUserAvatar($comment['uid']); ?>.jpg" alt="userAvatar">
          </div>
          <div class="comment-text-box col-md-10">
            <div>
              <div class="username col-md-6">
                <a href="/user/<?php echo getUserLogin($comment['uid']); ?>"><?php echo getUserLogin($comment['uid']); ?></a>
                <?php if (isLogged() && (getUser('role')['role'] == 'admin')): ?>
                  <a href="/d/c/<?php echo $comment['id'].'/'.$article['id']; ?>" data-cid="<?php echo $comment['id']; ?>" title="Удалить" class="dc pull-right"><i class="fa fa-close" area-hidden="true"></i></a>
                <?php endif; ?>
              </div>
              <div class="col-md-4 text-center tiny-text">
                <?php echo $comment['date']; ?>
              </div>
              <div class="col-md-2 text-center">
                <?php
                  if (isLogged()) echo '<i class="fa fa-arrow-up rating" data-dir="l"></i> ';
                  echo '<span class="'.getClass('c',$comment['id']).'">'.$comment['rating'].'</span>';
                  if (isLogged()) echo ' <i class="fa fa-arrow-down rating" data-dir="d"></i>';
                ?>
              </div>
            </div>
            <div class="clearfix comment-head"></div>
            <div class="comment-text col-md-12">
              <?php echo urldecode($comment['text']); ?>
            </div>
          </div>
        </div>
        <div class="clearfix"></div>
      <?php
        endforeach;
        if (isLogged()):
      ?>
      <hr/>
      <form class="comment-form" id="commentForm" method="POST" action="/post/comment/<?php echo $articleId; ?>">
        <div class="form-group col-md-12">
          <label for="commentInput">Ваш комментарий:</label>
          <textarea class="form-control" required="required" name="commentText" rows="5" id="commentInput" placeholder="А что Вы думаете об этой статье? Выскажите своё мнение!"></textarea>
        </div>
        <div class="pull-right col-md-4">
          <input type="submit" class="form-control" value="Отправить">
        </div>
      </form>
      <?php
        else:
      ?>
        <div class="col-md-12 text-center">
          <h2>Войдите или зарегистрируйтесь чтобы оставлять комментарии!</h2>
        </div>
      <?php
        endif;
      ?>
    </div>
  </div>
</div>
