<?php

// Источник: https://gist.github.com/girvan/2155412
/**
 * Разбивает строку на символы || группы символов с учётом кодировки
 *
 * @param {String} Строка на вход
 * @param {Integer} Длина фрагментов = 1
 * @return {Array} Массив с разбиением
 */
function mb_str_split($string,$string_length=1) {
  if(mb_strlen($string) && $string_length || !$string_length) {
    do {
      $c = mb_strlen($string);
      $parts[] = mb_substr($string,0,$string_length);
      $string = mb_substr($string,$string_length);
    }while(!empty($string));
  } else {
    $parts = array($string);
  }
  return $parts;
}

/**
 * Создаёт RegExp из строки
 *
 * @param {String} Строка на вход
 * @return {String} Регулярное выражение
 */
function getRegExp($src) {
  $src = mb_strtolower($src);
  $result = '/';

  foreach (mb_str_split($src) as $chr) {
    if ($chr == ' ') $result .= '\s';
    elseif (preg_match('/^[a-zA-Zа-яА-ЯеЁ]{1}$/',$chr)) $result .= '['.mb_strtoupper($chr).$chr.']';
    elseif (preg_match('/^[\^\$\[\]\(\)\\\.\,\+\?\!\*\|\{\}\/]{1}$/',$chr)) $result .= '\\'.$chr;
    else $result .= $chr;
  }

  $result .= '/';
  return $result;
}

// Вынес получение списка плохих слов в отдельную функцию
// чтобы не создавать его заново при каждом вызове фильтра
/**
 * Возвращает список "плохих" слов
 *
 * @return {Array} список
 */
function getBadWords() {
  $badWordsList = getFContent(ROOT.'/application/components/badWords.txt','txt');
  $badWords = array();
  foreach ($badWordsList as $str) {
    // Игнорирование закомментированных строк
    if ($str[0] != '#' && $str != '')
      $badWords = array_merge($badWords,explode(',',trim($str)));
  }
  return $badWords;
}

/**
 * Фильтруе строку на наличие "плохих" слов
 *
 * @param {String} Строка для фильтрации
 * @return {String} Отфильтрованная строка
 */
function filterBadWords($src) {
  static $badWords = false;
  if (!$badWords) $badWords = getBadWords();
  $set = '~№-@#$%^&*?!';
  $minus = 0;
  foreach ($badWords as $word) {
    if (preg_match('/'.$word.'/',$src)) {
      // Генерация кода для замены 'плохого' слова
      $replace = '';
      for ($i = 0;$i < mb_strlen($word, 'UTF-8');$i++) {
        $dig = rand(0,strlen($set));
        $replace .= substr($set,$dig,1);
      }
      // Замена
      $src = preg_replace(getRegExp($word),$replace,$src);
      $minus++;
    }
  }

  if (isLogged()) {
    // Изменение репутации
    $sql = 'SELECT rating FROM users WHERE id = :uid';
    $params['uid'] = $_SESSION['uid'];
    $params = json_encode($params);
    $result = query($sql,$params,true);
    $result = $result[0][0] - $minus;
    $sql = 'UPDATE users SET `rating` = :rating WHERE id = :uid';
    $params = array(
      'uid' => $_SESSION['uid'],
      'rating' => $result
    );
    $params = json_encode($params);
    query($sql,$params);
  }

  return $src;
}

// Вынес получение списка словаря переносов в отдельную функцию
// чтобы не создавать его заново при каждом вызове фильтра
/**
 * Возвращает словарь переносов
 *
 * @return {Array} словарь
 */
function getShyWords() {
  $shyWordsList = getFContent(ROOT.'/application/components/shyWords.txt','txt');
  $shyWords = array();
  foreach ($shyWordsList as $str) {
    // Игнорирование закомментированных строк
    if ($str[0] != '#' && $str != '')
      $shyWords = array_merge($shyWords,explode(',',trim($str)));
  }
  foreach ($shyWords as &$word) $word = trim($word);
  return $shyWords;
}

/**
 * Доюавляет "мягкие переносы" по словарю
 *
 * @param {String} Строка для фильтрации
 * @return {String} Отфильтрованная строка
 */
function addShy($src) {
  static $shyWords = false;
  if (!$shyWords) $shyWords = getShyWords();

  foreach ($shyWords as $word) {
    $raw = preg_replace('/-/','',$word);
    $raw = getRegExp($raw);

    $replace = preg_replace('/-/','&shy;',$word);

    $src = preg_replace($raw,$replace,$src);
  }

  return $src;
}

/**
 * Подготавливает текст к записи в базу данных
 *
 * @param {String} Текст, который нужно преобразовать
 * @return {String} Результат преобразования
 */
function prepareText($src) {
  $src = trim($src);
  $src = filterBadWords($src);
  $src = htmlspecialchars($src,ENT_IGNORE);
  return $src;
}

/**
 * Перерабатывает bb коды
 *
 * @param {String} Текст с bb кодами
 * @param {Boolean}
 * @return {String} Текст с html тэгами
 */
function BBCode($text,$reverseMode = false)	{
  $replace = array(
    '/\[(\/?)(b|i|u|s|h[1-6])\s*\]/' => "<$1$2>",
    '/\[code\]/' => "<pre><code>",
    '/\[\/code\]/' => "</code></pre>",
    '/\[(\/?)quote\]/' => "<$1blockquote>",
    '/\[(\/?)quote(\s*=\s*([\'"]?)([^\'"]+)\3\s*)?\]/' => "<$1blockquote>Цитата $4:<br />",
    '/\[url\](?:https?[:]\/\/)?([a-z0-9-.]+\.\w{2,4})\[\/url\]/' => "<a href=\"//$1\">$1</a>",
    '/\[url\s*=\s*([\'"]?)(?:https?[:]\/\/)?([a-z0-9-.]+\.\w{2,4})\1\](.*)\[\/url\]/' => "<a href=\"//$2\">$3</a>",
    '/\[img\s*=\s*([\'"]?)([^\'"\]]+)\1\](.*)\[\/img\]/' => "<img src=\"$2\" alt=\"$3\" title=\"$3\"/>"
  );

  if ($reverseMode) {
    $replace = array(
      '/\<(\/?)(b|i|u|s|h[1-6])\s*\>/' => "[$1$2]",
      '/\<pre\>\<code\>/' => "[code]",
      '/\<\/code\>\<\/pre\>/' => "[/code]",
      '/\<(\/?)blockquote\>/' => "[$1quote]",
      '/\<a\shref="(?:\/\/)?(.*)"\>(.*)\<\/a\>/' => "[url=\"$1\"]$2[/url]",
      '/\<img\ssrc="(.*)"\salt="(.*)"\stitle="\2"\/\>/' => "[img=\"$1\"]$2[/img]"
    );
  }

  foreach ($replace as $regExp => $replacement) {
    $text = preg_replace($regExp, $replacement, $text);
  }

	return $text;
}
