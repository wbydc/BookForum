<div class="container col-md-9 col-sm-12">
  <div class="posts-area col-md-12 no-bg">
	<div class="page-heading">
      <h2>
		<div class="text-center">Вы зашли на книжный форум</div>
		<br/>
		&emsp;Здесь за&shy;ре&shy;гис&shy;три&shy;ро&shy;ванные пользователи пишут свои статьи, отзывы о книгах, оставляют комментарии и оценивают публикации других участников сообщества. Не&shy;за&shy;ре&shy;гистри&shy;ро&shy;ванные посетители имеют возможность просматривать вышеописанное.
		<br/>
		&emsp;За активное участие в жизни сайта вы получаете достижения, влияющие на вашу репутацию. Их список можно просмотреть в разделе "О сайте".
		<br/>
		&emsp;Правила поведения на форуме:
		<ol>
		  <li>Запрещена нецензурная лексика. Любые нецензурные выражения автоматически блокируются. За каждое заблокированное выражение пользователь получает -1 к репутации</li>
		  <li>Запрещена "накрутка" репутации и комментариев</li>
		</ol>
	  </h2>
    </div>

    <div class="page-heading">
      <h1>Последние статьи:</h1>
    </div>

    <?php
      $articles = getArticles('id',1,3);
      foreach ($articles as $article): ?>
      <article id="article-<?php echo $article['id']; ?>">
        <div class="article-head row col-md-12">
          <div class="article-header col-md-8"><h1><a href="/view/<?php echo $article['id']; ?>"><?php echo $article['header']; ?></a></h1></div>
          <div class="article-date col-md-4"><h3 class="tiny-text"><a href="/user/<?php echo getUserLogin($article['uid']); ?>"><?php echo getUserLogin($article['uid']); ?></a> at <?php echo $article['date']; ?></h3></div>
        </div>
        <div class="article-body row col-md-12">
          <div class="article-content">
            <?php
              $length = 300;
              $articleText = mb_substr($article['text'],0,$length,'UTF-8');
              if (substr_count($articleText,'>') != substr_count($articleText,'<')) {
                $length = mb_strrpos($articleText,'<','UTF-8');
                $articleText = mb_substr($articleText,0,$length,'UTF-8');
              }
              echo $articleText.((strlen($article['text']) > $length) ? "..." : '');
            ?>
            <div class="article-full">
              <a href="/view/<?php echo $article['id']; ?>" class="tiny-text"><i class="fa fa-newspaper-o"></i> Читать полностью...</a>
            </div>
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
        </div>
      </article>
      <div class="clearfix article-border"></div>
    <?php endforeach; ?>


  </div>
</div>
