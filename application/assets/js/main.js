$('.rating').click(function() {
  var elem = $(this);
  var dir = elem.attr('data-dir');
  var id = elem.parent().parent().parent().attr('id');
  var area = id.substr(0,1);
  id = id.match(/\d+$/);
  var url = '/cr/' + area + '/' + id + '/' + dir;
  $.get(url, function(result) {
    var span = elem.parent().find('span');
    var spanClass = span.attr('class');
    switch (dir) {
      case 'l':
        if (span.hasClass('disliked')) span.removeClass('disliked');
        if (span.hasClass('liked')) span.removeClass('liked');
        else span.addClass('liked');
        break;
      case 'd':
        if (span.hasClass('liked')) span.removeClass('liked');
        if (span.hasClass('disliked')) span.removeClass('disliked');
        else span.addClass('disliked');
        break;
    }
    if (result.match(/^-?\d+$/)) span.html(result);
  });
});
$('#commentForm').submit(function() {
  event.preventDefault();
  var data = $(this).serialize();
  var action = $('#commentForm').attr('action');
  $.post(action,data,function(result) {
    if (result == 'OK') window.location.reload();
  });
});
$('.hc-shbutton').click(function() {$('.hc-content').toggle();});

var userEdit = false;
$('.userEdit').click(function() {
  $('.overview .userData').each(function(i) {
    if (userEdit) {
      var val = $(this).find('input').val();
      if (val == '') val = 'Не указано';
      $(this).html(val);
    } else {
      var val = $(this).html();
      if (val == 'Не указано') val = '';
      else if (i == 3) {
        val = $(this).find('a').html();
      }

      var html = '<input name="' + i + '" type="text" value="' + val + '">';
      $(this).html(html);
    }
  });
  $('.userEditSubmit').toggle();
  userEdit = !userEdit;
});
$('.overview form').submit(function() {
  event.preventDefault();
  var data = $(this).serialize();
  var action = $(this).attr('action');
  $.post(action,data,function(result) {
    if (result == 'OK') {$('.userEdit').click();}
    else {alert(result);}
  });
});

$('.clearSearch a').click(function() {
  event.preventDefault();
  $('.searchBar .input-group > input').val('');
});

$('.dc').click(function() {
  event.preventDefault();
  var action = $(this).attr('href');
  var id = $(this).attr('data-cid');
  action = action.substr(0,action.lastIndexOf('/') + 1) + 'r';
  $.get(action,function(result) {
    if (result == 'OK') $('#comment-' + id).remove();
  });
});
