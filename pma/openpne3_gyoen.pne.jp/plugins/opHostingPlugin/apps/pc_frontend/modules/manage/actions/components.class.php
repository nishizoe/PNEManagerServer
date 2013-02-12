<?php

class manageComponents extends sfComponents
{
  public function executeManageCommunity(sfWebRequest $request)
  {
    if ('1' === $this->id)
    {
      $this->getResponse()->addStyleSheet('/opHostingPlugin/css/easy-setup.css');
      $this->getResponse()->addJavascript('/opHostingPlugin/js/easy-setup.js', 'last');
    }

    return sfView::SUCCESS;
  }
}

