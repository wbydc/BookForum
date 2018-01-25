<div class="container col-md-9 col-sm-12">
  <div class="posts-area col-md-12 no-bg">
	<h1>Поиск по статьям</h1>
    <div class="searchBar">
      <form action="" method="GET">
        <div class="input-group">
          <span class="input-group-addon">
            <i class="fa fa-search fa-fw" aria-hidden="true"></i>
          </span>
          <input name="q" type="text" placeholder="Поиск..." required="required" value="<?php echo isset($_GET['q']) ? $_GET['q'] : ''; ?>">
          <span class="input-group-addon clearSearch">
            <a href="/search"><i class="fa fa-times"></i></a>
          </span>
          <span class="input-group-addon searchButton">
            <input type="submit" value="Искать">
          </span>
        </div>
      </form>
    </div>
    <?php
      if (!isset($_GET['q'])):
    ?>
      <div class="text-center">
        <h2>Введите поисковый запрос в строке выше</h2>
      </div>
    <?php
      else:
        $articles = search($_GET['q']);
        if (empty($articles)):
      ?>
      <div class="text-center">
        <h2>По вашему запросу ничего не найдено :(</h2>
      </div>
      <?php
        else:
          foreach ($articles as $article):
        ?>
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
        <?php
          endforeach;
        endif;
      endif;
    ?>
  </div>
</div>
