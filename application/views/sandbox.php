<div class="container col-md-9 col-sm-12">
  <div class="posts-area col-md-12 no-bg">
    <h1>Статьи в песочнице:</h1>
    <?php
      $curPage = $_GET['p'];
      $pagesCount = getPagesCount(2);
      if ($curPage > $pagesCount) PageNotFound();
      $from = 20 * ($curPage - 1);
      $articles = getArticles('id',2,$from.',10');
      if (count($articles) == 0):
    ?>
      <div class="article-content">
        <h2>&emsp;Песочница - это место, доступное для всех посетителей, куда попадают статьи пользователей после их написания. Здесь они находятся ровно неделю. Если статья в течении этой недели набирает более +20 очков рейтинга, то она попадает в архив ко всем статьям. В противном случае - она АВТОМАТИЧЕСКИ удаляется.</h2>
      </div>
    <?php
      else:
        $date = strtotime(date('Y/m/d'));
        $deleted = false;
        foreach ($articles as $article) {
          $articleDate = strtotime($article['date']);
          if (($date-$articleDate)/(60*60*24) > 7) {
            deleteArticle($article['id']);
            $deleted = true;
          }
        }
        if ($deleted) {
          header("Refresh:0");
          exit();
        }
        foreach ($articles as $article):
    ?>
      <article id="article-<?php echo $article['id']; ?>">
        <div class="article-head row col-md-12">
          <div class="article-header col-md-8"><h1><a href="/view/<?php echo $article['id']; ?>"><?php echo $article['header']; ?></a></h1></div>
          <div class="article-date col-md-4"><h3 class="tiny-text"><a href="/user/<?php echo getUserLogin($article['uid']); ?>"><?php echo getUserLogin($article['uid']); ?></a> at <?php echo $article['date']; ?></h3></div>
        </div>
        <div class="article-body row col-md-12">
          <div class="article-content">
            <?php echo (strlen($article['text']) > 300) ? mb_substr($article['text'],0,300,'UTF-8')."..." : $article['text']; ?>
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
    <?php
        endforeach;
      ?>
        <div class="pagination">
          <ul class="pagination-ul">
          <?php for ($i = $pagesCount; $i >= 1; $i--): ?>
            <li class="pagination-li"><a href="?p=<?php echo $i; ?>"><?php echo $i; ?></a></li>
          <?php endfor; ?>
          </ul>
        </div>
      <?php
      endif;
    ?>
  </div>
</div>
