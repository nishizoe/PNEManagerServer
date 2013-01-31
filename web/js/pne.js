//var domain = "pne.jp";
var domain = "cqc.jp";

var $ = jQuery.noConflict();
var domainUrl = location.protocol + '//' + location.hostname + '/';

var domainValid = false;
var mailValid = false;
var enabled = function() {
  if (domainValid && mailValid) {
    //$("#sendbutton2").attr("onclick", "send()");
    $("#sendbutton2").removeAttr("disabled");
  } else {
    //$("#sendbutton2").removeAttr("onclick");
    $("#sendbutton2").attr("disabled", "true");
  }
};

$('#sendbutton2').click( function() {
  send();
});

$('*[name=mode]').click( function() {
  var mode = $(this).attr('value');
  $('.mode-hide').hide();
  switch (mode) {
    case 'plane':
      $('.plane-mode').show();
      break;
    case 'business':
      $('.business-mode').show();
      break;
    case 'game':
      $('.game-mode').show();
      break;
    default:
      $('.plane-mode').show();
  }
});

$(function(){
  $("#domain-form").bind("keyup", function(e) {
    var re = new RegExp(/^[a-z0-9]{3,16}$/);
    var isValid = re.test($("#domain-form").val());
    if (isValid) {
      $.ajax({
        type: "GET",
        url: domainUrl + "api/domain/available",
        data: "domain=" + $("#domain-form").val() + "." + domain,
        dataType: "json",
        success: function(msg) {
          if (msg.result == true) {
            $("#available").html("使用可能です");
            $("#available").css("color", "green");
            domainValid = true;
            enabled();
          } else {
            $("#available").html("すでに使用されています");
            $("#available").css("color", "red");
            domainValid = false;
            enabled();
          }
        }
      });
    } else {
      $("#available").html("3～16字までの半角英数字（小文字）で入力してください。");
      $("#available").css("color", "red");
      domainValid = false;
      enabled();
    }
  });
  $("#mail-form").bind("keyup", function(e){
    if ($("#mail-form").val() == "") {
      $("#mailvalid").html("必須項目です。");
      $("#mailvalid").css("color", "red");
      mailValid = false;
      enabled();
    }else{
      $("#mailvalid").html("");
      mailValid = true;
      enabled();
    }
  });
});
///var send = function(){
function send() {
    $.ajax({
        type: "POST",
        url: domainUrl + "api/sns/apply",
        data: "domain=" + $("#domain-form").val() + '.' + domain + "&email=" + $("#mail-form").val() + "&options=mode:" + $("input[name=mode]:checked").val(),
        dataType: "json",
        success: function(json){
          if (json.result == true){
            document.location = domainUrl + 'success.html'
          }else{
            alert("エラーが発生しました。\n入力内容を確認して下さい。");
          }
        },
	error: function(e){
            alert("エラーが発生しました。\n入力内容を確認して下さい。");
	}
    });
};
