<header class="<?= $mobile ? '' : 'row'; ?>">
  <? if ($mobile): ?>
  <nav class="navbar navbar-default mobile-nav" role="navigation">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
        <a class="navbar-brand" href="/">Книжный форум</a>
      </div>
      <div class="collapse navbar-collapse" id="navbar">
        <ul class="nav navbar-nav">
          <li><a href="/"><span>Главная</span></a></li>
          <li><a href="/sandbox"><span>Песочница</span></a></li>
          <li><a href="/archive"><span>Архив</span></a></li>
          <li><a href="/helpful"><span>Полезные ссылки</span></a></li>
          <li><a href="/about"><span>О сайте</span></a></li>
        </ul>
        <form class="navbar-form navbar-right topSearch" action="/search" role="search">
          <div class="input-group">
            <input type="text" name="q" placeholder="Поиск" required="required">
            <span class="input-group-addon"><button><i class="fa fa-search fa-fw" aria-hidden="true"></i></button></span>
          </div>
        </form>
      </div>
    </div>
  </nav>
  <? else: ?>
  <div class="pc-nav">
    <nav class="col-md-9">
      <ul class="nav navbar-nav pull-right nav-left">
        <li><a href="/"><span>Главная</span></a></li>
        <li><a href="/sandbox"><span>Песочница</span></a></li>
        <li><a href="/archive"><span>Архив</span></a></li>
        <li><a href="/helpful"><span>Полезные ссылки</span></a></li>
        <li><a href="/about"><span>О сайте</span></a></li>
      </ul>
    </nav>
    <div class="col-md-3 topSearch">
      <form action="/search" class="input-group col-md-11">
        <input type="text" name="q" placeholder="Поиск" required="required">
        <span class="input-group-addon"><button><i class="fa fa-search fa-fw" aria-hidden="true"></i></button></span>
      </form>
    </div>
  </div>
  <? endif; ?>
</header>
<div class="col-md-12">
