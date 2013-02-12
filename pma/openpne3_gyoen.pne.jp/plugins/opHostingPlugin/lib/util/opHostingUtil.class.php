<?php

class opHostingUtil
{
  
  public static function isLoggedInPage()
  {
    //ログインしていない場合は強制的にログインページを表示している
    $memberInstance = sfContext::getInstance()->getUser()->getMember();
    return (get_class($memberInstance) !== 'opAnonymousMember');
  }

  public static function isSNSManagerCommunityURL()
  {
   
    //commnunityデータ表示画面以外ではアクセスできないようにする
    if (sfContext::getInstance()->getModuleName() !== 'community')
    {
      return false;
    }

    if (sfContext::getInstance()->getActionName() === 'sNSManage')
    {
      return true;
    }

    if (sfContext::getInstance()->getActionName() !== 'home')
    {
      return false;
    }

    return ((int)sfContext::getInstance()->getRequest()->getParameter('id') === opHostingSnsManager::COMMUNITY_ID);
  }

  public static function canUseThemePlugin()
  {
    //クラスだとスコープが違っているためチェックできないので、ファイルが存在するかで確認する
    $pluginClassPath = self::_getSkinThemePluginLibBasePath().'/theme/opTheme.class.php';

    if (!file_exists($pluginClassPath))
    {
      return false;
    }


    $plugin = opPlugin::getInstance('opSkinThemePlugin');    
    if (!$plugin->getIsActive())
    {
      return false;
    }

    return true;
  }

  public static function requireThemePluginAllLib()
  {
    $basePath = self::_getSkinThemePluginLibBasePath().'/';

    require_once ($basePath.'event/opThemeEvent.class.php');
    require_once ($basePath.'theme/opTheme.class.php');
    require_once ($basePath.'theme/opThemeAssetSearchFactory.class.php');
    require_once ($basePath.'theme/opThemeAssetSearch.class.php');
    require_once ($basePath.'theme/opThemeConfig.class.php');
    require_once ($basePath.'theme/opThemeInfoParser.class.php');
  }

  public static function getRequiredPlugin()
  {
    $requiredPlugins = array(
      'opHostingPlugin'
    );

    $snsManager = new opHostingSnsManager();
    if (!$snsManager->isRegisterdHostingPaidService())
    {
      $requiredPlugins[] = 'opHostingBetaPlugin';
    }
    
    return $requiredPlugins;
  }



  private static function _getSkinThemePluginLibBasePath()
  {
    $pluginClassPath = sfConfig::get('sf_root_dir');
    $pluginClassPath .= '/plugins/opSkinThemePlugin/lib';
    return $pluginClassPath;

  }
}
