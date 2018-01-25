<div class="container col-md-9 col-sm-12">
  <div class="posts-area col-md-12 no-bg">
    <h1>Редактирование статьи</h1>
    <div class="hc-wrapper">
      <div class="hc-shbutton">
        <span class="fa-stack fa-lg" title="Справка по форматированию">
          <i class="fa fa-circle-thin fa-stack-2x"></i>
          <i class="fa fa-info fa-stack-1x"></i>
        </span>
      </div>
      <div class="hc-content">
        <h2>Особые возможности:</h2>
        <ul>
          <li>
            Ссылки:
            <ol>
              <li>[url]<code>ссылка</code>[/url]</li>
              <li>[url=<code>ссылка</code>]<code>подпись</code>[/url]</li>
              <li>[url="<code>ссылка</code>"]<code>подпись</code>[/url]</li>
            </ol>
          </li>
          <li>
            Изображения:
            <ol>
              <li>[img=<code>ссылка на изображение</code>]<code>подпись</code>[/img]</li>
              <li>[img="<code>ссылка на изображение</code>"]<code>подпись</code>[/img]</li>
            </ol>
          </li>
          <li>
            Цитата:
            <ol>
              <li><blockquote>[quote]Цитата :<br/><code>Текст</code>[/quote]</blockquote></li>
              <li><blockquote>[quote=<code>Автор</code>]Цитата <code>Автор</code>:<br/><code>Текст</code>[/quote]</blockquote></li>
              <li><blockquote>[quote="<code>Автор</code>"]Цитата <code>Автор</code>:<br/><code>Текст</code>[/quote]</blockquote></li>
            </ol>
          </li>
          <li>Заголовки:
            <h1>[h1]<code>Текст</code>[/h1]</h1>
            <h2>[h2]<code>Текст</code>[/h2]</h2>
            <h3>[h3]<code>Текст</code>[/h3]</h3>
            <h4>[h4]<code>Текст</code>[/h4]</h4>
            <h5>[h5]<code>Текст</code>[/h5]</h5>
            <h6>[h6]<code>Текст</code>[/h6]</h6>
          </li>
          <li>Жирный шрифт: <b>[b]<code>Текст</code>[/b]</b></li>
          <li>Курсив: <i>[i]<code>Текст</code>[/i]</i></li>
          <li>Подчёркнутый: <u>[u]<code>Текст</code>[/u]</u></li>
          <li>Зачёркнутый: <s>[s]<code>Текст</code>[/s]</s></li>
        </ul>
      </div>
    </div>
    <?php
      $article = getArticle($articleId);
    ?>
    <form action="/post/edit/<?php echo $articleId; ?>" method="POST">
      <h2>Заголовок:</h2>
      <input class="nai" type="text" name="header" value="<?php echo $article['header']; ?>">
      <br/>
      <h2>Текст статьи:</h2>
      <textarea class="nai" name="text" rows="7"><?php echo BBcode(preg_replace('/\<br \/\>/',"",$article['text']),true); ?></textarea>
      <h2>Теги:</h2>
      <input class="nai" type="text" name="tags" value="<?php echo $article['tags'] ?>">
      <br/>
      <input class="pull-right" type="submit" value="Обновить">
    </form>
  </div>
</div>
