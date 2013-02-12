<?php

class opHostingPluginActivationForm extends PluginActivationForm
{

  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
    //必須プラグインはフォームの動作に関係なく外せないようにする フォームではdisableなので値は渡っていない
    if (isset($taintedValues['plugin']))
    {
      $taintedValues['plugin'] = array_merge($taintedValues['plugin'], opHostingUtil::getRequiredPlugin());
    }
    else
    {
      $taintedValues['plugin'] = opHostingUtil::getRequiredPlugin();
    }

    return parent::bind($taintedValues, $taintedFiles);
  }

}
