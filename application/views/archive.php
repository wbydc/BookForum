<div class="container col-md-9 col-sm-12">
  <div class="posts-area col-md-12 no-bg">
    <?php
      $curPage = $_GET['p'];
      $pagesCount = getPagesCount();
      if ($curPage > $pagesCount) PageNotFound();
      $articles = array_reverse(getAArticles($curPage));
    ?>
    <h2>Список всех статей: <div class="pull-right">Страница <?php echo $curPage; ?></div></h2>
    <?php foreach ($articles as $article): ?>
      <a class="userActionBody" href="/view/<?php echo $article['id']; ?>">
        <div class="col-md-6"><?php echo strlen($article['header']) > 50 ? mb_substr($article['header'],0,50,'UTF-8')."..." : $article['header']; ?></div>
        <div class="col-md-2"><?php echo $article['date']; ?></div>
        <div class="col-md-2"><i class="fa fa-line-chart"></i> <?php echo $article['rating']; ?></div>
        <div class="col-md-2"><i class="fa fa-eye"></i> <?php echo $article['views']; ?></div>
      </a>
      <div class="clearfix userAction"></div>
    <?php endforeach; ?>
    <div class="pagination">
      <ul class="pagination-ul">
        <?php for ($i = $pagesCount; $i >= 1; $i--): ?>
          <li class="pagination-li"><a href="?p=<?php echo $i; ?>"><?php echo $i; ?></a></li>
        <?php endfor; ?>
      </ul>
    </div>
  </div>
</div>
