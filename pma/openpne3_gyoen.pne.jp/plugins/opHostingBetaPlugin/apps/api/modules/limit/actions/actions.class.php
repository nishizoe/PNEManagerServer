<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */
class limitActions extends opJsonApiActions
{
  /**
   * @var opHostingBetaLimit
   */
  private $_limit;

  public function  preExecute()
  {
    parent::preExecute();

    $this->_limit = new opHostingBetaLimit();
  }

  public function executeUser(sfWebRequest $request)
  {

    $addCount = (int)$request->getParameter('add', 0);
    $isLimitSafe = ($addCount + $this->_limit->countRegistUser() <= opHostingBetaLimit::USER_LIMIT);

    return $this->renderJSON(array('limit' => $isLimitSafe));
  }
  
}
