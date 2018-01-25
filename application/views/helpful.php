<div class="container col-md-9 col-sm-12">
  <div class="posts-area col-md-12 no-bg">
    <h2>Интернет-библиотеки c бесплатными книгами</h2>
    <ul>
      <li><a target="_blank" href="http://aldebaran.ru/">Aldebaran</a></li>
      <li><a target="_blank" href="http://samolit.com/">Samolit</a></li>
      <li><a target="_blank" href="http://pda.litres.ru/">Бесплатный отдел Litres</a></li>
      <li><a target="_blank" href="https://www.gutenberg.org">Gutenberg</a></li>
      <li><a target="_blank" href="http://tolstoy.ru/creativity/">Толстой.ru</a></li>
      <li><a target="_blank" href="http://thankyou.ru/lib">ThankYou</a></li>
      <li><a target="_blank" href="http://hyperlib.libfl.ru/rubr.php">ВГБИЛ</a></li>
      <li><a target="_blank" href="http://www.litmir.me/">Litmir</a></li>
      <?php
        // Если пользователь испольнует Tor выдём список пиратских библиотек
        // Метод работает только в 50% случаев
        if (preg_match('/tor/',gethostbyaddr($_SERVER['REMOTE_ADDR']))):
      ?>
        <h3>Пиратские ресурсы</h3>
        <li><a target="_blank" href="http://flibusta.is/">Flibusta — Очень активно развивающаяся электронная библиотека</a> <a target="_blank" href="https://flibustahezeous3.onion.cab/">Альтернативный вход 2</a></li>
        <li><a target="_blank" href="http://coollib.net/">Библиотека собранная из Флибусты+Либрусек, без блокировок, как на Флибусте</a> <a target="_blank" href="http://coollib.com/">Мобильная версия</a> <a target="_blank" href="http://proxy.coollib.net/">Альтернативный вход 1</a> <a target="_blank" href="http://coollib.xyz">Альтернативный вход 2</a></li>
        <li><a target="_blank" href="http://lib.ru/">Библиотека Максима Мошкова</a></li>
        <li><a target="_blank" href="http://ilibrary.ru/">Библиотека Алексея Комарова</a></li>
        <li><a target="_blank" href="http://lib.aldebaran.ru/">Библиотека OCR Альдебаран</a></li>
        <li><a target="_blank" href="http://lib.rus.ec/">Платная библиотека Либрусек</a></li>
        <li><a target="_blank" href="http://fenzin.org/">"Фензин" сайт о фантастике и фэнтези, книг теперь нет, но есть описания и оценки</a></li>
        <li><a target="_blank" href="http://www.oldmaglib.com/">Библиотека Старого Чародея</a></li>
        <li><a target="_blank" href="http://www.litportal.ru">Литературный сетевой ресурс</a></li>
        <li><a target="_blank" href="http://2lib.ru/">Электронная библиотека Lib.align.ru</a></li>
        <li><a target="_blank" href="http://zhurnal.lib.ru/">Зеркало для России — Журнал "Самиздат"</a></li>
        <li><a target="_blank" href="http://bookz.ru/">Электронная библиотека</a></li>
        <li><a target="_blank" href="http://www.knigi4u.com/">«Книга - людям» бесплатная электронная библиотека</a></li>
        <li><a target="_blank" href="http://www.kulichki.com/inkwell/noframes/noframes.htm">Чернильница</a></li>
        <li><a target="_blank" href="http://www.twirpx.com">Всё для студента</a></li>
        <li><a target="_blank" href="http://www.fictionbook.ru/">Библиотека FictionBook им. GribUser'a</a></li>
        <li><a target="_blank" href="http://www.fanlib.ru/">Библиотека ФанЛиб — книги в формате FB2</a></li>
        <li><a target="_blank" href="http://fantasy-worlds.org/">Бесплатная электронная библиотека фэнтези и фантастики</a></li>
        <li><a target="_blank" href="http://www.fictionbook.ws">электронная библиотека с возможностью скачать книги в форматах fb2, txt, rtf</a></li>
        <li><a target="_blank" href="http://www.fictionbook.in">чтение книг он-лайн</a></li>
      <?php endif; ?>
    </ul>
    <h2>Сдающим экзамен по литературе</h2>
    <ul>
      <li><a target="_blank" href="https://ege.yandex.ru/literature/">Яндекс</a></li>
      <li><a target="_blank" href="https://vk.com/love_lit">Группа ВК</a></li>
      <li><a target="_blank" href="http://www.examen.ru/add/ege/ege-po-literature/">Examen.ru</a></li>
      <li><a target="_blank" href="https://vk.com/ege_literature">Группа ВК</a></li>
    </ul>
    <h2>Поиск</h2>
    <ul>
      <li><a target="_blank" href="http://www.ekniga.com.ua/">Литературная информационно-поисковая система-каталог</a></li>
      <li><a target="_blank" href="http://www.poiskknig.ru/">Поиск электронных книг</a></li>
      <li><a target="_blank" href="http://zpdd.chat.ru/search.htm">Поиск литературы</a></li>
    </ul>
    <h6 class="tiny-tex" style="margin:0"><i>Используете Tor но не видите ничего нового? Попробуйте "New Tor Circuit for this Site"</i></h6>
  </div>
</div>
