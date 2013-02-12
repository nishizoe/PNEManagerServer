<?php

class opHostingSnsManager
{
  const COMMUNITY_ID = 1;

  /**
   *
   * @var SnsConfig
   */
  private $_nameConfig;

  public function  __construct()
  {
    $this->_nameConfig = $nameConfig = Doctrine::getTable('SnsConfig')->retrieveByName('sns_name');
  }

  public function updateSNSInfoByInputData(array $inputData)
  {
    $this->_nameConfig->setValue($inputData['name']);
    $this->_nameConfig->save();

    if (opHostingUtil::canUseThemePlugin())
    {
      $themeConfig = new opThemeConfig();
      $themeConfig->save($inputData['theme']);
    }

    return true;
  }

  public function isCommunityMemberByMemberId($memberId)
  {
    return (Doctrine::getTable('CommunityMember')->isMember($memberId, self::COMMUNITY_ID));
  }

  public function findSNSInfo()
  {
    $info = array(
      'name' => $this->_nameConfig->getValue(),
    );

    return $info;
  }


  public function registerHostingPaidService()
  {
    $hostingConfig = $this->_findHostingPaidServiceConfig();
    $hostingConfig->setValue(true);
    $hostingConfig->save();
  }

  public function cancelHostingPaidService()
  {
    $hostingConfig = $this->_findHostingPaidServiceConfig();
    $hostingConfig->setValue(false);
    $hostingConfig->save();
  }

  public function isRegisterdHostingPaidService()
  {
    $hostingConfig = $this->_findHostingPaidServiceConfig();

    return (boolean)$hostingConfig->getValue();
  }


  private function _findHostingPaidServiceConfig()
  {
    $hostingConfig = Doctrine::getTable('SnsConfig')->retrieveByName('used_hosting_paid_service');

    if ($hostingConfig === null)
    {
      $hostingConfig = new SnsConfig();
      $hostingConfig->setName('used_hosting_paid_service');
    }

    return $hostingConfig;
  }

}

