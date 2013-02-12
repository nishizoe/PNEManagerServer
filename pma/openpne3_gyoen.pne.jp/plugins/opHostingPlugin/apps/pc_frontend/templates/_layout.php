<?php if(opHostingUtil::isLoggedInPage()): ?>
<?php //ログインしているページ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
<?php include_http_metas() ?>
<?php include_metas() ?>
<?php include_title() ?>
<?php use_stylesheet('/cache/css/customizing.css') ?>
<?php include_stylesheets() ?>
<?php if (Doctrine::getTable('SnsConfig')->get('customizing_css')): ?>
<link rel="stylesheet" type="text/css" href="<?php echo url_for('@customizing_css') ?>" />
<?php endif; ?>
<?php if (opConfig::get('enable_jsonapi') && opToolkit::isSecurePage()): ?>
<?php
use_helper('Javascript');

use_javascript('jquery.min.js');
use_javascript('jquery.tmpl.min.js');
use_javascript('jquery.notify.js');
use_javascript('op_notify.js');
$jsonData = array(
  'apiKey' => $sf_user->getMemberApiKey(),
  'apiBase' => app_url_for('api', 'homepage'),
);

echo javascript_tag('
var openpne = '.json_encode($jsonData).';
');
?>
<?php endif ?>
<?php include_javascripts() ?>
<?php echo $op_config->get('pc_html_head') ?>
</head>
<body id="<?php printf('page_%s_%s', $view->getModuleName(), $view->getActionName()) ?>" class="<?php echo opToolkit::isSecurePage() ? 'secure_page' : 'insecure_page' ?>">
<?php echo $op_config->get('pc_html_top2') ?>
<div id="Body">
<?php echo $op_config->get('pc_html_top') ?>
<div id="Container">

<div id="Header" class="navbar">
<div id="HeaderContainer" class="navbar-inner">
<div class="container">
<?php include_partial('global/header') ?>
</div>
</div><!-- HeaderContainer -->
</div><!-- Header -->

<div id="Contents">
<div id="ContentsContainer">

<div id="localNav">
<?php
$context = sfContext::getInstance();
$module = $context->getActionStack()->getLastEntry()->getModuleName();
$localNavOptions = array(
  'is_secure' => opToolkit::isSecurePage(),
  'type'      => sfConfig::get('sf_nav_type', sfConfig::get('mod_'.$module.'_default_nav', 'default')),
  'culture'   => $context->getUser()->getCulture(),
);
if ('default' !== $localNavOptions['type'])
{
  $localNavOptions['nav_id'] = sfConfig::get('sf_nav_id', $context->getRequest()->getParameter('id'));
}
include_component('default', 'localNav', $localNavOptions);
?>
</div><!-- localNav -->

<div id="Layout<?php echo $layout ?>" class="Layout">

<?php if ($sf_user->hasFlash('error')): ?>
<?php op_include_parts('alertBox', 'flashError', array('body' => __($sf_user->getFlash('error'), $sf_data->getRaw('sf_user')->getFlash('error_params', array())))) ?>
<?php endif; ?>
<?php if ($sf_user->hasFlash('notice')): ?>
<?php op_include_parts('alertBox', 'flashNotice', array('body' => __($sf_user->getFlash('notice'), $sf_data->getRaw('sf_user')->getFlash('notice_params', array())))) ?>
<?php endif; ?>

<?php if (has_slot('op_top')): ?>
<div id="Top">
<?php include_slot('op_top') ?>
</div><!-- Top -->
<?php endif; ?>

<?php if (has_slot('op_sidemenu')): ?>
<div id="Left">
<?php include_slot('op_sidemenu') ?>
</div><!-- Left -->
<?php endif; ?>

<div id="Center">
<?php echo $sf_content ?> 
</div><!-- Center -->

<?php if (has_slot('op_bottom')): ?>
<div id="Bottom">
<?php include_slot('op_bottom') ?>
</div><!-- Bottom -->
<?php endif; ?>

</div><!-- Layout -->

<div id="sideBanner">
<?php include_component('default', 'sideBannerGadgets'); ?>
</div><!-- sideBanner -->

</div><!-- ContentsContainer -->
</div><!-- Contents -->

<?php if ($sf_request->isSmartphone(false)): ?>
<div id="SmtSwitch">
<a href="javascript:void(0)" id="SmtSwitchLink"><?php echo __('View this page on smartphone style') ?></a>
<?php echo javascript_tag('
document.getElementById("SmtSwitchLink").addEventListener("click", function() {
  opCookie.set("disable_smt", "0");
  location.reload();
}, false);
') ?>
</div>
<?php endif ?>

<div id="Footer">
<div id="FooterContainer">
<?php include_partial('global/footer') ?>
</div><!-- FooterContainer -->
</div><!-- Footer -->

<?php echo $op_config->get('pc_html_bottom2') ?>
</div><!-- Container -->
<?php echo $op_config->get('pc_html_bottom') ?>
</div><!-- Body -->
</body>
</html>
<?php //ログインしているページ終了 ?>
<?php else: ?>
<?php //ログインページ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
<?php include_http_metas() ?>
<?php include_metas() ?>
<?php include_title() ?>
<?php use_stylesheet('/cache/css/customizing.css') ?>
<?php use_stylesheet('/opHostingPlugin/css/bootstrap.min.css') ?>
<?php use_stylesheet('/opHostingPlugin/css/bootstrap-responsive.min.css') ?>
<?php use_stylesheet('/opHostingPlugin/css/typica-login.css') ?>
<?php use_javascript('/opHostingPlugin/js/jquery.js', 'last') ?>
<?php use_javascript('/opHostingPlugin/js/bootstrap.js', 'last') ?>
<?php use_javascript('/opHostingPlugin/js/backstretch.min.js', 'last') ?>
<?php use_javascript('/opHostingPlugin/js/typica-login.js', 'last') ?>
<?php include_stylesheets() ?>
<?php if (Doctrine::getTable('SnsConfig')->get('customizing_css')): ?>
<link rel="stylesheet" type="text/css" href="<?php echo url_for('@customizing_css') ?>" />
<?php endif; ?>
<?php if (opConfig::get('enable_jsonapi') && opToolkit::isSecurePage()): ?>
<?php
use_helper('Javascript');

use_javascript('jquery.min.js');
use_javascript('jquery.tmpl.min.js');
use_javascript('jquery.notify.js');
use_javascript('op_notify.js');
$jsonData = array(
  'apiKey' => $sf_user->getMemberApiKey(),
  'apiBase' => app_url_for('api', 'homepage'),
);

echo javascript_tag('
var openpne = '.json_encode($jsonData).';
');
?>
<?php endif ?>
<?php include_javascripts() ?>
<?php echo $op_config->get('pc_html_head') ?>
</head>
<body id="<?php printf('page_%s_%s', $view->getModuleName(), $view->getActionName()) ?>" class="<?php echo opToolkit::isSecurePage() ? 'secure_page' : 'insecure_page' ?>">
<?php echo $op_config->get('pc_html_top2') ?>
<div id="Body">
<?php echo $op_config->get('pc_html_top') ?>
<div id="Container">

  <div id="login-wraper">
    <form class="form login-form" action="/member/login/authMode/MailAddress" method="POST">
    <legend>Sign in to <span class="blue"><?php echo $op_config['sns_name'] ?></span></legend>

        <div class="body">
          <label for="authMailAddress_mail_address">email</label>
          <input type="text" name="authMailAddress[mail_address]" id="authMailAddress_mail_address">
          <br />
          <label for="authMailAddress_password">Password</label>
          <input type="password" name="authMailAddress[password]" id="authMailAddress_password">
        </div>

        <div class="footer">
          <label class="checkbox inline" for="authMailAddress_is_remember_me">
            <input type="checkbox" name="authMailAddress[is_remember_me]" id="authMailAddress_is_remember_me"> Remember me
            <input value="member/home" type="hidden" name="authMailAddress[next_uri]" id="authMailAddress_next_uri" />
          </label>

          <button type="submit" class="btn btn-success">Login</button>
          <p class="password_query"><a href="/opAuthMailAddress/helpLoginError">ログインできない方はこちら</a></p>
<?php 
$sns = Doctrine_Core::getTable('snsConfig');
$con = $sns->getConnection();
$inviteMode = $con->fetchRow('select * from sns_config where name ="op_auth_MailAddress_plugin_invite_mode"');
?>
<?php if (2 == (int)$inviteMode['value']): ?>
  <a href="/opAuthMailAddress/requestRegisterURL">新規登録</a>
<?php endif; ?>
        </div>
    </form>
  </div>




<!--<div id="Header">-->
<!--<div id="HeaderContainer">-->
<?php //include_partial('global/header') ?>
<!--</div>--><!-- HeaderContainer -->
<!--</div>--><!-- Header -->

<!--<div id="Contents">
<div id="ContentsContainer"> -->

<div id="localNav">
<?php
$context = sfContext::getInstance();
$module = $context->getActionStack()->getLastEntry()->getModuleName();
$localNavOptions = array(
  'is_secure' => opToolkit::isSecurePage(),
  'type'      => sfConfig::get('sf_nav_type', sfConfig::get('mod_'.$module.'_default_nav', 'default')),
  'culture'   => $context->getUser()->getCulture(),
);
if ('default' !== $localNavOptions['type'])
{
  $localNavOptions['nav_id'] = sfConfig::get('sf_nav_id', $context->getRequest()->getParameter('id'));
}
include_component('default', 'localNav', $localNavOptions);
?>
</div><!-- localNav -->

<div id="Layout<?php echo $layout ?>" class="Layout">

<?php if ($sf_user->hasFlash('error')): ?>
<?php op_include_parts('alertBox', 'flashError', array('body' => __($sf_user->getFlash('error'), $sf_data->getRaw('sf_user')->getFlash('error_params', array())))) ?>
<?php endif; ?>
<?php if ($sf_user->hasFlash('notice')): ?>
<?php op_include_parts('alertBox', 'flashNotice', array('body' => __($sf_user->getFlash('notice'), $sf_data->getRaw('sf_user')->getFlash('notice_params', array())))) ?>
<?php endif; ?>

<?php if (has_slot('op_top')): ?>
<div id="Top">
<?php// include_slot('op_top') ?>
</div><!-- Top -->
<?php endif; ?>

<?php if (has_slot('op_sidemenu')): ?>
<div id="Left">
<?php include_slot('op_sidemenu') ?>
</div><!-- Left -->
<?php endif; ?>

<div id="Center">
<?php echo $sf_content ?>
</div><!-- Center -->

<?php if (has_slot('op_bottom')): ?>
<div id="Bottom">
<?php include_slot('op_bottom') ?>
</div><!-- Bottom -->
<?php endif; ?>

</div><!-- Layout -->

<!--<div id="sideBanner">-->
<?php //include_component('default', 'sideBannerGadgets'); ?>
<!--</div>--><!-- sideBanner -->

<!--</div>--><!-- ContentsContainer -->
<!--</div>--><!-- Contents -->

<?php if ($sf_request->isSmartphone(false)): ?>
<div id="SmtSwitch">
<a href="javascript:void(0)" id="SmtSwitchLink"><?php echo __('View this page on smartphone style') ?></a>
<?php echo javascript_tag('
document.getElementById("SmtSwitchLink").addEventListener("click", function() {
  opCookie.set("disable_smt", "0");
  location.reload();
}, false);
') ?>
</div>
<?php endif ?>

<!--<div id="Footer">-->
<!--<div id="FooterContainer">-->
<?php// include_partial('global/footer') ?>
<!--</div>--><!-- FooterContainer -->
<!--</div>--><!-- Footer -->

<?php echo $op_config->get('pc_html_bottom2') ?>
</div><!-- Container -->
<?php echo $op_config->get('pc_html_bottom') ?>
</div><!-- Body -->
</body>
</html>
<?php //ログインページ終了 ?>
<?php endif; ?>
