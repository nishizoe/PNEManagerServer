$(function(){

  //定義部分
  function existsErrorDipslay()
  {
    return ($('#limit_error').length >= 1);
  }

  function findFormBodyDom()
  {
    return $('form table tbody');
  }

  function prependFormErrorDomByDom(targetDom, errorText)
  {
    var tag = '<tr>';
    tag += '<td colspan="2">';
    tag += '<ul class="error_list" id="limit_error">';
    tag += '<li>' + errorText + '</li>';
    tag += '</ul>';
    tag += '</td>';
    tag += '</tr>';

    $(targetDom).prepend(tag);
  }

  function executeLimitError(params)
  {
    if (!existsErrorDipslay())
    {
      var formBody = findFormBodyDom();
      prependFormErrorDomByDom(formBody, params.text);
    }
    else
    {
      $('#limit_error').show();
      $('#limit_error').text(params.text);
    }

    var buttonSelector = '#' + params.buttonId;
    $(buttonSelector).attr('disabled', 'disabled');
  }

  function executeLimitSuccess(params)
  {
    if (existsErrorDipslay())
    {
      $('#limit_error').hide();
      $('#limit_error').text('');
    }

    var buttonSelector = '#' + params.buttonId;
    $(buttonSelector).removeAttr('disabled');
  }



  //処理部分
  $("#member_config_mail_address").blur(function() {
    //@todo メールだけカウントするようにする
    var mailText = $("#member_config_mail_address").val();
    var mailCount = mailText.split("\n").length;

    $.getJSON('/api.php/limit/user.json', {
      add: mailCount
    }, function(json){

      if (json.limit) {
        executeLimitSuccess({
          buttonId: 'inviteMemberButton'
        });
        
      } else {
        executeLimitError({
          text: 'ユーザーを追加するとユーザー上限数をオーバーしてしまいます',
          buttonId: 'inviteMemberButton'
        });
        
      }
    });
  });



});

