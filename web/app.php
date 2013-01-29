<?php

//URIが空の場合になぜか正常に実行できないので、index.htmlにリダイレクトさせる
if ($_SERVER['REQUEST_URI'] === '/')
{
  if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
  {
    $protocl = 'https://';
  }
  else
  {
    $protocl = 'http://';
  }

  $redirectURL = $protocl.$_SERVER['SERVER_NAME'].'/index.html';
  header('Location: '.$redirectURL); exit;
}

require_once __DIR__.'/../app/bootstrap.php.cache';
require_once __DIR__.'/../app/AppKernel.php';
//require_once __DIR__.'/../app/AppCache.php';

use Symfony\Component\HttpFoundation\Request;

//$kernel = new AppKernel('prod', false);
$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();

//var_dump(Request::createFromGlobalsaa());

//$kernel = new AppCache($kernel);
$kernel->handle(Request::createFromGlobals())->send();
