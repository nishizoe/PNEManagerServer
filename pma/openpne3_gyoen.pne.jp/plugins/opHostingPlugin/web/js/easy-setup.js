$(function () {
  $.ajax({
    type: 'GET',
    url: openpne.apiBase + 'theme/search.json?',
    success: function (json)
    {
      $('#theme-list').append($('#themeListTemplate').tmpl(json.data));
    }
  });

  $('#sns-name-submit').click( function() {
    var snsName = $('#sns-name').val();
    $.ajax({
      type: 'POST',
      url: openpne.apiBase + 'sns/name.json?',
      data: {
        'name': snsName
      },
      success: function (json)
      {
        location.reload();
      }
    });
  });

  $('.theme-select').live('click', function() {
    var themeName = $(this).val();
    $.ajax({
      type: 'POST',
      url: openpne.apiBase + 'theme/select.json?',
      data: {
        'name': themeName
      },
      success: function (json)
      {
        location.reload();
      }
    });
  });
});

