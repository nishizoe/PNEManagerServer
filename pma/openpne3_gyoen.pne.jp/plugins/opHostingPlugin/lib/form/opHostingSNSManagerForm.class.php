<?php

class opHostingSNSManagerForm extends sfForm
{

  public function configure()
  {

    $this->_checkParams();

    $this->widgetSchema['name'] = new sfWidgetFormInputText();
    $this->widgetSchema->setLabels(array(
        'name' => 'SNS名',
    ));

    $this->widgetSchema['name']->setDefault($this->getOption('name'));
    $this->setValidator('name', new sfValidatorString(array('required' => true), array('required' => 'SNS名を入力してください')));

    if (opHostingUtil::canUseThemePlugin())
    {
      $themeForm = new opThemeActivationForm(array(), array('themes' => $this->getOption('themes')));
      $themeFiledKey = opThemeActivationForm::THEME_FILED_KEY;
      $themeWidget = $themeForm->getWidget($themeFiledKey);
      $this->widgetSchema[$themeFiledKey] = $themeWidget;

      $this->widgetSchema[$themeFiledKey]->setDefault($themeForm->findDefaultThemeName());

      $this->setValidator($themeFiledKey, $themeForm->getValidator($themeFiledKey));
    }

    $this->widgetSchema->setNameFormat('manager[%s]');
  }

  private function _checkParams()
  {
    if (opHostingUtil::canUseThemePlugin())
    {
      if (null === $this->getOption('themes'))
      {
        throw new RuntimeException('themesのパラメーターが渡されていません');
      }
    }
  }

}
