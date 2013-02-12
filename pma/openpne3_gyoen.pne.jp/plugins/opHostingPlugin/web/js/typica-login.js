jQuery(document).ready(function($) {

	$.backstretch([
      "/opHostingPlugin/bg1.png"
  	], {duration: 3000, fade: 750});
		
  $('.partsHeading').remove();
  $('#backLink').remove();

  var errMessage = $('#loginError').text();
  $('legend').after('<div style="color: #f00; padding-bottom: 10px;">' + errMessage + '</div>');

  $('#loginError').remove();
  
  var url = document.URL;
  if (0 <= url.indexOf("opAuthMailAddress/requestRegisterURL"))
  {
    $('#login-wraper').children().remove();
    $('#login-wraper').append($('#requestRegisterURL'));
    $('#login-wraper').append($('#requestSuccess'));
    $('table').css('margin-top', '10px');
    $('table').css('border-width', '1px 1px 1px 0');
    $('th').css('padding-top', '10px');
    $('td').css('padding', '10px');
    $('#request_register_url_mail_address').css('margin', '0');
  }

  if (0 <= url.indexOf("member/registerInput"))
  {
    $('#login-wraper').children().remove();
    $('#login-wraper').append($('#RegisterForm'));
    $('#login-wraper').css('width', '810px');
    $('#login-wraper').css('margin-top', '-300px');
    $('#login-wraper').css('margin-left', '-430px');
    $('table').css('border-width', '1px 1px 1px 0');
  }
});


