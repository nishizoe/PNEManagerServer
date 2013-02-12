<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * community actions.
 *
 * @package    OpenPNE
 * @subpackage community
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class communityActions extends opCommunityAction
{

  const VIEW_SNS_MANAGER = 'SnsManager';

  /**
   *
   * @var opHostingSnsManager
   */
  private $_snsManager;

  public function preExecute()
  {
    parent::preExecute();

    if (opHostingUtil::canUseThemePlugin())
    {
      opHostingUtil::requireThemePluginAllLib();

      $this->search = opThemeAssetSearchFactory::createSearchInstance();
      $this->config = new opThemeConfig();
    }

    $this->_snsManager = new opHostingSnsManager();

    //アクションごとに定義していくとコードが分散してしまうのでここに定義する
    if (opHostingUtil::isSNSManagerCommunityURL())
    {
      if (!$this->_snsManager->isCommunityMemberByMemberId((int) $this->getUser()->getMember()->getId()))
      {
        $this->redirect('@homepage');
      }
    }


    

  }

  /**
   * Executes home action
   *
   * @param opWebRequest $request A request object
   */
  public function executeHome(opWebRequest $request)
  {
    $this->forwardIf($request->isSmartphone(), 'community', 'smtHome');

    if (opHostingUtil::isSNSManagerCommunityURL())
    {
      $this->_operateSNSManagerForm($request);
    }

    return parent::executeHome($request);
  }

  /**
   * Executes smtHome action
   *
   * @param opWebRequest $request A request object
   */
  public function executeSmtHome(opWebRequest $request)
  {
    $gadgets = Doctrine::getTable('Gadget')->retrieveGadgetsByTypesName('smartphoneCommunity');
    $this->contentsGadgets = $gadgets['smartphoneCommunityContents'];

    $this->community = Doctrine::getTable('Community')->find($this->id);
    $this->forward404Unless($this->community);

    opSmartphoneLayoutUtil::setLayoutParameters(array('community' => $this->community));

    return sfView::SUCCESS;
  }

  private function _operateSNSManagerForm(opWebRequest $request)
  {
    if (opHostingUtil::canUseThemePlugin())
    {
      $this->themes = $this->search->loadThemeInsance();
      $this->useTheme = $this->config->findUseTheme();
      $this->unRegisterUseTheme = $this->config->unRegisteredisTheme();

      $this->checkThemeDirValidity();
    }

    $snsInfo = $this->_snsManager->findSNSInfo();

    $formParam = array(
      'name'   => $snsInfo['name'],
    );

    if (opHostingUtil::canUseThemePlugin())
    {
      $formParam['themes'] = $this->themes;
    }

    $this->form = new opHostingSNSManagerForm(array(), $formParam);

    if ($request->isMethod(sfRequest::POST))
    {
      $this->form->bind($request->getPostParameter('manager'));
      if ($this->form->isValid())
      {
        $this->getUser()->setFlash('input_data', $request->getPostParameter('manager'));
        $this->redirect('community/sns_manage');
      }
      else
      {
        
      }
    }
  }

  public function executeSNSManage(opWebRequest $request)
  {
    $inputData = $this->getUser()->getFlash('input_data');

    if ($inputData === null)
    {
      $this->redirect('community/'.opHostingSnsManager::COMMUNITY_ID);
    }

    $this->_snsManager->updateSNSInfoByInputData($inputData);

    $this->getUser()->setFlash('notice', 'SNSの情報を変更しました');
    $this->redirect('community/'.opHostingSnsManager::COMMUNITY_ID);
  }

    /**
   * テーマが正しく設置されているかを確認する
   */
  private function checkThemeDirValidity()
  {
    //まだテーマを選択していない場合はエラーチェックをしない
    if ($this->config->unRegisteredisTheme()) {
      //そもそも使用するテーマがないので存在するものとして扱う
      $this->existsUseTheme = true;
    }
    else
    {
      $this->existsUseTheme = $this->search->existsAssetsByThemeName($this->useTheme);
    }

    if ($this->existsNotInfoTheme())
    {
      $this->notInfoThemeList = $this->findNotInfoThemeNames();
    }

    $this->isExistsErrorTheme = (
            isset($this->notInfoThemeList)
            || $this->existsUseTheme === false);
  }

  private function existsNotInfoTheme()
  {
    foreach ($this->themes as $theme)
    {
      if (!$theme->existsInfoFile())
      {
        return true;
      }
    }

    return false;
  }

}
