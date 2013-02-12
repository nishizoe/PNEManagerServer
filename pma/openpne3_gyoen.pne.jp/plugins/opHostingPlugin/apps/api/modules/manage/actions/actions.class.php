<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */
class manageActions extends opJsonApiActions
{
  public function executeThemeSearch(sfWebRequest $request)
  {
    $this->entrys = $this->themeDirCheck();
  }

  public function executeThemeSelect(sfWebRequest $request)
  {
    $this->forward400If(!isset($request['name']), 'name is not specified');

    $entrys = $this->themeDirCheck();
    $entryCheck = false;
    foreach ($entrys as $entry)
    {
      if ($entry == $request['name'])
      {
        $entryCheck = true;
      }
    }

    $this->forward400If(!$entryCheck, 'no theme');

    $themeUsed = Doctrine::getTable('SnsConfig')->retrieveByName('Theme_used');
    $themeUsed->setValue($request['name']);
    $themeUsed->save();
  }

  public function executeSnsNameChange(sfWebRequest $request)
  {
    $this->forward400If(!isset($request['name']), 'name is not specified');

    $snsName = Doctrine::getTable('SnsConfig')->retrieveByName('sns_name');
    if (is_null($snsName))
    {   
      $snsName = new SnsConfig();
      $snsName->setName('sns_name');
    }   

    $snsName->setValue($request['name']);
    $snsName->save();
  }

  private function themeDirCheck()
  {
    $dirName = sfConfig::get('sf_web_dir').'/opSkinThemePlugin';
    $entrys = array();
    if ($dirHandler = opendir($dirName))
    {
      while ($entry = readdir($dirHandler))
      {
        if (file_exists($dirName.'/'.$entry.'/css/main.css') && $entry != "." && $entry != ".." )
        {
          $entrys[] = $entry;
        }
      }
      closedir($dirHandler);
    }
    return $entrys;
  }
}
